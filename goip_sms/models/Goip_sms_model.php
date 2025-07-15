<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Goip_sms_model extends CI_Model
{
    private $settings_table = 'goip_sms_settings';
    private $log_table = 'goip_sms_log';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_settings()
    {
        $settings = [];
        $query = $this->db->get($this->settings_table);
        
        foreach ($query->result() as $setting) {
            $settings[$setting->name] = $setting->value;
        }
        
        // Default values
        $defaults = [
            'host' => '',
            'port' => '80',
            'username' => 'admin',
            'password' => '',
            'enabled' => '0',
            'invoice_created_enabled' => '0',
            'payment_recorded_enabled' => '0',
            'invoice_created_template' => 'New invoice #{invoice_number} created for {client_name}. Total: {invoice_total}. Due: {invoice_duedate}',
            'payment_recorded_template' => 'Payment received for invoice #{invoice_number}. Amount: {payment_amount}. Date: {payment_date}. Thank you!'
        ];
        
        return array_merge($defaults, $settings);
    }

    public function update_settings($data)
    {
        foreach ($data as $name => $value) {
            $this->db->where('name', $name);
            $query = $this->db->get($this->settings_table);
            
            if ($query->num_rows() > 0) {
                $this->db->where('name', $name);
                $this->db->update($this->settings_table, ['value' => $value]);
            } else {
                $this->db->insert($this->settings_table, [
                    'name' => $name,
                    'value' => $value
                ]);
            }
        }
        
        return true;
    }

    public function send_sms($phone, $message)
    {
        $settings = $this->get_settings();
        
        if (empty($settings['host']) || empty($settings['password'])) {
            return [
                'success' => false,
                'error' => 'GoIP settings not configured'
            ];
        }

        $url = "http://{$settings['host']}:{$settings['port']}/default/en_US/send.html";
        
        $data = [
            'u' => $settings['username'],
            'p' => $settings['password'],
            'l' => '1', // Line 1 for GoIP-1
            'n' => $phone,
            'msg' => $message
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        // Log the attempt
        $this->log_sms($phone, $message, $httpCode, $response, $error);

        if ($error) {
            return [
                'success' => false,
                'error' => 'CURL Error: ' . $error
            ];
        }

        if ($httpCode !== 200) {
            return [
                'success' => false,
                'error' => 'HTTP Error: ' . $httpCode
            ];
        }

        if (strpos($response, 'OK') !== false || strpos($response, 'Sending') !== false) {
            return [
                'success' => true,
                'response' => $response
            ];
        } else {
            return [
                'success' => false,
                'error' => 'GoIP Error: ' . $response
            ];
        }
    }

    public function log_sms($phone, $message, $http_code, $response, $error = null)
    {
        $status = 'failed';
        if ($http_code == 200 && (strpos($response, 'OK') !== false || strpos($response, 'Sending') !== false)) {
            $status = 'sent';
        }

        $this->db->insert($this->log_table, [
            'phone' => $phone,
            'message' => $message,
            'status' => $status,
            'http_code' => $http_code,
            'response' => $response,
            'error' => $error,
            'sent_at' => date('Y-m-d H:i:s')
        ]);
    }

    public function get_sms_log($limit = 100)
    {
        $this->db->order_by('sent_at', 'DESC');
        $this->db->limit($limit);
        return $this->db->get($this->log_table)->result();
    }

    public function is_enabled($trigger)
    {
        $settings = $this->get_settings();
        return isset($settings[$trigger . '_enabled']) && $settings[$trigger . '_enabled'] == '1';
    }

    public function get_template($trigger)
    {
        $settings = $this->get_settings();
        return isset($settings[$trigger . '_template']) ? $settings[$trigger . '_template'] : '';
    }
}
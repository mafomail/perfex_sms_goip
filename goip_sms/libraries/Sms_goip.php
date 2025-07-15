/**
 * GoIP SMS Module for Perfex CRM
 * Copyright (C) 2025 ProEM, s.r.o.
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 */
<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sms_goip extends App_sms
{
    private $host;
    private $port;
    private $username;
    private $password;

    public function __construct()
    {
        parent::__construct();
        
        $this->host     = $this->get_option('goip', 'host');
        $this->port     = $this->get_option('goip', 'port');
        $this->username = $this->get_option('goip', 'username');
        $this->password = $this->get_option('goip', 'password');

        $this->add_gateway('goip', [
            'name' => 'GoIP GSM Gateway',
            'info' => '<p>GoIP GSM Gateway integration. Zadajte IP adresu vašej GoIP brány a prihlasovacie údaje.</p><hr class="hr-10" />',
            'options' => [
                [
                    'name'          => 'host',
                    'label'         => 'GoIP Host/IP adresa',
                    'default_value' => '192.168.1.100'
                ],
                [
                    'name'          => 'port',
                    'label'         => 'Port',
                    'default_value' => '80'
                ],
                [
                    'name'          => 'username',
                    'label'         => 'Používateľské meno',
                    'default_value' => 'admin'
                ],
                [
                    'name'  => 'password',
                    'label' => 'Heslo',
                ]
            ],
        ]);
    }

    public function send($number, $message, $trigger = '')
    {
        if (empty($this->host) || empty($this->password)) {
            $this->set_error('GoIP nastavenia nie sú kompletné');
            return false;
        }

        $url = "http://{$this->host}:{$this->port}/default/en_US/send.html";
        
        $data = [
            'u'   => $this->username,
            'p'   => $this->password,
            'l'   => '1',              // GoIP-1 má len jednu SIM kartu
            'n'   => $number,
            'msg' => $message
        ];

        try {
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

            if ($error) {
                $this->set_error('CURL Error: ' . $error);
                return false;
            }

            if ($httpCode !== 200) {
                $this->set_error('HTTP Error: ' . $httpCode);
                return false;
            }

            // GoIP odpoveď obsahuje "OK" alebo "Sending" pri úspešnom odoslaní
            if (strpos($response, 'OK') !== false || strpos($response, 'Sending') !== false) {
                $this->logSuccess($number, $message);
                return true;
            } else {
                $this->set_error('GoIP Error: ' . $response);
                return false;
            }

        } catch (Exception $e) {
            $this->set_error($e->getMessage());
            return false;
        }
    }
}

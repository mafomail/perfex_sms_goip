<?php
defined('BASEPATH') or exit('No direct script access allowed');

// Create settings table
if (!$CI->db->table_exists(db_prefix() . 'goip_sms_settings')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . 'goip_sms_settings` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `name` varchar(255) NOT NULL,
        `value` text,
        PRIMARY KEY (`id`),
        UNIQUE KEY `name` (`name`)
    ) ENGINE=InnoDB DEFAULT CHARSET=' . $CI->db->char_set . ';');
}

// Create log table
if (!$CI->db->table_exists(db_prefix() . 'goip_sms_log')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . 'goip_sms_log` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `phone` varchar(20) NOT NULL,
        `message` text NOT NULL,
        `status` varchar(20) NOT NULL,
        `http_code` int(11) DEFAULT NULL,
        `response` text,
        `error` text,
        `sent_at` datetime NOT NULL,
        PRIMARY KEY (`id`),
        KEY `phone` (`phone`),
        KEY `status` (`status`),
        KEY `sent_at` (`sent_at`)
    ) ENGINE=InnoDB DEFAULT CHARSET=' . $CI->db->char_set . ';');
}

// Insert default settings
$default_settings = [
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

foreach ($default_settings as $name => $value) {
    $CI->db->where('name', $name);
    $exists = $CI->db->get(db_prefix() . 'goip_sms_settings')->num_rows();
    
    if ($exists == 0) {
        $CI->db->insert(db_prefix() . 'goip_sms_settings', [
            'name' => $name,
            'value' => $value
        ]);
    }
}
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

<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
Module Name: GoIP SMS
Description: GoIP SMS Gateway integration for Perfex CRM
Version: 1.0.0
Author: Your Name
*/

define('GOIP_SMS_MODULE_NAME', 'goip_sms');

/**
 * Register module activation hook
 */
register_activation_hook('goip_sms', 'goip_sms_activation_hook');

function goip_sms_activation_hook()
{
    $CI = &get_instance();
    require_once(__DIR__ . '/install.php');
}

/**
 * Register module deactivation hook
 */
register_deactivation_hook('goip_sms', 'goip_sms_deactivation_hook');

function goip_sms_deactivation_hook()
{
    $CI = &get_instance();
    require_once(__DIR__ . '/uninstall.php');
}

/**
 * Load SMS class when module is active
 */
hooks()->add_action('app_init', 'goip_sms_load_library');

function goip_sms_load_library()
{
    $CI = &get_instance();
    
    // Load the SMS library
    $CI->load->library('goip_sms/sms_goip');
}
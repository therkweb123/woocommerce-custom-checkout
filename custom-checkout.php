<?php
/**
 * Plugin Name: WooCommerce Custom Checkout Optimization
 * Plugin URI: https://github.com/therkweb123/woocommerce-custom-checkout
 * Description: Custom WooCommerce checkout optimization example focused on improving conversions and user experience.
 * Version: 1.0
 * Author: Rahul Kumar Kushwaha
 * Author URI: https://github.com/therkweb123
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Remove unnecessary checkout fields
 */
add_filter('woocommerce_checkout_fields', 'rk_custom_checkout_fields');

function rk_custom_checkout_fields($fields)
{
    // Remove company field
    unset($fields['billing']['billing_company']);

    // Remove address line 2
    unset($fields['billing']['billing_address_2']);

    // Remove order comments
    unset($fields['order']['order_comments']);

    return $fields;
}

/**
 * Add custom checkout field
 */
add_filter('woocommerce_checkout_fields', 'rk_add_custom_checkout_field');

function rk_add_custom_checkout_field($fields)
{
    $fields['billing']['billing_whatsapp'] = array(
        'type'        => 'text',
        'label'       => __('WhatsApp Number', 'woocommerce'),
        'placeholder' => __('Enter your WhatsApp number'),
        'required'    => false,
        'class'       => array('form-row-wide'),
        'priority'    => 25,
    );

    return $fields;
}

/**
 * Save custom checkout field
 */
add_action('woocommerce_checkout_update_order_meta', 'rk_save_custom_checkout_field');

function rk_save_custom_checkout_field($order_id)
{
    if (!empty($_POST['billing_whatsapp'])) {
        update_post_meta(
            $order_id,
            '_billing_whatsapp',
            sanitize_text_field($_POST['billing_whatsapp'])
        );
    }
}

/**
 * Display custom field in admin order page
 */
add_action('woocommerce_admin_order_data_after_billing_address', 'rk_display_whatsapp_admin_order_meta', 10, 1);

function rk_display_whatsapp_admin_order_meta($order)
{
    $whatsapp = get_post_meta($order->get_id(), '_billing_whatsapp', true);

    if ($whatsapp) {
        echo '<p><strong>WhatsApp Number:</strong> ' . esc_html($whatsapp) . '</p>';
    }
}

/**
 * Add checkout optimization notice
 */
add_action('woocommerce_before_checkout_form', 'rk_checkout_notice');

function rk_checkout_notice()
{
    echo '<div class="woocommerce-message" style="margin-bottom:20px;">
    Fast & Secure Checkout Experience
    </div>';
}

/**
 * Optimize checkout placeholder text
 */
add_filter('woocommerce_checkout_fields', 'rk_customize_checkout_placeholders');

function rk_customize_checkout_placeholders($fields)
{
    $fields['billing']['billing_first_name']['placeholder'] = 'First Name';
    $fields['billing']['billing_last_name']['placeholder'] = 'Last Name';
    $fields['billing']['billing_phone']['placeholder'] = 'Phone Number';
    $fields['billing']['billing_email']['placeholder'] = 'Email Address';

    return $fields;
}
<?php
/**
 * XuWbk 前台用户管理功能
 * Description:  前台管理用户积分、余额、VIP、等级、身份认证的功能
 * Version:      1.0.1
 * Author:       XuWbk
 * Requires PHP: 7.0-8.5
 */

// 防止直接访问
if (!defined('ABSPATH')) {
    exit;
}

// 定义功能常量
define('XUWBK_USER_MANAGE_VERSION', '1.0.1');
define('XUWBK_USER_MANAGE_PATH', dirname(__FILE__));
define('XUWBK_USER_MANAGE_URL', get_stylesheet_directory_uri() . '/page/xuwbk-user/');

// 引入必要的文件
require_once XUWBK_USER_MANAGE_PATH . '/functions.php';
require_once XUWBK_USER_MANAGE_PATH . '/ajax.php';

/**
 * 前台引入资源文件
 */
function xuwbk_user_manage_enqueue_scripts()
{
    // 只在前台且用户已登录时加载
    if (is_admin() || !is_user_logged_in()) {
        return;
    }

    // 只有管理员才加载
    if (!current_user_can('manage_options')) {
        return;
    }

    wp_enqueue_style(
        'xuwbk_user_manage_style',
        XUWBK_USER_MANAGE_URL . 'assets/css/main.css',
        array(),
        XUWBK_USER_MANAGE_VERSION
    );

    wp_enqueue_script(
        'xuwbk_user_manage_script',
        XUWBK_USER_MANAGE_URL . 'assets/js/main.js',
        array('jquery'),
        XUWBK_USER_MANAGE_VERSION,
        true
    );

    // 传递AJAX URL到JS
    wp_localize_script('xuwbk_user_manage_script', 'xuwbk_user_manage', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('xuwbk_user_manage_nonce'),
    ));
}
add_action('wp_enqueue_scripts', 'xuwbk_user_manage_enqueue_scripts');

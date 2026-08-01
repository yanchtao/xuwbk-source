<?php
/**
 * 《主题函数》入口文件
 * @package XuWbk
 * @author 轩玮
 * @version 1.0.0
 * @created 2024-09-29 13:18:36
 * @modified 2025-06-23 16:27:00
 * @description 由《轩玮博客》开发用于WordPress to zibll主题的美化《XuWbk主题》
 * @contact QQ:6050640 邮箱：6050640@qq.com 网址：www.xuwbk.com
 * @copyright Copyright (c) 2025 by XuWbk.Com, All Rights Reserved.
 */

// 引入父主题核心函数
require_once get_theme_file_path('/inc/inc.php');

// ========== Zib_CFSwidget 完全自包含包装（彻底避免 CSF_Widget 类冲突） ==========
// 不再调用 Zib_CFSwidget 的任何方法，而是完全复制其逻辑

if (!function_exists('xuwbk_cfswidget_show_class')) {
    function xuwbk_cfswidget_show_class($instance) {
        $show_type = isset($instance['show_type']) ? $instance['show_type'] : 'all';

        $wp_is_mobile = wp_is_mobile();
        if ($show_type == 'only_pc' && $wp_is_mobile) {
            return '';
        }
        if ($show_type == 'only_sm' && !$wp_is_mobile) {
            return '';
        }

        if (!empty($instance['show_id_type']) && !empty($instance['show_ids'])) {
            if (is_singular()) {
                $the_id   = get_the_ID();
                $show_ids = preg_split("/,|，|\s|\n/", $instance['show_ids']);
                if ($instance['show_id_type'] == 'show' && !in_array($the_id, $show_ids)) {
                    return '';
                }
                if ($instance['show_id_type'] == 'hide' && in_array($the_id, $show_ids)) {
                    return '';
                }
            }
        }

        if ($show_type == 'only_pc') {
            return 'hidden-xs';
        }
        if ($show_type == 'only_sm') {
            return 'visible-xs-block';
        }

        return true;
    }
}

if (!function_exists('xuwbk_cfswidget_show_title')) {
    function xuwbk_cfswidget_show_title($instance = array()) {
        if (empty($instance['title'])) {
            return '';
        }

        $title    = $instance['title'];
        $subtitle = !empty($instance['subtitle']) ? $instance['subtitle'] : '';
        $subtitle = $subtitle ? '<small class="ml10">' . $subtitle . '</small>' : '';

        $link_url   = !empty($instance['title_link']['url']) ? $instance['title_link']['url'] : '';
        $link_text  = !empty($instance['title_link']['text']) ? $instance['title_link']['text'] : '<i class="fa fa-angle-right fa-fw"></i>更多';
        $link_blank = !empty($instance['title_link']['target']) && $instance['title_link']['target'] == '_blank' ? ' target="_blank"' : '';

        $more_but = $link_url ? '<div class="pull-right em09 mt3"><a' . $link_blank . ' href="' . esc_url($link_url) . '" class="muted-2-color">' . $link_text . '</a></div>' : '';

        return '<div class="box-body notop"><div class="title-theme">' . $title . $subtitle . $more_but . '</div></div>';
    }
}

if (!function_exists('xuwbk_cfswidget_echo_before')) {
    function xuwbk_cfswidget_echo_before($instance = array(), $class = 'mb20', $wp_args = array()) {
        $show_class = xuwbk_cfswidget_show_class($instance);
        if ($show_class && $show_class !== true) {
            $class .= ' ' . $show_class;
        }

        $affix = !empty($instance['sidebar_affix']) ? ' data-affix="true"' : '';

        // 入场动画
        $animation      = !empty($instance['animation_in']) ? $instance['animation_in'] : '';
        $animation_attr = $animation ? ' data-animation="' . esc_attr($animation) . '"' : '';
        $animation_attr .= $animation && !empty($instance['animation_repeat']) ? ' data-animation-repeat="true"' : '';
        if ($animation_attr && $animation) {
            $class .= ' obs-animate ani-' . $animation;
        }

        $title = xuwbk_cfswidget_show_title($instance);

        do_action('zib_cfswidget_echo_before', $instance, $class);
        $class_attr = $class ? ' class="' . esc_attr($class) . '"' : '';

        echo '<div' . $affix . $class_attr . $animation_attr . '>';
        echo $title;
    }
}

if (!function_exists('xuwbk_cfswidget_echo_after')) {
    function xuwbk_cfswidget_echo_after($instance = array(), $wp_args = array()) {
        echo '</div>';
        do_action('zib_cfswidget_echo_after', $instance);
    }
}

// 引入子主题核心函数
require_once get_theme_file_path('/core/core.php');

// 引入数据管理器（处理主题数据初始化和修复）
require_once get_theme_file_path('/core/functions/component/xuwbk-data-manager.php');

// 引入用户状态功能（优化版）
if (file_exists(get_theme_file_path('/core/functions/article/XuWau_status/user-status-optimized.php'))) {
    require_once get_theme_file_path('/core/functions/article/XuWau_status/user-status-optimized.php');
}

// 引入广告订单支付页面
if (file_exists(get_theme_file_path('/pages/xuwbk_ad_order_payment.php'))) {
    require_once get_theme_file_path('/pages/xuwbk_ad_order_payment.php');
}

// 引入自定义函数文件
if (file_exists(get_theme_file_path('/func.php'))) {
    require_once get_theme_file_path('/func.php');
}

// 引入单行文章列表小部件（覆盖父主题）
if (file_exists(get_theme_file_path('/inc/widgets/xuwbk-oneline-posts.php'))) {
    require_once get_theme_file_path('/inc/widgets/xuwbk-oneline-posts.php');
}

// 禁用自动保存
add_action('wp_print_scripts', 'disable_autosave');
function disable_autosave() {
    wp_deregister_script('autosave');
}

// 添加全站复制提醒功能
add_action('wp_footer', 'xuwbk_copy_reminder');
function xuwbk_copy_reminder() {
    // 检查是否已经输出过
    static $copy_script_loaded = false;
    if ($copy_script_loaded) {
        return;
    }
    $copy_script_loaded = true;
    
    // 只在内容页加载
    if (!is_single() && !is_page()) {
        return;
    }
    ?>
    <script>
    (function() {
        'use strict';
        
        // 配置常量
        const CONFIG = {
            SUCCESS: {
                title: '叮！复制成功',
                message: '若要转载请务必保留原文链接！谢谢~',
                type: 'success',
                color: '#67C23A'
            },
            ERROR: {
                title: '咦？复制失败',
                message: '啊噢...你没还没选择内容呢！',
                type: 'warning',
                color: '#E6A23C'
            },
            DURATION: 3000,
            POSITION: 'bottom-right',
            OFFSET: 50
        };
        
        // 获取选中的文本
        function getSelectedText() {
            return window.getSelection ? window.getSelection().toString() : document.selection.createRange().text;
        }
        
        // Vue通知方法
        function showVueNotification(data) {
            if (typeof window.Vue !== 'undefined') {
                try {
                    const vm = new window.Vue();
                    vm.$notify({
                        title: data.title,
                        message: data.message,
                        position: CONFIG.POSITION,
                        offset: CONFIG.OFFSET,
                        showClose: true,
                        type: data.type
                    });
                    return true;
                } catch (e) {
                    console.warn('Vue通知调用失败:', e);
                }
            }
            return false;
        }
        
        // 原生JavaScript通知方法
        function showNativeNotification(data) {
            // 移除已存在的提示
            const existingToast = document.querySelector('.copy-toast');
            if (existingToast) {
                existingToast.remove();
            }
            
            const toast = document.createElement('div');
            toast.className = 'copy-toast';
            toast.innerHTML = `<strong>${data.title}</strong><br>${data.message}`;
            
            // 样式
            Object.assign(toast.style, {
                position: 'fixed',
                bottom: '20px',
                right: '20px',
                background: data.color,
                color: 'white',
                padding: '15px 20px',
                borderRadius: '8px',
                zIndex: '10000',
                fontSize: '14px',
                lineHeight: '1.4',
                boxShadow: '0 4px 12px rgba(0,0,0,0.15)',
                maxWidth: '300px'
            });
            
            document.body.appendChild(toast);
            
            // 自动移除
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.style.opacity = '0';
                    toast.style.transform = 'translateX(100%)';
                    toast.style.transition = 'all 0.3s ease';
                    setTimeout(() => toast.remove(), 300);
                }
            }, CONFIG.DURATION);
            
            // 点击关闭
            toast.addEventListener('click', () => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateX(100%)';
                toast.style.transition = 'all 0.3s ease';
                setTimeout(() => toast.remove(), 300);
            });
        }
        
        // 主复制事件处理函数
        function handleCopy() {
            const selectedText = getSelectedText();
            const data = selectedText.trim() ? CONFIG.SUCCESS : CONFIG.ERROR;
            
            // 优先尝试Vue通知，失败则使用原生通知
            if (!showVueNotification(data)) {
                showNativeNotification(data);
            }
        }
        
        // 绑定复制事件
        document.addEventListener('copy', handleCopy);
        
    })();
    </script>
    <?php
}

// 隐藏多余的评论输入框
add_action('wp_head', 'xuwbk_hide_extra_comment_box');
function xuwbk_hide_extra_comment_box() {
    ?>
    <style>
    .virtual-input, .fixed-input {
        display: none !important;
    }
    </style>
    <?php
}

/**
 * 修改ZibPay订单类型名称
 * 将广告订单的通知消息中的"付费阅读"改为"广告购买"
 */

/**
 * 广告订单价格缓存查询（避免同一请求中多次 $wpdb 查询）
 * @param int    $order_id   订单ID
 * @param string $order_num  订单号（备选）
 * @return float 订单价格
 */
function xuwbk_ad_get_cached_price($order_id = 0, $order_num = '') {
    static $price_cache = array();
    $key = $order_id ? 'id_' . $order_id : 'num_' . $order_num;
    if (isset($price_cache[$key])) return $price_cache[$key];
    
    global $wpdb;
    if ($order_id) {
        $price = $wpdb->get_var($wpdb->prepare(
            "SELECT order_price FROM {$wpdb->prefix}zibpay_order WHERE id = %d", $order_id
        ));
    } elseif ($order_num) {
        $price = $wpdb->get_var($wpdb->prepare(
            "SELECT order_price FROM {$wpdb->prefix}zibpay_order WHERE order_num = %s", $order_num
        ));
    } else {
        $price = 0;
    }
    $price_cache[$key] = floatval($price);
    return $price_cache[$key];
}

/**
 * 统一广告订单消息修复
 */
function xuwbk_ad_fix_message($content, $order_price) {
    $content = str_replace('付费阅读', '广告购买', $content);
    $content = str_replace('类型：付费阅读', '类型：广告购买', $content);
    if ($order_price > 0) {
        $content = preg_replace('/(付款明细：|金额：|已支付：|-)￥0(?:\.0+)?/', '$1￥' . number_format($order_price, 2), $content);
    }
    return $content;
}
add_filter('zibpay_payment_success_msg', 'xuwbk_ad_payment_success_msg_filter', 10, 2);
function xuwbk_ad_payment_success_msg_filter($msg, $order) {
    // 检查是否是广告订单
    $product_id = isset($order->product_id) ? $order->product_id : '';
    if (strpos($product_id, 'xuwbk_ad_') === false) {
        return $msg;
    }

    // 替换消息中的"付费阅读"为"广告购买"
    $msg = str_replace('付费阅读', '广告购买', $msg);
    $msg = str_replace('类型：付费阅读', '类型：广告购买', $msg);

    // 确保金额正确显示
    $order_price = isset($order->order_price) ? $order->order_price : 0;
    if ($order_price > 0) {
        // 替换金额为0的情况
        $msg = preg_replace('/付款明细：￥0\.0+/', '付款明细：￥' . number_format($order_price, 2), $msg);
        $msg = preg_replace('/金额：￥0\.0+/', '金额：￥' . number_format($order_price, 2), $msg);
    }

    return $msg;
}

/**
 * 修改支付成功钩子中的订单数据
 * 在触发通知前修改订单信息
 */
add_action('payment_order_success', 'xuwbk_ad_modify_order_before_notice', 1, 1);
function xuwbk_ad_modify_order_before_notice($order) {
    // 检查是否是广告订单
    $product_id = isset($order->product_id) ? $order->product_id : '';
    if (strpos($product_id, 'xuwbk_ad_') === false) {
        return;
    }

    // 临时修改订单类型为特殊值,让通知系统能识别
    global $xuwbk_current_order_is_ad;
    $xuwbk_current_order_is_ad = true;
}

/**
 * 修复ZibPay通知消息中的金额显示
 * 确保订单通知显示正确的支付金额
 */
add_filter('zibpay_payment_notice_msg', 'xuwbk_ad_payment_notice_msg_filter', 10, 2);
function xuwbk_ad_payment_notice_msg_filter($msg, $order) {
    // 检查是否是广告订单
    $product_id = isset($order->product_id) ? $order->product_id : '';
    if (strpos($product_id, 'xuwbk_ad_') === false) {
        return $msg;
    }

    // 替换"付费阅读"为"广告购买"
    $msg = str_replace('付费阅读', '广告购买', $msg);
    $msg = str_replace('类型：付费阅读', '类型：广告购买', $msg);

    // 确保金额正确显示
    $order_price = isset($order->order_price) ? $order->order_price : 0;
    if ($order_price > 0) {
        // 替换金额为0的情况
        $msg = preg_replace('/付款明细：￥0\.0+/', '付款明细：￥' . number_format($order_price, 2), $msg);
        $msg = preg_replace('/金额：￥0\.0+/', '金额：￥' . number_format($order_price, 2), $msg);
    }

    return $msg;
}

/**
 * 修改ZibPay用户消息通知
 * 修改用户收到的所有通知消息
 */
add_filter('zibpay_user_notice_msg', 'xuwbk_ad_user_notice_msg_filter', 10, 2);
function xuwbk_ad_user_notice_msg_filter($msg, $order) {
    // 检查是否是广告订单
    $product_id = isset($order->product_id) ? $order->product_id : '';
    if (strpos($product_id, 'xuwbk_ad_') === false) {
        return $msg;
    }

    // 替换"付费阅读"为"广告购买"
    $msg = str_replace('付费阅读', '广告购买', $msg);
    $msg = str_replace('类型：付费阅读', '类型：广告购买', $msg);

    // 确保金额正确显示
    $order_price = isset($order->order_price) ? $order->order_price : 0;
    if ($order_price > 0) {
        // 替换金额为0的情况
        $msg = preg_replace('/付款明细：￥0\.0+/', '付款明细：￥' . number_format($order_price, 2), $msg);
        $msg = preg_replace('/金额：￥0\.0+/', '金额：￥' . number_format($order_price, 2), $msg);
    }

    return $msg;
}

/**
 * 修改用户消息通知的内容
 * 针对所有类型的通知消息
 */
add_filter('zibll_user_msg_content', 'xuwbk_ad_user_msg_content_filter', 999, 3);

/**
 * 直接拦截用户消息添加过程
 * 确保广告订单的消息正确显示
 */
add_filter('zib_user_message_data', 'xuwbk_ad_user_message_data_filter', 999, 2);
function xuwbk_ad_user_message_data_filter($message_data, $order_data) {
    // 检查是否是广告订单
    if (!isset($order_data['product_id']) || strpos($order_data['product_id'], 'xuwbk_ad_') === false) {
        return $message_data;
    }

    // 确保消息内容正确
    if (isset($message_data['content'])) {
        $order_price = isset($order_data['order_price']) ? $order_data['order_price'] : 0;

        if ($order_price > 0) {
            // 替换"付费阅读"为"广告购买"
            $message_data['content'] = str_replace('付费阅读', '广告购买', $message_data['content']);
            
            // 替换金额显示
            $message_data['content'] = preg_replace('/￥0(?:\.0+)?/', '￥' . number_format($order_price, 2), $message_data['content']);
            $message_data['content'] = preg_replace('/付款明细：￥0(?:\.0+)?/', '付款明细：￥' . number_format($order_price, 2), $message_data['content']);
        }
    }

    return $message_data;
}

/**
 * 直接修改用户消息内容
 * 在消息保存到数据库前进行修改
 */
add_filter('zib_user_message_content', 'xuwbk_ad_user_message_content_filter', 999, 2);
function xuwbk_ad_user_message_content_filter($content, $order_data) {
    // 检查是否是广告订单
    if (!isset($order_data['product_id']) || strpos($order_data['product_id'], 'xuwbk_ad_') === false) {
        return $content;
    }

    $order_price = isset($order_data['order_price']) ? $order_data['order_price'] : 0;
    
    if ($order_price > 0) {
        // 替换"付费阅读"为"广告购买"
        $content = str_replace('付费阅读', '广告购买', $content);
        
        // 替换金额显示
        $content = preg_replace('/￥0(?:\.0+)?/', '￥' . number_format($order_price, 2), $content);
        $content = preg_replace('/付款明细：￥0(?:\.0+)?/', '付款明细：￥' . number_format($order_price, 2), $content);
    }

    return $content;
}
function xuwbk_ad_user_msg_content_filter($content, $msg_type, $msg_data) {
    // 检查是否是支付成功消息
    if ($msg_type !== 'pay_success') {
        return $content;
    }

    // 检查是否是广告订单
    if (!isset($msg_data['order']) || !isset($msg_data['order']['product_id'])) {
        return $content;
    }

    $product_id = $msg_data['order']['product_id'];
    if (strpos($product_id, 'xuwbk_ad_') === false) {
        return $content;
    }

    // 替换"付费阅读"为"广告购买"
    $content = str_replace('付费阅读', '广告购买', $content);
    $content = str_replace('类型：付费阅读', '类型：广告购买', $content);

    // 确保金额正确显示
    $order_price = isset($msg_data['order']['order_price']) ? $msg_data['order']['order_price'] : 0;

    // 尝试从其他字段获取价格
    if ($order_price == 0) {
        $order_price = isset($msg_data['order']['price']) ? $msg_data['order']['price'] : 0;
    }
    if ($order_price == 0) {
        $order_price = isset($msg_data['price']) ? $msg_data['price'] : 0;
    }
    if ($order_price == 0) {
        $order_price = isset($msg_data['amount']) ? $msg_data['amount'] : 0;
    }

    // 价格缓存查询
    if ($order_price == 0 && isset($msg_data['order']['id'])) {
        $order_price = xuwbk_ad_get_cached_price($msg_data['order']['id']);
    }

    if ($order_price > 0) {
        $content = xuwbk_ad_fix_message($content, $order_price);
    }

    return $content;
}

/**
 * 修改订单通知消息
 * 在发送通知前修改消息内容
 */
add_filter('zibpay_order_notice', 'xuwbk_ad_order_notice_filter', 10, 2);
function xuwbk_ad_order_notice_filter($notice_data, $order) {
    // 检查是否是广告订单
    $product_id = isset($order->product_id) ? $order->product_id : '';
    if (strpos($product_id, 'xuwbk_ad_') === false) {
        return $notice_data;
    }

    // 修改通知内容
    if (isset($notice_data['msg'])) {
        $notice_data['msg'] = str_replace('付费阅读', '广告购买', $notice_data['msg']);
        $notice_data['msg'] = str_replace('类型：付费阅读', '类型：广告购买', $notice_data['msg']);

        // 修正金额
        $order_price = isset($order->order_price) ? $order->order_price : 0;

        // 尝试从其他字段获取价格
        if ($order_price == 0) {
            $order_price = isset($order->price) ? $order->price : 0;
        }
        if ($order_price == 0) {
            $order_price = isset($notice_data['price']) ? $notice_data['price'] : 0;
        }

        if ($order_price > 0) {
            // 替换所有金额为0的情况，包括各种格式
            $notice_data['msg'] = preg_replace('/付款明细：￥0\.0+/', '付款明细：￥' . number_format($order_price, 2), $notice_data['msg']);
            $notice_data['msg'] = preg_replace('/金额：￥0\.0+/', '金额：￥' . number_format($order_price, 2), $notice_data['msg']);
            $notice_data['msg'] = preg_replace('/-金额：￥0\.0+/', '-金额：￥' . number_format($order_price, 2), $notice_data['msg']);
        }
    }

    return $notice_data;
}

/**
 * 添加广告订单详情过滤器
 * 修改订单数据的显示
 */
add_filter('zibpay_order_data', 'xuwbk_ad_order_data_filter', 10, 2);
function xuwbk_ad_order_data_filter($order_data, $order_id) {
    // 检查是否是广告订单
    if (empty($order_data['product_id']) || strpos($order_data['product_id'], 'xuwbk_ad_') === false) {
        return $order_data;
    }

    // 订单数据过滤处理

    // 获取订单的other数据
    $other = isset($order_data['other']) ? maybe_unserialize($order_data['other']) : array();
    if (empty($other) || !is_array($other)) {
        return $order_data;
    }

    // 构建广告详情HTML用于显示
    $ad_details_html = '';
    if (!empty($other['slot_name'])) {
        $ad_details_html .= '<div style="margin-bottom: 5px;"><strong>广告位:</strong> ' . esc_html($other['slot_name']) . '</div>';
    }
    if (!empty($other['start_date']) && !empty($other['end_date'])) {
        $ad_details_html .= '<div style="margin-bottom: 5px;"><strong>投放周期:</strong> ' . esc_html($other['start_date']) . ' 至 ' . esc_html($other['end_date']) . '</div>';
    }
    if (!empty($other['contact_method']) && !empty($other['contact_value'])) {
        $ad_details_html .= '<div style="margin-bottom: 5px;"><strong>联系方式:</strong> ' . esc_html($other['contact_method']) . ' - ' . esc_html($other['contact_value']) . '</div>';
    }
    if (!empty($other['ad_link'])) {
        $ad_details_html .= '<div style="margin-bottom: 5px;"><strong>链接:</strong> ' . esc_html($other['ad_link']) . '</div>';
    }
    if (!empty($other['ad_description'])) {
        $ad_details_html .= '<div style="margin-bottom: 5px;"><strong>描述:</strong> ' . esc_html($other['ad_description']) . '</div>';
    }

    // 如果有广告图片，添加缩略图
    if (!empty($other['ad_image'])) {
        $order_data['product_info']['thumbnail'] = $other['ad_image'];
    }

    // 将广告详情添加到opt_name或title
    if (!empty($ad_details_html)) {
        $order_data['product_info']['opt_name'] = '<div style="background: #f5f7fa; padding: 10px; border-radius: 4px; font-size: 12px; line-height: 1.6;">' . $ad_details_html . '</div>';
    }

    return $order_data;
}

/**
 * 修复广告订单价格
 * 在订单保存前确保价格正确
 */
add_filter('zibpay_add_order_data', 'xuwbk_ad_fix_order_price', 10, 2);
function xuwbk_ad_fix_order_price($order_data, $product_id) {
    // 检查是否是广告订单
    if (empty($product_id) || strpos($product_id, 'xuwbk_ad_') === false) {
        return $order_data;
    }

    // 修复广告订单价格

    // 确保order_price大于0
    if (isset($order_data['order_price']) && $order_data['order_price'] <= 0) {
        // 尝试从other数据中获取价格
        $other = isset($order_data['other']) ? (is_array($order_data['other']) ? $order_data['other'] : maybe_unserialize($order_data['other'])) : array();
        if (!empty($other) && is_array($other)) {
            // 检查other中是否有价格信息
            if (isset($other['order_price']) && $other['order_price'] > 0) {
                $order_data['order_price'] = floatval($other['order_price']);
            }
        }

        // 如果仍然为0，设置为默认值
        if ($order_data['order_price'] <= 0) {
            $order_data['order_price'] = 1; // 设置最小价格
        }
    }

    return $order_data;
}

/**
 * 修改ZibPay后台订单显示
 * 将广告订单的"付费阅读"改为"广告购买"
 */
add_action('admin_footer', 'xuwbk_ad_order_type_display_fix');
function xuwbk_ad_order_type_display_fix() {
    $current_page = isset($_GET['page']) ? $_GET['page'] : '';
    if ($current_page !== 'zibpay_order_page') {
        return;
    }
    ?>
    <script>
    (function() {
        // 检测是否是广告订单的函数
        function isAdOrder(rowData) {
            if (!rowData) return false;
            var productId = rowData.product_id || '';
            var productName = rowData.product_name || '';
            var orderNum = rowData.order_num || '';

            // 通过多种方式检测广告订单
            return (productId.indexOf('xuwbk_ad_') === 0 ||
                    productId.indexOf('img-slot-') !== -1 ||
                    productName.indexOf('img-slot-') !== -1 ||
                    orderNum.indexOf('ad-') !== -1);
        }

        // 拦截Vue的数据更新
        function interceptVueData() {
            // 尝试通过Vue DevTools Hook修改数据
            if (typeof Vue !== 'undefined') {
                // 等待Vue实例挂载
                setTimeout(function() {
                    updateOrderTypeDisplay();
                }, 1000);
            }
        }

        // 更新订单类型显示（优化版）
        function updateOrderTypeDisplay() {
            var fixedCount = 0;
            
            // 查找所有包含"付费阅读"或"￥0"的文本节点
            var walker = document.createTreeWalker(
                document.body,
                NodeFilter.SHOW_TEXT,
                {
                    acceptNode: function(node) {
                        var text = node.textContent || '';
                        if (text.includes('付费阅读') || text.includes('￥0')) {
                            return NodeFilter.FILTER_ACCEPT;
                        }
                        return NodeFilter.FILTER_REJECT;
                    }
                },
                false
            );

            var textNodes = [];
            while(walker.nextNode()) {
                textNodes.push(walker.currentNode);
            }

            textNodes.forEach(function(node) {
                var text = node.textContent || '';
                var parent = node.parentElement;
                if (!parent) return;

                var parentHTML = parent.innerHTML || '';
                var isAdOrder = parentHTML.includes('xuwbk_ad_') || 
                               parentHTML.includes('img-slot-') || 
                               parentHTML.includes('ad-');
                
                if (!isAdOrder) return;

                var changed = false;

                // 修复"付费阅读"为"广告购买"
                if (text === '付费阅读' || text === '付费阅读无需发货') {
                    node.textContent = text.replace('付费阅读', '广告购买');
                    changed = true;
                    fixedCount++;
                }

                // 修复金额显示
                if (text.includes('已支付：￥0') || text.includes('付款明细：￥0')) {
                    // 尝试从父元素获取真实价格
                    var parentRow = parent.closest('tr, [class*="order"]');
                    if (parentRow) {
                        var rowHTML = parentRow.innerHTML;
                        var priceMatch = rowHTML.match(/price["\s:]+(\d+\.?\d*)/);
                        if (priceMatch) {
                            var realPrice = parseFloat(priceMatch[1]).toFixed(2);
                            node.textContent = text.replace(/￥0\.0+/, '￥' + realPrice);
                            changed = true;
                            fixedCount++;
                        }
                    }
                }
            });
        }

        // 初始执行
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function() {
                interceptVueData();
                updateOrderTypeDisplay();
            });
        } else {
            interceptVueData();
            updateOrderTypeDisplay();
        }

        // 监听URL变化
        var lastUrl = location.href;
        new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (location.href !== lastUrl) {
                    lastUrl = location.href;
                    updateOrderTypeDisplay();
                }
            });
        }).observe(document.body, { childList: true, subtree: true });
    })();
    </script>
    <?php
}

/**
 * 前端广告订单显示修复（统一版）
 * 合并了订单类型名称和消息金额显示修复，使用单一 MutationObserver
 */
add_action('wp_footer', 'xuwbk_ad_frontend_unified_fix');
function xuwbk_ad_frontend_unified_fix() {
    if (!is_user_logged_in()) return;
    ?>
    <script>
    (function() {
        var priceCache = {};
        var AD_MARKERS = ['xuwbk_ad_', 'img-slot-', '260214'];

        function isAdRelated(text, html) {
            for (var i = 0; i < AD_MARKERS.length; i++) {
                if (html.indexOf(AD_MARKERS[i]) !== -1) return true;
            }
            return (text.indexOf('￥0') !== -1 && text.indexOf('订单号') !== -1);
        }

        function tryGetPrice(node) {
            var text = node.textContent || '';
            var orderMatch = text.match(/订单号\[(\d+)\]/);
            if (!orderMatch) return null;
            var orderNum = orderMatch[1];
            if (priceCache[orderNum]) return priceCache[orderNum];

            // 从Vue数据获取
            if (typeof window.vueData !== 'undefined' && window.vueData.orders) {
                var order = window.vueData.orders.find(function(o) { return o.order_num === orderNum; });
                if (order && order.order_price > 0) {
                    return (priceCache[orderNum] = order.order_price.toFixed(2));
                }
            }
            // 从DOM属性获取
            var el = document.querySelector('[data-order-num="' + orderNum + '"][data-order-price]');
            if (el && parseFloat(el.getAttribute('data-order-price')) > 0) {
                return (priceCache[orderNum] = parseFloat(el.getAttribute('data-order-price')).toFixed(2));
            }
            // 从文本中尝试匹配
            var priceMatch = node.parentElement ? node.parentElement.innerHTML.match(/price["\s:]+(\d+\.?\d*)/) : null;
            if (priceMatch) {
                return (priceCache[orderNum] = parseFloat(priceMatch[1]).toFixed(2));
            }
            return null;
        }

        function fixTextNode(node) {
            var text = node.textContent || '';
            if (!text || (text.indexOf('付费阅读') === -1 && text.indexOf('￥0') === -1)) return false;

            var parent = node.parentElement;
            if (!parent) return false;

            var parentHTML = parent.innerHTML || '';
            if (!isAdRelated(text, parentHTML)) return false;

            var newText = text;
            if (text.indexOf('付费阅读') !== -1) {
                newText = newText.replace(/付费阅读/g, '广告购买');
            }
            if (text.indexOf('￥0') !== -1) {
                var price = tryGetPrice(node);
                if (price) newText = newText.replace(/￥0(?:\.0+)?/g, '￥' + price);
            }

            if (newText !== text) {
                node.textContent = newText;
                return true;
            }
            return false;
        }

        function scanAndFix() {
            var walker = document.createTreeWalker(document.body, NodeFilter.SHOW_TEXT, null, false);
            while (walker.nextNode()) fixTextNode(walker.currentNode);
        }

        function throttle(fn, wait) {
            var timer, last = 0;
            return function() {
                var now = Date.now(), remaining = wait - (now - last);
                if (remaining <= 0) { last = now; fn(); }
                else if (!timer) timer = setTimeout(function() { last = Date.now(); timer = null; fn(); }, remaining);
            };
        }

        var throttledScan = throttle(scanAndFix, 1000);

        scanAndFix();

        new MutationObserver(function(mutations) {
            if (mutations.some(function(m) { return m.addedNodes && m.addedNodes.length; })) {
                throttledScan();
            }
        }).observe(document.body, { childList: true, subtree: true });
    })();
    </script>
    <?php
}

/**
 * 修改ZibPay订单类型名称
 * 通过过滤器修改后台和前端的订单类型显示
 */
add_filter('zibpay_vue_data_filter', 'xuwbk_ad_order_type_name_filter', 999, 1);
function xuwbk_ad_order_type_name_filter($vue_data) {
    // 检查是否有order_type_name
    if (!isset($vue_data['order_type_name'])) {
        return $vue_data;
    }

    // 直接修改order_type=1的名称为"广告购买"
    // 这样所有使用order_type=1的订单都会显示为"广告购买"
    // 但我们需要区分广告订单和真正的付费阅读订单
    // 由于在Vue数据层面无法判断,我们在JavaScript中处理

    // 添加一个用于JavaScript检测的标记
    $vue_data['xuwbk_ad_type_id'] = '1';

    return $vue_data;
}

/**
 * 修改ZibPay订单数据中的订单类型名称
 * 这将影响后台订单列表的显示
 */
add_filter('zibpay_order_data', 'xuwbk_ad_order_type_name_in_order_data', 5, 2);
function xuwbk_ad_order_type_name_in_order_data($order_data, $order_id) {
    // 检查是否是广告订单
    if (empty($order_data['product_id']) || strpos($order_data['product_id'], 'xuwbk_ad_') === false) {
        return $order_data;
    }

    // 修改订单类型名称显示
    if (isset($order_data['order_type']) && $order_data['order_type'] == 1) {
        // 在product_info中添加自定义的订单类型名称
        if (!isset($order_data['product_info'])) {
            $order_data['product_info'] = array();
        }
        $order_data['product_info']['custom_order_type_name'] = '广告购买';
    }

    return $order_data;
}

/**
 * 核心消息修复系统
 * 在WordPress所有可能的消息生成点进行拦截
 */

/**
 * 1. 在消息保存到数据库前进行拦截
 */
add_filter('pre_insert_user_message', 'xuwbk_ad_pre_insert_message_filter', 999, 3);
function xuwbk_ad_pre_insert_message_filter($message_data, $order_data, $message_type) {
    // 检查是否是广告订单
    if (!isset($order_data['product_id']) || strpos($order_data['product_id'], 'xuwbk_ad_') === false) {
        return $message_data;
    }

    // 消息保存前处理

    return xuwbk_ad_fix_message_content($message_data, $order_data);
}

/**
 * 2. 在消息内容生成时进行拦截
 */
add_filter('the_content', 'xuwbk_ad_message_content_filter', 999);
function xuwbk_ad_message_content_filter($content) {
    // 检查是否是用户消息页面
    if (!is_user_logged_in() || !function_exists('zib_get_user_messages')) {
        return $content;
    }

    // 检查内容中是否包含广告订单的标识
    if (strpos($content, 'xuwbk_ad_') !== false || 
        (strpos($content, '￥0') !== false && strpos($content, '订单号') !== false)) {
        
        // 尝试修复内容
        return xuwbk_ad_fix_displayed_content($content);
    }

    return $content;
}

/**
 * 3. 消息内容修复函数
 */
function xuwbk_ad_fix_message_content($message_data, $order_data) {
    if (!isset($message_data['content'])) return $message_data;
    
    $order_price = isset($order_data['order_price']) ? floatval($order_data['order_price']) : 0;
    if ($order_price == 0 && isset($order_data['id'])) {
        $order_price = xuwbk_ad_get_cached_price($order_data['id']);
    }
    if ($order_price == 0) {
        global $xuwbk_ad_current_order;
        if (!empty($xuwbk_ad_current_order['price'])) $order_price = floatval($xuwbk_ad_current_order['price']);
    }
    
    $message_data['content'] = xuwbk_ad_fix_message($message_data['content'], $order_price);
    return $message_data;
}

/**
 * 4. 显示内容修复函数
 */
function xuwbk_ad_fix_displayed_content($content) {
    preg_match('/订单号\[(\d+)\]/', $content, $order_matches);
    if (empty($order_matches)) return $content;
    
    $order_price = xuwbk_ad_get_cached_price(0, $order_matches[1]);
    if ($order_price <= 0) return $content;
    
    return xuwbk_ad_fix_message($content, $order_price);
}



/**
 * 强力广告订单消息修复系统
 */

/**
 * 直接修改用户消息内容 - 最高优先级
 */
add_filter('zibll_user_msg_content', 'xuwbk_ad_force_msg_fix', 9999, 3);
function xuwbk_ad_force_msg_fix($content, $msg_type, $msg_data) {
    // 只处理支付成功消息
    if ($msg_type !== 'pay_success') {
        return $content;
    }

    // 消息修复系统

    // 检查是否是广告订单 - 多种检测方式
    $is_ad_order = false;
    $order_data = null;
    
    // 方式1：通过product_id检测
    if (isset($msg_data['order']) && isset($msg_data['order']['product_id'])) {
        $product_id = $msg_data['order']['product_id'];
        if (strpos($product_id, 'xuwbk_ad_') !== false) {
            $is_ad_order = true;
            $order_data = $msg_data['order'];
        }
    }

    // 方式2：通过订单号检测（260214开头的订单）
    if (!$is_ad_order && isset($msg_data['order']) && isset($msg_data['order']['order_num'])) {
        $order_num = $msg_data['order']['order_num'];
        if (strpos($order_num, '260214') === 0) {
            $is_ad_order = true;
            $order_data = $msg_data['order'];
        }
    }

    // 方式3：通过内容检测
    if (!$is_ad_order && strpos($content, '广告购买') !== false) {
        $is_ad_order = true;
    }

    if (!$is_ad_order) {
        return $content;
    }

    // 获取订单价格（使用缓存避免重复查询）
    $order_price = 0;
    if ($order_data && !empty($order_data['order_price'])) {
        $order_price = floatval($order_data['order_price']);
    }
    if ($order_price == 0 && $order_data && !empty($order_data['id'])) {
        $order_price = xuwbk_ad_get_cached_price($order_data['id']);
    }
    if ($order_price == 0 && !empty($msg_data['order']['order_num'])) {
        $order_price = xuwbk_ad_get_cached_price(0, $msg_data['order']['order_num']);
    }

    if ($order_price > 0) {
        $content = xuwbk_ad_fix_message($content, $order_price);
    }

    return $content;
}

/**
 * 最终保障：在消息保存到数据库前进行修复
 */
add_filter('zib_user_message_content', 'xuwbk_ad_ultimate_msg_fix', 99999, 2);
function xuwbk_ad_ultimate_msg_fix($content, $order_data) {
    if (!isset($order_data['product_id']) || strpos($order_data['product_id'], 'xuwbk_ad_') === false) {
        return $content;
    }
    $order_price = isset($order_data['order_price']) ? floatval($order_data['order_price']) : 0;
    if ($order_price == 0 && isset($order_data['order_num'])) {
        $order_price = xuwbk_ad_get_cached_price(0, $order_data['order_num']);
    }
    return xuwbk_ad_fix_message($content, $order_price);
}

/**
 * 悬浮导航栏 - 加载CSS和JS资源
 * 作者: 轩玮
 * 版本: 1.0.0
 */
add_action('wp_enqueue_scripts', 'xuwbk_float_nav_enqueue_assets', 20);
function xuwbk_float_nav_enqueue_assets() {
    // 检查是否启用悬浮导航
    $options = get_option('XuWbk', array());
    $float_nav_enabled = isset($options['float_nav_enabled']) ? $options['float_nav_enabled'] : true;
    
    if (!$float_nav_enabled) {
        return;
    }
    
    // 加载CSS
    $css_path = get_stylesheet_directory() . '/assets/css/xuwbk_float_nav.css';
    if (file_exists($css_path)) {
        wp_enqueue_style(
            'xuwbk-float-nav-css',
            get_stylesheet_directory_uri() . '/assets/css/xuwbk_float_nav.css',
            array('font-awesome'),
            filemtime($css_path)
        );
    }
    
    // 加载JS
    $js_path = get_stylesheet_directory() . '/assets/js/xuwbk_float_nav.js';
    if (file_exists($js_path)) {
        wp_enqueue_script(
            'xuwbk-float-nav-js',
            get_stylesheet_directory_uri() . '/assets/js/xuwbk_float_nav.js',
            array(),
            filemtime($js_path),
            true
        );
    }
}

/**
 * 单行文章列表CSS - 根据后台设置动态调整文章宽度
 * 在所有页面加载，优先级设最高确保覆盖父主题样式
 */
add_action('wp_enqueue_scripts', 'xuwbk_oneline_posts_compact_css', 999);
function xuwbk_oneline_posts_compact_css() {
    $css_path = get_stylesheet_directory() . '/assets/css/oneline-posts-compact.css';
    if (file_exists($css_path)) {
        wp_enqueue_style(
            'xuwbk-oneline-posts-compact',
            get_stylesheet_directory_uri() . '/assets/css/oneline-posts-compact.css',
            array(),
            filemtime($css_path)
        );
    }
}

/**
 * 用户中心布局修复CSS - 解决用户中心页面错位问题
 * 仅在 /user/ 相关页面加载，优先级设最高
 */
add_action('wp_enqueue_scripts', 'xuwbk_user_center_fix_css', 999);
function xuwbk_user_center_fix_css() {
    // 仅在用户中心相关页面加载
    $is_user_page = false;
    
    // 检查 URL 路径是否包含 /user
    $request_uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
    if (strpos($request_uri, '/user') !== false) {
        $is_user_page = true;
    }
    
    // 检查是否是 author 页面（个人主页也使用相同头部）
    if (is_author()) {
        $is_user_page = true;
    }
    
    if (!$is_user_page) {
        return;
    }
    
    $css_path = get_stylesheet_directory() . '/assets/css/user-center-fix.css';
    if (file_exists($css_path)) {
        wp_enqueue_style(
            'xuwbk-user-center-fix',
            get_stylesheet_directory_uri() . '/assets/css/user-center-fix.css',
            array(),
            filemtime($css_path)
        );
    }
}

/**
 * 修复 layout_bg post meta 中 background-image 数组到字符串的转换
 * 模板导入时 background-image 存储为数组 {url: "..."}，但父主题 zib-head.php 期望字符串。
 * 
 * 三层防护：
 * 1. save 时拦截（update_post_metadata 过滤器）—— 预防新数据
 * 2. 页面加载时修复（wp 钩子）—— 修复已有损坏数据
 * 3. 读取时纠正（get_post_metadata 过滤器）—— 兜底保护
 */

// 第一层：元数据保存时标准化
add_filter('update_post_metadata', 'xuwbk_filter_save_layout_bg', 10, 5);
function xuwbk_filter_save_layout_bg($check, $object_id, $meta_key, $meta_value, $prev_value) {
    if ($meta_key !== 'layout_bg') {
        return $check;
    }
    // 允许 WordPress 继续处理，利用 added_post_meta / updated_post_meta 动作
    return $check;
}

// 第二层：数据实际写入前标准化（更可靠）
add_action('added_post_meta', 'xuwbk_fix_layout_bg_on_added', 10, 4);
add_action('updated_post_meta', 'xuwbk_fix_layout_bg_on_updated', 10, 4);
function xuwbk_fix_layout_bg_on_added($meta_id, $object_id, $meta_key, $meta_value) {
    xuwbk_maybe_fix_layout_bg_meta($object_id, $meta_key, $meta_value);
}
function xuwbk_fix_layout_bg_on_updated($meta_id, $object_id, $meta_key, $meta_value) {
    xuwbk_maybe_fix_layout_bg_meta($object_id, $meta_key, $meta_value);
}
function xuwbk_maybe_fix_layout_bg_meta($object_id, $meta_key, $meta_value) {
    if ($meta_key !== 'layout_bg' || !is_array($meta_value)) {
        return;
    }
    $normalized = xuwbk_normalize_bg_image($meta_value);
    if ($normalized !== $meta_value) {
        // 直接更新，确保标准化后的数据存入数据库
        remove_action('updated_post_meta', 'xuwbk_fix_layout_bg_on_updated', 10);
        update_post_meta($object_id, 'layout_bg', $normalized);
        add_action('updated_post_meta', 'xuwbk_fix_layout_bg_on_updated', 10, 4);
    }
}

// 第三层：页面加载时主动修复当前页的损坏数据（在 wp_head 之前执行）
add_action('wp', 'xuwbk_fix_current_page_layout_bg', 1);
function xuwbk_fix_current_page_layout_bg() {
    if (!is_page()) return;
    $post_id = get_queried_object_id();
    if (!$post_id) return;

    $layout_bg = get_post_meta($post_id, 'layout_bg', true);
    if (!is_array($layout_bg)) return;

    $needs_fix = false;
    foreach (array('img_white', 'img_dark') as $key) {
        if (isset($layout_bg[$key]['background-image']) && is_array($layout_bg[$key]['background-image'])) {
            $needs_fix = true;
            break;
        }
    }

    if ($needs_fix) {
        $normalized = xuwbk_normalize_bg_image($layout_bg);
        // 绕开 updated_post_meta 钩子避免递归
        remove_action('updated_post_meta', 'xuwbk_fix_layout_bg_on_updated', 10);
        update_post_meta($post_id, 'layout_bg', $normalized);
        add_action('updated_post_meta', 'xuwbk_fix_layout_bg_on_updated', 10, 4);
        // 清除 post meta 缓存，确保后续读取获取最新数据
        wp_cache_delete($post_id, 'post_meta');
    }
}

// 第四层：读取时兜底标准化（捕获通过标准 get_post_meta 的调用）
add_filter('get_post_metadata', 'xuwbk_fix_layout_bg_read', 10, 4);
function xuwbk_fix_layout_bg_read($value, $object_id, $meta_key, $single) {
    if ($meta_key !== 'layout_bg' || $value !== null) {
        return $value;
    }
    // 不移除过滤器（此处 WordPress 尚未递归，直接获取原始值）
    // 使用 remove + get + add 模式防止递归
    remove_filter('get_post_metadata', 'xuwbk_fix_layout_bg_read', 10);
    $raw = get_post_meta($object_id, $meta_key, true);
    add_filter('get_post_metadata', 'xuwbk_fix_layout_bg_read', 10, 4);

    if (!is_array($raw)) return $value;

    $normalized = xuwbk_normalize_bg_image($raw);
    if ($normalized !== $raw) {
        // 后台静默修复数据库
        update_post_meta($object_id, $meta_key, $normalized);
    }
    return $single ? $normalized : array($normalized);
}

/**
 * 递归标准化背景图片数据，将 background-image 从数组转为 URL 字符串
 */
function xuwbk_normalize_bg_image($data) {
    if (!is_array($data)) {
        return $data;
    }

    foreach (array('img_white', 'img_dark') as $key) {
        if (isset($data[$key]['background-image']) && is_array($data[$key]['background-image'])) {
            if (isset($data[$key]['background-image']['url'])) {
                $data[$key]['background-image'] = $data[$key]['background-image']['url'];
            } else {
                $data[$key]['background-image'] = '';
            }
        }
    }

    return $data;
}

/**
 * 第五层：后台编辑页面加载时主动修复 layout_bg 数据
 * 解决 CSF 批量获取 post meta 时 get_post_metadata 过滤器无法逐键拦截的问题。
 * load-post.php 在 metabox 渲染之前执行，修复后后续 CSF 字段读取到正确字符串。
 */
add_action('load-post.php', 'xuwbk_fix_admin_edit_page_layout_bg');
function xuwbk_fix_admin_edit_page_layout_bg() {
    $post_id = isset($_GET['post']) ? intval($_GET['post']) : 0;
    if (!$post_id || get_post_type($post_id) !== 'page') return;

    $layout_bg = get_post_meta($post_id, 'layout_bg', true);
    if (!is_array($layout_bg)) return;

    $normalized = xuwbk_normalize_bg_image($layout_bg);
    if ($normalized !== $layout_bg) {
        remove_action('updated_post_meta', 'xuwbk_fix_layout_bg_on_updated', 10);
        update_post_meta($post_id, 'layout_bg', $normalized);
        add_action('updated_post_meta', 'xuwbk_fix_layout_bg_on_updated', 10, 4);
        wp_cache_delete($post_id, 'post_meta');
    }
}

// ============================================================
// 以下为安全加固 & 性能优化代码（2026-07-11 添加）
// ============================================================

/**
 * 移除 WordPress 冗余元标签和资源
 */
add_action('init', 'xuwbk_cleanup_wp_head');
function xuwbk_cleanup_wp_head() {
    // 移除 REST API 链接头
    remove_action('wp_head', 'rest_output_link_wp_head', 10);
    remove_action('template_redirect', 'rest_output_link_header', 11);
    // 移除短链接
    remove_action('wp_head', 'wp_shortlink_wp_head');
    // 移除 oEmbed
    remove_action('wp_head', 'wp_oembed_add_discovery_links');
    remove_action('wp_head', 'wp_oembed_add_host_js');
}

/**
 * 前端优化：移除块编辑器样式（已禁用古腾堡）
 */
add_action('wp_enqueue_scripts', 'xuwbk_remove_block_styles', 100);
function xuwbk_remove_block_styles() {
    if (!is_admin()) {
        wp_dequeue_style('wp-block-library');
        wp_dequeue_style('wp-block-library-theme');
        wp_dequeue_style('wc-blocks-style');
        wp_dequeue_style('global-styles');
    }
}

/**
 * 非关键 JS 添加 defer 属性（减少阻塞渲染）
 */
add_filter('script_loader_tag', 'xuwbk_add_defer_async', 10, 2);
function xuwbk_add_defer_async($tag, $handle) {
    $defer_scripts = array('xuwbk-ai-chat', 'xuwbk-dock', 'xuwbk-ad');
    if (in_array($handle, $defer_scripts, true)) {
        return str_replace(' src', ' defer src', $tag);
    }
    return $tag;
}

/**
 * 主查询优化：归档/搜索页禁用不必要缓存
 */
add_action('pre_get_posts', 'xuwbk_optimize_queries');
function xuwbk_optimize_queries($query) {
    if (is_admin() || !$query->is_main_query()) return;
    if ($query->is_archive() || $query->is_search()) {
        $query->set('no_found_rows', true);
        $query->set('update_post_term_cache', false);
        $query->set('update_post_meta_cache', false);
    }
}

// ===== REST API 安全加固 =====
/**
 * 对未登录用户限制 REST API 访问（保留支付/主题必要端点的公开访问）
 */
add_filter('rest_authentication_errors', 'xuwbk_rest_api_limit');
function xuwbk_rest_api_limit($result) {
    if (is_user_logged_in()) return $result;
    
    $allowed_routes = array('/zibpay/', '/xuwbk/', '/zibll/');
    $current_uri = $_SERVER['REQUEST_URI'];
    foreach ($allowed_routes as $route) {
        if (strpos($current_uri, $route) !== false) {
            return $result;
        }
    }
    // 允许公开端点的 GET 请求
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        return $result;
    }
    return new WP_Error(
        'rest_forbidden',
        __('REST API 仅限登录用户。', 'xuwbk'),
        array('status' => 401)
    );
}

// ===== 登录安全 =====
/**
 * 隐藏登录错误详情（防止用户名枚举）
 */
add_filter('login_errors', function() {
    return '<strong>错误：</strong>用户名或密码不正确，请重试。';
});

/**
 * 移除 WordPress 版本号
 */
remove_action('wp_head', 'wp_generator');
add_filter('the_generator', '__return_empty_string');

// ===== CDN 静态资源重写（生产环境启用） =====
if (defined('CDN_URL') && CDN_URL) {
    add_filter('script_loader_src', 'xuwbk_cdn_rewrite');
    add_filter('style_loader_src', 'xuwbk_cdn_rewrite');
    add_filter('theme_file_uri', 'xuwbk_cdn_rewrite');
    function xuwbk_cdn_rewrite($url) {
        if (is_admin() || is_login()) return $url;
        return str_replace(home_url(), CDN_URL, $url);
    }
}

/**
 * 优化：上传图片自动转 WebP（如果服务器支持）
 * 仅在需要时启用，避免冲突
 */
// add_filter('wp_handle_upload', 'xuwbk_convert_to_webp');
// function xuwbk_convert_to_webp($upload) { ... }

/**
 * 安全检查：在 admin_footer 显示已激活插件数量提示（仅管理员可见）
 */
if (is_admin() && current_user_can('manage_options')) {
    add_action('admin_notices', 'xuwbk_plugin_count_notice');
    function xuwbk_plugin_count_notice() {
        $active_count = count(get_option('active_plugins', array()));
        if ($active_count > 30) {
            echo '<div class="notice notice-warning is-dismissible"><p>';
            echo sprintf(
                '【XuWbk 优化提示】当前激活 %d 个插件，建议精简至 25 个以内以提升性能。',
                $active_count
            );
            echo '</p></div>';
        }
    }
}

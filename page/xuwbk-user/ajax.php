<?php
/**
 * 前台用户管理插件 - AJAX处理
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * AJAX: 显示用户管理弹窗
 */
function xuwbk_user_manage_modal_ajax()
{
    // 权限检查
    if (!current_user_can('manage_options')) {
        if (function_exists('zib_ajax_notice_modal')) {
            zib_ajax_notice_modal('danger', '权限不足');
        } else {
            echo '<div class="text-center c-red padding-lg">权限不足</div>';
        }
        exit;
    }

    $user_id = isset($_REQUEST['user_id']) ? intval($_REQUEST['user_id']) : 0;

    if (!$user_id) {
        if (function_exists('zib_ajax_notice_modal')) {
            zib_ajax_notice_modal('danger', '参数错误');
        } else {
            echo '<div class="text-center c-red padding-lg">参数错误</div>';
        }
        exit;
    }

    echo xuwbk_user_manage_get_modal_content($user_id);
    exit;
}
add_action('wp_ajax_xuwbk_user_manage_modal', 'xuwbk_user_manage_modal_ajax');

/**
 * AJAX: 保存用户数据
 */
function xuwbk_user_manage_save_ajax()
{
    // 权限检查
    if (!current_user_can('manage_options')) {
        wp_send_json_error(array('msg' => '权限不足'));
    }

    // Nonce验证
    if (!wp_verify_nonce($_POST['xuwbk_user_manage_nonce'], 'xuwbk_user_manage_nonce')) {
        wp_send_json_error(array('msg' => '安全验证失败'));
    }

    $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;

    if (!$user_id) {
        wp_send_json_error(array('msg' => '参数错误'));
    }

    $user = get_userdata($user_id);
    if (!$user) {
        wp_send_json_error(array('msg' => '用户不存在'));
    }

    // 获取原始数据用于对比
    $old_data = xuwbk_user_manage_get_user_data($user_id);
    $changes = array();
    $remark = isset($_POST['remark']) ? sanitize_textarea_field($_POST['remark']) : '';
    $send_email = isset($_POST['send_email']) && $_POST['send_email'] == '1';

    // 处理积分
    if (isset($_POST['points']) && function_exists('zibpay_update_user_points') && function_exists('_pz') && _pz('points_s')) {
        $new_points = floatval($_POST['points']);
        $old_points = $old_data['points'];
        $diff = $new_points - $old_points;

        if ($diff != 0) {
            $data = array(
                'value' => $diff,
                'type'  => '管理员调整',
                'desc'  => $remark ? $remark : '前台管理员操作',
            );
            zibpay_update_user_points($user_id, $data);
            $changes['积分'] = $old_points . ' → ' . $new_points;
        }
    }

    // 处理余额
    if (isset($_POST['balance']) && function_exists('zibpay_update_user_balance') && function_exists('_pz') && _pz('pay_balance_s')) {
        $new_balance = floatval($_POST['balance']);
        $old_balance = $old_data['balance'];
        $diff = $new_balance - $old_balance;

        if ($diff != 0) {
            $data = array(
                'value' => $diff,
                'type'  => '管理员调整',
                'desc'  => $remark ? $remark : '前台管理员操作',
            );
            zibpay_update_user_balance($user_id, $data);
            $changes['余额'] = $old_balance . ' → ' . $new_balance;
        }
    }

    // 处理VIP
    if (isset($_POST['vip_level']) && function_exists('_pz') && (_pz('pay_user_vip_1_s', true) || _pz('pay_user_vip_2_s', true))) {
        $new_vip_level = intval($_POST['vip_level']);
        $old_vip_level = $old_data['vip_level'];

        // VIP到期时间
        $vip_permanent = isset($_POST['vip_permanent']) && $_POST['vip_permanent'] == '1';
        $vip_exp_input = isset($_POST['vip_exp_date']) && !empty($_POST['vip_exp_date']) ? sanitize_text_field($_POST['vip_exp_date']) : '';
        // datetime-local格式转换为标准格式
        if ($vip_exp_input && strpos($vip_exp_input, 'T') !== false) {
            $vip_exp_input = str_replace('T', ' ', $vip_exp_input) . ':00';
        }
        $new_vip_exp_date = $vip_permanent ? 'Permanent' : $vip_exp_input;

        if ($new_vip_level != $old_vip_level || $new_vip_exp_date != $old_data['vip_exp_date']) {
            if ($new_vip_level > 0) {
                // 设置VIP
                if (function_exists('zibpay_update_user_vip')) {
                    $vip_data = array(
                        'vip_level' => $new_vip_level,
                        'exp_date'  => $new_vip_exp_date ? $new_vip_exp_date : 'Permanent',
                        'type'      => '管理员设置',
                        'desc'      => $remark ? $remark : '前台管理员操作',
                    );
                    zibpay_update_user_vip($user_id, $vip_data);
                } else {
                    update_user_meta($user_id, 'vip_level', $new_vip_level);
                    update_user_meta($user_id, 'vip_exp_date', $new_vip_exp_date ? $new_vip_exp_date : 'Permanent');
                }

                $vip_name = _pz('pay_user_vip_' . $new_vip_level . '_name', 'VIP' . $new_vip_level);
                $exp_text = $new_vip_exp_date == 'Permanent' ? '永久' : $new_vip_exp_date;
                $changes['VIP'] = $vip_name . '（' . $exp_text . '）';
            } else {
                // 取消VIP
                update_user_meta($user_id, 'vip_level', 0);
                update_user_meta($user_id, 'vip_exp_date', '');
                $changes['VIP'] = '已取消';
            }
        }
    }

    // 处理用户等级
    if (isset($_POST['user_level']) && function_exists('_pz') && _pz('user_level_s', true)) {
        $new_level = intval($_POST['user_level']);
        $old_level = $old_data['user_level'];

        if ($new_level != $old_level) {
            update_user_meta($user_id, 'level', $new_level);
            $changes['等级'] = 'LV' . $old_level . ' → LV' . $new_level;
        }

        // 经验值
        if (isset($_POST['level_integral'])) {
            $new_integral = intval($_POST['level_integral']);
            $old_integral = $old_data['level_integral'];

            if ($new_integral != $old_integral) {
                update_user_meta($user_id, 'level_integral', $new_integral);
                $changes['经验值'] = $old_integral . ' → ' . $new_integral;
            }
        }
    }

    // 处理身份认证 - 使用子比主题的认证系统
    if (function_exists('_pz') && _pz('user_auth_s', true)) {
        $new_auth_status = isset($_POST['user_auth']) && $_POST['user_auth'] == '1' ? 1 : 0;
        $new_auth_name = isset($_POST['auth_name']) ? sanitize_text_field($_POST['auth_name']) : '';
        $new_auth_desc = isset($_POST['auth_desc']) ? sanitize_text_field($_POST['auth_desc']) : '';

        $old_auth_status = $old_data['user_auth'] ? 1 : 0;

        // 检查是否有变更
        if ($new_auth_status != $old_auth_status || $new_auth_name != $old_data['auth_name'] || $new_auth_desc != $old_data['auth_desc']) {
            if ($new_auth_status) {
                // 设置认证 - 使用子比主题的函数
                if (function_exists('zib_add_user_auth')) {
                    zib_add_user_auth($user_id, array(
                        'name' => $new_auth_name ? $new_auth_name : '官方认证',
                        'desc' => $new_auth_desc,
                    ));
                } else {
                    // 如果主题函数不存在，手动设置
                    update_user_meta($user_id, 'auth', 1);
                    $auth_info = array(
                        'name' => $new_auth_name ? $new_auth_name : '官方认证',
                        'desc' => $new_auth_desc,
                        'time' => current_time('Y-m-d H:i'),
                    );
                    if (function_exists('zib_update_user_meta')) {
                        zib_update_user_meta($user_id, 'auth_info', $auth_info);
                    } else {
                        update_user_meta($user_id, 'auth_info', $auth_info);
                    }
                }
                $changes['身份认证'] = '已认证' . ($new_auth_name ? '（' . $new_auth_name . '）' : '');
            } else {
                // 取消认证
                delete_user_meta($user_id, 'auth');
                delete_user_meta($user_id, 'auth_info');
                $changes['身份认证'] = '已取消';
            }
        }
    }

    // 如果没有任何修改
    if (empty($changes)) {
        wp_send_json_success(array(
            'msg'        => '没有检测到数据变更',
            'hide_modal' => false,
        ));
    }

    // 发送邮件通知
    if ($send_email && !empty($user->user_email)) {
        xuwbk_user_manage_send_notification_email($user, $changes, $remark);
    }

    // 刷新缓存
    wp_cache_flush();

    // 获取最佳的重定向URL
    $redirect_url = '';
    
    // 优先使用当前用户页面
    if (function_exists('zib_get_user_center_url')) {
        $redirect_url = zib_get_user_center_url();
    }
    
    // 如果没有用户中心URL，使用作者页面
    if (empty($redirect_url)) {
        $redirect_url = get_author_posts_url($user_id);
    }
    
    // 最后备选：首页
    if (empty($redirect_url)) {
        $redirect_url = home_url();
    }
    
    wp_send_json_success(array(
        'msg'        => '用户数据已更新',
        'hide_modal' => true,
        'reload'     => false, // 不刷新当前页面
        'redirect'   => $redirect_url,
        'user_id'    => $user_id
    ));
}
add_action('wp_ajax_xuwbk_user_manage_save', 'xuwbk_user_manage_save_ajax');

/**
 * 发送邮件通知用户
 */
function xuwbk_user_manage_send_notification_email($user, $changes, $remark = '')
{
    if (empty($user->user_email) || !is_email($user->user_email)) {
        return false;
    }

    $blog_name = get_bloginfo('name');
    $subject = '[' . $blog_name . '] 您的账户信息已更新';

    $message = '尊敬的 ' . $user->display_name . '：<br><br>';
    $message .= '您在 ' . $blog_name . ' 的账户信息已由管理员更新，变更内容如下：<br><br>';

    foreach ($changes as $field => $change) {
        $message .= '<b>' . $field . '：</b>' . $change . '<br>';
    }

    if ($remark) {
        $message .= '<br><b>备注：</b>' . esc_html($remark) . '<br>';
    }

    $message .= '<br>更新时间：' . current_time('Y-m-d H:i:s') . '<br>';
    $message .= '<br>如有疑问，请联系网站管理员。<br>';
    $message .= '<br>—— ' . $blog_name;

    // 设置邮件头为HTML格式
    $headers = array('Content-Type: text/html; charset=UTF-8');

    return wp_mail($user->user_email, $subject, $message, $headers);
}

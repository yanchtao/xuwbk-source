<?php
/**
 * 前台用户管理插件 - 核心函数
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * 在author页面用户身份区域添加管理按钮
 * 挂载到 author_header_identity 钩子
 */
function xuwbk_user_manage_author_header_btn($desc, $user_id)
{
    // 只有管理员可以看到和操作
    if (!current_user_can('manage_options')) {
        return $desc;
    }

    // 不能管理自己（可选，如需管理自己可删除此判断）
    // if ($user_id == get_current_user_id()) {
    //     return $desc;
    // }

    $btn = xuwbk_user_manage_get_modal_link($user_id);
    return $desc . $btn;
}
add_action('zib_require_end', function () {
    add_filter('author_header_identity', 'xuwbk_user_manage_author_header_btn', 99, 2);
});

/**
 * 在author页面侧边栏添加悬浮管理按钮
 * 使用主题float-right round样式
 */
function xuwbk_user_manage_float_btn()
{
    // 只在author页面显示
    if (!is_author()) {
        return;
    }

    // 只有管理员可以看到
    if (!current_user_can('manage_options')) {
        return;
    }

    $author_id = get_queried_object_id();
    if (!$author_id) {
        return;
    }

    $args = array(
        'tag'           => 'a',
        'data_class'    => 'um-modal-width full-sm',
        'class'         => 'float-btn jb-blue',
        'mobile_bottom' => true,
        'height'        => 500,
        'text'          => '<i class="fa fa-cog"></i>',
        'query_arg'     => array(
            'action'  => 'xuwbk_user_manage_modal',
            'user_id' => $author_id,
        ),
    );

    $btn = zib_get_refresh_modal_link($args);

    echo '<div class="float-right round um-float-wrap">' . $btn . '</div>';
}
add_action('wp_footer', 'xuwbk_user_manage_float_btn');

/**
 * 获取用户管理弹窗链接按钮
 */
function xuwbk_user_manage_get_modal_link($user_id, $class = '', $text = '')
{
    if (!current_user_can('manage_options') || !$user_id) {
        return '';
    }

    if (empty($text)) {
        $text = '<i class="fa fa-cog mr3"></i>管理';
    }

    $args = array(
        'tag'           => 'a',
        'data_class'    => 'um-modal-width full-sm',
        'class'         => 'badg c-blue user-manage-btn ' . $class,
        'mobile_bottom' => true,
        'height'        => 500,
        'text'          => $text,
        'query_arg'     => array(
            'action'  => 'xuwbk_user_manage_modal',
            'user_id' => $user_id,
        ),
    );

    return zib_get_refresh_modal_link($args);
}

/**
 * 获取用户管理弹窗内容
 */
function xuwbk_user_manage_get_modal_content($user_id)
{
    if (!current_user_can('manage_options') || !$user_id) {
        return '';
    }

    $user = get_userdata($user_id);
    if (!$user) {
        return '<div class="text-center c-red padding-lg">用户不存在</div>';
    }

    // 获取用户头像和名称
    $avatar = function_exists('zib_get_avatar_box') ? zib_get_avatar_box($user_id, 'avatar-img', false, false) : get_avatar($user_id, 50);
    $display_name = $user->display_name;

    // 获取用户当前数据
    $user_data = xuwbk_user_manage_get_user_data($user_id);

    // 获取VIP图标
    $vip_icon = '';
    if (function_exists('zibpay_get_vip_icon') && $user_data['vip_level']) {
        $vip_icon = zibpay_get_vip_icon($user_data['vip_level'], 'ml3');
    }

    // 获取用户等级徽章
    $level_badge = '';
    if (function_exists('zib_get_user_level_badge')) {
        $level_badge = zib_get_user_level_badge($user_id, 'ml3');
    }

    // 获取用户认证徽章
    $auth_badge = '';
    if (function_exists('zib_get_user_auth_badge')) {
        $auth_badge = zib_get_user_auth_badge($user_id, 'ml3');
    }

    // 构建弹窗头部
    $header = zib_get_modal_colorful_header('jb-blue', '<i class="fa fa-user-circle-o"></i>', '用户数据管理');

    // 用户信息卡片 - 白色背景
    $user_card = '<div class="flex ac mb20 zib-widget padding-10 radius8">';
    $user_card .= '<div class="mr10">' . $avatar . '</div>';
    $user_card .= '<div class="flex1">';
    $user_card .= '<div class="font-bold em12 flex ac">' . esc_html($display_name) . $vip_icon . $auth_badge . $level_badge . '</div>';
    $user_card .= '<div class="em09 muted-2-color mt3">ID: ' . $user_id . ' · ' . esc_html($user->user_email) . '</div>';
    $user_card .= '</div>';
    $user_card .= '</div>';

    // 构建表单
    $form = '<form id="zibll-user-manage-form">';
    $form .= '<input type="hidden" name="action" value="xuwbk_user_manage_save">';
    $form .= '<input type="hidden" name="user_id" value="' . $user_id . '">';
    $form .= wp_nonce_field('xuwbk_user_manage_nonce', 'xuwbk_user_manage_nonce', true, false);

    // 资产管理区块（积分+余额）- 仿主题样式
    $assets_html = '';

    // 余额设置
    if (function_exists('zibpay_get_user_balance') && function_exists('_pz') && _pz('pay_balance_s')) {
        $assets_html .= '<div style="flex: 1;" class="zib-widget padding-10 flex1">';
        $assets_html .= '<div class="muted-color em09 mb6">余额</div>';
        $assets_html .= '<div class="flex jsb ac">';
        $assets_html .= '<input type="number" name="balance" value="' . esc_attr($user_data['balance']) . '" class="um-asset-input c-blue-2" step="0.01">';
        $assets_html .= zib_get_svg('money-color-2', null, 'em14');
        $assets_html .= '</div>';
        $assets_html .= '</div>';
    }

    // 积分设置
    if (function_exists('zibpay_get_user_points') && function_exists('_pz') && _pz('points_s')) {
        $assets_html .= '<div style="flex: 1;" class="zib-widget padding-10 flex1">';
        $assets_html .= '<div class="muted-color em09 mb6">积分</div>';
        $assets_html .= '<div class="flex jsb ac">';
        $assets_html .= '<input type="number" name="points" value="' . esc_attr($user_data['points']) . '" class="um-asset-input c-yellow" step="1">';
        $assets_html .= zib_get_svg('points-color', null, 'em14');
        $assets_html .= '</div>';
        $assets_html .= '</div>';
    }

    if ($assets_html) {
        $form .= '<div class="muted-box padding-h10 mb15">';
        $form .= '<div class="border-title mb10"><i class="fa fa-wallet mr6"></i>资产管理</div>';
        $form .= '<div class="flex col-ml6">' . $assets_html . '</div>';
        $form .= '</div>';
    }

    // VIP设置区块
    if (function_exists('zib_get_user_vip_level') && function_exists('_pz') && (_pz('pay_user_vip_1_s', true) || _pz('pay_user_vip_2_s', true))) {
        $form .= '<div class="muted-box padding-h10 mb15">';
        $form .= '<div class="border-title mb10"><i class="fa fa-diamond mr6 c-yellow"></i>会员管理</div>';

        $vip_options = array('0' => '无会员');
        if (_pz('pay_user_vip_1_s', true)) {
            $vip_options['1'] = _pz('pay_user_vip_1_name', 'VIP1');
        }
        if (_pz('pay_user_vip_2_s', true)) {
            $vip_options['2'] = _pz('pay_user_vip_2_name', 'VIP2');
        }

        $form .= '<div class="row gutters-10 mb10">';
        $form .= '<div class="col-6">';
        $form .= '<div class="em09 muted-2-color mb6">会员等级</div>';
        $form .= '<div class="form-select flex ac">';
        $form .= '<select name="vip_level" class="form-control">';
        foreach ($vip_options as $opt_value => $opt_label) {
            $selected = ($user_data['vip_level'] == $opt_value) ? ' selected' : '';
            $form .= '<option value="' . esc_attr($opt_value) . '"' . $selected . '>' . esc_html($opt_label) . '</option>';
        }
        $form .= '</select>';
        $form .= '</div>';
        $form .= '</div>';

        $form .= '<div class="col-6">';
        $form .= '<div class="em09 muted-2-color mb6">到期时间</div>';
        $exp_date_value = ($user_data['vip_exp_date'] == 'Permanent' || empty($user_data['vip_exp_date'])) ? '' : date('Y-m-d\TH:i', strtotime($user_data['vip_exp_date']));
        $form .= '<input type="datetime-local" name="vip_exp_date" value="' . esc_attr($exp_date_value) . '" class="form-control" ' . ($user_data['vip_exp_date'] == 'Permanent' ? 'disabled' : '') . '>';
        $form .= '</div>';
        $form .= '</div>';

        $form .= '<label class="flex ac jsb pointer em09"><span>设为永久会员</span><input type="checkbox" name="vip_permanent" value="1" class="hide" ' . ($user_data['vip_exp_date'] == 'Permanent' ? 'checked' : '') . '><span class="form-switch"></span></label>';
        $form .= '</div>';
    }

    // 等级设置区块
    if (function_exists('zib_get_user_level') && function_exists('_pz') && _pz('user_level_s', true)) {
        $form .= '<div class="muted-box padding-h10 mb15">';
        $form .= '<div class="border-title mb10"><i class="fa fa-star mr6 c-yellow"></i>等级管理</div>';

        $level_max = _pz('user_level_max', 10);

        $form .= '<div class="row gutters-10">';
        $form .= '<div class="col-6">';
        $form .= '<div class="em09 muted-2-color mb6">用户等级</div>';
        $form .= '<input type="number" name="user_level" value="' . esc_attr($user_data['user_level']) . '" class="form-control" min="0" max="' . $level_max . '" step="1">';
        $form .= '</div>';

        $form .= '<div class="col-6">';
        $form .= '<div class="em09 muted-2-color mb6">经验值</div>';
        $form .= '<input type="number" name="level_integral" value="' . esc_attr($user_data['level_integral']) . '" class="form-control" step="1">';
        $form .= '</div>';
        $form .= '</div>';
        $form .= '</div>';
    }

    // 身份认证区块 - 使用子比主题的认证系统
    if (function_exists('_pz') && _pz('user_auth_s', true)) {
        $form .= '<div class="muted-box padding-h10 mb15">';
        $form .= '<div class="border-title mb10"><i class="fa fa-check-circle mr6 c-blue"></i>身份认证</div>';

        // 认证状态开关
        $is_auth_checked = $user_data['user_auth'] ? ' checked' : '';
        $form .= '<div class="mb10">';
        $form .= '<label class="flex ac jsb pointer">';
        $form .= '<span class="em09">认证状态</span>';
        $form .= '<input type="checkbox" name="user_auth" value="1" class="hide"' . $is_auth_checked . '>';
        $form .= '<span class="form-switch"></span>';
        $form .= '</label>';
        $form .= '</div>';

        // 认证信息输入框
        $form .= '<div class="row gutters-10">';
        $form .= '<div class="col-6">';
        $form .= '<div class="em09 muted-2-color mb6">认证名称</div>';
        $form .= '<input type="text" name="auth_name" value="' . esc_attr($user_data['auth_name']) . '" class="form-control" placeholder="例如：官方认证">';
        $form .= '</div>';
        $form .= '<div class="col-6">';
        $form .= '<div class="em09 muted-2-color mb6">认证描述</div>';
        $form .= '<input type="text" name="auth_desc" value="' . esc_attr($user_data['auth_desc']) . '" class="form-control" placeholder="认证描述信息">';
        $form .= '</div>';
        $form .= '</div>';
        
        $form .= '<div class="muted-2-color em09 mt6">提示：开启认证状态后，用户将显示认证徽章</div>';
        $form .= '</div>';
    }

    // 操作选项区块
    $form .= '<div class="mt15 pt10 border-top">';
    $form .= '<div class="mb10">';
    $form .= '<div class="em09 muted-2-color mb6">操作备注</div>';
    $form .= '<input type="text" name="remark" class="form-control" placeholder="可选，填写修改原因">';
    $form .= '</div>';
    $form .= '<label class="flex ac jsb pointer em09"><span>修改后发送邮件通知用户</span><input type="checkbox" name="send_email" value="1" class="hide"><span class="form-switch"></span></label>';
    $form .= '</div>';

    // 提交按钮 - 使用主题modal-full-footer样式
    $form .= '<div class="modal-full-footer">';
    $form .= '<button type="submit" class="but jb-blue btn-block radius padding-lg">';
    $form .= '<i class="fa fa-check mr6"></i>保存修改';
    $form .= '</button>';
    $form .= '</div>';

    $form .= '</form>';

    $html = '<div class="user-manage-modal">';
    $html .= $header;
    $html .= '<div class="box-body mini-scrollbar scroll-y" style="max-height:60vh;">';
    $html .= $user_card;
    $html .= $form;
    $html .= '</div>';
    $html .= '</div>';

    return $html;
}

/**
 * 获取用户当前数据
 */
function xuwbk_user_manage_get_user_data($user_id)
{
    $data = array(
        'points'         => 0,
        'balance'        => 0,
        'vip_level'      => 0,
        'vip_exp_date'   => '',
        'user_level'     => 0,
        'level_integral' => 0,
        'user_auth'      => '',
        'auth_name'      => '',
        'auth_desc'      => '',
    );

    // 积分
    if (function_exists('zibpay_get_user_points')) {
        $data['points'] = zibpay_get_user_points($user_id);
    }

    // 余额
    if (function_exists('zibpay_get_user_balance')) {
        $data['balance'] = zibpay_get_user_balance($user_id);
    }

    // VIP
    if (function_exists('zib_get_user_vip_level')) {
        $data['vip_level'] = zib_get_user_vip_level($user_id);
        $data['vip_exp_date'] = get_user_meta($user_id, 'vip_exp_date', true);
    }

    // 用户等级
    if (function_exists('zib_get_user_level')) {
        $data['user_level'] = zib_get_user_level($user_id);
        $data['level_integral'] = (int) get_user_meta($user_id, 'level_integral', true);
    }

    // 身份认证 - 使用子比主题的认证系统
    // auth meta存储认证等级（1表示已认证）
    $data['user_auth'] = get_user_meta($user_id, 'auth', true);
    
    // auth_info meta存储认证详细信息
    if (function_exists('zib_get_user_meta')) {
        $auth_info = zib_get_user_meta($user_id, 'auth_info', true);
    } else {
        $auth_info = get_user_meta($user_id, 'auth_info', true);
    }
    
    if (is_array($auth_info)) {
        $data['auth_name'] = isset($auth_info['name']) ? $auth_info['name'] : '';
        $data['auth_desc'] = isset($auth_info['desc']) ? $auth_info['desc'] : '';
    }

    return $data;
}



/**
 * 在"我的服务"区块添加用户管理图标按钮
 */
function xuwbk_user_manage_service_btn($buttons)
{
    // 只有管理员可以看到
    if (!current_user_can('manage_options')) {
        return $buttons;
    }

    // 从配置中获取按钮设置
    $icon = xuwbk_user_manage_get_option('service_btn_icon', 'fa fa-users');
    $color = xuwbk_user_manage_get_option('service_btn_color', 'c-blue');
    $name = xuwbk_user_manage_get_option('service_btn_name', '用户管理');
    $tab = xuwbk_user_manage_get_option('service_btn_tab', 'user-manage');

    // 添加用户管理按钮到"我的服务"
    $buttons[] = array(
        'html' => '',
        'icon' => '<i class="' . esc_attr($icon) . ' ' . esc_attr($color) . '"></i>',
        'name' => esc_html($name),
        'tab'  => esc_attr($tab),
    );

    return $buttons;
}
add_filter('zib_user_center_page_sidebar_button_1_args', 'xuwbk_user_manage_service_btn', 99);

/**
 * 添加用户管理Tab到用户中心
 */
function xuwbk_user_manage_add_tab($tabs_array)
{
    // 只有管理员可以看到
    if (!current_user_can('manage_options')) {
        return $tabs_array;
    }

    // 从配置中获取设置
    $icon = xuwbk_user_manage_get_option('service_btn_icon', 'fa fa-users');
    $name = xuwbk_user_manage_get_option('service_btn_name', '用户管理');
    $tab = xuwbk_user_manage_get_option('service_btn_tab', 'user-manage');

    $tabs_array[$tab] = array(
        'title'    => esc_html($name),
        'icon'     => '<i class="' . esc_attr($icon) . '"></i>',
        'nav_attr' => '',
        'content'  => xuwbk_user_manage_get_tab_content(),
    );

    return $tabs_array;
}
add_filter('user_ctnter_main_tabs_array', 'xuwbk_user_manage_add_tab', 99);

/**
 * 获取用户管理Tab内容
 */
function xuwbk_user_manage_get_tab_content()
{
    $html = '<div class="zib-widget">';
    $html .= '<div class="box-body">';

    // 标题
    $html .= '<div class="border-title mb15"><i class="fa fa-users mr6 c-blue"></i>用户搜索管理</div>';

    // 搜索表单
    $html .= '<div class="mb20">';
    $html .= '<div class="flex ac">';
    $html .= '<input type="text" id="um-search-input" class="form-control flex1 mr10" placeholder="输入用户名、邮箱或ID搜索...">';
    $html .= '<button type="button" id="um-search-btn" class="but jb-blue"><i class="fa fa-search mr3"></i>搜索</button>';
    $html .= '</div>';
    $html .= '</div>';

    // 搜索结果区域
    $html .= '<div id="um-search-results" class="mini-scrollbar"></div>';

    $html .= '</div>';
    $html .= '</div>';

    return $html;
}

/**
 * AJAX搜索用户
 */
function xuwbk_user_manage_search_users()
{
    if (!current_user_can('manage_options')) {
        wp_send_json_error(array('msg' => '无权限'));
    }

    $keyword = isset($_POST['keyword']) ? sanitize_text_field($_POST['keyword']) : '';

    if (empty($keyword)) {
        wp_send_json_error(array('msg' => '请输入搜索关键词'));
    }

    // 搜索用户
    $args = array(
        'search'         => '*' . $keyword . '*',
        'search_columns' => array('user_login', 'user_email', 'user_nicename', 'display_name', 'ID'),
        'number'         => 20,
    );

    // 如果是纯数字，也按ID搜索
    if (is_numeric($keyword)) {
        $args['include'] = array(intval($keyword));
        unset($args['search']);
        unset($args['search_columns']);
    }

    $users = get_users($args);

    if (empty($users)) {
        wp_send_json_success(array('html' => '<div class="text-center muted-2-color padding-lg">未找到相关用户</div>'));
    }

    $html = '';
    foreach ($users as $user) {
        $avatar = function_exists('zib_get_avatar_box') ? zib_get_avatar_box($user->ID, 'avatar-img', false, false) : get_avatar($user->ID, 40);

        // 获取VIP图标
        $vip_icon = '';
        if (function_exists('zibpay_get_vip_icon') && function_exists('zib_get_user_vip_level')) {
            $vip_level = zib_get_user_vip_level($user->ID);
            if ($vip_level) {
                $vip_icon = zibpay_get_vip_icon($vip_level, 'ml3');
            }
        }

        // 管理按钮
        $manage_btn = xuwbk_user_manage_get_modal_link($user->ID, 'but c-blue em09', '<i class="fa fa-cog mr3"></i>管理');

        $html .= '<div class="flex ac jsb padding-10 border-bottom">';
        $html .= '<div class="flex ac flex1">';
        $html .= '<div class="mr10">' . $avatar . '</div>';
        $html .= '<div class="flex1">';
        $html .= '<div class="font-bold">' . esc_html($user->display_name) . $vip_icon . '</div>';
        $html .= '<div class="em09 muted-2-color">ID: ' . $user->ID . ' · ' . esc_html($user->user_email) . '</div>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '<div>' . $manage_btn . '</div>';
        $html .= '</div>';
    }

    wp_send_json_success(array('html' => $html));
}
add_action('wp_ajax_xuwbk_user_manage_search', 'xuwbk_user_manage_search_users');

/**
 * 获取用户管理选项配置
 * 使用 XuWbk 主题的配置系统
 */
function xuwbk_user_manage_get_option($option_name, $default = '') {
    $options = get_option('XuWbk');
    $user_manage_options = isset($options['xuwbk_user_manage']) ? $options['xuwbk_user_manage'] : array();
    return isset($user_manage_options[$option_name]) ? $user_manage_options[$option_name] : $default;
}

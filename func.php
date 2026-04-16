<?php

/**
 * 新用户注册时自动用id为1的用户发送私信
 */
function auto_send_private($new_user_id) {
    // 检查是否是新用户，并确保用户ID为1存在
    $send_user_id = 1;  // 假设用户1存在
    // 获取私信内容
    $msg_content = '欢迎新用户注册！';
    // 定义私信参数
    $msg_args = array(
        'send_user'    => $send_user_id,
        'receive_user' => $new_user_id,  // 新用户ID
        'content'      => $msg_content,
        'parent'       => '',
        'status'       => '',
        'meta'         => '',
        'other'        => '',
    );
    // 发送私信
    $msg = Zib_Private::add($msg_args);
}
add_action('user_register', 'auto_send_private');

/**
 * 扩展父主题的订单类型名称函数，添加"打赏作者"和"购买广告"类型
 * 由于父主题函数无法直接覆盖，我们通过过滤器扩展
 */
add_filter('zibpay_get_pay_type_name_filter', function($name, $pay_type) {
    // 添加新的订单类型
    $extra_types = array(
        '11' => '打赏作者',
        '12' => '购买广告',
    );
    
    if (isset($extra_types[$pay_type])) {
        return $extra_types[$pay_type];
    }
    
    return $name;
}, 10, 2);

/**
 * 自定义订单类型名称获取函数（用于支持打赏和广告类型）
 */
function xuwbk_get_pay_type_name($pay_type = null, $show_icon = false) {
    // 获取父主题的原始类型
    $name = array(
        '1'  => '付费阅读', //文章，帖子
        '2'  => '付费资源', //文章
        '5'  => '付费图片', //文章
        '6'  => '付费视频', //文章
        '3'  => '产品购买', //页面，未使用
        '4'  => '购买会员', //用户
        '7'  => '自动售卡', //未启用
        '8'  => '余额充值', //用户
        '9'  => '购买积分', //用户
        '10' => '购买商品', //商城，商品
        '11' => '打赏作者', //打赏作者
        '12' => '购买广告', //购买广告
    );

    if (!$pay_type) {
        return $name;
    }

    $n = isset($name[$pay_type]) ? $name[$pay_type] : '付费内容';
    if ($show_icon) {
        $icons = array(
            '1'  => '<i class="fa fa-book"></i>',
            '2'  => '<i class="fa fa-download"></i>',
            '5'  => '<i class="fa fa-image"></i>',
            '6'  => '<i class="fa fa-video"></i>',
            '3'  => '<i class="fa fa-shopping-cart"></i>',
            '4'  => '<i class="fa fa-vip"></i>',
            '7'  => '<i class="fa fa-ticket"></i>',
            '8'  => '<i class="fa fa-wallet"></i>',
            '9'  => '<i class="fa fa-coins"></i>',
            '10' => '<i class="fa fa-store"></i>',
            '11' => '<i class="fa fa-heart"></i>',
            '12' => '<i class="fa fa-ad"></i>',
        );
        $n = (isset($icons[$pay_type]) ? $icons[$pay_type] . ' ' : '') . $n;
    }
    return $n;
}



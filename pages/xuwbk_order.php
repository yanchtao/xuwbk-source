<?php
/*
Plugin Name: XuWbk-订单查询
Plugin URI: https://www.xuwbk.com/
Description: 启用后，新建页面——页面模板——选择XuWbk-订单查询，访问页面即可查询订单
Version: 2.2
Author: 轩玮博客
Author URI: https://www.xuwbk.com/
*/

// 注册订单查询页面
function add_zib_search_order( $templates ) {  
    $templates['zib_search_order'] = 'XuWbk-订单查询';  
    return $templates;  
}  
add_filter( 'theme_page_templates', 'add_zib_search_order' );

// 设置订单页面文件路由
function zib_search_order_include( $template ) {  
    global $wp_query;  
  
    // 检查当前页面是否使用了你的自定义模板  
    if ( get_page_template_slug( $wp_query->get_queried_object_id() ) === 'zib_search_order' ) {  
        // 指定插件中模板文件的路径  
        $plugin_template = dirname(__FILE__) . '/order.php';  
  
        // 检查文件是否存在并加载它  
        if ( file_exists( $plugin_template ) ) {  
            return $plugin_template;  
        }  
    }  
  
    return $template;  
}  
add_filter( 'template_include', 'zib_search_order_include' );

// 在插件激活时检查并创建字段
function zib_check_and_add_count_field() {
    global $wpdb;

    // 检查 `count` 字段是否存在
    $table_name = $wpdb->prefix . 'zibpay_order';
    $column_exists = $wpdb->get_results("SHOW COLUMNS FROM `$table_name` LIKE 'count'");

    // 如果字段不存在，则添加字段
    if (empty($column_exists)) {
        $wpdb->query("ALTER TABLE `$table_name` ADD `count` INT NOT NULL DEFAULT 0 AFTER `status`");
    }
}
register_activation_hook(__FILE__, 'zib_check_and_add_count_field');

// 注册 AJAX 处理函数
function xuwbk_search_order() {
    // 验证 nonce 以提高安全性
    if (!wp_verify_nonce($_POST['nonce'] ?? '', 'xuwbk_order_search_nonce')) {
        zib_send_json_error('安全验证失败');
    }
    
    // 获取传递的订单号
    $order_num = isset($_POST['order_num']) ? sanitize_text_field($_POST['order_num']) : '';

    if (!$order_num) {
        zib_send_json_error('请输入订单号');
    }

    global $wpdb;
    
    // 确保表名正确
    $table_name = $wpdb->prefix . 'zibpay_order';
    
    // 检查表是否存在
    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") !== $table_name) {
        zib_send_json_error('订单表不存在');
    }
    
    // 赋值$order查询数据库订单号支付状态
    $order = $wpdb->get_row($wpdb->prepare("SELECT * FROM `$table_name` WHERE `order_num` = %s AND `status` = %d", $order_num, 1));

    // 订单号存在，查询订单内容
    if ($order) {
        // 更新查询次数
        $new_count = intval($order->count) + 1; // 计数加 1
        $update_result = $wpdb->update($table_name, ['count' => $new_count], ['order_num' => $order_num]);
        
        if ($update_result === false) {
            error_log('XuWbk订单查询：更新查询次数失败 - 订单号: ' . $order_num);
        }

        // 获取查询次数
        $count = $new_count;

        // 生成查询次数的输出
        if ($count == 1) {
            $count_message = '<span class="c-green">该订单号首次查询！</span>';
        } else {
            $count_message = '<span class="c-red">该订单号已存在' . $count . '次处理记录，请谨慎审核！</span>';
        }

        // 获取产品id
        $product_id       = $order->product_id; 
        // 获取订单支付时间
        $buy_time         = $order->pay_time; 
        // 获取支付类型
        $type             = $order->pay_type; 
        // 获取文章id
        $post_id          = $order->post_id; 
        // 获取用户id
        $user_id          = $order->user_id; 
        // 获取用户呢称
        $user_name = get_userdata($user_id);
        // 获取用户头像盒子
        $avatar = '';
        if (function_exists('zib_get_avatar_box') && $user_id) {
            $user_data = get_userdata($user_id);
            if ($user_data) {
                $avatar = zib_get_avatar_box($user_id, 'avatar-mini author-moderator-avatar mr6') . $user_data->display_name;
            }
        }
        // 通过文章id获取文章标题
        $posts_title      = get_the_title($post_id); 
        // 获取订单类型
        $order_type_name = '';
        if (function_exists('zibpay_get_pay_type_name')) {
            $order_type_name = zibpay_get_pay_type_name($order->order_type);
            
            // 如果返回的是默认值且类型为11或12，使用自定义名称
            if ($order->order_type == 11 && $order_type_name == '付费内容') {
                $order_type_name = '打赏作者';
            }
            if ($order->order_type == 12 && $order_type_name == '付费内容') {
                $order_type_name = '购买广告';
            }
        }
        // 输出订单类型
        $order_type = $order_type_name ? '<div class="pay-tag badg badg-sm mr6">' . $order_type_name . '</div>' : '';
        // 订单类型class
        $class = 'order-type-' . $order->order_type;

        // 根据 product_id 设置相应的产品名称
        switch ($product_id) {
            case 'vip_1_0_pay': // 非永久时长的一级会员
                $product_name = '一级会员';
                break;
            case 'vip_2_0_pay': // 非永久时长的二级会员
                $product_name = '二级会员';
                break;
            case 'vip_1_1_pay': // 永久时长的一级会员
                $product_name = '一级会员·永久';
                break;
            case 'vip_2_1_pay': // 永久时长的二级会员
                $product_name = '二级会员·永久';
                break;
            default:
                // 其他产品的处理
                $product_name = $product_id ?: $posts_title; // 若产品id内容为空则通过文章id输出文章标题
                break;
        }

        // 支付类型
        switch ($type) {
            case 'balance': // 余额
                $pay_type = '余额支付';
                $list_class = 'c-blue';
                break;
            case 'points': // 积分
                $pay_type = '积分支付';
                $list_class = 'c-yellow';
                break;
            case 'alipay': // 支付宝
                $pay_type = '支付宝';
                $list_class = 'c-blue';
                break;
            case 'wechat': // 微信
                $pay_type = '微信支付';
                $list_class = 'c-green';
                break;
            case 'paypal': // PayPal
                $pay_type = 'PayPal';
                $list_class = 'c-blue-2';
                break;
            case '卡密支付': // 卡密
                $pay_type = '卡密支付';
                $list_class = 'jb-pink';
                break;
            default:
                // 更多的支付类型可以按照上面的代码拓展
                $pay_type = '未知支付方式';
                $list_class = 'b-purple';
                break;
        }

        // 增加游客购买判断
        if (!$user_id) {
            $avatar = function_exists('zib_get_avatar_box') ? zib_get_avatar_box(0, 'avatar-mini author-moderator-avatar mr6') . '游客' : '游客';
        }

        $result = '<div class="result-ok"><svg class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="2166"><path d="M512 76.8c-236.8 0-435.2 192-435.2 435.2s192 435.2 435.2 435.2 435.2-192 435.2-435.2S748.8 76.8 512 76.8z m249.6 320L480 704c-12.8 12.8-38.4 12.8-51.2 0L288 556.8c-12.8-12.8-12.8-38.4 0-51.2 12.8-12.8 38.4-12.8 51.2 0l115.2 115.2L704 339.2c12.8-12.8 38.4-12.8 51.2 0 25.6 12.8 25.6 38.4 6.4 57.6z" fill="#68D279" p-id="2167"></path></svg>找到订单信息</div>';
        $result .= '<div class="result-list ' . $class . '">';
        $result .= '<div class="mb6">订单号：' . $order_num . '</div>';
        $result .= '<div class="mb6">购买时间：' . $buy_time . '</div>';
        $result .= '<div class="mb6">处理记录：' . $count_message . '</div>'; // 输出查询次数消息
        $result .= '<div class="mb6">购买内容：<b class="c-red">' . $product_name . '</b></div>';
        $result .= '<div class="mb6">订单类型：' . $order_type . '</div>';
        $result .= '<div class="mb6">支付方式：<b class="' . $list_class . '">' . $pay_type . '</b></div>';
        $result .= '<div class="mb6">购买用户：' . $avatar . '</div>';
        $result .= '</div>';

        zib_send_json_success('查询成功！', $result);
    } else {
        zib_send_json_error('未找到订单信息');
    }
}
add_action('wp_ajax_xuwbk_search_order', 'xuwbk_search_order');
add_action('wp_ajax_nopriv_xuwbk_search_order', 'xuwbk_search_order');

/**
 * Template name: XuWbk-订单查询
 * Description:   search-order
 */

get_header();
?>

<style>
.c-ok, .result-ok {
    color: #64d476;
}
.result-ok,
.result-error {
    margin: 40px 0 10px;
    text-align: center;
}
.result-list {
    font-size: 13px;
    color: #888;
    background: var(--main-bg-color);
    padding: 10px;
    border-radius: 4px;
    width: 300px;
    margin: auto;
}
</style>

<main class="container">
    <form class="text-center" style="top: 100px;">
        <div class="name">
            <div class="sign-logo"><?php echo zib_get_adaptive_theme_img(_pz('logo_src'), _pz('logo_src_dark')); ?></div>
            <h3 class="mb40">订单查询系统</h3>
        </div>
        <div class="form-group mt20" style="display: flex; justify-content: center; align-items: center;">
            <div style="width: 300px;">
                <input type="text" class="form-control" id="order_num" name="order_num" placeholder="请输入订单号" style="height: 45px; font-size: 16px; background: var(--main-bg-color);">
            </div>
        </div>
        <span class="balance-charge-link user-auth-apply but jb-blue padding-lg btn-block mt10 submit-order" style="height:40px;width:300px">提交</span>
        <input type="hidden" name="action" value="xuwbk_search_order">
        <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('xuwbk_order_search_nonce'); ?>">
        <div class="result-box text-left"></div>
    </form>
</main>

<script>
jQuery(document).ready(function($) {
    // 处理第二个 AJAX 请求
    $('.submit-order').on('click', function(e) {
        e.preventDefault();
        
        var orderNum = $('#order_num').val();
        
        // 添加加载提示
        notyf("加载中，请稍等...", "load", 2000, "result-box");

        $.ajax({
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'xuwbk_search_order',
                order_num: orderNum,
                nonce: $('input[name="nonce"]').val()
            },
            beforeSend: function() {
                $('.result-box').html(''); // 清空结果框
            },
            success: function(response) {
                if (!response.error && response.type) {
                    $('.result-box').html(response.type);
                    notyf("请求成功", "success", 2000, "result-box");
                } else {
                    $('.result-box').html('<div class="result-error"><svg class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="3355"><path d="M512 512m-435.2 0a435.2 435.2 0 1 0 870.4 0 435.2 435.2 0 1 0-870.4 0Z" fill="#FE6D68" p-id="3356"></path><path d="M563.2 512l108.8-108.8c12.8-12.8 12.8-38.4 0-51.2-12.8-12.8-38.4-12.8-51.2 0L512 460.8 403.2 352c-12.8-12.8-38.4-12.8-51.2 0-12.8 12.8-12.8 38.4 0 51.2L460.8 512 352 620.8c-12.8 12.8-12.8 38.4 0 51.2 12.8 12.8 38.4 12.8 51.2 0L512 563.2l108.8 108.8c12.8 12.8 38.4 12.8 51.2 0 12.8-12.8 12.8-38.4 0-51.2L563.2 512z" fill="#FFFFFF" p-id="3357"></path></svg>未找到相关结果</div>');
                    notyf("请求失败: " + response.message, "error", 2000, "result-box");
                }
            },
            error: function() {
                $('.result-box').html('<div class="result-error">发生错误，请重试。</div>');
                notyf("发生错误，请重试。", "error", 2000, "result-box");
            }
        });
    });
});
</script>
<?php
get_footer();
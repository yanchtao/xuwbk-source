<?php
/*
Template Name: 广告订单支付
*/

// 获取订单号
$order_num = isset($_GET['order_num']) ? sanitize_text_field($_GET['order_num']) : '';

if (!$order_num) {
    echo '<div style="text-align:center;padding:50px;"><h3>订单号不存在</h3><p><a href="' . home_url() . '">返回首页</a></p></div>';
    exit;
}

// 查询订单
global $wpdb;
$table_name = $wpdb->prefix . 'zibpay_order';
$order = $wpdb->get_row($wpdb->prepare("SELECT * FROM `$table_name` WHERE `order_num` = %s", $order_num));

if (!$order) {
    echo '<div style="text-align:center;padding:50px;"><h3>订单不存在</h3><p>订单号: ' . esc_html($order_num) . '</p><p><a href="' . home_url() . '">返回首页</a></p></div>';
    exit;
}

// 获取订单的其他数据
$other_data = !empty($order->other) ? maybe_unserialize($order->other) : array();

get_header();
?>

<style>
    .order-pay-container {
        max-width: 800px;
        margin: 40px auto;
        background: var(--main-bg-color, #fff);
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    .order-pay-title {
        text-align: center;
        margin-bottom: 30px;
        color: #333;
    }
    .order-info-section {
        background: var(--muted-bg, #f5f5f5);
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
    }
    .order-info-row {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        border-bottom: 1px solid #ddd;
    }
    .order-info-row:last-child {
        border-bottom: none;
    }
    .order-info-label {
        color: #666;
        font-weight: 500;
    }
    .order-info-value {
        color: #333;
        font-weight: 600;
    }
    .order-price {
        color: #ff4c77;
        font-size: 24px;
        font-weight: bold;
    }
    .pay-methods {
        display: grid;
        gap: 15px;
        margin: 20px 0;
    }
    .pay-method {
        border: 2px solid #ddd;
        padding: 20px;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
    }
    .pay-method:hover {
        border-color: var(--main-color, #007bff);
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    .pay-method.active {
        border-color: var(--main-color, #007bff);
        background: rgba(0,123,255,0.05);
    }
    .pay-method-icon {
        width: 50px;
        height: 50px;
        margin-right: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
    }
    .pay-method-info h4 {
        margin: 0 0 5px 0;
        font-size: 16px;
    }
    .pay-method-info p {
        margin: 0;
        color: #666;
        font-size: 14px;
    }
    .order-actions {
        text-align: center;
        margin-top: 30px;
    }
    .btn-primary {
        background: var(--main-color, #007bff);
        color: #fff;
        padding: 12px 40px;
        border-radius: 25px;
        border: none;
        font-size: 16px;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .btn-primary:hover {
        opacity: 0.9;
        transform: translateY(-2px);
    }
    .btn-secondary {
        background: #6c757d;
        color: #fff;
        padding: 12px 30px;
        border-radius: 25px;
        border: none;
        font-size: 16px;
        cursor: pointer;
        margin-left: 10px;
    }
    .contact-info {
        background: #fff3cd;
        padding: 15px;
        border-radius: 5px;
        margin-top: 20px;
        border-left: 4px solid #ffc107;
    }
    /* 子比主题收银台样式 */
    .zibpay-pay-box {
        background: #fff;
        padding: 20px;
        border-radius: 8px;
        margin-top: 20px;
    }
</style>

<div class="order-pay-container">
    <h2 class="order-pay-title">广告订单支付</h2>

    <?php if ($order->status == 1): ?>
        <div style="text-align:center;padding:30px;background:#d4edda;color:#155724;border-radius:8px;margin-bottom:20px;">
            <h3>✓ 已支付</h3>
            <p>订单已成功支付，请等待广告上线</p>
        </div>
    <?php elseif ($order->status == -1): ?>
        <div style="text-align:center;padding:30px;background:#f8d7da;color:#721c24;border-radius:8px;margin-bottom:20px;">
            <h3>✗ 订单已关闭</h3>
            <p>该订单已关闭，无法继续支付</p>
        </div>
    <?php else: ?>
        <div class="order-info-section">
            <h3 style="margin-top:0;">订单信息</h3>
            <div class="order-info-row">
                <span class="order-info-label">订单号</span>
                <span class="order-info-value"><?php echo esc_html($order_num); ?></span>
            </div>
            <div class="order-info-row">
                <span class="order-info-label">广告名称</span>
                <span class="order-info-value"><?php echo esc_html($other_data['ad_title'] ?? '未知'); ?></span>
            </div>
            <div class="order-info-row">
                <span class="order-info-label">订单金额</span>
                <span class="order-info-value order-price">¥<?php echo number_format($order->order_price, 2); ?></span>
            </div>
            <div class="order-info-row">
                <span class="order-info-label">开始日期</span>
                <span class="order-info-value"><?php echo esc_html($other_data['start_date'] ?? '未知'); ?></span>
            </div>
            <div class="order-info-row">
                <span class="order-info-label">结束日期</span>
                <span class="order-info-value"><?php echo esc_html($other_data['end_date'] ?? '未知'); ?></span>
            </div>
            <div class="order-info-row">
                <span class="order-info-label">联系方式</span>
                <span class="order-info-value"><?php echo esc_html($other_data['contact_value'] ?? '未知'); ?></span>
            </div>
            <?php if (!empty($other_data['ad_image'])): ?>
            <div class="order-info-row" style="display:block;">
                <span class="order-info-label">广告预览</span>
                <div style="margin-top:10px;">
                    <img src="<?php echo esc_url($other_data['ad_image']); ?>" alt="广告预览" style="max-width:300px;border-radius:4px;">
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- 使用子比主题的支付系统 -->
        <div class="zibpay-pay-box">
            <h3>在线支付</h3>
            <?php
            // 尝试从子比主题获取支付方式
            $payment_methods = array();
            if (function_exists('zibpay_get_payment_methods')) {
                $payment_methods = zibpay_get_payment_methods();
                error_log('从子比主题获取到支付方式: ' . print_r($payment_methods, true));
            } else {
                error_log('zibpay_get_payment_methods 函数不存在');
            }

            // 如果没有获取到支付方式，使用备用配置
            if (empty($payment_methods)) {
                error_log('支付方式为空，尝试使用备用配置');
                // 使用子比主题的 _pz 函数检查支付配置
                if (function_exists('_pz')) {
                    $pay_wechat_sdk = _pz('pay_wechat_sdk_options');
                    $pay_alipay_sdk = _pz('pay_alipay_sdk_options');
                    $pay_paypal_sdk = _pz('pay_paypal_sdk_s');
                    $pay_balance = function_exists('zibpay_is_allow_balance_pay') && zibpay_is_allow_balance_pay();

                    error_log('支付配置检查 - 微信: ' . ($pay_wechat_sdk && 'null' != $pay_wechat_sdk ? 'yes' : 'no') .
                              ', 支付宝: ' . ($pay_alipay_sdk && 'null' != $pay_alipay_sdk ? 'yes' : 'no') .
                              ', PayPal: ' . ($pay_paypal_sdk ? 'yes' : 'no') .
                              ', 余额: ' . ($pay_balance ? 'yes' : 'no'));

                    if ($pay_wechat_sdk && 'null' != $pay_wechat_sdk) {
                        $payment_methods['wechat'] = array('name' => '微信');
                    }
                    if ($pay_alipay_sdk && 'null' != $pay_alipay_sdk) {
                        $payment_methods['alipay'] = array('name' => '支付宝');
                    }
                    if ($pay_paypal_sdk) {
                        $payment_methods['paypal'] = array('name' => 'PayPal');
                    }
                    if ($pay_balance) {
                        $payment_methods['balance'] = array('name' => '余额');
                    }
                } else {
                    // 最后的备用方案：直接提供支付宝选项
                    error_log('_pz 函数不存在，使用备用支付方式');
                    $payment_methods['alipay'] = array('name' => '支付宝');
                }
                error_log('使用备用支付方式: ' . print_r($payment_methods, true));
            }

            // 如果仍然没有支付方式，强制添加支付宝
            if (empty($payment_methods)) {
                error_log('所有方法都失败，强制添加支付宝');
                $payment_methods['alipay'] = array('name' => '支付宝');
            }

            error_log('最终使用的支付方式: ' . print_r($payment_methods, true));

            // 显示支付方式
            if (!empty($payment_methods)): ?>
                <div class="pay-methods">
                    <?php foreach ($payment_methods as $method_key => $method_info):
                        $method_name = $method_info['name'] ?? ucfirst($method_key);
                        $icon = '';

                        // 根据支付方式设置图标
                        switch ($method_key) {
                            case 'wechat':
                                $icon = '<div class="pay-method-icon" style="color:#07c160;">💬</div>';
                                break;
                            case 'alipay':
                                $icon = '<div class="pay-method-icon" style="color:#1677ff;">💳</div>';
                                break;
                            case 'paypal':
                                $icon = '<div class="pay-method-icon" style="color:#003087;">🌐</div>';
                                break;
                            case 'balance':
                                $icon = '<div class="pay-method-icon" style="color:#ffc107;">💰</div>';
                                break;
                            default:
                                $icon = '<div class="pay-method-icon" style="color:#666;">💰</div>';
                        }
                    ?>
                    <div class="pay-method" onclick="initiatePay('<?php echo esc_attr($method_key); ?>', <?php echo esc_attr($order->id); ?>)">
                        <?php echo $icon; ?>
                        <div class="pay-method-info">
                            <h4><?php echo esc_html($method_name); ?></h4>
                            <p>使用<?php echo esc_html($method_name); ?>完成支付</p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="contact-info">
                    <strong>温馨提示：</strong>
                    <p>系统暂未配置在线支付方式，请联系客服完成支付。</p>
                </div>
            <?php endif; ?>
        </div>

        <div class="contact-info">
            <strong>支付说明：</strong>
            <ul style="margin:10px 0 0 20px;">
                <li>订单创建后请在30分钟内完成支付</li>
                <li>支付成功后广告将自动上线</li>
                <li>如有问题请联系客服QQ：<?php echo esc_html(get_option('XuWbk')['contact_qq'] ?? '6050640'); ?></li>
            </ul>
        </div>

        <div class="order-actions">
            <button class="btn-secondary" onclick="window.location.href='<?php echo home_url(); ?>'">返回首页</button>
        </div>
    <?php endif; ?>
</div>

<script>
var selectedPayMethod = '';

function selectPayMethod(method) {
    selectedPayMethod = method;
    document.querySelectorAll('.pay-method').forEach(function(el) {
        el.classList.remove('active');
    });
    event.currentTarget.classList.add('active');
}

// 发起支付
function initiatePay(payMethod, orderId) {
    var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
    
    // 显示加载提示
    var loadingMsg = '<div class="text-center padding-lg"><i class="fa fa-spinner fa-spin fa-2x"></i><br><br>正在生成支付订单，请稍候...</div>';
    
    // 创建临时弹窗显示加载状态
    var modal = document.createElement('div');
    modal.style.cssText = 'position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.5);display:flex;align-items:center;justify-content:center;z-index:9999;';
    modal.innerHTML = '<div style="background:#fff;padding:40px;border-radius:10px;max-width:400px;text-align:center;">' + loadingMsg + '</div>';
    document.body.appendChild(modal);
    
    // 发送AJAX请求 - 使用自定义的支付处理函数
    var formData = new FormData();
    formData.append('action', 'xuwbk_ad_initiate_pay');
    formData.append('order_id', orderId);
    formData.append('pay_method', payMethod);
    
    fetch(ajaxurl, {
        method: 'POST',
        body: formData
    })
    .then(function(response) {
        console.log('原始响应:', response);
        console.log('响应状态:', response.status);
        return response.json();
    })
    .then(function(data) {
        console.log('解析后的JSON数据:', data);
        console.log('数据类型:', typeof data);
        console.log('所有字段:', Object.keys(data));
        // 移除加载弹窗
        if (document.body.contains(modal)) {
            document.body.removeChild(modal);
        }
        
        console.log('支付响应:', data);
        
        // 检查是否成功 - 子比主题可能返回不同的格式
        var isSuccess = false;
        var qrCodeUrl = '';
        var message = '';
        
        // 格式1: {success: true, data: {qrcode: "..."}}
        if (data.success && data.data) {
            isSuccess = true;
            // 优先使用 url_qrcode (base64格式), 其次使用 qr_code, 最后使用 qrcode
            qrCodeUrl = data.data.url_qrcode || data.data.qr_code || data.data.qrcode;
            message = data.msg || '支付请求已提交';
        }
        // 格式2: {code: "10000", qrcode: "...", msg: "..."}
        else if (data.code === "10000") {
            isSuccess = true;
            // 优先使用 url_qrcode (base64格式), 其次使用 qr_code, 最后使用 qrcode
            qrCodeUrl = data.url_qrcode || data.qr_code || data.qrcode;
            message = data.msg || '请扫码支付';
        }
        // 格式3: 直接包含 qrcode 字段
        else if (data.qrcode || data.qr_code) {
            isSuccess = true;
            // 优先使用 url_qrcode (base64格式), 其次使用 qr_code, 最后使用 qrcode
            qrCodeUrl = data.url_qrcode || data.qr_code || data.qrcode;
            message = data.msg || '请扫码支付';
        }
        
        if (isSuccess) {
            console.log('支付发起成功:', message);
            console.log('二维码URL:', qrCodeUrl);
            
            if (qrCodeUrl) {
                // 显示二维码
                showQrCodeModal(qrCodeUrl, payMethod);
            } else {
                // 没有二维码，可能是其他支付方式
                alert(message);
                // 刷新页面查看订单状态
                setTimeout(function() {
                    window.location.reload();
                }, 2000);
            }
        } else {
            // 失败的情况
            var errorMsg = data.msg || data.message || data.data || '未知错误';
            console.error('支付发起失败:', errorMsg);
            alert('支付发起失败：' + errorMsg);
        }
    })
    .catch(function(error) {
        // 移除加载弹窗
        if (document.body.contains(modal)) {
            document.body.removeChild(modal);
        }
        console.error('支付错误:', error);
        alert('网络错误，请稍后重试');
    });
}

// 显示二维码弹窗
function showQrCodeModal(qrCodeUrl, payMethod) {
    console.log('开始显示二维码弹窗');
    console.log('二维码URL:', qrCodeUrl);
    console.log('支付方式:', payMethod);
    
    var payMethodNames = {
        'wechat': '微信支付',
        'alipay': '支付宝',
        'paypal': 'PayPal',
        'balance': '余额支付'
    };
    
    var modal = document.createElement('div');
    modal.id = 'payQrCodeModal';
    modal.style.cssText = 'position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.75);display:flex;align-items:center;justify-content:center;z-index:9999999;';
    
    var payMethodName = payMethodNames[payMethod] || '在线支付';
    
    modal.innerHTML = '<div style="background:#fff;padding:35px;border-radius:16px;max-width:450px;text-align:center;box-shadow:0 10px 40px rgba(0,0,0,0.4);position:relative;z-index:2;">' +
        '<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:25px;padding-bottom:15px;border-bottom:1px solid #eee;">' +
        '<h3 style="margin:0;font-size:22px;color:#333;font-weight:600;">' + payMethodName + '</h3>' +
        '<button id="closeQrCodeBtn" style="background:none;border:none;font-size:32px;color:#999;cursor:pointer;padding:0;width:35px;height:35px;line-height:35px;border-radius:50%;transition:all 0.2s;">&times;</button>' +
        '</div>' +
        '<p style="color:#666;margin:0 0 25px 0;font-size:15px;">请使用' + payMethodName + '扫描下方二维码完成支付</p>' +
        '<div id="qrCodeContainer" style="margin:25px 0;padding:20px;background:#f8f9fa;border-radius:10px;border:2px solid #e9ecef;position:relative;">' +
        '<div id="qrCodeLoading" style="position:absolute;top:0;left:0;right:0;bottom:0;display:flex;align-items:center;justify-content:center;background:rgba(255,255,255,0.9);">' +
        '<i class="fa fa-spinner fa-spin fa-2x" style="color:#007bff;"></i></div>' +
        '<img id="payQrCodeImg" src="' + qrCodeUrl + '" style="width:220px;height:220px;display:block;margin:0 auto;" alt="支付二维码" onerror="this.style.display=\'none\';document.getElementById(\'qrCodeError\').style.display=\'block\';">' +
        '<div id="qrCodeError" style="display:none;padding:20px;color:#dc3545;text-align:center;">' +
        '<i class="fa fa-exclamation-triangle fa-2x"></i><br><br>' +
        '二维码加载失败<br>' +
        '<a href="' + qrCodeUrl + '" target="_blank" style="color:#007bff;text-decoration:underline;">点击此处打开支付链接</a>' +
        '</div>' +
        '</div>' +
        '<p style="color:#999;font-size:13px;margin-bottom:20px;">订单金额: <strong style="color:#ff4c77;font-size:18px;">¥' + document.querySelector('.order-price').textContent.replace('¥', '') + '</strong></p>' +
        '<p style="color:#666;font-size:14px;margin-bottom:20px;">支付完成后点击下方按钮刷新页面</p>' +
        '<div style="display:flex;gap:12px;justify-content:center;">' +
        '<button id="paidBtn" style="background:#007bff;color:#fff;padding:12px 40px;border:none;border-radius:8px;cursor:pointer;font-size:16px;font-weight:500;transition:all 0.2s;box-shadow:0 2px 8px rgba(0,123,255,0.3);">我已完成支付</button>' +
        '</div>' +
        '</div>';
    
    document.body.appendChild(modal);
    console.log('弹窗已添加到DOM');
    
    // 绑定关闭按钮事件
    var closeBtn = document.getElementById('closeQrCodeBtn');
    if (closeBtn) {
        closeBtn.onclick = closeQrCodeModal;
        closeBtn.onmouseover = function() { this.style.background = '#f0f0f0'; };
        closeBtn.onmouseout = function() { this.style.background = 'none'; };
    }
    
    // 绑定已支付按钮事件
    var paidBtn = document.getElementById('paidBtn');
    if (paidBtn) {
        paidBtn.onmouseover = function() { this.style.background = '#0056b3'; };
        paidBtn.onmouseout = function() { this.style.background = '#007bff'; };
    }
    
    // 监听图片加载
    var qrImg = document.getElementById('payQrCodeImg');
    var qrLoading = document.getElementById('qrCodeLoading');
    var qrErrorDiv = document.getElementById('qrCodeError');
    
    console.log('二维码URL类型:', qrCodeUrl.substring(0, 50));
    
    // 判断URL类型
    var isBase64 = qrCodeUrl.startsWith('data:image');
    var isCustomProtocol = qrCodeUrl.startsWith('https://') || qrCodeUrl.startsWith('http://');
    
    console.log('是否base64:', isBase64);
    console.log('是否自定义协议:', isCustomProtocol);
    
    var finalUrl = qrCodeUrl;
    
    // 如果是自定义协议（支付宝/微信的唤起协议），不尝试加载图片，直接显示错误
    if (isCustomProtocol && !isBase64) {
        console.log('检测到自定义协议，无法直接显示图片');
        // 隐藏loading，显示错误
        if (qrLoading) {
            qrLoading.style.display = 'none';
        }
        if (qrErrorDiv) {
            qrErrorDiv.style.display = 'block';
        }
    } else {
        // 正常的图片URL或base64，尝试加载
        qrImg.onload = function() {
            console.log('二维码图片加载成功');
            if (qrLoading) {
                qrLoading.style.display = 'none';
            }
            if (qrErrorDiv) {
                qrErrorDiv.style.display = 'none';
            }
        };
        
        qrImg.onerror = function() {
            console.error('二维码图片加载失败:', finalUrl);
            if (qrLoading) {
                qrLoading.style.display = 'none';
            }
            if (qrErrorDiv) {
                qrErrorDiv.style.display = 'block';
            }
        };
        
        // 预加载图片
        var tempImg = new Image();
        tempImg.onload = function() {
            console.log('图片预加载成功');
        };
        tempImg.onerror = function() {
            console.error('图片预加载失败，URL:', finalUrl);
            // 预加载失败也隐藏loading，显示错误
            if (qrLoading) {
                qrLoading.style.display = 'none';
            }
            if (qrErrorDiv) {
                qrErrorDiv.style.display = 'block';
            }
        };
        tempImg.src = finalUrl;
    }
    
    console.log('最终使用的URL:', finalUrl.substring(0, 100));
    
    // 添加动画样式
    if (!document.getElementById('payModalStyles')) {
        var style = document.createElement('style');
        style.id = 'payModalStyles';
        style.textContent = '@keyframes fadeIn{from{opacity:0}to{opacity:1}}@keyframes slideUp{from{transform:translateY(30px);opacity:0}to{transform:translateY(0);opacity:1}}';
        document.head.appendChild(style);
    }
    
    // 强制重绘
    modal.offsetHeight;
    modal.style.animation = 'fadeIn 0.3s ease';
    modal.querySelector('div').style.animation = 'slideUp 0.3s ease';
    
    console.log('二维码弹窗显示完成');
}

function closeQrCodeModal() {
    var modal = document.getElementById('payQrCodeModal');
    if (modal) {
        modal.style.opacity = '0';
        setTimeout(function() {
            document.body.removeChild(modal);
        }, 300);
    }
}
</script>

<?php get_footer(); ?>

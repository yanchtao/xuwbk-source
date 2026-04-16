<?php
/*
Template Name: 购买广告位
*/

get_header();

// 获取URL参数中的广告位ID
$default_slot_id = isset($_GET['slot']) ? sanitize_text_field($_GET['slot']) : '';
?>

<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="theme-box p-3">
                <h1 class="text-center mb-4">购买广告位-文字-首页主内容上方</h1>
                <p class="text-center text-muted mb-4">请确保您的网站内容合法合规,广告内容需符合相关法律法规。支付成功后广告将自动上线,到期后自动下线。</p>

                <form id="ad-purchase-form" class="ad-purchase-form">
                    <!-- 购买时长 -->
                    <div class="form-group mb-4">
                        <label class="form-label">购买时长</label>
                        <div class="radio-group">
                            <label class="radio-option">
                                <input type="radio" name="duration" value="1" checked data-price="50.00">
                                <span class="radio-label">月付-1月-¥50.00</span>
                            </label>
                            <label class="radio-option">
                                <input type="radio" name="duration" value="3" data-price="140.00">
                                <span class="radio-label">季付-3月-¥140.00</span>
                            </label>
                            <label class="radio-option">
                                <input type="radio" name="duration" value="6" data-price="260.00">
                                <span class="radio-label">半年-6月-¥260.00</span>
                            </label>
                            <label class="radio-option">
                                <input type="radio" name="duration" value="12" data-price="480.00">
                                <span class="radio-label">年付-12月-¥480.00</span>
                            </label>
                        </div>
                        <div class="custom-duration" style="display: none;">
                            <label class="radio-option">
                                <input type="radio" name="duration" value="custom">
                                <span class="radio-label">自定义月数</span>
                            </label>
                            <input type="number" id="custom-months" class="form-control" min="1" max="36" value="1" style="width: 100px;">
                            <span class="text-muted">月</span>
                        </div>
                    </div>

                    <!-- 广告位选择 -->
                    <div class="form-group mb-4">
                        <label class="form-label">广告位</label>
                        <select id="slot-select" class="form-control" required>
                            <option value="">-- 请选择广告位 --</option>
                            <?php
                            $options = get_option('XuWbk');
                            if (!empty($options['image_ad_slots']) && is_array($options['image_ad_slots'])) {
                                foreach ($options['image_ad_slots'] as $slot) {
                                    $slot_id = esc_attr($slot['slot_id'] ?? '');
                                    $slot_name = esc_html($slot['slot_name'] ?? '未命名');
                                    $selected = ($slot_id === $default_slot_id) ? ' selected' : '';
                                    if (!empty($slot_id)) {
                                        echo '<option value="' . $slot_id . '"' . $selected . '>' . $slot_name . '</option>';
                                    }
                                }
                            }
                            ?>
                        </select>
                    </div>

                    <!-- 联系方式 -->
                    <div class="form-group mb-4">
                        <label class="form-label">联系方式</label>
                        <div class="contact-methods">
                            <label class="contact-option">
                                <input type="radio" name="contact_method" value="email" checked>
                                <span class="contact-label">邮箱</span>
                            </label>
                            <label class="contact-option">
                                <input type="radio" name="contact_method" value="qq">
                                <span class="contact-label">QQ</span>
                            </label>
                            <label class="contact-option">
                                <input type="radio" name="contact_method" value="wechat">
                                <span class="contact-label">微信</span>
                            </label>
                            <label class="contact-option">
                                <input type="radio" name="contact_method" value="phone">
                                <span class="contact-label">手机</span>
                            </label>
                        </div>
                        <input type="text" id="contact-value" class="form-control" placeholder="请输入邮箱地址" required>
                    </div>

                    <!-- 广告内容 -->
                    <div class="form-group mb-4">
                        <label class="form-label">广告内容</label>

                        <!-- 网站名称 -->
                        <div class="mb-3">
                            <label class="form-label-sm">网站名称</label>
                            <input type="text" id="website-name" class="form-control" placeholder="请输入网站名称(2-8个字)" required maxlength="8" minlength="2">
                            <small class="text-muted">建议长度:2-8个字 (<span id="name-count">0</span>字)</small>
                        </div>

                        <!-- 广告图片 -->
                        <div class="mb-3">
                            <label class="form-label-sm">广告图片</label>
                            <div class="ad-image-upload">
                                <input type="file" id="ad-image" class="form-control-file" accept="image/*" required>
                                <div id="image-preview" class="image-preview"></div>
                            </div>
                            <small class="text-muted">建议尺寸: 宽度800px以上, 高度根据广告位比例调整</small>
                        </div>

                        <!-- 广告链接 -->
                        <div class="mb-3">
                            <label class="form-label-sm">广告链接</label>
                            <input type="url" id="ad-url" class="form-control" placeholder="请输入广告链接" required>
                        </div>

                        <!-- 广告描述 -->
                        <div class="mb-3">
                            <label class="form-label-sm">广告描述 (可选)</label>
                            <textarea id="ad-description" class="form-control" rows="3" maxlength="200" placeholder="请输入广告描述(最多200字)"></textarea>
                            <small class="text-muted">(<span id="desc-count">0</span>/200字)</small>
                        </div>
                    </div>

                    <!-- 价格显示 -->
                    <div class="form-group mb-4">
                        <div class="price-display">
                            <span class="price-label">订单总额:</span>
                            <span class="price-value">¥<span id="total-price">50.00</span></span>
                        </div>
                    </div>

                    <!-- 提交按钮 -->
                    <div class="form-group mb-4">
                        <button type="submit" class="btn btn-primary btn-lg w-100" id="submit-btn">
                            <i class="fa fa-lock"></i> 立即购买
                        </button>
                    </div>

                    <div class="text-center text-muted small">
                        <p>提交订单后,请在24小时内完成支付,超时订单将自动取消。</p>
                        <p>如有疑问,请联系客服 QQ: <?php echo get_option('XuWbk')['contact_qq'] ?? '6050640'; ?></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.ad-purchase-form {
    max-width: 600px;
    margin: 0 auto;
    padding: 20px;
}

.form-group {
    margin-bottom: 20px;
}

.form-label {
    display: block;
    margin-bottom: 10px;
    font-weight: 600;
    font-size: 16px;
    color: #333;
}

.form-label-sm {
    display: block;
    margin-bottom: 8px;
    font-weight: 500;
    font-size: 14px;
    color: #666;
}

.form-control {
    width: 100%;
    padding: 10px 15px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 14px;
    transition: border-color 0.3s;
}

.form-control:focus {
    outline: none;
    border-color: #409eff;
}

.form-control-file {
    width: 100%;
    padding: 8px;
}

.radio-group {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

.radio-option {
    display: flex;
    align-items: center;
    padding: 10px 15px;
    border: 1px solid #ddd;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.3s;
}

.radio-option:hover {
    border-color: #409eff;
    background-color: #f5f7fa;
}

.radio-option input[type="radio"] {
    margin-right: 8px;
}

.radio-option input[type="radio"]:checked + .radio-label {
    color: #409eff;
    font-weight: 600;
}

.contact-methods {
    display: flex;
    gap: 10px;
    margin-bottom: 10px;
}

.contact-option {
    display: flex;
    align-items: center;
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.3s;
}

.contact-option:hover {
    border-color: #409eff;
    background-color: #f5f7fa;
}

.contact-option input[type="radio"] {
    margin-right: 6px;
}

.custom-duration {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-top: 10px;
    padding: 10px;
    background-color: #f5f7fa;
    border-radius: 6px;
}

.image-preview {
    margin-top: 10px;
    min-height: 100px;
    border: 2px dashed #ddd;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.image-preview img {
    max-width: 100%;
    max-height: 200px;
    object-fit: contain;
}

.price-display {
    padding: 20px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 8px;
    text-align: center;
    color: white;
}

.price-label {
    display: block;
    font-size: 16px;
    margin-bottom: 5px;
}

.price-value {
    display: block;
    font-size: 32px;
    font-weight: 700;
}

.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 12px 30px;
    border: none;
    border-radius: 6px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    text-decoration: none;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}

.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.w-100 {
    width: 100%;
}

.btn-lg {
    padding: 15px 40px;
    font-size: 18px;
}

.text-center {
    text-align: center;
}

.text-muted {
    color: #909399;
}

.small {
    font-size: 12px;
}

@media (max-width: 768px) {
    .radio-group {
        flex-direction: column;
    }

    .contact-methods {
        flex-wrap: wrap;
    }

    .radio-option,
    .contact-option {
        width: 100%;
    }
}
</style>

<script>
jQuery(document).ready(function($) {
    // 自动选择URL中的广告位
    const urlParams = new URLSearchParams(window.location.search);
    const slotParam = urlParams.get('slot');
    if (slotParam) {
        $('#slot-select').val(slotParam);
    }

    // 价格计算
    const basePrice = 50.00;

    // 时长选择
    $('input[name="duration"]').on('change', function() {
        const value = $(this).val();

        if (value === 'custom') {
            $('.custom-duration').slideDown();
        } else {
            $('.custom-duration').slideUp();
            updateTotalPrice();
        }
    });

    // 自定义月数
    $('#custom-months').on('input', function() {
        updateTotalPrice();
    });

    // 更新总价
    function updateTotalPrice() {
        const duration = $('input[name="duration"]:checked').val();
        let total = 0;

        if (duration === 'custom') {
            const months = parseInt($('#custom-months').val()) || 1;
            total = months * basePrice;
        } else {
            const selected = $('input[name="duration"]:checked');
            total = parseFloat(selected.data('price')) || basePrice;
        }

        $('#total-price').text(total.toFixed(2));
    }

    // 联系方式
    $('input[name="contact_method"]').on('change', function() {
        const method = $(this).val();
        let placeholder = '';

        switch(method) {
            case 'email':
                placeholder = '请输入邮箱地址';
                break;
            case 'qq':
                placeholder = '请输入QQ号';
                break;
            case 'wechat':
                placeholder = '请输入微信号';
                break;
            case 'phone':
                placeholder = '请输入手机号';
                break;
        }

        $('#contact-value').attr('placeholder', placeholder).val('');
    });

    // 网站名称字数统计
    $('#website-name').on('input', function() {
        const length = $(this).val().length;
        $('#name-count').text(length);

        if (length < 2 || length > 8) {
            $(this).css('border-color', '#ff4d4f');
        } else {
            $(this).css('border-color', '#52c41a');
        }
    });

    // 广告描述字数统计
    $('#ad-description').on('input', function() {
        const length = $(this).val().length;
        $('#desc-count').text(length);
    });

    // 图片预览
    $('#ad-image').on('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#image-preview').html('<img src="' + e.target.result + '" alt="广告图片预览">');
            };
            reader.readAsDataURL(file);
        }
    });

    // 表单提交
    $('#ad-purchase-form').on('submit', function(e) {
        e.preventDefault();

        const btn = $('#submit-btn');
        btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> 提交中...');

        // 获取表单数据
        const formData = new FormData();
        formData.append('action', 'xuwbk_submit_ad_order');
        formData.append('nonce', '<?php echo wp_create_nonce('xuwbk_ad_purchase_nonce'); ?>');
        formData.append('duration', $('input[name="duration"]:checked').val());
        formData.append('custom_months', $('#custom-months').val());
        formData.append('slot_id', $('#slot-select').val());
        formData.append('contact_method', $('input[name="contact_method"]:checked').val());
        formData.append('contact_value', $('#contact-value').val());
        formData.append('website_name', $('#website-name').val());
        formData.append('ad_image', $('#ad-image')[0].files[0]);
        formData.append('ad_url', $('#ad-url').val());
        formData.append('ad_description', $('#ad-description').val());

        // AJAX提交
        $.ajax({
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    alert('订单提交成功！请前往支付页面完成付款。');
                    // 跳转到支付页面
                    if (response.data.redirect_url) {
                        window.location.href = response.data.redirect_url;
                    }
                } else {
                    alert('提交失败：' + (response.data || '未知错误'));
                }
            },
            error: function() {
                alert('网络错误，请稍后重试');
            },
            complete: function() {
                btn.prop('disabled', false).html('<i class="fa fa-lock"></i> 立即购买');
            }
        });
    });
});
</script>

<?php get_footer(); ?>

<?php
/**
 * 悬浮导航栏 - HTML结构
 * 作者: 轩玮
 * 版本: 1.0.0
 * 更新时间: 2025-03-11
 * 
 * 使用说明:
 * 1. 在主题的 footer.php 文件中添加以下代码:
 *    <?php get_template_part('inc/xuwbk_float_nav_html'); ?>
 * 
 * 2. 确保CSS和JS文件已加载:
 *    - assets/css/xuwbk_float_nav.css
 *    - assets/js/xuwbk_float_nav.js
 */

// 防止直接访问
if (!defined('ABSPATH')) {
    exit;
}

// 获取主题选项
$options = get_option('XuWbk', array());
$float_nav_enabled = isset($options['float_nav_enabled']) ? $options['float_nav_enabled'] : true;

// 如果未启用，不显示
if (!$float_nav_enabled) {
    return;
}

// VIP链接
$vip_link = isset($options['vip_link']) && !empty($options['vip_link']) 
    ? $options['vip_link'] 
    : 'https://bwzy.bwxt88.com/%e4%bc%9a%e5%91%98/';

// 抽奖链接
$lottery_link = isset($options['lottery_link']) && !empty($options['lottery_link'])
    ? $options['lottery_link']
    : 'https://bwzy.bwxt88.com/%E6%8A%BD%E5%A5%96/';

// 二维码图片 - 使用base64编码的1x1透明像素作为默认占位符
$default_qrcode = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+P+/HgAFhAJ/wlseKgAAAABJRU5ErkJggg==';
$qr_code = isset($options['qr_code']) && !empty($options['qr_code'])
    ? $options['qr_code']
    : $default_qrcode;

// 客服用户ID
$service_user_id = isset($options['service_user_id']) ? $options['service_user_id'] : 1;

// 设备显示设置
$pc_show = isset($options['float_nav_pc_show']) ? $options['float_nav_pc_show'] : true;
$mobile_show = isset($options['float_nav_mobile_show']) ? $options['float_nav_mobile_show'] : true;

// 检测设备类型
$is_mobile = wp_is_mobile();

// 根据设备和设置决定是否显示
if (($is_mobile && !$mobile_show) || (!$is_mobile && !$pc_show)) {
    return; // 不显示导航栏
}

?>

<!-- 悬浮导航栏 - 开始 -->
<div class="comfortable-nav" id="xuwbk-float-nav">
    
    <!-- VIP会员 -->
    <div class="nav-item vip-item-right" data-feature="vip" data-href="<?php echo esc_url($vip_link); ?>">
        <div class="vip-pulse"></div>
        <div class="vip-pulse"></div>
        <div class="vip-pulse"></div>
        <i class="fas fa-gem nav-icon"></i>
        <span class="nav-tooltip">开通VIP</span>
        
        <div class="vip-panel">
            <h4><i class="fa fa-diamond"></i> 开通会员享特权</h4>
            <div class="vip-benefits">
                <div class="vip-benefit">
                    <i class="fa fa-check"></i>
                    <span>专属内容无限访问</span>
                </div>
                <div class="vip-benefit">
                    <i class="fa fa-check"></i>
                    <span>下载权限提升至最高级</span>
                </div>
                <div class="vip-benefit">
                    <i class="fa fa-check"></i>
                    <span>专属网站付费美化优惠</span>
                </div>
                <div class="vip-benefit">
                    <i class="fa fa-check"></i>
                    <span>免费下载更多精品资源</span>
                </div>
            </div>
            <div class="vip-pricing">
                <div class="vip-price">
                    <span class="current">¥59</span>
                    <span class="original">¥198</span>
                </div>
                <div class="vip-period">➡️轩玮博客专属会员</div>
            </div>
            <a href="<?php echo esc_url($vip_link); ?>" class="vip-cta" target="_blank" rel="noopener noreferrer">
                <i class="fa fa-rocket"></i> 立即开通
            </a>
        </div>
    </div>
    
    <!-- 抽奖功能 -->
    <div class="nav-item lottery-item" data-feature="lottery" data-href="<?php echo esc_url($lottery_link); ?>">
        <i class="fa fa-gift nav-icon"></i>
        <span class="nav-tooltip">幸运抽奖</span>
        
        <div class="lottery-panel">
            <h4><i class="fa fa-gift"></i> 幸运抽奖</h4>
            <div class="lottery-benefits">
                <div class="lottery-benefit">
                    <i class="fa fa-diamond"></i>
                    <span>VIP会员卡</span>
                </div>
                <div class="lottery-benefit">
                    <i class="fa fa-coins"></i>
                    <span>海量积分奖励</span>
                </div>
                <div class="lottery-benefit">
                    <i class="fa fa-chart-line"></i>
                    <span>成长经验值</span>
                </div>
                <div class="lottery-benefit">
                    <i class="fa fa-trophy"></i>
                    <span>多种实物奖品</span>
                </div>
            </div>
            <div class="lottery-pricing">
                <div class="lottery-label">抽奖规则</div>
                <div class="lottery-count">消耗积分/现金参与</div>
            </div>
            <a href="<?php echo esc_url($lottery_link); ?>" class="lottery-cta" target="_blank" rel="noopener noreferrer">
                <i class="fa fa-bolt"></i> 参与抽奖
            </a>
        </div>
    </div>

    <!-- AI智能客服 -->
    <div class="nav-item ai-item" data-feature="ai">
        <div class="ai-pulse"></div>
        <div class="ai-pulse"></div>
        <div class="ai-pulse"></div>
        <i class="fa fa-user-secret nav-icon"></i>
        <span class="nav-tooltip">AI客服</span>
    </div>
    
    <!-- 人工客服 -->
    <div class="nav-item service-item" data-feature="service">
        <button data-height="550" 
                data-remote="/wp-admin/admin-ajax.php?action=private_window_modal&receive_user=<?php echo esc_attr($service_user_id); ?>"
                class="service-button" 
                data-toggle="RefreshModal"
                title="点击联系客服">
            <i class="fa fa-comments-o nav-icon"></i>
            <span class="service-status">在线</span>
        </button>

        <!-- 工作时间面板 -->
        <div class="service-panel">
            <div style="text-align: center;">
                <div style="font-size: 16px;font-weight: 600;margin-bottom: 8px;color: #334155;">
                    <div>人工客服</div>
                </div>
                <div style="color: #666; margin-bottom: 6px; font-size: 14px;">全周无休：9:00-23:00</div>
                <div style="color: #888; font-size: 13px; margin-bottom: 12px;">欢迎大家骚扰</div>
                <div class="service-panel-button" style="line-height: 32px;border-radius: 30px;font-size: 12px;background: #6b74e6;color: white;width: 130px;margin: 0 auto;margin-top: 8px;cursor: pointer;transition: all 0.3s ease;" 
                     onclick="event.stopPropagation(); document.querySelector('.service-button').click()">
                    立即咨询
                </div>
            </div>
        </div>
    </div>
    
    <!-- 二维码 -->
    <div class="nav-item qrcode-item" data-feature="qrcode">
        <div class="qrcode-icon-wrapper">
            <i class="fa fa-qrcode nav-icon"></i>
        </div>
        <span class="nav-tooltip">公众号</span>
        
        <div class="qrcode-panel">
            <div class="qrcode-container">
                <img src="<?php echo esc_url($qr_code); ?>" alt="公众号二维码" class="qrcode-img" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                <div class="qrcode-placeholder" style="display: none;">请在后台上传二维码图片</div>
            </div>
            <div class="qrcode-title">扫码关注公众号</div>
            <div class="qrcode-desc">获取更多专属内容和最新资讯</div>
        </div>
    </div>

    <!-- 返回上一页 -->
    <div class="nav-item back-item" data-feature="back">
        <i class="fa fa-arrow-left nav-icon"></i>
        <span class="nav-tooltip">返回上一页</span>
    </div>
    
    <!-- 全屏 -->
    <div class="nav-item fullscreen-item" data-feature="fullscreen">
        <i class="fa fa-expand nav-icon" id="fullscreen-icon"></i>
        <span class="nav-tooltip" id="fullscreen-tooltip">展开全屏</span>
    </div>
    
    <!-- 返回顶部 -->
    <div class="nav-item backtop-item" data-feature="backtop">
        <i class="fa fa-arrow-up nav-icon"></i>
        <span class="nav-tooltip">返回顶部</span>
    </div>
    
</div>
<!-- 悬浮导航栏 - 结束 -->

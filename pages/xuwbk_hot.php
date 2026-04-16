<?php
/**
 * Template name: XuWbk-热榜页面
 * Description:   XuWbk-hot
 * Author: xuwbk
 * Author URI: https://www.xuwbk.com

 */
// 检查后台开关状态
$options = get_option('XuWbk');
$xuwbk_hot = isset($options['hot-page']) ? $options['hot-page'] : false;
if ($xuwbk_hot !== true && $xuwbk_hot !== '1' && $xuwbk_hot !== 1) {
  // 如果开关关闭，显示空白页（保留头部和尾部）
    get_header(); ?>
<!-- 确保jQuery加载 -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<?php
    echo '<main class="container"><div class="content-wrap"><div class="content-layout xy-article"><div class="nopw-sm box-body theme-box radius8 main-bg main-shadow"></div></div></div></main>';
    get_footer();
    exit();
}

// 确保jQuery已加载 - 在模板文件中直接加载
if (!wp_script_is('jquery', 'enqueued')) {
    wp_enqueue_script('jquery');
}

// 从后台设置获取分类
$options = get_option('XuWbk');
$hot_categories = isset($options['hot-categories']) ? $options['hot-categories'] : array(4,5,13);
$posts_per_page = isset($options['hot-posts-per-page']) ? intval($options['hot-posts-per-page']) : 20;

// 确保分类是数组格式
if (!is_array($hot_categories)) {
    $hot_categories = !empty($hot_categories) ? explode(',', $hot_categories) : array(4,5,13);
}
$hot_categories = array_map('intval', $hot_categories);
$hot_categories = array_filter($hot_categories);

// 如果没有选择分类，使用默认分类
if (empty($hot_categories)) {
    $hot_categories = array(4,5,13);
}

$cats = $hot_categories;

function zib_get_post_cover($post_id, $size = 'thumbnail') {
    $post_thumbnail_id = get_post_thumbnail_id($post_id);
    if ($post_thumbnail_id) {
        $image_src = function_exists('zib_get_attachment_image_src') ? zib_get_attachment_image_src($post_thumbnail_id, $size) : wp_get_attachment_image_src($post_thumbnail_id, $size);
        if (!empty($image_src[0])) {
            return $image_src[0];
        }
    }

    $post = get_post($post_id);
    if ($post) {
        if (preg_match('/<img.*?src=[\'"](.*?)[\'"].*?>/i', $post->post_content, $matches)) {
            if (!empty($matches[1])) {
                return $matches[1];
            }
        }
    }

    $img_url = function_exists('zib_get_post_meta') ? zib_get_post_meta($post_id, 'thumbnail_url', true) : get_post_meta($post_id, 'thumbnail_url', true);
    if ($img_url) {
        return $img_url;
    }

    return '默认图片链接';
}
?>
<!-- 确保jQuery加载 -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<?php get_header(); ?>
<style>
:root{--tengfei-primary-color:#ff6000;--tengfei-sidebar-bg:#fff;--tengfei-sidebar-text:#6d7278;--tengfei-sidebar-active-text:var(--tengfei-primary-color);--tengfei-sidebar-active-bg:#fff8f4;--tengfei-text-color:#363636;--tengfei-light-text-color:#545c63;--tengfei-meta-color:#9199a1;--tengfei-heat-color:#f01414;--tengfei-page-overall-bg:#f3f5f7;--tengfei-block-bg:#fff;--tengfei-radius:8px;--tengfei-shadow:0 2px 8px rgba(0,0,0,0.06);--tengfei-font-family:"AlimamaFangYuanTiVF-Thin","PingFang SC","Hiragino Sans GB","Microsoft YaHei",sans-serif;--tengfei-category-tag-bg:#eef2f7;--tengfei-top-banner-fixed-height:67vh;--tengfei-logo-area-top-position:90px;--tengfei-logo-area-visual-height:100px;--tengfei-content-start-top-offset:calc(var(--tengfei-logo-area-top-position) + var(--tengfei-logo-area-visual-height) + 10px);--tengfei-sidebar-width:200px;--tengfei-content-gap:20px;--tengfei-page-max-width:1150px;--tengfei-sidebar-border:#e0e0e0;--tengfei-item-border:#f0f0f0;--tengfei-item-bg:#fff;--tengfei-item-hover-bg:#f9f9f9;--tengfei-item-hover-shadow:0 5px 15px rgba(0,0,0,0.1);--tengfei-item-shadow:0 1px 3px rgba(0,0,0,0.04);--tengfei-top-number-bg:#eee;--tengfei-top-number-text:#333;--tengfei-top-1-bg:#FFD700;--tengfei-top-1-text:#564000;--tengfei-top-2-bg:#C0C0C0;--tengfei-top-2-text:#333333;--tengfei-top-3-bg:#CD7F32;--tengfei-top-3-text:#ffffff;--tengfei-top-4-bg:#6495ED;--tengfei-top-4-text:#ffffff;--tengfei-top-5-bg:#3CB371;--tengfei-top-5-text:#ffffff;}html{overflow-x:clip !important;}body{margin:0;padding:0;width:100%;background-color:var(--tengfei-page-overall-bg);}.tengfei-page-wrapper{font-family:var(--tengfei-font-family);position:relative;width:100%;box-sizing:border-box;padding-bottom:30px;}.tengfei-top-header-banner{position:absolute;top:0;left:0;width:100%;height:var(--tengfei-top-banner-fixed-height);z-index:1;overflow:visible;}.tengfei-hbbg-overlay{position:absolute;top:0;left:0;width:100%;height:100%;background:linear-gradient(270deg,#ff2700 0%,#ff4d00 100%);z-index:1;overflow:hidden;}.tengfei-hbbg-overlay::after{content:'';position:absolute;left:0;right:0;bottom:0;height:120px;background:linear-gradient(to top,var(--tengfei-page-overall-bg) 30%,transparent 100%);z-index:2;}.tengfei-banner-content-aligner{display:flex;justify-content:flex-end;align-items:flex-start;max-width:var(--tengfei-page-max-width);margin:0 auto;height:100%;position:relative;padding:0 15px;box-sizing:border-box;z-index:3;}.tengfei-hbg-flame{display:block;height:360px;width:750px;background-position:top right;background-size:contain;background-repeat:no-repeat;margin-top:-30px;margin-right:15px;z-index:1;opacity:0.65;pointer-events:none;}.tengfei-banner-logo-area{position:absolute;top:var(--tengfei-logo-area-top-position);left:0;right:0;height:var(--tengfei-logo-area-visual-height);z-index:4;pointer-events:none;}.tengfei-banner-logo-area-inner{max-width:var(--tengfei-page-max-width);margin:0 auto;padding:0 15px;box-sizing:border-box;height:100%;display:flex;align-items:center;}.tengfei-banner-logo-area-inner img{display:block;max-height:60px;max-width:var(--tengfei-sidebar-width);pointer-events:auto;}.tengfei-rankings-page-container.tengfei-style-page{display:flex;max-width:var(--tengfei-page-max-width);margin:-20px auto;padding:0 15px;gap:var(--tengfei-content-gap);position:relative;z-index:2;padding-top:var(--tengfei-content-start-top-offset);background-color:transparent;border:none !important;box-shadow:none !important;}.tengfei-sidebar{width:var(--tengfei-sidebar-width);flex-shrink:0;background-color:var(--tengfei-sidebar-bg);box-shadow:var(--tengfei-shadow);border-radius:var(--tengfei-radius);color:var(--tengfei-sidebar-text);padding:10px 0;height:fit-content;align-self:flex-start;position:sticky;top:10px;border:none !important;}#tengfei-menu{list-style:none;padding:0;margin:0;}#tengfei-menu .tengfei-sidebar-menu-item-title{padding:10px 15px;font-size:1em;font-weight:bold;color:var(--tengfei-text-color);border-bottom:1px solid var(--tengfei-sidebar-border);margin-bottom:5px;}#tengfei-menu .tengfei-sidebar-menu-item-title svg{margin-right:8px;vertical-align:-0.15em;color:var(--tengfei-primary-color);}#tengfei-menu .tengfei-sidebar-menu-item{position:relative;padding:10px 15px;cursor:pointer;transition:color .2s,background-color .2s;font-size:19px;line-height:1.4;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;text-align:center;}#tengfei-menu .tengfei-sidebar-menu-item:hover,#tengfei-menu .tengfei-sidebar-menu-item.tengfei-active-menu-item{color:var(--tengfei-sidebar-active-text);background-color:var(--tengfei-sidebar-active-bg);}#tengfei-menu .tengfei-sidebar-menu-item.tengfei-active-menu-item{font-weight:600;}.tengfei-content-area{flex-grow:1;min-width:0;background-color:transparent;position:relative;}.tengfei-ranking-content-block{display:none !important;}.tengfei-ranking-content-block.tengfei-active{display:block !important;}.tengfei-ranking-block-title-container{display:flex;justify-content:space-between;align-items:center;padding:10px 18px;background-color:var(--tengfei-block-bg);border-bottom:1px solid var(--tengfei-sidebar-border);border-radius:var(--tengfei-radius) var(--tengfei-radius) 0 0;}.tengfei-ranking-block-title-faux{font-size:1.4em;font-weight:bold;color:var(--tengfei-text-color);}.tengfei-ranking-update-time{font-size:0.8em;color:var(--tengfei-primary-color);font-weight:500;}.tengfei-ranking-update-time svg{vertical-align:middle;margin-right:4px;}.tengfei-ranking-block{background:var(--tengfei-block-bg);border-radius:var(--tengfei-radius);box-shadow:var(--tengfei-shadow);margin-bottom:20px;overflow:hidden;border:none !important;}.tengfei-ranking-list{padding:5px 18px 10px 18px;}.tengfei-ranking-item{display:flex;align-items:flex-start;text-decoration:none;padding:12px 10px;border-bottom:1px solid var(--tengfei-item-border);transition:background-color 0.2s,box-shadow 0.2s,transform 0.2s;box-shadow:var(--tengfei-item-shadow);border-radius:var(--tengfei-radius);margin-bottom:8px;background-color:var(--tengfei-item-bg);}.tengfei-ranking-item:last-child{border-bottom:none;margin-bottom:0;padding-bottom:12px;}.tengfei-ranking-item:hover{background-color:var(--tengfei-item-hover-bg);box-shadow:var(--tengfei-item-hover-shadow);transform:translateY(-3px);}.tengfei-ranking-item:hover .tengfei-ranking-item-title{color:var(--tengfei-primary-color);}.tengfei-ranking-item-number{font-size:12px;font-weight:bold;min-width:50px;padding:4px 8px;border-radius:4px;text-align:center;margin-right:15px;line-height:1.5;box-sizing:border-box;display:inline-block;flex-shrink:0;background-color:var(--tengfei-top-number-bg);color:var(--tengfei-top-number-text);}.tengfei-ranking-item-number.tengfei-top-1{background-color:var(--tengfei-top-1-bg);color:var(--tengfei-top-1-text);}.tengfei-ranking-item-number.tengfei-top-2{background-color:var(--tengfei-top-2-bg);color:var(--tengfei-top-2-text);}.tengfei-ranking-item-number.tengfei-top-3{background-color:var(--tengfei-top-3-bg);color:var(--tengfei-top-3-text);}.tengfei-ranking-item-number.tengfei-top-4{background-color:var(--tengfei-top-4-bg);color:var(--tengfei-top-4-text);}.tengfei-ranking-item-number.tengfei-top-5{background-color:var(--tengfei-top-5-bg);color:var(--tengfei-top-5-text);}.tengfei-ranking-item-number.tengfei-top-1,.tengfei-ranking-item-number.tengfei-top-2,.tengfei-ranking-item-number.tengfei-top-3,.tengfei-ranking-item-number.tengfei-top-4,.tengfei-ranking-item-number.tengfei-top-5{font-weight:bold;}.tengfei-ranking-item-thumbnail{width:80px;height:80px;margin-right:15px;flex-shrink:0;overflow:hidden;border-radius:8px;}.tengfei-ranking-item-thumbnail img{width:100%;height:100%;object-fit:cover;}.tengfei-ranking-item-info{flex-grow:1;overflow:hidden;}.tengfei-ranking-item-title{font-size:16px;font-weight:500;color:var(--tengfei-light-text-color);line-height:1.3;margin:0 0 3px 0;text-overflow:ellipsis;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;margin-top:0px !important;}.tengfei-ranking-item-category-lowest{font-size:12px;background-color:var(--tengfei-category-tag-bg);color:var(--tengfei-light-text-color);padding:2px 6px;border-radius:3px;display:inline-block;margin-top:2px;margin-bottom:4px;line-height:1.3;font-weight:500;margin:2px 7px 1px 0px;}.tengfei-ranking-item-excerpt{font-size:13px;color:var(--tengfei-meta-color);margin-top:2px;margin-bottom:6px;line-height:1.4;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}.tengfei-ranking-item-meta{font-size:13px;color:var(--tengfei-heat-color);font-weight:600;margin-top:4px;}.tengfei-ranking-block-viewall{display:block;width:120px;height:32px;line-height:32px;margin:20px auto 10px;font-size:13px;color:#fff;font-weight:500;background-image:linear-gradient(270deg,#ff4f39 0,#fd6400 100%);border-radius:16px;text-align:center;text-decoration:none;transition:all 0.3s;}.tengfei-ranking-block-viewall:hover{opacity:0.85;box-shadow:0 2px 8px rgba(253,100,0,0.3);}
@media screen and (max-width:1024px){#tengfei-menu .tengfei-sidebar-menu-item{font-size:15px;padding:8px 15px;}.tengfei-ranking-block-title-faux{font-size:1.3em;}.tengfei-ranking-item-title{font-size:15px;line-height:1.2;min-height:calc(15px * 1.2 * 2);margin-bottom:1px;}.tengfei-ranking-item-category-lowest{font-size:11px;padding:1px 5px;margin-top:1px;margin-bottom:1px;}.tengfei-ranking-item-excerpt{font-size:12px;margin-top:1px;}.tengfei-rankings-page-container.tengfei-style-page{flex-direction:column;gap:15px;padding:0 10px;padding-top:var(--tengfei-content-start-top-offset);}.tengfei-sidebar{width:100%;position:static;height:auto;border-radius:var(--tengfei-radius);margin-bottom:15px;top:auto !important;}#tengfei-menu{display:flex;flex-wrap:wrap;justify-content:center;padding:5px;}#tengfei-menu .tengfei-sidebar-menu-item{margin:5px;}#tengfei-menu .tengfei-sidebar-menu-item.tengfei-active-menu-item{background-color:var(--tengfei-primary-color);color:#fff;}.tengfei-content-area{margin-left:0;padding:0;}}@media screen and (max-width:768px){#tengfei-menu .tengfei-sidebar-menu-item{font-size:12px;padding:6px 10px;}.tengfei-ranking-block-title-faux{font-size:1.2em;}.tengfei-ranking-update-time{font-size:0.75em;}.tengfei-ranking-item-number{min-width:40px;padding:3px 6px;font-size:11px;}.tengfei-ranking-item-thumbnail{width:60px;height:60px;margin-right:10px;}.tengfei-ranking-item-title{font-size:14px;line-height:1.2;min-height:calc(14px * 1.2 * 2);margin-bottom:1px;}.tengfei-ranking-item-category-lowest{font-size:10px;padding:1px 4px;margin-top:1px;margin-bottom:1px;}.tengfei-ranking-item-excerpt{font-size:12px;margin-top:1px;}.tengfei-ranking-item-meta{font-size:0.8em;}.tengfei-ranking-block-title-container{padding:10px 15px;flex-direction:column;align-items:flex-start;}.tengfei-ranking-block-title-faux{font-size:1.1em;margin-bottom:5px;}.tengfei-ranking-list{padding:10px 15px;}}/* 暗色模式 */
.dark-theme{--tengfei-primary-color:#ff6a00;--tengfei-sidebar-bg:#2c2c2e;--tengfei-sidebar-text:#c7c7cc;--tengfei-sidebar-active-text:var(--tengfei-primary-color);--tengfei-sidebar-active-bg:#3a3a3c;--tengfei-text-color:#f2f2f7;--tengfei-light-text-color:#aeaeb2;--tengfei-meta-color:#98989e;--tengfei-heat-color:#ff5047;--tengfei-page-overall-bg:#1c1c1e;--tengfei-block-bg:#2c2c2e;--tengfei-shadow:0 2px 6px rgba(0,0,0,0.6);--tengfei-category-tag-bg:#3a3a3c;--tengfei-sidebar-border:#404040;--tengfei-item-border:#38383a;--tengfei-item-bg:#2c2c2e;--tengfei-item-hover-bg:#363638;--tengfei-item-hover-shadow:0 4px 12px rgba(0,0,0,0.7);--tengfei-item-shadow:0 1px 2px rgba(0,0,0,0.4);--tengfei-top-number-bg:#4a4a4c;--tengfei-top-number-text:#f2f2f7;--tengfei-top-1-bg:#FFD700;--tengfei-top-1-text:#332400;--tengfei-top-2-bg:#C0C0C0;--tengfei-top-2-text:#1E1E1E;--tengfei-top-3-bg:#CD7F32;--tengfei-top-3-text:#ffffff;--tengfei-top-4-bg:#6495ED;--tengfei-top-4-text:#ffffff;--tengfei-top-5-bg:#3CB371;--tengfei-top-5-text:#ffffff;}.dark-theme body{background-color:var(--tengfei-page-overall-bg);color:var(--tengfei-text-color);}.dark-theme .tengfei-hbbg-overlay::after{background:linear-gradient(to top,var(--tengfei-page-overall-bg) 30%,transparent 100%);}.dark-theme .tengfei-ranking-block-title-container{background-color:var(--tengfei-block-bg);border-bottom-color:var(--tengfei-sidebar-border);}.dark-theme .tengfei-ranking-item{background-color:var(--tengfei-item-bg);border-bottom-color:var(--tengfei-item-border);box-shadow:var(--tengfei-item-shadow);}.dark-theme .tengfei-ranking-item:hover{background-color:var(--tengfei-item-hover-bg);box-shadow:var(--tengfei-item-hover-shadow);}.dark-theme .tengfei-ranking-item-number{background-color:var(--tengfei-top-number-bg);color:var(--tengfei-top-number-text);}.dark-theme .tengfei-ranking-item-number.tengfei-top-1{background-color:var(--tengfei-top-1-bg);color:var(--tengfei-top-1-text);}.dark-theme .tengfei-ranking-item-number.tengfei-top-2{background-color:var(--tengfei-top-2-bg);color:var(--tengfei-top-2-text);}.dark-theme .tengfei-ranking-item-number.tengfei-top-3{background-color:var(--tengfei-top-3-bg);color:var(--tengfei-top-3-text);}.dark-theme .tengfei-ranking-item-number.tengfei-top-4{background-color:var(--tengfei-top-4-bg);color:var(--tengfei-top-4-text);}.dark-theme .tengfei-ranking-item-number.tengfei-top-5{background-color:var(--tengfei-top-5-bg);color:var(--tengfei-top-5-text);}@media screen and (max-width:1024px){.dark-theme #tengfei-menu .tengfei-sidebar-menu-item.tengfei-active-menu-item{background-color:var(--tengfei-primary-color);color:#fff;}}
</style>
<main class="container page-content-full page-id-2391">
    <div class="content-wrap">
        <div class="content-layout">
            <article class="article page-article main-bg theme-box box-body radius8 main-shadow">
                <div class="wp-posts-content">
                    <div class="tengfei-page-wrapper" id="tengfei-page-wrapper-683e89b58e56d">
                        <div class="tengfei-top-header-banner" id="tengfei-top-banner-js-hook">
                            <div class="tengfei-hbbg-overlay"></div>
                            <div class="tengfei-banner-content-aligner">
                                <i class="tengfei-hbg-flame" style="background-image: url('https://img.alicdn.com/imgextra/i3/2210123621994/O1CN01ZCCtxZ1QbIoe01QvO_!!2210123621994.png');"></i>
                            </div>
                            <div class="tengfei-banner-logo-area">
                                <div class="tengfei-banner-logo-area-inner">
                                    <a href="#">
                                        <img decoding="async" src="https://img.alicdn.com/imgextra/i3/2210123621994/O1CN01BQd9UX1QbIodvPVIl_!!2210123621994.png" alt="超级热榜">
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="tengfei-rankings-page-container tengfei-style-page">
                            <div class="tengfei-sidebar" id="tengfei-main-sidebar">
                                <ul id="tengfei-menu">
                                    <li class="tengfei-sidebar-menu-item-title">
                                        <svg class="icon" aria-hidden="true" viewBox="0 0 1024 1024">
                                            <use xlink:href="#tengfei-icon-hot"></use>
                                        </svg>
                                        热榜导航
                                    </li>
                                    <li class="tengfei-sidebar-menu-item tengfei-active-menu-item" data-target="#tengfei-ranking-block-content-total">
                                        热门总榜
                                    </li>
                                    <?php
                                    foreach($cats as $catid):
                                        $cat_obj = get_category($catid);
                                        if(!$cat_obj) continue;
                                    ?>
                                        <li class="tengfei-sidebar-menu-item" data-target="#tengfei-ranking-block-content-cat-<?php echo $catid; ?>">
                                            <?php echo esc_html($cat_obj->name); ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                            <div class="tengfei-content-area">
                                <div id="tengfei-ranking-block-content-total" class="tengfei-ranking-content-block tengfei-active" style="display: block;">
                                    <div class="tengfei-ranking-block">
                                        <div class="tengfei-ranking-block-title-container">
                                            <span class="tengfei-ranking-block-title-faux">热门总榜</span>
                                            <i class="tengfei-ranking-update-time">
                                                <svg class="icon" aria-hidden="true" viewBox="0 0 1024 1024" style="vertical-align: -0.125em;">
                                                    <use xlink:href="#tengfei-icon-time"></use>
                                                </svg>
                                                更新时间：<?php echo date('Y-m-d H:i:s'); ?> (实时)
                                            </i>
                                        </div>
                                        <div class="tengfei-ranking-list">
                                            <?php
                                            $args = array(
                                                'posts_per_page' => $posts_per_page,
                                                'meta_key' => 'views',
                                                'orderby' => 'meta_value_num',
                                                'order' => 'DESC',
                                            );
                                            $query = new WP_Query($args);
                                            $rank = 1;
                                            if ($query->have_posts()) :
                                                while ($query->have_posts()) : $query->the_post();
                                                    $thumb = zib_get_post_cover(get_the_ID(), 'thumbnail');
                                                    $cat = get_the_category();
                                                    $cat_name = $cat ? $cat[0]->name : '';
                                                    $views = get_post_meta(get_the_ID(), 'views', true);
                                                    if (!$views) $views = 0;
                                            ?>
                                                <a class="tengfei-ranking-item" href="<?php the_permalink(); ?>" target="_blank" rel="noopener noreferrer">
                                                    <span class="tengfei-ranking-item-number <?php if($rank<=3) echo 'tengfei-top-'.$rank; ?>">
                                                        <?php echo $rank <= 3 ? 'TOP '.$rank : $rank; ?>
                                                    </span>
                                                    <div class="tengfei-ranking-item-thumbnail">
                                                        <img alt="<?php the_title(); ?>" decoding="async" width="300" height="200" src="<?php echo esc_url($thumb); ?>">
                                                    </div>
                                                    <div class="tengfei-ranking-item-info">
                                                        <h4 class="tengfei-ranking-item-title"><?php the_title(); ?></h4>
                                                        <div class="tengfei-ranking-item-category-lowest"><?php echo esc_html($cat_name); ?></div>
                                                        <div class="tengfei-ranking-item-excerpt"><?php echo get_the_excerpt(); ?></div>
                                                        <div class="tengfei-ranking-item-views-line" style="margin-top:6px;color:#ff5722;font-size:13px;display:flex;align-items:center;">
                                                            <svg class="icon" aria-hidden="true" style="width:1em;height:1em;vertical-align:-0.15em;margin-right:4px;">
                                                                <use xlink:href="#tengfei-icon-hot"></use>
                                                            </svg>
                                                            <?php echo number_format($views); ?> 热度
                                                        </div>
                                                    </div>
                                                </a>
                                            <?php
                                                    $rank++;
                                                endwhile;
                                                wp_reset_postdata();
                                            else:
                                                echo '<p>暂无内容</p>';
                                            endif;
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                foreach($cats as $catid):
                                    $cat_obj = get_category($catid);
                                    if(!$cat_obj) continue;
                                ?>
                                <div id="tengfei-ranking-block-content-cat-<?php echo $catid; ?>" class="tengfei-ranking-content-block" style="display: none;">
                                    <div class="tengfei-ranking-block">
                                        <div class="tengfei-ranking-block-title-container">
                                            <span class="tengfei-ranking-block-title-faux">
                                                <?php echo esc_html($cat_obj->name); ?>
                                            </span>
                                            <i class="tengfei-ranking-update-time">
                                                <svg class="icon" aria-hidden="true" viewBox="0 0 1024 1024" style="vertical-align: -0.125em;">
                                                    <use xlink:href="#tengfei-icon-time"></use>
                                                </svg>
                                                更新时间：<?php echo date('Y-m-d H:i:s'); ?> (实时)
                                            </i>
                                        </div>
                                        <div class="tengfei-ranking-list">
                                            <?php
                                            $args = array(
                                                'cat' => $catid,
                                                'posts_per_page' => $posts_per_page,
                                                'meta_key' => 'views', 
                                                'orderby' => 'meta_value_num',
                                                'order' => 'DESC',
                                            );
                                            $query = new WP_Query($args);
                                            $rank = 1;
                                            if ($query->have_posts()) :
                                                while ($query->have_posts()) : $query->the_post();
                                                    $thumb = zib_get_post_cover(get_the_ID(), 'thumbnail');
                                                    $cat = get_the_category();
                                                    $cat_name = $cat ? $cat[0]->name : '';
                                                    $views = get_post_meta(get_the_ID(), 'views', true);
                                                    if (!$views) $views = 0;
                                            ?>
                                                <a class="tengfei-ranking-item" href="<?php the_permalink(); ?>" target="_blank" rel="noopener noreferrer">
                                                    <span class="tengfei-ranking-item-number <?php if($rank<=3) echo 'tengfei-top-'.$rank; ?>">
                                                        <?php echo $rank <= 3 ? 'TOP '.$rank : $rank; ?>
                                                    </span>
                                                    <div class="tengfei-ranking-item-thumbnail">
                                                        <img alt="<?php the_title(); ?>" decoding="async" width="300" height="200" src="<?php echo esc_url($thumb); ?>">
                                                    </div>
                                                    <div class="tengfei-ranking-item-info">
                                                        <h4 class="tengfei-ranking-item-title"><?php the_title(); ?></h4>
                                                        <div class="tengfei-ranking-item-category-lowest"><?php echo esc_html($cat_name); ?></div>
                                                        <div class="tengfei-ranking-item-excerpt"><?php echo get_the_excerpt(); ?></div>
                                                        <div class="tengfei-ranking-item-views-line" style="margin-top:6px;color:#ff5722;font-size:13px;display:flex;align-items:center;">
                                                            <svg class="icon" aria-hidden="true" style="width:1em;height:1em;vertical-align:-0.15em;margin-right:4px;">
                                                                <use xlink:href="#tengfei-icon-hot"></use>
                                                            </svg>
                                                            <?php echo number_format($views); ?> 热度
                                                        </div>
                                                    </div>
                                                </a>
                                            <?php
                                                    $rank++;
                                                endwhile;
                                                wp_reset_postdata();
                                            else:
                                                echo '<p>暂无内容</p>';
                                            endif;
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <svg id="tengfei-inline-svg-icons" aria-hidden="true" style="position:absolute;width:0px;height:0px;overflow:hidden;">
                            <symbol id="tengfei-icon-hot" viewBox="0 0 1024 1024">
                                <path d="M429.568429 1023.994675S87.04221 973.306939 87.04221 681.468456c0-291.838482 447.485673-319.486339 348.670187-681.468456 0 0 335.358256 104.959454 253.438682 431.613756 0 0 44.543768-28.159854 78.335592-83.455566 0 0 169.471119 131.071318 169.471119 330.75028 0 199.678962-235.006778 346.1102-346.622197 345.086205 0 0 120.319374-162.303156 10.751944-267.26261-155.135193-148.479228-89.087537-244.22273-89.087537-244.22273S133.633968 697.852371 429.568429 1023.994675">
                                </path>
                            </symbol>
                            <symbol id="tengfei-icon-time" viewBox="0 0 1024 1024">
                                <path d="M528.384 76.223488c-236.78976 0-428.736512 191.945728-428.736512 428.736512S291.59424 933.695488 528.384 933.695488s428.736512-191.945728 428.736512-428.736512S765.17376 76.223488 528.384 76.223488zM528.384 838.42048c-184.152064 0-333.461504-149.331968-333.461504-333.461504 0-184.152064 149.30944-333.461504 333.461504-333.461504 184.128512 0 333.461504 149.30944 333.461504 333.461504C861.84448 689.088512 712.512512 838.42048 528.384 838.42048z">
                                </path>
                                <path d="M576.02048 314.409984c26.331136 0 47.637504 21.32992 47.637504 47.637504l0 190.548992c0 26.331136-21.307392 47.637504-47.637504 47.637504-26.30656 0-47.637504-21.307392-47.637504-47.637504L528.382976 362.047488C528.384 335.739904 549.71392 314.409984 576.02048 314.409984z">
                                </path>
                                <path d="M385.471488 504.96 576.02048 504.96c26.331136 0 47.637504 21.32992 47.637504 47.637504 0 26.331136-21.307392 47.637504-47.637504 47.637504L385.471488 600.235008c-26.307584 0-47.637504-21.307392-47.637504-47.637504C337.833984 526.28992 359.163904 504.96 385.471488 504.96z">
                                </path>
                            </symbol>
                        </svg>
                    </div>
                    <script type="text/javascript">
                    // 确保jQuery已加载并等待DOM就绪
                    (function() {
                        'use strict';
                        
                        // 等待jQuery加载
                        function waitForJquery(callback) {
                            if (window.jQuery) {
                                callback(window.jQuery);
                            } else {
                                setTimeout(function() {
                                    waitForJquery(callback);
                                }, 50);
                            }
                        }
                        
                        waitForJquery(function($) {
                            // 确保DOM已加载
                            $(function() {
                            var $allContentBlocks = $('.tengfei-ranking-content-block');
                            var $allMenuItems = $('#tengfei-menu li.tengfei-sidebar-menu-item');
                            var $targetContentBlock = $();
                            var $targetMenuItem = $();

                            if ($allMenuItems.length > 0) {
                                $targetMenuItem = $allMenuItems.filter('.tengfei-active-menu-item').first();
                                if (!$targetMenuItem.length) {
                                    $targetMenuItem = $allMenuItems.first();
                                }
                                if ($targetMenuItem.length) {
                                    var targetId = $targetMenuItem.data('target');
                                    if (targetId && $(targetId).length) {
                                        $targetContentBlock = $(targetId);
                                    } else {
                                        $targetMenuItem = $();
                                    }
                                }
                            }

                            if (!$targetContentBlock.length && $allContentBlocks.length > 0) {
                                var $phpActiveContent = $allContentBlocks.filter('.tengfei-active').first();
                                if ($phpActiveContent.length) {
                                    $targetContentBlock = $phpActiveContent;
                                    if (!$targetMenuItem.length) {
                                        var phpActiveContentId = $targetContentBlock.attr('id');
                                        $targetMenuItem = $allMenuItems.filter('[data-target="#' + phpActiveContentId + '"]');
                                    }
                                } else {
                                    $targetContentBlock = $allContentBlocks.first();
                                    if (!$targetMenuItem.length) {
                                        var firstContentId = $targetContentBlock.attr('id');
                                        $targetMenuItem = $allMenuItems.filter('[data-target="#' + firstContentId + '"]');
                                    }
                                }
                            }

                            $allContentBlocks.not($targetContentBlock).hide().removeClass('tengfei-active');
                            $allMenuItems.not($targetMenuItem).removeClass('tengfei-active-menu-item');

                            if ($targetContentBlock.length) {
                                $targetContentBlock.show().addClass('tengfei-active');
                                if ($targetMenuItem.length) {
                                    $targetMenuItem.addClass('tengfei-active-menu-item');
                                }
                            }

                            $('#tengfei-menu li.tengfei-sidebar-menu-item').on('click',
                            function() {
                                var targetId = $(this).data('target');
                                var $clickedMenuItem = $(this);
                                $allMenuItems.removeClass('tengfei-active-menu-item');
                                $clickedMenuItem.addClass('tengfei-active-menu-item');
                                $allContentBlocks.hide().removeClass('tengfei-active');
                                if ($(targetId).length) {
                                    $(targetId).show().addClass('tengfei-active');
                                }
                            });

                            function dynamicBannerLayout() {
                                var $banner = $('#tengfei-top-banner-js-hook');
                                var $pageWrapper = $banner.closest('.tengfei-page-wrapper');

                                if ($banner.length && $pageWrapper.length) {
                                    var viewportWidth = $(window).width();
                                    var pageWrapperOffsetLeft = $pageWrapper.offset().left;

                                    $banner.css({
                                        'width': viewportWidth + 'px',
                                        'left': -pageWrapperOffsetLeft + 'px',
                                        'transform': 'none'
                                    });
                                }
                            }

                            dynamicBannerLayout();
                            $(window).on('resize', dynamicBannerLayout);
                        });
                        });
                    })();
                    </script>
                    <style>
                        .tengfei-ranking-item-category-lowest {
                            font-size: 12px;
                            background-color: var(--tengfei-category-tag-bg);
                            color: var(--tengfei-light-text-color);
                            padding: 2px 6px;
                            border-radius: 3px;
                            display: inline-block;
                            margin-top: 2px;
                            margin-bottom: 4px;
                            line-height: 1.3;
                            font-weight: 500;
                            margin-left: 4px;
                            margin-right: 4px;
                        }
                    </style>
                </div>
            </article>
        </div>
    </div>
</main>

<?php get_footer(); ?>

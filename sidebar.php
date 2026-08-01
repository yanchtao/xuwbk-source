<?php
/**
 * 侧边栏模板 - XuWbk 子主题覆盖
 * 修复：文章页始终显示侧边栏（绕过父主题 zib_is_show_sidebar 限制）
 */

// 文章页强制显示侧边栏，忽略父主题的 zib_is_show_sidebar() 结果
if (is_single() && !wp_is_mobile()) {
    // 文章详情页 + 桌面端：始终显示侧边栏
} elseif (!zib_is_show_sidebar() || wp_is_mobile()) {
    return;
}
?>
<div class="sidebar">
    <?php
    if (function_exists('dynamic_sidebar')) {
        if (!is_page()) {
            dynamic_sidebar('all_sidebar_top');
        }
        if (is_home()) {
            dynamic_sidebar('home_sidebar');
        } elseif (is_category() || is_tax('topics')) {
            dynamic_sidebar('cat_sidebar');
        } elseif (is_tag()) {
            dynamic_sidebar('tag_sidebar');
        } elseif (is_search()) {
            dynamic_sidebar('search_sidebar');
        } elseif (is_single()) {
            dynamic_sidebar('single_sidebar');
        } elseif (is_page()) {
            global $widgets_register_container, $page_id;
            if ($widgets_register_container && in_array('sidebar', $widgets_register_container)) {
                dynamic_sidebar('page_sidebar_' . $page_id);
            }
        }
        if (!is_page()) {
            dynamic_sidebar('all_sidebar_bottom');
        }
    }
    ?>
</div>

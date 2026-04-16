<?php
/*
Plugin Name: 子比自动汉化插件
Plugin URI: https://www.LittleSheep.cc
Description: 一个适用于子比主题的简单的多语言翻译插件
Version: 1.2.1
Author: LittleSheep
Author URI: https://www.LittleSheep.cc
License: GPLV2
*/

// 防止直接访问
if (!defined('ABSPATH')) {
    exit;
}

// 添加前端和后台样式
function mlt_enqueue_styles() {
    $options = get_option('XuWbk');
    $language_switch = isset($options['language_switch']) ? $options['language_switch'] : false;
    
    // 只有在翻译功能开启时才加载样式
    if ($language_switch) {
        // 字体样式
        wp_enqueue_style(
            'mlt-font-styles',
            '/wp-content/themes/XuWbk/page/Trans/assets/css/font.css',
            array(),
            '1.2.1'
        );
        
        // 前端样式
        wp_enqueue_style(
            'mlt-styles',
            '/wp-content/themes/XuWbk/page/Trans/assets/css/admin.css',
            array('mlt-font-styles'),
            '1.2.1'
        );
    }
}
add_action('wp_enqueue_scripts', 'mlt_enqueue_styles');

// 后台样式
function mlt_admin_styles() {
    if (isset($_GET['page']) && $_GET['page'] == 'XuWbk') {
        $options = get_option('XuWbk');
        $language_switch = isset($options['language_switch']) ? $options['language_switch'] : false;
        
        if ($language_switch) {
            // 加载Dashicons
            wp_enqueue_style('dashicons');
            
            // 字体样式
            wp_enqueue_style(
                'mlt-font-styles',
                '/wp-content/themes/XuWbk/page/Trans/assets/css/font.css',
                array('dashicons'),
                '1.2.1'
            );
            
            // 后台样式
            wp_enqueue_style(
                'mlt-styles',
                '/wp-content/themes/XuWbk/page/Trans/assets/css/admin.css',
                array('mlt-font-styles', 'dashicons'),
                '1.2.1'
            );
        }
    }
}
add_action('admin_enqueue_scripts', 'mlt_admin_styles');

// 添加JavaScript
function mlt_add_custom_js() {
    $options = get_option('XuWbk');
    $language_switch = isset($options['language_switch']) ? $options['language_switch'] : false;
    
    // 只有在翻译功能开启时才加载JS
    if ($language_switch) {
        $default_language = isset($options['language_default']) ? $options['language_default'] : 'english';
        
        // 使用CDN链接
        $cdn_urls = array(
            'https://cdn.staticfile.net/translate.js/3.12.0/translate.js',
            'https://unpkg.com/translate.js@3.12.0/dist/translate.min.js',
            'https://cdn.jsdelivr.net/npm/translate.js@3.12.0/dist/translate.min.js'
        );
        $js_path = $cdn_urls[0]; // 默认使用第一个CDN
        
        ?>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            window.translationJsLoaded = false;
            var cdnUrls = <?php echo json_encode($cdn_urls); ?>;
            
            // CDN加载函数
            function loadTranslationJs() {
                if (window.translationJsLoaded) return;
                
                tryLoadFromCdn(0);
            }
            
            function tryLoadFromCdn(index) {
                if (index >= cdnUrls.length) {
                    console.error('All CDN URLs failed to load translation JS');
                    return;
                }
                
                var currentUrl = cdnUrls[index];
                var script = document.createElement('script');
                script.src = currentUrl;
                script.onerror = function() {
                    tryLoadFromCdn(index + 1);
                };
                script.onload = function() {
                    window.translationJsLoaded = true;
                    if (typeof translate !== 'undefined') {
                        translate.setAutoDiscriminateLocalLanguage();
                        translate.language.setLocal('<?php echo esc_js($default_language); ?>');
                        translate.service.use('client.edge');
                        executeTranslation();
                    }
                };
                document.head.appendChild(script);
            }
            
            // 监听翻译按钮的悬停和点击事件
            document.addEventListener('mouseover', function(e) {
                if (e.target.closest('.toggle-radius') || e.target.closest('.btn-newadd')) {
                    loadTranslationJs();
                }
            });
            
            // 添加点击事件监听
            document.addEventListener('click', function(e) {
                var target = e.target.closest('.btn-newadd');
                if (target) {
                    e.preventDefault();
                    var languageCode = target.getAttribute('data-language');
                    
                    if (languageCode) {
                        loadTranslationJs();
                        
                        var checkInterval = setInterval(function() {
                            if (window.translationJsLoaded && typeof translate !== 'undefined') {
                                clearInterval(checkInterval);
                                translate.changeLanguage(languageCode);
                            }
                        }, 100);
                        
                        setTimeout(function() {
                            clearInterval(checkInterval);
                        }, 10000);
                    }
                }
            });
            
            // 执行翻译的函数
            function executeTranslation() {
                if (window.translate) {
                    translate.execute();
                }
            }
            
            // AJAX完成后重新执行翻译
            if (window.jQuery) {
                jQuery(document).ajaxComplete(function() {
                    if (window.translationJsLoaded) {
                        executeTranslation();
                    }
                });
            }
        });
        </script>
        <?php
    }
}
add_action('wp_head', 'mlt_add_custom_js');

// 翻译按钮输出函数
function custom_modify_radius_button($original_content, $user_id) {
    $options = get_option('XuWbk');
    $language_switch = isset($options['language_switch']) ? $options['language_switch'] : false;
    
    // 如果翻译功能未开启，直接返回原内容
    if (!$language_switch) {
        return $original_content;
    }
    
    // 获取按钮图标设置
    $language_icon_c = isset($options['language_icon_c']) ? $options['language_icon_c'] : '';
    $language_icon = isset($options['language_icon']) ? $options['language_icon'] : '';
    
    // 确定使用哪个图标
    $button_icon = '';
    if (!empty($language_icon_c)) {
        // 检查自定义图标代码是否包含外部引用
        if (strpos($language_icon_c, 'http') === 0) {
            // 如果是外部URL，使用默认图标
            $button_icon = '<i class="fa fa-language"></i>';
        } else {
            $button_icon = $language_icon_c;
        }
    } elseif (!empty($language_icon)) {
        $button_icon = '<i class="' . esc_attr($language_icon) . '"></i>';
    } else {
        $button_icon = '<i class="fa fa-language"></i>';
    }
    
    $new_button = '<span class="hover-show inline-block ml10">
        <a href="javascript:void(0);" rel="external nofollow" class="toggle-radius">
            ' . $button_icon . '
        </a>
        <div class="hover-show-con dropdown-menu drop-newadd">';
    
    // 获取语言配置
    $languages = isset($options['language']) ? $options['language'] : array();
    
    if (!empty($languages) && is_array($languages)) {
        foreach ($languages as $language) {
            // 检查语言是否启用
            $language_enabled = isset($language['language_id_switch']) ? $language['language_id_switch'] : false;
            
            if ($language_enabled) {
                $language_id = isset($language['language_id']) ? $language['language_id'] : 'english';
                $language_svg = isset($language['language_svg']) ? $language['language_svg'] : '';
                $language_img = isset($language['language_img']) ? $language['language_img'] : '';
                
                // 确定图标显示
                $icon_html = '';
                if (!empty($language_svg)) {
                    // 检查SVG内容是否包含外部引用
                    if (strpos($language_svg, 'http') === 0) {
                        // 如果是外部URL，使用默认图标
                        $icon_html = '<i class="fa fa-globe"></i>';
                    } else {
                        $icon_html = $language_svg;
                    }
                } elseif (!empty($language_img)) {
                    $img_url = $language_img;
                    $img_url = str_replace('/themes/zibll/', '/themes/XuWbk/', $img_url);
                    // 检查文件是否存在
                    if (strpos($img_url, 'http') === 0) {
                        // 外部URL，使用默认图标
                        $icon_html = '<i class="fa fa-globe"></i>';
                    } else {
                        $icon_html = '<img src="' . esc_url($img_url) . '" alt="" width="20" height="20">';
                    }
                } else {
                    $icon_html = '<i class="fa fa-globe"></i>';
                }
                
                // 语言名称映射
                $language_names = array(
                    'english' => 'English',
                    'chinese_simplified' => '中文简体',
                    'chinese' => '中文',
                    'japanese' => '日本語',
                    'korean' => '한국어',
                    'french' => 'Français',
                    'german' => 'Deutsch',
                    'spanish' => 'Español',
                    'russian' => 'Русский',
                );
                
                $language_name = isset($language_names[$language_id]) ? $language_names[$language_id] : $language_id;
                
                $new_button .= '<a rel="nofollow" class="btn-newadd" href="javascript:void(0);" data-language="' . esc_attr($language_id) . '" rel="external nofollow">
                    <icon>' . $icon_html . '</icon>
                    <text class="ignore">' . esc_html($language_name) . '</text>
                </a>';
            }
        }
    }
    
    $new_button .= '</div></span>';
    
    return $original_content . $new_button;
}
add_filter('zib_nav_radius_button', 'custom_modify_radius_button', 10, 2);
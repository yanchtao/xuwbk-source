<?php
/*
Plugin Name: 子比两行JS翻译插件
Plugin URI: https://www.LittleSheep.cc
Description: 两行JS实现HTML自动翻译 - 无需改动页面、无语言配置文件、无API Key、SEO友好
Version: 2.0.0
Author: LittleSheep
Author URI: https://www.LittleSheep.cc
License: GPLV2
*/

// 防止直接访问
if (!defined('ABSPATH')) {
    exit;
}

// 添加前端样式
function simple_translation_enqueue_styles() {
    $options = get_option('XuWbk');
    $language_switch = isset($options['language_switch']) ? $options['language_switch'] : false;
    
    if ($language_switch) {
        wp_enqueue_style(
            'simple-translation-styles',
            '/wp-content/themes/XuWbk/page/Trans/assets/css/admin.css',
            array(),
            '2.0.0'
        );
    }
}
add_action('wp_enqueue_scripts', 'simple_translation_enqueue_styles');

// 两行JS自动翻译实现
function simple_translation_add_js() {
    $options = get_option('XuWbk');
    $language_switch = isset($options['language_switch']) ? $options['language_switch'] : false;
    
    if ($language_switch) {
        $default_language = isset($options['language_default']) ? $options['language_default'] : 'english';
        $cdn_url = 'https://cdn.staticfile.net/translate.js/3.12.0/translate.js';
        
        ?>
        <!-- 两行JS自动翻译 - 第一行：加载翻译库 -->
        <script src="<?php echo esc_url($cdn_url); ?>"></script>
        
        <!-- 两行JS自动翻译 - 第二行：初始化和语言切换 -->
        <script>
        // 初始化翻译库
        if (typeof translate !== 'undefined') {
            translate.setAutoDiscriminateLocalLanguage();
            translate.language.setLocal('<?php echo esc_js($default_language); ?>');
            translate.service.use('client.edge');
        }
        
        // 语言切换函数
        function changeLanguage(languageCode) {
            if (typeof translate !== 'undefined') {
                translate.changeLanguage(languageCode);
                translate.execute();
            }
        }
        
        // 点击事件处理
        document.addEventListener('click', function(e) {
            var target = e.target.closest('.btn-newadd');
            if (target) {
                e.preventDefault();
                var languageCode = target.getAttribute('data-language');
                if (languageCode) {
                    changeLanguage(languageCode);
                }
            }
        });
        
        // AJAX兼容
        if (window.jQuery) {
            jQuery(document).ajaxComplete(function() {
                if (typeof translate !== 'undefined') {
                    translate.execute();
                }
            });
        }
        </script>
        <?php
    }
}
add_action('wp_footer', 'simple_translation_add_js');

// 翻译按钮输出函数
function simple_translation_button($original_content, $user_id) {
    $options = get_option('XuWbk');
    $language_switch = isset($options['language_switch']) ? $options['language_switch'] : false;
    
    if (!$language_switch) {
        return $original_content;
    }
    
    // 获取按钮图标设置
    $language_icon_c = isset($options['language_icon_c']) ? $options['language_icon_c'] : '';
    $language_icon = isset($options['language_icon']) ? $options['language_icon'] : '';
    
    // 确定使用哪个图标
    $button_icon = '';
    if (!empty($language_icon_c)) {
        if (strpos($language_icon_c, 'http') === 0) {
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
            $language_enabled = isset($language['language_id_switch']) ? $language['language_id_switch'] : false;
            
            if ($language_enabled) {
                $language_id = isset($language['language_id']) ? $language['language_id'] : 'english';
                $language_svg = isset($language['language_svg']) ? $language['language_svg'] : '';
                $language_img = isset($language['language_img']) ? $language['language_img'] : '';
                
                // 确定图标显示
                $icon_html = '';
                if (!empty($language_svg)) {
                    if (strpos($language_svg, 'http') === 0) {
                        $icon_html = '<i class="fa fa-globe"></i>';
                    } else {
                        $icon_html = $language_svg;
                    }
                } elseif (!empty($language_img)) {
                    $img_url = $language_img;
                    $img_url = str_replace('/themes/zibll/', '/themes/XuWbk/', $img_url);
                    if (strpos($img_url, 'http') === 0) {
                        $icon_html = '<i class="fa fa-globe"></i>';
                    } else {
                        $icon_html = '<img src="' . esc_url($img_url) . '" alt="" width="20" height="20">';
                    }
                } else {
                    $icon_html = '<i class="fa fa-globe"></i>';
                }
                
                // translate.js支持的语言代码映射
                $language_mapping = array(
                    'english' => 'english',
                    'chinese_simplified' => 'chinese_simplified',
                    'chinese' => 'chinese_simplified',
                    'japanese' => 'japanese',
                    'korean' => 'korean',
                    'french' => 'french',
                    'german' => 'german',
                    'spanish' => 'spanish',
                    'russian' => 'russian',
                    'arabic' => 'arabic',
                    'portuguese' => 'portuguese',
                    'italian' => 'italian',
                    'dutch' => 'dutch',
                    'thai' => 'thai',
                    'vietnamese' => 'vietnamese',
                    'zh' => 'chinese_simplified',
                    'zh-cn' => 'chinese_simplified',
                    'zh_CN' => 'chinese_simplified',
                    'en' => 'english',
                    'ja' => 'japanese',
                    'ko' => 'korean',
                    'fr' => 'french',
                    'de' => 'german',
                    'es' => 'spanish',
                    'ru' => 'russian'
                );
                
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
                    'arabic' => 'العربية',
                    'portuguese' => 'Português',
                    'italian' => 'Italiano',
                    'dutch' => 'Nederlands',
                    'thai' => 'ไทย',
                    'vietnamese' => 'Tiếng Việt'
                );
                
                // 获取正确的语言代码
                $mapped_language = isset($language_mapping[$language_id]) ? $language_mapping[$language_id] : $language_id;
                $language_name = isset($language_names[$language_id]) ? $language_names[$language_id] : $language_id;
                
                $new_button .= '<a rel="nofollow" class="btn-newadd" href="javascript:void(0);" data-language="' . esc_attr($mapped_language) . '" rel="external nofollow">
                    <span class="language-icon">' . $icon_html . '</span>
                    <span class="ignore">' . esc_html($language_name) . '</span>
                </a>';
            }
        }
    }
    
    $new_button .= '</div></span>';
    
    return $original_content . $new_button;
}
add_filter('zib_nav_radius_button', 'simple_translation_button', 10, 2);
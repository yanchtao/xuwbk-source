<?php
/**
 * Template Name: XuWbk-网站地图
 * 
 * @author Japhia
 * @version 1.0
 * @package XuWbk
 */
/**************************************************************************** 
 $ @ 作者名称: Japhia
 $ @ 创建日期: 2024-10-20 05:50:24
 $ @ 最后修改: 2024-11-14 19:14:54
 $ @ 文件路径: /wp-content/themes/XuWbk/pages/xuwbk-map.php
 $ @ 简要说明: 有问题联系作者：QQ:181682233 邮箱：japhia@mail.com 网址：waimao.la
 $ @ Copyright (c) 2024 by Japhia, All Rights Reserved. 
 ****************************************************************************/
 // 检查后台开关状态
$options = get_option('XuWbk');
$xuwbk_map_switch = isset($options['xuwbk_map_switch']) ? $options['xuwbk_map_switch'] : false;
if ($xuwbk_map_switch == '1') {
// 如果开关关闭，显示空白页（保留头部和尾部）
get_header();
echo '<div class="container"><div class="content-wrap"><div class="content-layout"></div></div></div>';
get_footer();
exit;
}     

// 获取链接列表
get_header();
$header_style = zib_get_page_header_style();
?>
<main class="container">
    <div class="content-wrap">
        <div class="content-layout">
            <?php while (have_posts()) : the_post(); ?>
                <?php if ($header_style != 1) {
                    echo zib_get_page_header();
                } ?>
                <?php endwhile;  ?>
                <div class="box-body theme-box radius8 main-bg main-shadow">
                    <?php if ($header_style == 1) {
                        echo zib_get_page_header();
                    } ?>
                <!--主体开始-->
                <style>
                    @media (min-width: 1170px) {
                        div#mcontent {
                            width: 688px;
                            margin: auto;
                            margin-bottom: -10px;
                        }
                        .mheader,.mtitle{margin:5px auto;font-size: 27px;z-index:2;display:flex;}
                        h2.wml_map_title {
                            font-size: 20px;
                            margin-top: 50px;
                            margin-bottom: 13px;
                        }
                        ul.wml_map_ul a:hover{
                            text-decoration: underline;
                        }
                    }
                    .wml_map{}
                    ul.wml_map_ul {list-style: disc;margin: 0 0 1.5em 3em;}
                    .wml_map>ul>li::marker {font-size: 20px;}
                    .wml_map>ul>li a{font-size: 16px;height:33px;line-height:33px;}
                    span.wml_map_li_span {float: right;}
                </style>
                <div id="mcontent">

                <div class="mheader"><div class="mtitle">站点地图</div></div>
                <div class="wml_map">
                    <h2 class="wml_map_title">文章</h2>
                    <ul class="wml_map_ul">
                        <?php
                        _child('page_map_num')?$num=_child('page_map_num'):$num=-1;
                        $args=array(
                            'category__not_in' => _child('page_map_cats'), // 排除分类ID
                            'post__not_in' => _child('page_map_posts'), // 排除文章ID
                            'post_type' => 'post',//文章类型
                            'post_status' => 'publish',//已发布
                            'caller_get_posts' => 1,//ID为1的作者
                            'posts_per_page' => $num//数量，-1为全部
                        );
                        query_posts($args);
                        // 主循环
                        if ( have_posts() ) : while ( have_posts() ) : the_post();
                        ?>
                        <li><a href="<?php the_permalink(); ?>" target="_blank"><?php the_title(); ?></a><span class="wml_map_li_span"><?php echo the_time('Y-m-d')?></span></li>
                        <?php endwhile; else: endif; wp_reset_query();?>
                    </ul>

                    <h2 class="wml_map_title">页面</h2>
                    <ul class="wml_map_ul">
                        <?php
                        $args=array(
                            'post_type' => 'page',//文章类型
                            'post_status' => 'publish',//已发布
                            'caller_get_posts' => 1,//ID为1的作者
                            'posts_per_page' => -1//数量，-1为全部
                        );
                        query_posts($args);
                        // 主循环
                        if ( have_posts() ) : while ( have_posts() ) : the_post();
                        ?>
                        <li><a href="<?php the_permalink(); ?>" target="_blank"><?php the_title(); ?></a><span class="wml_map_li_span"><?php echo the_time('Y-m-d')?></span></li>
                        <?php endwhile; else: endif; wp_reset_query();?>
                    </ul>
                
                </div>

                </div>
                <!--主体结束-->
                </div>
                <?php comments_template('/template/comments.php', true); ?>
        </div>
    </div>
    <?php get_sidebar(); ?>
</main>
<?php
get_footer();
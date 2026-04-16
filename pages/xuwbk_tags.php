<?php
/**
 * Template Name: XuWbk-热门标签
 * 
 * @author Japhia
 * @version 1.0
 * @package XuWbk
 */
/**************************************************************************** 
 $ @ 作者名称: Japhia
 $ @ 创建日期: 2024-10-18 15:36:04
 $ @ 最后修改: 2024-11-14 19:11:09
 $ @ 文件路径: /wp-content/themes/XuWbk/pages/xuwbk-tags.php
 $ @ 简要说明: 有问题联系作者：QQ:181682233 邮箱：japhia@mail.com 网址：waimao.la
 $ @ Copyright (c) 2024 by Japhia, All Rights Reserved. 
 ****************************************************************************/
 // 检查后台开关状态
$options = get_option('XuWbk');
$xuwbk_tages_switch = isset($options['xuwbk_tages_switch']) ? $options['xuwbk_tages_switch'] : false;
if ($xuwbk_tages_switch == '1') {
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
                <div class="box-body theme-box radius8 main-bg main-shadow">
                    <?php if ($header_style == 1) {
                        echo zib_get_page_header();
                    } ?>
                    <div class="tagslist tags-page wrapper">
                        <ul>
                          <?php 
                            $tags_count = 60;
                            $tagslist = get_tags('orderby=count&order=DESC&number='.$tags_count);
                            foreach($tagslist as $tag) {
                              echo '<li style="list-style-type:none;"><a class="name box b2-radius b2-mg" href="'.get_tag_link($tag).'"><h2>'. $tag->name .'</h2><small></br><p>共'. $tag->count .'篇文章</p></a></li>';} 
                          ?>
                        </ul>
                    </div>
                    <?php wp_link_pages('link_before=<span>&link_after=</span>&before=<div class="article-paging">&after=</div>&next_or_number=number'); ?>
                <?php endwhile;  ?>
                </div>
                <?php comments_template('/template/comments.php', true); ?>
        </div>
    </div>
    <?php get_sidebar(); ?>
<!--CSS样式-->
  <style type="text/css">
 .wrapper{/*width: 1142px; max-width: 100%;*/ margin: 0 auto; padding: 0;} .b2-mg{margin: 16px;} .b2-radius{border-radius: 5px;} .box,.side-fixed{background-color: #fff;-webkit-transition: all .2s cubic-bezier(.455,.03,.515,.955); -webkit-box-shadow: 0 0 22px -12px rgba(0,36,100,.3);-moz-box-shadow:0 0 22px -12px rgba(0,36,100,.3);box-shadow: 0 0 22px -12px rgba(0,36,100,.3)box-shadow: 0 0 0 1px #ebebed;box-shadow: 0 1px 3px rgba(26,26,26,.1);box-shadow: 0px 5px 40px -1px rgba(2, 10, 18, 0.1);}/*标签页面*/.tags-page ul{display: flex; flex-flow: wrap;}.tags-page ul li{width: 16.66667%;}.tags-page ul li a{display: block; padding: 20px 10px; text-align: center; border-radius: 10px;}.tags-page ul li a{background-color: #FF6666; border: 2px solid rgba(255, 255, 255, 0);}.tags-page ul li a:hover{box-shadow: 0 3px 10px #ccc; border: 2px solid #fff;}.tags-page ul li:nth-child(7n + 2) a{background-color: #FF9900;}.tags-page ul li:nth-child(7n + 3) a{background-color: #339966;}.tags-page ul li:nth-child(7n + 4) a{background-color: #339999;}.tags-page ul li:nth-child(7n + 5) a{background-color: #6699CC;}.tags-page ul li:nth-child(7n + 6) a{background-color: #8f82bc;}.tags-page ul li:nth-child(7n + 7) a{background-color: #FF99CC;}.tags-page ul li h2{display: -webkit-box; -webkit-box-orient: vertical; -webkit-line-clamp: 1; height: 25px; overflow: hidden; margin-bottom: -20px; font-weight: 700; font-size: 17px; color: #fff;}.tags-page ul li p{font-size: 12px; color: rgba(255, 255, 255, 0.63);}.tags-page h1{font-size: 28px; text-align: center; margin: 30px 0;}@media screen and (max-width: 768px){.tags-page ul li{width: 50%;} .tags-page h1{margin: 20px 0;} .tags-page ul li a{padding: 10px 5px;}}.b2-radius:not(article){transition: all 0.3s;}.b2-radius:not(article):hover{transform: translateY(-10px);}
  </style>
</main>
<?php
get_footer();
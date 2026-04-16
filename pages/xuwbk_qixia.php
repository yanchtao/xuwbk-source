<?php
/**
 * Template Name: XuWbk-旗下站点
 * 
 * @author Japhia
 * @version 1.0
 * @package XuWbk
 */
/**************************************************************************** 
 $ @ 作者名称: Japhia
 $ @ 创建日期: 2024-10-27 23:08:55
 $ @ 最后修改: 2024-11-14 19:14:39
 $ @ 文件路径: \wml-zib-diy\core\functions\page\wml-qixia.php
 $ @ 简要说明: 有问题联系作者：QQ:181682233 邮箱：japhia@mail.com 网址：waimao.la
 $ @ Copyright (c) 2024 by Japhia, All Rights Reserved. 
 ****************************************************************************/

// 使用CSF框架的选项获取方式
$options = get_option('XuWbk');
$xuwbk_qixia = isset($options['xuwbk_qixia']) ? $options['xuwbk_qixia'] : false;
if (!($xuwbk_qixia === true || $xuwbk_qixia === '1' || $xuwbk_qixia === 1)) {
    // 如果开关关闭，显示空白页（保留头部和尾部）
    get_header();
    echo '<main class="container"><div class="content-wrap"><div class="content-layout xy-article"><div class="nopw-sm box-body theme-box radius8 main-bg main-shadow"></div></div></div></main>';
    get_footer();
    exit();
}

// 获取链接列表
get_header();
$header_style = zib_get_page_header_style();
?>

<main class="container">
<div class="content-wrap">
<div class="content-layout">


<div class="page-cover theme-box radius8 main-shadow">
<img class="fit-cover no-scale lazyload" src="<?php echo isset($options['qixia_bj']) ? $options['qixia_bj'] : ''; ?>" data-src="<?php echo isset($options['qixia_bj']) ? $options['qixia_bj'] : ''; ?>">
<div class="absolute page-mask"></div>
<div class="list-inline box-body abs-center text-center">
<div class="title-h-center">
<h3><?php echo isset($options['qixia_name']) ? $options['qixia_name'] : ''; ?></h3>
         </div>
     </div>
</div>      


<article class="article page-article main-bg theme-box box-body radius8 main-shadow">
<div class="wp-posts-content">

<?php
if(isset($options['qixia_zd']) && is_array($options['qixia_zd'])){
    foreach($options['qixia_zd'] as $val) {
        echo '<div class="wp-block-zibllblock-feature feature feature-default" data-icon="'.$val['icon'].'" data-color="'.$val['color'].'"><div class="feature-icon"><i style="color:'.$val['color'].'" class="'.$val['icon'].'"></i></div><div class="feature-title"><a href="'.$val['link']['url'].'" target="'.$val['link']['target'].'" rel="noreferrer noopener">'.$val['name'].'</a></div><div class="feature-note">'.$val['info'].'</div></div>';
    }
}
?>
</div>
</article>
     </div>
</div>
    </main>
    
<?php
get_footer();
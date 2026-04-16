<?php
/**
 * Template Name: XuWbk-链接检测
 * 
 * @author Japhia
 * @version 1.0
 * @package XuWbk
 */
/**************************************************************************** 
 $ @ 作者名称: Japhia
 $ @ 创建日期: 2024-10-28 00:01:47
 $ @ 最后修改: 2024-11-23 14:33:25
 $ @ 文件路径: \wml-zib-diy\core\functions\page\wml-links.php
 $ @ 简要说明: 有问题联系作者：QQ:181682233 邮箱：japhia@mail.com 网址：waimao.la
 $ @ Copyright (c) 2024 by Japhia, All Rights Reserved. 
 ****************************************************************************/

// 检查后台开关状态
$options = get_option('XuWbk');
$xuwbk_links = $options['xuwbk_links'];
if (!($xuwbk_links === true || $xuwbk_links === '1' || $xuwbk_links === 1)) {
    // 如果开关关闭，显示空白页（保留头部和尾部）
    get_header();
    echo '<main class="container"><div class="content-wrap"><div class="content-layout xy-article"><div class="nopw-sm box-body theme-box radius8 main-bg main-shadow"></div></div></div></main>';
    get_footer();
    exit();
}

// 获取链接列表
function zib_page_links()
{
    $type = 'card';
    $post_ID = get_queried_object_id();
    $args_orderby = get_post_meta($post_ID, 'page_links_orderby', true);
    $args_order = get_post_meta($post_ID, 'page_links_order', true);
    $args_limit = get_post_meta($post_ID, 'page_links_limit', true);
    $args_category = get_post_meta($post_ID, 'page_links_category', true);
    $args = array(
        'orderby'        => $args_orderby ? $args_orderby : 'name', //排序方式
        'order'          => $args_order ? $args_order : 'ASC', //升序还是降序
        'limit'          => $args_limit ? $args_limit : -1, //最多显示数量
        'category'       => $args_category, //以逗号分隔的类别ID列表
    );
    $links = get_bookmarks($args);

    $html = '';

    if ($links) {
        $html .= zib_links_box($links, $type, false);
    } elseif (is_super_admin()) {
        $html = '<a class="author-minicard links-card radius8" href="' . admin_url('link-manager.php') . '" target="_blank">添加链接</a>';
    } else {
        $html = '<div class="author-minicard links-card radius8">暂无链接，请联系管理员添加</div>';
    }
    return $html;
}

function Links(){
    global $wpdb;
    $links = $wpdb->prefix . 'links';
    $links_count = $wpdb->get_var( "SELECT COUNT(`link_id`) FROM {$links}" );
    if(get_current_user_id() == 1){
        $gl = true;
        $gl_td = '<th align="center" class="xy-width-100 xy_hide">管理</th>';
    }
                    //<span class="but jb-pink inspect" style="float: right;font-size: 12px;">一键检测</span>
    $html = '
        <div class="zib-widget">
            <h2>友情链接检测

            </h2>
            <div class="xypro_describe"> 
                <p class="xy_height_hide_p2 xy_display_btn">查看更多友链 <i class="fa fa-angle-down ml6 xy-more-btn"></i></p>
                <p class="xy_height_hide_p"></p>
                <div class="xypro_describe_title">
                    检测列表（'.$links_count.'条友链）
                </div> 
                <div id="xy_hide" class="xypro_describe_content xy_height_hide">
                    <table class="yq">
                        <thead>
                            <tr>
                                <th align="center" class="xy-width-190">检测时间</th>
                                <th align="center">网站名称</th>
                                <th align="center">下链原因</th>
                                '.$gl_td.'
                            </tr>
                        </thead>';
    $html .= '          <tbody class="link-list">';
    
    //获取分页数据 :每页显示的数量 默认为50
    $limit = 15;
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    //计算每一页第一条记录的显示偏移量
    //偏移量 = (页码 -1) \* 每页的显示数量
    $offset = ( $paged - 1 ) * $limit;
    
    $total = $wpdb->get_var( "SELECT COUNT(`link_id`) FROM $links" );
    $pages = ceil( $total / $limit );
    
    //调用
    $link = $wpdb->get_results("SELECT * FROM $links WHERE link_id ORDER BY link_id desc LIMIT {$limit} OFFSET {$offset}");
    
    foreach ($link as $k => $v){
        $name = $v->link_name;
        $btn = '<a href="javascript:;" class="but jb-pink testing" style="float:right;font-size:12px" id="'.$v->link_url.'">检测</a>';
        $btn = get_current_user_id() == 1 ? $btn : '';
        if($v->link_notes == '检测友情链接正常！'){
            $msg_btn = 'color: green;';
        }else{
            $msg_btn = 'color: red;';
        }
        if($gl){
            $gl_tr_td = '<td class="xy_hide">
            <a  href="javascript:;" class="admin-btn">管理</a>
            <ul class="admin-guanli">
                <li><a class="link_JC" href="javascript:;" id="Y'.$v->link_id.'">显示</a></li>
                <li><a class="link_JC" href="javascript:;" id="N'.$v->link_id.'">隐藏</a></li>
                <li><a class="link_JC" href="javascript:;" id="D'.$v->link_id.'">删除</a></li>
            </ul>
            </td>';
        }
        if($v->link_notes == '检测友情链接正常！'){
            $title = '检测友情链接正常';
            $name = '<a href="'.$v->link_url.'" target="_blank" data-toggle="tooltip" data-original-title="'.$v->link_description.'">'.$name.'</a>';
        }elseif($v->link_notes == '未获得URL相关数据，请重试！'){
            $title = '请检查网站是否开了重定向';
        }elseif($v->link_notes == '请确认您已经添加本站的链接'){
            $title = '请检查该链接是否做了本站友链';
        }elseif(!$v->link_notes){
            $title = '该网站站长还未检测';
        }else{
            $title = '检测网站访问时间过长';
        }
        $link_notes = $v->link_notes ? $v->link_notes : '该网站站长还未检测';
        $html .= '<tr class="yq_link">
                    <td>'.$v->link_updated.'</td>
                    <td>'.$name.'</td>
                    <td style="'.$msg_btn.'"><span data-toggle="tooltip" data-original-title="'.$title.'">'.$link_notes.'</span><span class="xy_hide">'.$btn.'</span></td>
                    '.$gl_tr_td.'
                </tr>';
    }
    
    $html .= '<script>
 
        var clicktype = true;
        $(".link_JC").on("click",function(e){
            if(confirm(\'是否进入下一步操作?\')) {
                if(clicktype == false){return false};
                clicktype = false;
                var link_id = $(this).attr("id");
                $.ajax({
                    type:"POST",
                    url:"'.admin_url('admin-ajax.php').'",
                    data:{
                        "action":"xy_link_jc",
                        "link_id":link_id,
                    },
                    cache:false,
                    dataType:"json",
                    success:function(data){
                        if(data.code == 0){
                            notyf(data.msg,"success");
                        }else{
                            notyf(data.msg,"warning");
                        }
                        clicktype = true;
                    },
                    error:function(data){
                        notyf("请求数据错误","warning");
                        clicktype = true;
                    }
                });
            }
        });
    </script>';
    
    $html .= '<script>
        var clicktype = true;
        $(".testing").on("click",function(e){
            if(clicktype == false){return false};
            clicktype = false;
            var link_url = $(this).attr("id");
            notyf("检测中请稍等...","load", "2");
            var text = "<i class=loading mr6></i> 检测中";
            $(this).attr("disabled",true);
            $(this).text("");
            $(this).append(text);
            $(this).addClass("jc-load");
            $.ajax({
                    type:"POST",
                    url:"'.admin_url('admin-ajax.php').'",
                    data:{
                        "action":"xy_link",
                        "user_url":link_url,
                    },
                    cache:false,
                    dataType:"json",
                    success:function(data){
                        if(data.code == 0){
                            notyf(data.msg,"success");
                        }else{
                            notyf(data.msg,"warning");
                        }
                        clicktype = true;
                        $(".jc-load").text("");
                        $(".jc-load").append("检测");
                        $(".jc-load").attr("disabled",false);
                      //  link_url.removeClass("jc-load");
                    },
                    error:function(data){
                        notyf("请求数据错误","warning");
                        clicktype = true;
                        $(".jc-load").text("");
                        $(".jc-load").append("检测");
                        $(".jc-load").attr("disabled",false);
                       // link_url.removeClass("jc-load");
                    }
                });
        })
    </script>';
    
    $html .= '<script>
        $(document).on("click",".xy_display_btn",function(){
            $(".xy_display_btn").remove();
            $(".xy_height_hide_p").remove();
            $("#xy_hide").removeClass("xy_height_hide");
            $(".xypro_describe").append("<p class=\'xy_height_hide_p2 xy_hide_btn\'>隐藏友链列表 <i class=\'fa fa-angle-up ml6 xy-more-btn\'></i></p>");
        });
        $(document).on("click",".xy_hide_btn",function(){
            $(".xy_hide_btn").remove();
            $("#xy_hide").addClass("xy_height_hide");
            $(".xypro_describe").append("<p class=\'xy_height_hide_p2 xy_display_btn\'>查看更多友链 <i class=\'fa fa-angle-down ml6 xy-more-btn\'></i></p><p class=\'xy_height_hide_p\'></p>");
            $("body,html").animate({scrollTop:$(".xypro_describe").offset().top},400);

        });
    </script>';
    $html .= '</tbody></table>';
    $html .= $link ? xy_pages($pages,$paged) : '<div class="text-center ajax-item "><img style="width:280px;opacity: .7;" src="wp-content/themes/zibll/img/null.svg"><p style="" class="em09 muted-3-color separator">暂无友链链接</p></div>';
    $html .= '</div></div></div>';
    return $html;
}
/**
* 数字分页函数
* 因为wordpress默认仅仅提供简单分页
* 所以要实现数字分页，需要自定义函数      
*/
function xy_pages($max_page,$paged) {
    $html = '';
    $html.= '<style>
        .pagination{margin:30px 0;padding:0 10px;text-align:center;font-size:12px;display:block;border-radius:0}
        .excerpts .pagination{margin-bottom: 10px;}
        .pagination ul{display:inline-block !important;margin-left:0;margin-bottom:0;padding:0}
        .pagination ul > li{display:inline}
        .pagination ul > li > a,.pagination ul > li > span{margin:0 2px;padding:6px 12px;background-color:#ddd;color:#666;border-radius:2px;opacity:.88}
        .pagination ul > li > a:hover,.pagination ul > li > a:focus{opacity:1}
        .pagination ul > .active > a,.pagination ul > .active > span{background-color:#1d1d1d;color:#fff}
        .pagination ul > .active > a,.pagination ul > .active > span{cursor:default}
        .pagination ul > li > span,.pagination ul > .disabled > span,.pagination ul > .disabled > a,.pagination ul > .disabled > a:hover,.pagination ul > .disabled > a:focus{color:#999999;background-color:transparent;cursor:default}
    </style>';
    
    $p = 2;
    if ( $max_page == 1 ) {
        return;
    }
    $html.= '<span class="pagination"><ul>';
    $paged = !empty( $paged ) ? $paged : 1;
    
    if ( $paged > 1 ) {
        $html.= '<li><a href="'.esc_html( get_pagenum_link( 1 ) ).'">首页</a></li>';
    }
    
    $html.= '<li class="prev-page">';
    $html.= '</li>';
    for ( $i = $paged - $p; $i <= $paged + $p; $i++ ) {
        if ( $i > 0 && $i <= $max_page ) {
            if($i == $paged){
                $html.= "<li class=\"active\"><span>{$i}</span></li>";
            }else{
                $html.= '<li><a href="'.esc_html( get_pagenum_link( $i ) ).'">'. $i .'</a></li>';
            }
        }
    }
    $html.= '<li class="next-page">';
    $html.= '</li>';
    $html.= '<li><a href="'.esc_html( get_pagenum_link( $max_page ) ).'">尾页</a></li>';
    //$html.= '<li><span>共 '.$max_page.' 页</span></li>';
    $html.= '</ul></span>';
    return $html;
}

get_header();
$post_id = get_queried_object_id();
$header_style = zib_get_page_header_style();
$page_links_content_s = get_post_meta($post_id, 'page_links_content_s', true);
$page_links_content_position = get_post_meta($post_id, 'page_links_content_position', true);
$page_links_submit_s = get_post_meta($post_id, 'page_links_submit_s', true);
?>
<style>
.admin-btn{background: #8486f8;padding: 2px 10px;color: #fff;border-radius: 4px;}
.admin-guanli{visibility:hidden;position: absolute;min-width: 80px;background-color: var(--main-bg-color);padding: 10px 5px;z-index: 99;border-radius: var(--main-radius);box-shadow: 0 0 10px rgba(0,0,0,.1);right: -40px;margin-top: -40px;}
.xy_hide:hover>.admin-guanli{visibility:unset;}
.xy_hide:hover>.admin-btn{color: #fff;background: #6d6fd8;}
.f12{font-size:12px;}
.xypro_describe {position: relative;border: 1px dashed #dcdfe6;line-height: 26px;}
.xypro_describe_title {position: absolute;top: 0;left: 8px;-webkit-transform: translateY(-50%);transform: translateY(-50%);background: #fff;padding: 0 5px;color: #303133;font-weight: 500;max-width: 200px;white-space: nowrap;overflow: hidden;text-overflow: ellipsis;}
.xypro_describe_content{color: #606266;padding: 18px 15px 30px;}
.yq{width: 100%;max-width: 100%;table-layout: fixed;color: #909399;margin-bottom: 18px;border-top: 1px solid #ebeef5;border-left: 1px solid #ebeef5;}
.yq thead th {font-weight: 500;background: #ebeef5;text-align: center;padding: 8px;border-bottom: 1px solid #ebeef5;border-right: 1px solid #ebeef5;}
.yq_link td {text-align: center;padding: 8px;border-bottom: 1px solid #ebeef5;border-right: 1px solid #ebeef5;text-overflow: ellipsis;
    white-space: nowrap;overflow: hidden;}
.xy_li {text-align: center;font-size: 16px;line-height: 30px;}
.xy_li::marker {content: "#" counter(list-item) " ";color: var(--theme-color);}
.xy-mask {background-color: rgba(0,0,0,.5);}
.xy_height_hide{height: 300px;text-overflow: ellipsis;white-space: nowrap;overflow: hidden;}
.xy_height_hide_p{background: linear-gradient(180deg,hsla(0,0%,100%,0),#fff);width: 100%;z-index: 1;position: absolute;bottom: 0;margin: 0;height: 100px;}
.xy_height_hide_p2{text-align: center;bottom: 0;z-index: 2;position: absolute;left: 0;right: 0;cursor: pointer !important;}
.xy-more-btn{width: 20px;height: 20px;background-color: var(--main-bg-color);border: 1px solid rgb(237, 237, 237);border-radius: 50%;line-height: 18px;}

code{font-family: "lovely";}
.xy_callout ol li::marker {content: "#" counter(list-item) " ";color: var(--theme-color);}
.xy_callout {padding: 20px;border: 1px solid #e4e4e4;border-left-width: 5px;border-radius: 6px;line-height: 30px;font-weight: 600;border-left-color: var(--theme-color);}
.xy_content>h5{margin: 0;font-weight:600;font-size: 24px;line-height: 32px;padding:20px 0;text-align: center;}
.xy_checkbox:checked{background:var(--theme-color);-webkit-appearance: none;position: relative;border-radius: 2px;width: 15px;height: 15px;vertical-align: -2px;}
.xy_content h5:before {content: '「';color: var(--theme-color);font-weight: 600;margin-left: 5px;}
.xy_content h5:after {content: '」';color: var(--theme-color);font-weight: 600;margin-right: 5px;}
/**.xy_checkbox:checked:after {content:'';width: 6px;height: 10px;position: absolute;top: 1px;left: 5px;border: 2px solid #fff;border-top: 0;border-left: 0;-webkit-transform: rotate(45deg);transform: rotate(45deg);}**/
.wp-posts-content li{margin-bottom: 0;}
.xy-width{padding:0 30px 30px;}
.wp-posts-content ol>li>span{color: var(--theme-color);}
@media screen and (max-width:500px){.xy-width{padding:10px;}.wp-posts-content ol:not(.blocks-gallery-grid){margin:0;}.xy_hide{display:none;}.title-h-center{display:none;}.xy-mask {background-color: rgb(0 0 0 / 5%);}}
@media screen and (min-width:500px){.xy-width-190{width: 190px;}.xy-width-100{width: 100px;}}
</style>
<script>
//$.getJSON("https://api.qjqq.cn/api/Yi?c=f&encode=json",function(data){ $("#yulu").text(data.hitokoto);});$(function(){$("#yulu").click(function() {$(this).select();})})
</script>

<main class="container">
    <div class="content-wrap">
        <div class="content-layout">
            <div class="page-cover theme-box radius8 main-shadow">
                    <img class="fit-cover no-scale lazyloaded" src="<?php echo isset($options['links_bj']) ? $options['links_bj'] : ''; ?>" data-src="<?php echo isset($options['links_bj']) ? $options['links_bj'] : ''; ?>">
                    <div class="absolute xy-mask"></div>
                    <div class="list-inline box-body abs-center text-center">
                    <div class="title-h-center">
                        <span class="xy_content"><h5><font size="6" color="red"> 注</font>意提醒 </h5>
                            <li class="xy_li">开启外链重定向会判定失败</li>
                            <li class="xy_li">网站打开时间过长会请求失败</li>
                            <li class="xy_li">首页未有本站链接会判定失败</li>
                            <li class="xy_li">如有疑问可找站长或进群</li>
                        </span>
                    </div>
                    </div>
                </div>
            <?php while (have_posts()) : the_post(); ?>
                <?php echo zib_get_page_header(); ?>
                <?php                 if ($page_links_content_position == 'top') {
                    echo '<div class="zib-widget">' . zib_page_links() . '</div>';
                }?>
                <?php echo Links(); ?>
                <?php
                if ($page_links_content_position != 'top') {
                    echo '<div class="zib-widget">' . zib_page_links() . '</div>';
                }
                $cats_query_args = array(
                    'taxonomy'   => array('link_category'),
                    'hide_empty' => false,
                );
                $cats_query = new WP_Term_Query($cats_query_args);
        
                $cats_options = '';
                if (!is_wp_error($cats_query) && !empty($cats_query->terms)) {
                    foreach ($cats_query->terms as $item) {
                        $cats_options .= '<option value="' . $item->term_id . '">' . $item->name . '</option>';
                    }
                }
                $cats_options = $cats_options ? '<div class="col-sm-12 mb10">
                    <div class="em09 muted-2-color mb6">网站类别</div>
                    <div class="form-select"><select name="link_category" class="form-control">' . $cats_options . '</select></div>
                </div>' : '';
                
                echo'<div class="box-body notop">
                      <div class="title-theme">友链申请说明<small class="ml10">请注意查看</small></div>
                    </div>
                    <div class="zib-widget xy-width">
                      <span class="xy_content" style="display: block;">
                        <h5>申请前 <font size="6" color="red">必</font> 阅读</h5>
                        <section class="xy_callout wp-posts-content"
                          style="font-weight:400;padding:0 20px 10px;margin-bottom:15px;border-left-color: #1890ff;">
                          <h3>申请条件</h3>
                          <ol>
                            <li>先确认申请的博客内容符合以下类别，即使未列出的亦可继续申请。<br>
                              <i class="fa fa-check" aria-hidden="true"></i>
                              某领域技术性博客<br>
                              <i class="fa fa-check" aria-hidden="true"></i> 
                              资源下载类博客<br>
                              <i class="fa fa-check" aria-hidden="true"></i>
                              论坛，社区或聚合媒体类网站<br> 
                              <i class="fa fa-times" aria-hidden="true"></i>
                              纯视频站，纯图片站<br> 
                              <i class="fa fa-times" aria-hidden="true"></i>
                              赌博、发卡、支付类网站<br> 
                              <i class="fa fa-times" aria-hidden="true"></i>
                              原创少于1篇的博客<br>
                              <i class="fa fa-times" aria-hidden="true"></i>
                              没有独立博客服务器的博客（例如搭设在WordPress，blogger，typecho等）
                            </li>
                            <li>博客需每月至少有一篇更新博客，或者有经常更新的日常笔记，并且已存在至少<code>20篇</code>博文。</li>
                            <li>站点需要全站https，也不接受带ip或端口的链接，并且国内无墙。</li>
                            <li>在小站有效留言至少一条，至少我得熟悉你吧，这样才好申请友链。</li>
                            <li>博客有RSS2.0地址或atom地址（一般的博客程序都自带，请不要关闭，如果是自制程序没有可忽略）。</li>
                            <li style="color:red">权重大于3，以上条件皆可无视。</li>
                          </ol>
                          <h3>申请过程</h3>
                          <ol>
                            <li>申请前请先将本站友链加好。</li>
                            <li>申请信息真认真写好，请勿乱写。</li>
                            <li>申请时请写清<code>网站名称</code> <code>网站头像</code> <code>网站介绍</code> <code>网站链接</code></li>
                          </ol>
                          <h3>申请后续</h3>
                          <ol>
                            <li>如果不符合要求会无视掉申请，<font color="red">一天内都会通过。</font>
                            </li>
                            <li>网站修改友链信息请申请页面留言即可，无格式要求。</li>
                            <li>排名一般来说是有先后顺序的，但是还是要说，排名不分先后。</li>
                            <li>若发现站点无法访问，将会在一个月后删除。</li>
                            <li>网站正常访问但是无故下掉链接的，会拉入黑名单，不再接受友链申请。</li>
                            <li>本站使用
                              接口检测模式，
                              设有重定向、访问过慢的网站无法正常识别。</li>
                          </ol>
                        </section>
                        
                         <div class="xy_callout">
                           <div style=""> 
                           <p><code>申请前请先将本站友链加好。</code></p>
                           <input type="checkbox" class="xy_checkbox" checked disabled>
                              名称：<span>'.get_bloginfo('name').'</span><br> <input type="checkbox" class="xy_checkbox" checked disabled>
                              地址：<span>'.get_bloginfo('url').'</span><br> <input type="checkbox" class="xy_checkbox" checked disabled>
                              介绍：<span>'.(isset($options['links_js']) ? $options['links_js'] : '').'</span><br> <input type="checkbox" class="xy_checkbox" checked disabled>
                              头像：<span>'.(isset($options['links_logo']) ? $options['links_logo'] : '').'</span>
                           </div>
                           <!-- 提交链接开始 -->
                        <div class="mt20"><a class="padding-h10 hollow but c-theme btn-block text-ellipsis" href="#submit-links-modal" data-toggle="modal" style="overflow: hidden; position: relative;">申请入驻</a></div><div class="modal fade" id="submit-links-modal" tabindex="-1" role="dialog" aria-hidden="false">    <div class="modal-dialog" role="document">    <div class="modal-content" style=""><div class="modal-body"><div class="mb20"><button class="close" data-dismiss="modal"><svg class="ic-close" aria-hidden="true"><use xlink:href="#icon-close"></use></svg></button><b class="modal-title flex ac"><span class="toggle-radius mr10 b-theme"><i class="fa fa-pencil-square-o"></i></span>申请入驻</b></div><div class="muted-box em09">
                        '.(isset($options['links_text']) ? $options['links_text'] : '').'
                    </div><form class="form-horizontal mt10 form-upload"><div class="row gutters-5">
                        <div class="col-sm-6 mb10">
                            <div class="em09 muted-2-color mb6">网站名称（必填）</div>
                            <input type="text" class="form-control" id="link_name" name="link_name" placeholder="请输入网站名称">
                        </div>
                        <div class="col-sm-6 mb10">
                            <div class="em09 muted-2-color mb6">网站地址（必填）</div>
                            <input type="text" class="form-control" id="link_url" name="link_url" placeholder="https://...">
                        </div>

                    <div class="col-sm-12 mb10">
                        <div class="em09 muted-2-color mb6">网站简介</div>
                        <input type="text" class="form-control" id="link_description" name="link_description" placeholder="一句话介绍网站">
                    </div>
                    ' . $cats_options . '

                    <div class="col-sm-12 mb10">
                        <div class="em09 muted-2-color mb6">LOGO图像</div>
                        <label class="pointer"><div class="preview preview-square"><img class="fit-cover" src="wp-content/themes/zibll/img/upload-add.svg" alt="添加图片"></div>
                            <input class="hide" type="file" zibupload="image_upload" accept="image/gif,image/jpeg,image/jpg,image/png" name="image">
                        </label>
                        <div class="px12 muted-2-color mb6">请选择正方形LOGO图像，支持jpg/png/gif格式</div>
                    </div>
                </div><div class="col-sm-9" style="max-width: 300px;"><input machine-verification="slider" type="hidden" name="captcha_mode" value="slider" slider-id=""></div><div class="text-right edit-footer">
                        <button type="button" zibupload="submit" class="but c-blue padding-lg input-expand-upload" name="submit"><i class="fa fa-check" aria-hidden="true"></i>确认提交</button>
                    </div>'.wp_nonce_field('frontend_links_submit', '_wpnonce', false, false).'<input type="hidden" name="action" value="frontend_links_submit"></form></div></div>    </div>    </div>
                    <!-- 提交链接结束 -->
                        </div>
                      </span>
                    </div>';
                if ($page_links_content_s) {
                    echo '<div class="zib-widget"><article class="article wp-posts-content">';
                    the_content();
                    echo '</article>';
                    wp_link_pages(
                        array(
                            'before'           => '<p class="text-center post-nav-links radius8 padding-6">',
                            'after'            => '</p>',
                        )
                    );
                    echo '</div>';
                }
                if ($page_links_submit_s) {
                    $submit_args = array(
                        'title' => get_post_meta($post_id, 'page_links_submit_title', true),
                        'subtitle' => get_post_meta($post_id, 'page_links_submit_subtitle', true),
                        'dec' => get_post_meta($post_id, 'page_links_submit_dec', true)
                    );
                    echo zib_submit_links_card($submit_args);
                }
                ?>
                
                <?php ?>
            <?php endwhile; ?>
            <?php comments_template('/template/comments.php', true); ?>
        </div>
    </div>
    <?php get_sidebar(); ?>
</main>

<?php
get_footer();
?>
<?php
/**
 * Template Name: XuWbk-封禁黑屋
 * 
 * @author Japhia
 * @version 1.0
 * @package XuWbk
 */

/**************************************************************************** 
 $ @ 作者名称: Japhia
 $ @ 创建日期: 2024-10-27 08:35:16
 $ @ 最后修改: 2024-11-14 19:16:03
 $ @ 文件路径: \wml-zib-diy\core\functions\page\wml-heiwu.php
 $ @ 简要说明: 有问题联系作者：QQ:181682233 邮箱：japhia@mail.com 网址：waimao.la
 $ @ Copyright (c) 2024 by Japhia, All Rights Reserved. 
 ****************************************************************************/

// 检查后台开关状态
// 使用CSF框架的选项获取方式
$options = get_option('XuWbk');
$xuwbk_heiwu = isset($options['xuwbk_heiwu']) ? $options['xuwbk_heiwu'] : false;
if (!($xuwbk_heiwu === true || $xuwbk_heiwu === '1' || $xuwbk_heiwu === 1)) {
    // 如果开关关闭，显示空白页（保留头部和尾部）
    get_header();
    echo '<main class="container"><div class="content-wrap"><div class="content-layout xy-article"><div class="nopw-sm box-body theme-box radius8 main-bg main-shadow"></div></div></div></main>';
    get_footer();
    exit();
}

get_header();

global $wpdb;
$total = $wpdb->get_var( "SELECT COUNT(`meta_value`) FROM $wpdb->usermeta WHERE meta_key='banned' AND meta_value !='0'" );

function block_banned(){
    global $wpdb;
    
    //获取分页数据 :每页显示的数量 默认为50
    $limit = 20;
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    
    //计算每一页第一条记录的显示偏移量
    //偏移量 = (页码 -1) \* 每页的显示数量
    
    $offset = ( $paged - 1 ) * $limit;
    $total = $wpdb->get_var( "SELECT COUNT(`meta_value`) FROM $wpdb->usermeta WHERE meta_key='banned' AND meta_value !='0'" );
    $pages = ceil( $total / $limit );
    
    //调用
    $banned =  $wpdb->get_results("SELECT meta_value,user_id,meta_key FROM {$wpdb->usermeta} WHERE meta_key='banned' AND meta_value !='0' ORDER BY user_id DESC LIMIT $limit OFFSET {$offset}");
    
    $html = '
        <div class="zib-widget">
            <div class="xypro_describe"> 
                <div class="xypro_describe_title">
                    封禁列表（'.$total.'名成员）
                </div> 
                <div id="xy_hide" class="xypro_describe_content xy_height_hide">
                    <table class="yq">
                        <thead>
                            <tr>
                                <th align="center" width="5%"><strong>用户名</strong></th>
                                <th align="center" width="5%"><strong>封禁时间</strong></th>
                                <th align="center" width="5%"><strong>封禁类型</strong></th>
                                <th align="center" width="5%"><strong>封禁时长</strong></th>
                                <th align="center" width="5%"><strong>封禁原因</strong></th>
                                <th align="center" width="5%"><strong>备注</strong></th>
                            </tr>
                        </thead>';
    $html .= '          <tbody class="link-list">';
    
    foreach ($banned as $k => $v){
        $user_info = get_userdata($v->user_id);
        $username = $user_info ? $user_info->user_login : '未知用户';
        $ban_data = maybe_unserialize($v->meta_value);
        
        // 解析封禁数据
        $ban_time = isset($ban_data['time']) ? $ban_data['time'] : '未知时间';
        $ban_type = isset($ban_data['type']) ? ($ban_data['type'] == 2 ? '禁封中' : '小黑屋') : '未知类型';
        $ban_duration = isset($ban_data['banned_time']) ? 
            ($ban_data['banned_time'] ? round((strtotime($ban_data['banned_time']) - strtotime($ban_data['time'])) / 86400) . '天' : '永久') : '未知时长';
        $ban_reason = isset($ban_data['reason']) ? $ban_data['reason'] : '未知原因';
        $ban_desc = isset($ban_data['desc']) ? $ban_data['desc'] : '';
        $no_appeal = isset($ban_data['no_appeal']) && !empty($ban_data['no_appeal']) ? '（不可申诉）' : '（可申诉）';
        
        $html .= '<tr class="yq_link">
                    <td>'.$username.'</td>
                    <td>'.$ban_time.'</td>
                    <td>'.$ban_type.'</td>
                    <td>'.$ban_duration.'<span style="color:var(--theme-color);">'.$no_appeal.'</span></td>
                    <td>'.$ban_reason.'</td>
                    <td>'.$ban_desc.'</td>
                </tr>';
    }
    
    $html .= '</tbody></table>';
    $html .= $banned ? xy2_pages($pages,$paged) : '<div class="text-center ajax-item "><p class="em09 muted-3-color separator">暂无封禁用户</p></div>';
    $html .= '</div></div></div>';
    return $html;
}
/**
* 数字分页函数
* 因为wordpress默认仅仅提供简单分页
* 所以要实现数字分页，需要自定义函数      
*/
function xy2_pages($max_page,$paged) {
    $html = '';
    $html.= '<style>
        .pagination{margin:0;text-align:center;font-size:12px;display:block;border-radius:0}
        .excerpts .pagination{margin-bottom: 10px;}
        .pagination ul{display:inline-block !important;margin-left:0;margin-bottom:0;padding:0}
        .pagination ul > li{display:inline}
        .pagination ul > li > a,.pagination ul > li > span{margin:0 2px;padding:8px 12px;background-color:#ddd;color:#666;border-radius:2px;opacity:.88}
        .pagination ul > li > a:hover,.pagination ul > li > a:focus{opacity:1}
        .pagination ul > .active > a,.pagination ul > .active > span{background-color:#000;color:#fff}
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
?>
<style>
.wp-posts-content p{margin: 0 0 10px;}
.xypro_describe {position: relative;border: 1px dashed #dcdfe6;line-height: 26px;margin-top: 10px;}
.xypro_describe_title {position: absolute;top: 0;left: 8px;-webkit-transform: translateY(-50%);transform: translateY(-50%);background:var(--main-bg-color);padding: 0 5px;font-weight: 500;max-width: 200px;white-space: nowrap;overflow: hidden;text-overflow: ellipsis;}
.xypro_describe_content{color: #606266;padding: 18px 15px 0;width: 100%;text-overflow: ellipsis;white-space: nowrap;overflow: hidden;overflow-x: scroll;}
.yq{max-width: 100%;table-layout: fixed;color: #909399;margin-bottom: 18px;border-top: 1px solid #ebeef5;border-left: 1px solid #ebeef5;}
#xy_hide::-webkit-scrollbar {
    display: none;
}
.yq thead th {font-weight: 500;background: #ebeef5;text-align: center;padding: 8px;border-bottom: 1px solid #ebeef5;border-right: 1px solid #ebeef5;}
.yq_link td {text-align: center;padding: 8px;border-bottom: 1px solid #ebeef5;border-right: 1px solid #ebeef5;text-overflow: ellipsis;
    white-space: nowrap;overflow: hidden;}
@media screen and (max-width: 700px){
    .yq{width: 600px !important;}
}
</style>
<main class="container">
    <div class="content-wrap">
        <div class="content-layout xy-article">
            <div class="nopw-sm box-body theme-box radius8 main-bg main-shadow">
                <!-- <article class="article wp-posts-content"> -->
                    <div class="wp-block-zibllblock-quote">
                        <div class="quote_q" data-color="#fb2121" style="--quote-color:#fb2121">
                            <i class="fa fa-quote-left"></i>
                            <p>本网站严禁发布违法违规以及发布毫无意义的评论等内容，如有违反，封禁处理，以下是本站封禁用户列表，请大家珍惜自己的账户！</p>
                            </div>
                        </div>
                        <div style="padding: 0 20px;margin-bottom: 20px;">
                            <p><strong>封禁类型说明：</strong></p>
                            <p>1.小黑屋：用户拉入小黑屋后，用户将失去所有的发布权限及大部分的操作权限，例如发帖、评论、下载等；</p>
                            <p>2.封号：禁封的用户将不能登录，将失去全部权限;</p>
                            <p class="mb10-sm" style="color:#fb2121;">累计封禁用户:<?php echo $total ? $total : 0;?>个 数据截止日期:<?php echo date("Y-m-d", time()+8*60*60);?></p>
                        </div>
                        <?php echo block_banned();?>
                        <div style="text-align:center;color:var(--theme-color);">
                        - - - 善语结善缘，恶语伤人心 - - -
                    </div>
                    </div>
                    
                <!-- </article> -->
            </div>
        </div>
    </div>
</main>
<?php
get_footer();
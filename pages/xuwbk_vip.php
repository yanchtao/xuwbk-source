<?php
/**
 * Template Name: XuWbk-VIP说明
 * 
 * @author Japhia
 * @version 1.0
 * @package XuWbk
 */

/**************************************************************************** 
 $ @ 作者名称: Japhia
 $ @ 创建日期: 2024-10-16 21:14:45
 $ @ 最后修改: 2024-11-14 19:04:53
 $ @ 文件路径: \wml-zib-diy\core\functions\page\wml-vip.php
 $ @ 简要说明: 有问题联系作者：QQ:181682233 邮箱：japhia@mail.com 网址：waimao.la
 $ @ Copyright (c) 2024 by Japhia, All Rights Reserved. 
 ****************************************************************************/

// 检查后台开关状态
// 使用CSF框架的选项获取方式
$options = get_option('XuWbk');
$xuwbk_vip = isset($options['vip_info']) ? $options['vip_info'] : false;

// 获取VIP相关设置
$vip_name1 = isset($options['vip_name1']) ? $options['vip_name1'] : '月度VIP';
$vip_name2 = isset($options['vip_name2']) ? $options['vip_name2'] : '年度VIP';
$vip_rmb1 = isset($options['vip_rmb1']) ? $options['vip_rmb1'] : '29';
$vip_rmb2 = isset($options['vip_rmb2']) ? $options['vip_rmb2'] : '199';
$vip_time1 = isset($options['vip_time1']) ? $options['vip_time1'] : '月';
$vip_time2 = isset($options['vip_time2']) ? $options['vip_time2'] : '年';
$vip_rs1 = isset($options['vip_rs1']) ? $options['vip_rs1'] : '0';
$vip_rs2 = isset($options['vip_rs2']) ? $options['vip_rs2'] : '0';


if (!($xuwbk_vip === true || $xuwbk_vip === '1' || $xuwbk_vip === 1)) {
    // 如果开关关闭，显示空白页（保留头部和尾部）
    get_header();
    echo '<main class="container"><div class="content-wrap"><div class="content-layout xy-article"><div class="nopw-sm box-body theme-box radius8 main-bg main-shadow"></div></div></div></main>';
    get_footer();
    exit();
}

get_header();
?>
<main class="container">
<div class="wp-posts-content">
                        <div class="my-membership-content">
 <div class="beijing" style="background: url('<?php echo get_stylesheet_directory_uri(); ?>/assets/images/page/vip/bg.jpg') center top no-repeat;border-radius: 15px;">
  <div class="count-qb">
  <div class="my-membership-title"><img decoding="async" class="my-membership-img" style="width: 100%;height: 65px;margin-bottom: 15px;" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/page/vip/jitheme_vip_v.png" ></div>
  
  
  
  <div class="pcvip">
    <div class="Onecad_vip_privilege_list">
  <span class="my-vipbt"><i class="fa fa-bolt" aria-hidden="true"></i>专线客服</span>
  <span class="my-vipbt"><i class="fa fa-graduation-cap" aria-hidden="true"></i>无限阅读</span>
  <span class="my-vipbt"><i class="fa fa-thumbs-up" aria-hidden="true"></i>免费指导</span>
  <span class="my-vipbt"><i class="fa fa-star" aria-hidden="true"></i>特价商品</span>
  </div>
  </div>
  
  
  <div class="wapvip">
  <div class="my-hyjhjieshao">成为我们的VIP会员，尊享无限免费下载使用</div>
  </div>
                                       
<div class="vip-list">
		<div class="vip-item" style="background-image: url('<?php echo get_stylesheet_directory_uri(); ?>/assets/images/page/vip/huangzuan.png');box-shadow: 0 12px 36px -12px #d0a360;margin-left: 20px;">
			<p class="txt_tag"><span><b id="hd">加入<?php echo $vip_rs1;?>人</b></span></p>
			<div class="vip-list-in">
				<div class="tequan-type-head">
				<h2 class="xb-vip-1"><?php echo $vip_name1;?></h2>
				<p class="xb-vip-ms" style="margin-bottom: auto;">会员折扣商品</p>
				<p class="xb-vip-ms" style="margin-bottom: auto;">平台客服优先接入</p>
				</div>
				<div class="type2">
				<div class="vip-price">
				<div class="vip-price-money shu">￥<span><?php echo $vip_rmb1;?></span><sub>元</sub></div>
				<div class="vip-price-day shu">/<?php echo $vip_time1;?></div>
				</div>
				<div class="xb-vip-buy"><button><a href="javascript:;" data-plan="vip_1" class="float-btn pay-vip my-custom-class-name">立刻加入</a></button></div>
				</div>
			</div>
		</div>
<div class="vip-item" style="background-image: url('<?php echo get_stylesheet_directory_uri(); ?>/assets/images/page/vip/heizuan.png');box-shadow: 0 12px 36px -12px #d0a360;margin-left: 20px; ">
			<p class="txt_tag"><span><b id="hd">加入<?php echo $vip_rs2;?>人</b></span></p>
			<div class="vip-list-in">
				<div class="tequan-type-head">
				<h2 class="xb-vip-1"><?php echo $vip_name2;?></h2>
				<p class="xb-vip-ms" style="margin-bottom: auto;">会员专属商品</p>
				<p class="xb-vip-ms" style="margin-bottom: auto;">贵宾客服极速响应</p>
				</div>
				<div class="type2">
				<div class="vip-price">
				<div class="vip-price-money shu">￥<span><?php echo $vip_rmb2;?></span><sub style="background: #4b4b4b;">元</sub></div>
				<div class="vip-price-day shu">/<?php echo $vip_time2;?></div>
				</div>
				<div class="xb-vip-buy"><button><a href="javascript:;" data-plan="vip_1" class="float-btn pay-vip my-custom-class-name">立刻加入</a></button></div>
				</div>
			</div>
		</div>
  </div>
<div class="vip-count" style="display:none;">
  <div class="vip-count-bk">
  <div class="vip-count-xx"><span></span>
  <img decoding="async" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/page/vip/huiyuan1d.svg" alt="会员一的大图标" style="height: 80px;width: 80px;margin-bottom: 15px;" class="lazyload" imgbox-index="1">
  <span class="vip-count-x1"><?php echo $vip_name1;?></span><span class="vip-count-span"><b>已加入</b><?php echo $vip_rs1;?><b>人</b></span></div>
  
  <div class="vip-count-xx" <div=""><span></span>
  <img decoding="async" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/page/vip/huiyuan2d.svg" alt="会员二的大图标" style="height: 80px;width: 80px;margin-bottom: 15px;" class="lazyload" imgbox-index="2">
  <span class="vip-count-x2"><?php echo $vip_name2;?></span><span class="vip-count-span"><b>已加入</b><?php echo $vip_rs2;?><b>人</b></span></div>
  </div>
  </div>
  
  <div class="newvip-session newvip-sess2" >
	<div class="warr">
		<h2 class="new-sess-head"><span class="nuw-vip-hx">VIP会员核心特权</span>
			<ul class="newvip-nr" style="list-style-type: none;padding-left: unset;margin: auto;pointer-events: none;">
			<li class="newvip-li"><img decoding="async" style="width: 4em;height: 4em;" class="icon2" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/page/vip/zhekou.svg"  alt="折扣商品图标"><p>尊享折扣商品</p></li>
			<li class="newvip-li"><img decoding="async" style="width: 4em;height: 4em;" class="icon2" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/page/vip/shenhe.svg"  alt="审核图标"><p>文章优先审核</p></li>
			<li class="newvip-li"><img decoding="async" style="width: 4em;height: 4em;" class="icon2" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/page/vip/hezuo.svg"  alt="合作图标"><p>官方优先合作</p></li>
			<li class="newvip-li"><img decoding="async" style="width: 4em;height: 4em;" class="icon2" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/page/vip/xianxia.svg"  alt="线下图标"><p>线下内测资格</p></li>
			<li class="newvip-li"><img decoding="async" style="width: 4em;height: 4em;" class="icon2" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/page/vip/vipsp.svg"  alt="VIP商品图标"><p>VIP专属商品</p></li>
			<li class="newvip-li"><img decoding="async" style="width: 4em;height: 4em;" class="icon2" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/page/vip/huiyuan.svg"  alt="会员图标"><p>荣耀标识</p></li>
			<li class="newvip-li"><img decoding="async" style="width: 4em;height: 4em;" class="icon2" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/page/vip/kefu.svg"  alt="客服图标"><p>VIP专属客服</p></li>
			<li class="newvip-li"><img decoding="async" style="width: 4em;height: 4em;" class="icon2" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/page/vip/gengduo.svg"  alt="更多图标"><p>更多特权上线中</p></li>
			</ul>
	</div>
</div>

<div class="ceo_vip_ping" >
	<h2 class="new-sess-head">
	<span class="nuw-vip-hx">会员好评展示</span>
	</h2>
	<ul style="list-style-type: none;">
	<li class="newvip-hy"><div class="deanmbavar"><img decoding="async" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/page/vip/touxiang.png"><span></span></div>
		<div class="deanmbusename">用户39811858<b>VIP</b></div></br>
		<p><b>"</b>感谢<?php echo get_bloginfo('name');?>，能够让我学到子比主题的美化教程，我学了很多教程！<b>"</b></p>
	</li>
	<li class="newvip-hy"><div class="deanmbavar"><img decoding="async" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/page/vip/100.png"><span></span></div>
		<div class="deanmbusename">傲绝<b>VIP</b></div></br>
		<p><b>"</b>成功加入<?php echo get_bloginfo('name');?>永久会员，下载了很多好东西，还实现了很多有趣的项目。会员非常值。<b>"</b></p>
	</li>
	<li class="newvip-hy"><div class="deanmbavar"><img decoding="async" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/page/vip/th.jpg"><span></span></div>
		<div class="deanmbusename">ios小萝卜<b>VIP</b></div></br>
		<p><b>"</b><?php echo get_bloginfo('name');?>他手把手教我把网站给建了起来，还帮我配置了防火墙，体验是真的很棒。<b>"</b></p>
	</li>
	</ul>
</div>

  </div>
  </div>
  </div>
  </div>
  </div>
  
<style>
  @media (min-width: 1240px){
.container {
max-width: none;
width: auto;
}
}
.article {
  padding: unset;
  overflow: hidden;
}

</style>                  
    </div>

    </main>

<!--=========================================================================-->
                
                <br><center style="font-size: 13px; color:#999;">会员权益试用阶段，最终解释权归<?php echo get_bloginfo('name');?>所有！</center><br>
            </main>
        </div>
    </div>
</div>
<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/assets/css/page_vips.css" type="text/css">
<?php
get_footer();
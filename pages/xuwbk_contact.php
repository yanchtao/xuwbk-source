<?php
/**
 * Template Name: mnpc-联系我们页面
 * Description: 梦楠联系我们页面
 */
$options = get_option('XuWbk');

// 获取联系我们页面配置
$contact_page = isset($options['contact-page']) ? $options['contact-page'] : false;
$wechat_qr = 'https://www.mnpc.net/wp-content/uploads/2025/10/20251001212516859-20251001113545516.webp'; // 默认值
if (isset($options['contact-wechat-qr'])) {
    $qr_data = $options['contact-wechat-qr'];
    if (is_array($qr_data)) {
        // 如果是数组，尝试获取URL
        if (isset($qr_data['url'])) {
            $wechat_qr = $qr_data['url'];
        } elseif (isset($qr_data[0])) {
            $wechat_qr = $qr_data[0];
        } elseif (isset($qr_data['id'])) {
            // 如果有ID，尝试获取图片URL
            $image_url = wp_get_attachment_url($qr_data['id']);
            if ($image_url) {
                $wechat_qr = $image_url;
            }
        }
    } elseif (is_string($qr_data) && !empty($qr_data)) {
        // 如果是字符串且不为空
        $wechat_qr = $qr_data;
    }
}
$wechat_name = isset($options['contact-wechat-name']) ? (is_array($options['contact-wechat-name']) ? (isset($options['contact-wechat-name'][0]) ? $options['contact-wechat-name'][0] : '梦楠') : $options['contact-wechat-name']) : '梦楠';
$wechat_desc = isset($options['contact-wechat-desc']) ? (is_array($options['contact-wechat-desc']) ? (isset($options['contact-wechat-desc'][0]) ? $options['contact-wechat-desc'][0] : '热点事实早知道') : $options['contact-wechat-desc']) : '热点事实早知道';
$qq_number = isset($options['contact-qq-number']) ? (is_array($options['contact-qq-number']) ? (isset($options['contact-qq-number'][0]) ? $options['contact-qq-number'][0] : '') : $options['contact-qq-number']) : '';
$qq_link = isset($options['contact-qq-link']) ? (is_array($options['contact-qq-link']) ? (isset($options['contact-qq-link'][0]) ? $options['contact-qq-link'][0] : '#') : $options['contact-qq-link']) : '#';
$qq_button_text = isset($options['contact-qq-button-text']) ? (is_array($options['contact-qq-button-text']) ? (isset($options['contact-qq-button-text'][0]) ? $options['contact-qq-button-text'][0] : '点击交谈') : $options['contact-qq-button-text']) : '点击交谈';


if (!($contact_page === true || $contact_page === '1' || $contact_page === 1)) {
    // 如果开关关闭，显示空白页
    get_header();
    echo '<main class="container"><div class="content-wrap"><div class="content-layout xy-article"><div class="nopw-sm box-body theme-box radius8 main-bg main-shadow"></div></div></div></main>';
    get_footer();
    exit();
}
get_header();
?>
<style>
.about-contact{background:var(--main-bg-color)}.about-contact-item{text-align:center}.about-contact-item .ac-subtitle,.about-contact-item .ac-title{font-weight:400;font-size:14px;line-height:1.66666667em;color:var(--key-color);margin-top:0;margin-bottom:0}.about-contact-item .ac-title .clr_orange{color:var(--theme-color);font-style:normal}.about-contact-item .ac-title .clr_blue{color:var(--theme-color);font-style:normal}.about-contact-item .ac-subtitle{color:#626F86}.about-contact-item .ac-ewm{-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;margin:0 auto;padding:.71428571em}.about-contact-item .ac-ewm img{display:block}.about-contact-item .ac-qq{-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;margin:0 auto;padding:1.07142857em;-moz-border-radius:100%}.about-contact-item .ac-qq img{display:block;margin:0 auto}.about-contact-item .qq-btns{margin-top:1.07142857em}.about-contact-item .btn{padding:0.2em 2.28571429em;line-height:2.28571429em}.about-contact-item .btns{margin-top:1.42857143em;margin-bottom:5px}.about-section-title{position:relative;margin-bottom:.71428571em;text-align:center;font-weight:400;font-size:inherit}.about-section-title .tt{position:relative;font-style:normal}.about-section-title .txt{position:relative;font-weight:700;font-size:220%;line-height:1.28571429em}.about-section-subtitle{color:#626F86;text-align:center;font-weight:400;font-size:inherit;line-height:1.28571429em}.mini_about .btn-blue{background-color:var(--theme-color);color:#fff}.mini_about .btn-blue:hover{background-color:#124cd2}.about-contact{-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;padding:2.14285714em 1.42857143em;width:22.85714286em;-webkit-box-shadow:0 1px 40px rgba(0,0,0,.08);box-shadow:0 1px 40px rgba(0,0,0,.08);border-radius:var(--main-radius)}.about-contact .c-header{margin-bottom:1.07142857em}.about-contact .c-header-in{position:relative;display:flex;flex-wrap:wrap;align-items:center}.about-calture .about-section-subtitle{margin-bottom:1.42857143em}.about-contact .c-avatar{position:absolute;top:0;left:0;width:3em}.about-contact .c-avatar .g-avatar{background-color:#dcdde1}.about-contact .c-title{margin-bottom:.375em;font-size:157.14285714%;line-height:1.25em;margin-top:0}.about-contact .c-entry{color:#626F86;font-size:85.71428571%;line-height:1.5em;margin:0}.about-contact .c-menus{margin-bottom:1.07142857em}.about-contact .c-menu{display:block;padding:.57142857em 0;width:50%;text-align:center;cursor:pointer;background:rgba(200,200,200,0.4)}.about-contact .c-menu.active{position:relative;background:var(--theme-color);color:#fff;cursor:default}.about-contact .c-menu.active::after{position:absolute;top:100%;left:50%;display:block;border:.41666667em solid transparent;content:'';-webkit-transform:translate(-50%,0);transform:translate(-50%,0);border-top-color:var(--theme-color);-ms-transform:translate(-50%,0)}.about-contact .c-menu .jitheme{margin-right:5px;padding:3px;-webkit-border-radius:100%;-moz-border-radius:100%;border-radius:100%;background-color:#fff;color:#626F86}.about-contact .c-menu.active .jitheme{color:var(--theme-color)!important}.about-contact .c-toggles{height:17.14285714em}.about-contact .ac-ewm{width:14.28571429em;height:14.28571429em}.about-contact .ac-qq{width:5.71428571em}.about-contact .about-contact-qq{-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;padding:0 2.14285714em 2.14285714em}.about-header{position:relative;margin-top:-20px;background-color:#8ddaff}.about-header .bg-img{position:absolute;top:0;right:0;bottom:0;left:0}.about-header .bg-img .wrapper{height:100%}.about-header .bg-img .img{display:block;width:100%;height:100%;background-position:right center;background-size:auto 100%;background-repeat:no-repeat;-webkit-background-size:auto 100%}.about-header .main-container{padding:2.71428571em 0}.wrapper{width:1400px;max-width:100%;margin:0 auto}.about-honour{position:relative;z-index:2;background-color:var(--main-bg-color);-webkit-box-shadow:0 10px 60px rgba(14,79,209,.06);box-shadow:0 10px 60px rgba(14,79,209,.06)}.about-contactus{margin-bottom:-30px;padding:3.57142857em 0;background-color:#2a65ed}.about-contactus .c-items{margin:0 -1.14285714em}.about-contactus .c-item{padding:0 1.14285714em}.about-contactus .intro-wrap .c-box{background:var(--main-bg-color);background-position:right top;background-size:cover;background-repeat:no-repeat;-webkit-background-size:cover;-webkit-backdrop-filter:blur(20px);backdrop-filter:blur(20px)}.about-contactus .intro{position:relative;-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;padding:3.21428571em 1.78571429em 1.78571429em 46%;height:100%;box-shadow:0 0 10px var(--main-shadow)}.about-contactus .intro .item-thumb{position:absolute;bottom:0;left:0;width:42%}.about-contactus .intro .item-thumb .thumb{background-repeat:no-repeat;display:block;padding-top:106.84210526%;background-position:left bottom;background-size:contain;-webkit-background-size:contain;width:170px;position:relative;border-radius:20%}.about-contactus .intro .item-thumb .hi{position:absolute;top:3.5em;right:1.5em;width:2.85714286em;height:2.85714286em;-webkit-border-radius:100%;-moz-border-radius:100%;border-radius:100%;background-color:var(--theme-color);color:#fff;text-align:center;font-weight:700;font-size:200%;line-height:2.85714286em}.about-contactus .intro .item-thumb .hi::before{position:absolute;bottom:2%;left:-5%;display:block;border:.64285714em solid transparent;content:'';-webkit-transform:rotate(160deg);transform:rotate(160deg);border-top-color:var(--theme-color);-ms-transform:rotate(160deg)}.about-contactus .intro .item-title{margin-bottom:.83333333em;font-weight:700;font-size:257.14285714%;color:var(--key-color)}.about-contactus .intro .item-desc{margin-bottom:3.125em;color:#626F86;font-size:114.28571429%;line-height:1.4375em}.about-contactus .intro .item-list ul{overflow:hidden}.about-contactus .intro .item-list li{float:left;margin-bottom:1em;width:50%;font-size:114.28571429%;line-height:1.25em;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}.about-contactus .intro .item-list li .ico{display:inline-block;margin-top:.25em;width:1.16666667em;height:1.16666667em;color:#2cc87d;vertical-align:top;font-size:75%;line-height:1.16666667em}.about-contactus .contact-wrap .c-box{background:var(--main-bg-color);padding:1.78571429em;box-shadow:0 0 10px var(--main-shadow)}.about-contactus .ct-items{margin:0 -1.07142857em}.about-contactus .ct-item{padding:0 1.07142857em}.about-contactus .ct-box{background:rgba(116,116,116,0.08);padding:1.07142857em;box-shadow:0 0 10px var(--main-shadow)}.about-contactus .ct-title{margin-bottom:.83333333em;color:var(--theme-color);text-align:center;font-weight:700;font-size:108.57142857%;line-height:1.11111111em}.about-contactus .ac-ewm,.about-contactus .ac-qq{margin:0 auto 1.07142857em;padding:.71428571em;width:10.71428571em;height:10.71428571em}.about-contactus .ac-qq{display:-webkit-box;display:-webkit-flex;display:-moz-box;display:-ms-flexbox;display:flex;-webkit-box-align:center;-webkit-align-items:center;-moz-box-align:center;-ms-flex-align:center;align-items:center;-webkit-box-pack:center;-webkit-justify-content:center;-moz-box-pack:center;-ms-flex-pack:center;justify-content:center;-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;-moz-box-orient:vertical;-moz-box-direction:normal;-ms-flex-direction:column;flex-direction:column}.about-contactus .ac-qq img{display:block;width:3.14285714em}.about-contactus .ac-qq .btn{padding:0.2em 1.57142857em;line-height:2.28571429em}.about-selection{padding:7.14285714em 0 0}.about-section-subtitle{margin-bottom:2.85714286em}.about-selection .details{margin-bottom:2.14285714em}.about-selection .icons{margin-bottom:2.5em}.Jitheme_about_main .about-selection .d-items{margin:0 5px 5px 0}.about-selection .i-item{padding:0 5px}.Jitheme_about_main .about-selection .d-item{margin-right:15px;margin-bottom:15px}.about-selection .detail{display:block;padding:1.14285714em;background-color:#fff;-webkit-box-shadow:0 1px 5px rgba(8,114,246,.04);box-shadow:0 1px 5px rgba(8,114,246,.04)}.about-selection .detail-in{position:relative;overflow:hidden;padding-left:5em}.about-selection .detail-in .item-thumb{position:absolute;top:0;left:0;overflow:hidden;width:4.28571429em}.about-selection .detail-in .item-thumb .thumb{padding-top:100%;border-radius:10px}.about-selection .detail-in .item-title{margin-bottom:.2em;font-size:114.28571429%;line-height:1.25em}.about-selection .detail-in .item-desc{color:#626F86;font-size:85.71428571%;line-height:1.5}.about-selection .i-item{width:6.25%}.about-selection .icon{overflow:hidden}.about-selection .icon .item-thumb{width:100%}.about-selection .icon .thumb{padding-top:100%}.about-calture{padding:3.71428571em 0}.about-calture .about-section-subtitle{margin-bottom:3.57142857em}.about-calture .item-label{margin-bottom:1.07142857em;font-weight:400;font-size:inherit}.about-calture .item-label .label{position:relative;display:inline-block;padding:0 .875em;background-color:var(--theme-color);color:#fff;font-style:normal;font-weight:700;font-size:114.28571429%;line-height:2.5em}.about-calture .item-title{text-align:left;margin-bottom:.25em;font-size:228.57142857%;line-height:1.40625em;color:var(--key-color)}.about-calture .item-desc{color:#626F86;font-weight:400;font-size:inherit;line-height:1.28571429em}.about-calture .item-thumb{position:absolute;top:50%;right:0;width:7.14285714em;-webkit-transform:translate(0,-50%);transform:translate(0,-50%);-ms-transform:translate(0,-50%)}.about-calture .item-thumb .thumb{display:block;width:100%;height:0;background-position:center;background-size:cover;background-repeat:no-repeat;padding-top:100%}.about-calture .c1,.about-calture .c2{overflow:hidden;background:var(--main-bg-color);box-shadow:0 0 10px var(--main-shadow)}.about-calture .c1{padding:3.21428571em 2.14285714em 2.14285714em;background-color:#2a65ed;background-image:url(../images/culture-bg.png);background-position:right top;background-size:contain;background-repeat:no-repeat;-webkit-background-size:contain}.about-calture .c1 .label{background-color:#fff;color:var(--theme-color)}.about-calture .c1 .item-desc,.about-calture .c1 .item-title{color:#fff}.about-calture .c1 .item-desc{margin-bottom:1.285714em}.about-calture .c2{margin-bottom:2.14285714em;padding:2.214286em 2.85714286em;border-radius:var(--main-radius)}.about-calture .c2:last-child{margin-bottom:0}.item-label .label:before{display:none}.about-calture .c2 .c-in{position:relative;padding-right:7.14285714em}.about-calture .c1-items{padding:2.5em 0;background:var(--main-bg-color);border-radius:var(--main-radius)}.about-calture .aa-in{text-align:center}.about-calture .it-thumb{margin:0 auto 1em;width:5em}.about-calture .it-thumb .thumb{display:block;width:100%;height:0;background-position:center;background-size:cover;background-repeat:no-repeat;padding-top:100%}.about-calture .it-title{margin-bottom:.3em;font-weight:700;font-size:18px;line-height:1.3em}.about-calture .it-desc{color:#626F86;font-weight:400}.flex .f-box{display:block;width:100%;height:100%;border-radius:var(--main-radius)}.g-thumb{display:block;border-radius:100%;width:100%;height:0;background-position:center;background-size:cover;background-repeat:no-repeat;-webkit-transition:all .2s;-o-transition:all .2s;transition:all .2s;-webkit-transform:rotate(0);transform:rotate(0);-webkit-background-size:cover;-ms-transform:rotate(0)}.g-avatar{display:block;-webkit-border-radius:100%;-moz-border-radius:100%;-webkit-transform:rotate(0);transform:rotate(0);-ms-transform:rotate(0)}.g-avatar .g-thumb{padding-top:100%}.about-header .wrapper{position:relative}.sm\:f-2>.f-item{width:50%}.flex.lg\:f-2{gap:30px}.btn-blue{border-color:var(--theme-color);background:var(--theme-color);color:#fff}.about-serve .boxlist{display:flex;margin-right:5px;padding:1rem 0;justify-content:space-around;align-items:center}.about-serve .boxlist .serve_box{margin:0 15px 15px 0;padding:1em;width:calc(25% - 15px);background-color:#fff;text-align:center}.about-serve .boxlist .serve_box .icon{display:block;margin:0 auto;padding:.2rem;width:4rem;border-radius:50%;background-color:#edf5ff}.about-serve .boxlist .serve_box i{display:none}.about-serve .boxlist .serve_box h4{margin:.85rem auto .6rem;font-weight:500;font-size:1.2rem;font-family:PingFangSC-Medium;line-height:1.4rem}.about-serve .boxlist .serve_box p{color:#626F86;font-size:.9rem;line-height:25px}.about-serve .boxlist .serve_box i{display:none}.quk_img{width:50px;height:50px;margin-top:5px;padding:0px;margin-right:16px}#Mini-quku .b-header .b-title{margin-bottom:.3em;font-weight:600;font-size:20px;line-height:1.5;margin:0}#Mini-quku .b-header{padding-right:3em;-webkit-flex-shrink:0;-ms-flex-negative:0;flex-shrink:0;-webkit-box-flex:0;-webkit-flex-grow:0;-moz-box-flex:0;-ms-flex-positive:0;flex-grow:0;text-align:center;max-width:300px}#Mini-quku .jitheme_qk{display:flex;width:100%;align-items:center;margin-top:0;padding-left:0}#Mini-quku .ji-qk-item{padding-left:20px}#Mini-quku .jitheme-config-desc{overflow:hidden;text-overflow:ellipsis;white-space:nowrap}#Mini-quku .jitheme-config>li{overflow:hidden;width:100%;height:100%;cursor:pointer}#Mini-quku .jitheme-config{width:100%}.insert-post-content{position:relative;z-index:1;display:flex;padding-left:16px;height:100px;flex:1;flex-direction:column;justify-content:space-between}.jitheme_tips.vip{background-image:linear-gradient(90deg,#fee2b6,#fec86e);color:#a26b0f}.tips_text{z-index:1;display:inline-block;margin:0 auto;border-radius:0;color:#fff;text-align:center;font-style:normal;font-size:13px}.jitheme_qk{position:relative;z-index:1;padding:15px;width:100%;background:var(--main-bg-color);transition:opacity .15s linear}.jitheme-config{display:-webkit-box;display:-ms-flexbox;display:flex;-webkit-box-pack:justify;-ms-flex-pack:justify;justify-content:space-between}.jitheme-config>li{overflow:hidden;width:100%;height:100%;cursor:pointer}.ji-qk-item:hover .jitheme-config-title{color:var(--theme-color)}.jitheme-config-desc{float:left;color:#626F86;font-size:14px;line-height:22px}.ji-qk-item{display:flex;height:100%}.jitheme_qk .item-ico{margin-right:15px}.item-ico i{font-size:35px;line-height:40px}.ji-qk-item a{color:var(--key-color)}.jitheme-config-title{font-weight:600;font-size:18px;-webkit-transition:.3s;transition:.3s}.jitheme-config-title .go{position:absolute;margin-top:7px;margin-left:5px;padding:2px 5px;border-radius:4px;color:var(--theme-color);vertical-align:middle;text-transform:uppercase;font-weight:400;font-size:13px;line-height:15px;cursor:pointer;-webkit-transform:scale(.9);transform:scale(.9);-ms-transform:scale(.9)}.ji-qk-item:hover .item-ico{background-color:var(--theme-color);-webkit-transition:.3s;transition:.3s}.ji-qk-item:hover .item-ico i{color:#fff;-webkit-transition:.3s;transition:.3s}.jitheme_tips{position:absolute;top:0;left:0;padding:0 10px}.about-section-title .txt::before{content:'';display:block;position:absolute;left:0;right:0;bottom:10%;top:70%;background-color:rgba(255,255,0,0.7)}@media (min-width:1920px){.about-contactus .intro{padding-left:50%}.about-contactus .intro .item-thumb{width:47%}}@media (min-width:1330px) and (max-width:1679.5px){.about-contactus .intro .item-title{font-size:30px}}@media (min-width:1330px){.lg\:f-4>.f-item{width:calc(25% - 15px)}.lg\:f-2>.f-item{width:50%}}@media (min-width:1024px) and (max-width:1329.5px){.about-selection .detail-in{padding-left:5em}.about-selection .detail-in .item-thumb{width:3.28571429em}.about-contactus .intro{padding:3.21428571em}}@media (min-width:1024px){.f-3>.f-item{width:33.33333333%}.about-contactus .f-2>.f-item{width:50%}#Mini-quku .ji-qk-item{padding-left:0}#Mini-quku .jitheme_qk,.jitheme-config{gap:10px}}@media (max-width:1024px){.wrapper{width:97%}.about-contactus .intro .item-thumb{display:none}#Mini-quku .b-header{padding-right:0}}@media screen and (max-width:768px){.about-header{margin-top:0}.Jitheme_about_main .about-honour{display:none}.Jitheme_about_main .about-selection{padding:.85714286em 0 2.14285714em}.Jitheme_about_main .about-selection .about-section-subtitle{margin-bottom:1.42857143em}.Jitheme_about_main .about-selection .icons{display:none}.Jitheme_about_main .about-selection .d-item{width:calc(50% - var(--ji--1item))}.Jitheme_about_main .about-selection .detail{padding:1.42857143em .71428571em}.Jitheme_about_main .about-selection .detail-in{padding-left:0}.Jitheme_about_main .about-selection .detail-in .item-thumb{position:relative;top:auto;left:auto;margin:0 auto .71428571em;width:3.57142857em}.Jitheme_about_main .about-selection .detail-in .item-title{margin-bottom:.42857143em;text-align:center;font-size:inherit}.Jitheme_about_main .about-selection .detail-in .item-desc{color:#9a9a9a;text-align:center;font-size:85.71428571%}.Jitheme_about_main .about-contact{width:calc(100% - 1em);margin:0 .5em}.Jitheme_about_main .about-header .main-container{padding:.5em 0}.Jitheme_about_main .about-calture{padding:2.85714286em 0}.Jitheme_about_main .about-section .about-section-title .txt{font-size:164.28571429%}.Jitheme_about_main .about-section-title .txt::before{position:absolute;top:60%;right:0;bottom:0;left:0;display:block;background-color:rgba(255,255,0,.7);content:''}.Jitheme_about_main .about-section .about-section-subtitle{font-size:85.71428571%}.about-calture .about-section-subtitle,.about-section-subtitle{margin-bottom:1.42857143em}.about-calture .aa-item{padding:0 .35714286em;width:33.33333%}.about-calture .it-thumb{width:3.57142857em;margin:0 auto .71428571em}.about-calture .it-desc{font-size:85.71428571%}.about-calture .it-title{font-size:inherit}.about-calture .c1-items{padding:.71428571em}.about-calture .a-item,.about-calture .c2{margin-bottom:.71428571em}.about-calture .c2{padding:1.42857143em 1.07142857em}.about-calture .c1,.about-calture .c1-items,.about-calture .c2{-webkit-border-radius:.57142857em;-moz-border-radius:.57142857em;border-radius:.57142857em}.about-calture .item-label{margin-bottom:.71428571em}.about-calture .item-title{font-size:100%}.about-calture .item-desc{font-size:85.71428571%;margin-bottom:0}.about-selection .detail{padding:1.42857143em .71428571em;-webkit-border-radius:.57142857em;-moz-border-radius:.57142857em;border-radius:.57142857em}.about-selection .detail-in .item-thumb{position:relative;left:auto;top:auto;width:3.57142857em;-webkit-border-radius:.57142857em;-moz-border-radius:.57142857em;border-radius:.57142857em;margin:0 auto .71428571em}.about-calture .c1,.about-calture .c1-items,.about-calture .c2{-webkit-border-radius:.57142857em;-moz-border-radius:.57142857em;border-radius:.57142857em}.about-calture .c1{padding:1.5em}.about-serve .boxlist .serve_box{width:calc(50% - 20px);float:left}.sm\:f-2>.f-item{width:48%}.about-contactus .intro{padding:2em 5%;height:100%}.about-contactus .intro .item-list li{width:inherit;font-size:inherit}.about-contactus .contact-wrap .c-box{padding:1em}.about-contactus .ct-box{padding:5px}.about-contactus .c-item,.about-contactus .ct-item{padding:0}.about-contactus .c-items{margin:0}.flex{display:flex;justify-content:space-around}#Mini-quku .b-header{display:none}.about-header{background-color:#bce9ff}}@media (max-width:425px){.wrapper{width:95%!important}.quk_img,.about-honour,.intro-wrap.c-item.f-item{display:none}#Mini-quku .ji-qk-item{padding-left:0}#Mini-quku .jitheme-config-desc{max-width:95px}.jitheme_qk{padding:15px 0}.about-calture .a-item{padding:0}.sm\:f-2>.f-item{width:100%}.flex{flex-wrap:wrap}.about-header{background-color:#c1ebff}.about-contactus .ct-item{max-width:160px}}
</style>
<div class="about-header">
  <div class="bg-img">
    <div class="wrapper">
      <i class="img" style="background-image: url('https://img.alicdn.com/imgextra/i1/2210123621994/O1CN01vSUlJK1QbIpI9XYou_!!2210123621994.webp');"></i>
    </div>
  </div>
  <div class="wrapper main-container">
    <div class="about-contact b2-radius">
      <div class="contact-in">
        <div class="c-header">
          <div class="c-header-in">
            <div class="user-avatar">
              <a href="/author/1">
                <span class="avatar-img avatar-lg">
                  <img alt="梦楠的头像-梦楠" src="/favicon.ico" data-src="/favicon.ico" class="lazyload avatar avatar-id-1">
                  <img class="lazyload avatar-badge" src="https://www.mnpc.net/wp-content/uploads/2025/10/20251003160555240-Vip-copy.webp"
                    data-src="https://www.mnpc.net/wp-content/uploads/2025/10/20251003160555240-Vip-copy.webp"
                    data-toggle="tooltip" title="黑钻会员" alt="黑钻会员">
                </span>
              </a>
            </div>
            <div style="flex: 1;padding-left: 1em;">
              <h1 class="c-title">联系我们</h1>
              <p class="c-entry">Hi~合作洽谈、广告投放、反馈建议、任何问题均可联系。</p>
            </div>
          </div>
        </div>
        <!-- 下面微信QQ切换内容 -->
        <div class="c-menus flex f-items f-2 b2-radius">
          <div class="c-menu b2-radius active" data-target="wechat"><div class="txt">站长微信</div></div>
          <div class="c-menu b2-radius" data-target="qq"><div class="txt">客服QQ</div></div>
        </div>
        <div class="c-toggles">
          <div class="c-toggle toggle-wechat" style="display:block">
            <div class="about-contact-wechat about-contact-item b2-radius">
              <div class="ac-ewm">
                <img src="<?php echo esc_url($wechat_qr); ?>"  alt="微信二维码">
              </div>
              <h3 class="ac-title">
                微信公众号：<em class="clr_orange"><?php echo esc_html($wechat_name); ?></em>
              </h3>
              <?php echo esc_html($wechat_desc); ?>
            </div>
          </div>
          <div class="c-toggle toggle-qq" style="display:none">
            <div class="about-contact-qq about-contact-item b2-radius">
              <div class="ac-qq acqq-use-1 b2-radius">
                <img src="https://img.alicdn.com/imgextra/i4/2210123621994/O1CN01ktb3Xt1QbIpCVXsnD_!!2210123621994.png" alt="QQ图标">
              </div>
              <h3 class="ac-title">
                QQ：<em class="clr_blue"><?php echo !empty($qq_number) ? esc_html($qq_number) : '暂未公布'; ?></em>
              </h3>
              <h4 class="ac-subtitle">商务号添加请务必说明来意</h4>
              <div class="btns">
                <a href="<?php echo esc_url($qq_link); ?>" class="btn btn-blue b2-radius"><?php echo esc_html($qq_button_text); ?></a>
              </div>
            </div>
          </div>
        </div>
        <!-- 结束 -->
      </div>
    </div>
  </div>
</div>

</div>
<div class="about-honour">
    <div class="wrapper">
        <div id="Mini-quku" class="html-box">
            <div class="box jitheme_qk b2-radius">
                <div class="b-header">
                    <h2 class="b-title">
                        打造具有影响力的网站
                    </h2>
                    <h4 class="b-desc">
                        让您更方便快捷地学到专业知识
                    </h4>
                </div>
                <ul class="jitheme-config">
                    <li>
                        <div class="ji-qk-item">
                            <div class="quk_img b2-radius">
                                <img class="sort-config-icon" alt="认证达人" 
 src="/wp-content/themes/XuWbk/assets/images/contact/bb1.svg">
                            </div>
                            <a href="/user/auth" target="_blank">
                                <p class="jitheme-config-title">
                                    认证达人
                                </p>
                                <span class="jitheme-config-desc">
                                    认证专属蓝V称号
                                </span>
                            </a>
                        </div>
                    </li>
                    <li>
                        <div class="ji-qk-item">
                            <div class="quk_img b2-radius">
                                <img class="sort-config-icon" alt="文档中心" 
 src="/wp-content/themes/XuWbk/assets/images/contact/bb3.svg">
                            </div>
                            <a href="/" target="_blank">
                                <p class="jitheme-config-title">
                                    文档中心
                                </p>
                                <span class="jitheme-config-desc">
                                    详细的使用指南
                                </span>
                            </a>
                        </div>
                    </li>
                    <li>
                        <div class="ji-qk-item">
                            <div class="quk_img b2-radius">
                                <img class="sort-config-icon" alt="问题反馈" 
 src="/wp-content/themes/XuWbk/assets/images/contact/bb4.svg">
                            </div>
                            <a href="/" target="_blank">
                                <p class="jitheme-config-title">
                                    问题反馈
                                </p>
                                <span class="jitheme-config-desc">
                                    提交工单解决问题
                                </span>
                            </a>
                        </div>
                    </li>
                    <li>
                        <div class="ji-qk-item">
                            <div class="quk_img b2-radius">
                                <img class="sort-config-icon" alt="加入会员" 
 src="/wp-content/themes/XuWbk/assets/images/contact/bb5.svg">
                            </div>
                            <a href="/vips" target="_blank">
                                <p class="jitheme-config-title">
                                    加入会员
                                </p>
                                <span class="jitheme-config-desc">
                                    享受尊贵的VIP服务
                                </span>
                            </a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<div class="about-calture about-section">
    <div class="wrapper">
        <h2 class="about-section-title">
            <strong class="txt">
                <i class="tt">
                    欢迎加入我们
                </i>
            </strong>
        </h2>
        <h3 class="about-section-subtitle">
            说出你的故事，分享网络的快乐吧
        </h3>
        <div class="section-content">
            <div class="flex lg:f-2">
                <div class="a-item f-item">
                    <div class="a-box f-box c1 b2-radius">
                        <div class="c-in">
                            <h4 class="item-label">
                                <i class="label b2-radius">
                                    网站愿景
                                </i>
                            </h4>
                            <h2 class="item-title">
                                成为具有影响力的网络资源平台
                            </h2>
                            <h3 class="item-desc">
                                为所有追求卓越的粉丝们，带来更全面的知识盛筵
                            </h3>
                            <div class="c1-items b2-radius">
                                <div class="aa-items f-items flex f-3">
                                    <div class="f-item aa-item">
                                        <div class="aa-box f-box">
                                            <div class="aa-in">
                                                <div class="it-thumb">
                                                    <i class="thumb" alt="创作之美" 
 style="background-image: url(/wp-content/themes/XuWbk/assets/images/contact/2024050703343933.png)">
                                                    </i>
                                                </div>
                                                <div class="it-title">
                                                    创作之美
                                                </div>
                                                <div class="it-desc">
                                                    为建设精品资源库
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="f-item aa-item">
                                        <div class="aa-box f-box">
                                            <div class="aa-in">
                                                <div class="it-thumb">
                                                    <i class="thumb" alt="成功之美" 
 style="background-image: url(/wp-content/themes/XuWbk/assets/images/contact/2024050703343778.png)">
                                                    </i>
                                                </div>
                                                <div class="it-title">
                                                    成功之美
                                                </div>
                                                <div class="it-desc">
                                                    共建专业爱好者领地
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="f-item aa-item">
                                        <div class="aa-box f-box">
                                            <div class="aa-in">
                                                <div class="it-thumb">
                                                    <i class="thumb" alt="增长之美" 
 style="background-image: url(/wp-content/themes/XuWbk/assets/images/contact/2024050703343814.png)">
                                                    </i>
                                                </div>
                                                <div class="it-title">
                                                    增长之美
                                                </div>
                                                <div class="it-desc">
                                                    最快找到需要的资源
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="a-item f-item">
                    <div class="a-box f-box a-box-2">
                        <div class="c2 b2-radius">
                            <div class="c-in">
                                <h4 class="item-label">
                                    <i class="label b2-radius">
                                        时代变迁
                                    </i>
                                </h4>
                                <h2 class="item-title">
                                    不断进步和革新
                                </h2>
                                <h3 class="item-desc">
                                    以崭新的面貌，让更多人理解本站的魅力所在，共建专业爱好者领地
                                </h3>
                                <div class="item-thumb">
                                    <i class="thumb" alt="不断进步和革新" 
 style="background-image: url(/wp-content/themes/XuWbk/assets/images/contact/2024050703343819.png)">
                                    </i>
                                </div>
                            </div>
                        </div>
                        <div class="c2 b2-radius">
                            <div class="c-in">
                                <h4 class="item-label">
                                    <i class="label b2-radius">
                                        创立之初
                                    </i>
                                </h4>
                                <h2 class="item-title">
                                    吸引同爱好粉丝
                                </h2>
                                <h3 class="item-desc">
                                    搜索更多专业知识分享给众多爱好者
                                </h3>
                                <div class="item-thumb">
                                    <i class="thumb" alt="吸引同爱好粉丝" 
 style="background-image: url(/wp-content/themes/XuWbk/assets/images/contact/2024050703343841.png)">
                                    </i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="vip-footer mobile-hidden">
    <div id="about_contactus" class="about-contactus">
        <div class="wrapper">
            <div class="c-items f-items flex sm:f-2">
                <div class="intro-wrap c-item f-item">
                    <div class="c-box f-box b2-radius">
                        <div class="intro">
                            <div class="item-thumb">
                                <i class="thumb" alt="联系我们" 
 style="background-image: url('https://img.alicdn.com/imgextra/i1/2210123621994/O1CN01QUg1t81QbIpJCQ23s_!!2210123621994.jpg')">
                                </i>
                                <i class="hi">
                                    Hi
                                </i>
                            </div>
                            <h3 class="item-title">
                                联系我们
                            </h3>
                            <p class="item-desc">
                                Hi~合作洽谈、广告投放、反馈建议、任何问题均可反馈。
                            </p>
                            <div class="item-list">
                                <ul>
                                    <li>
                                        <svg class="icon" aria-hidden="true">
                                            <use xlink:href="#icon-yunxu">
                                            </use>
                                        </svg>
                                        合作洽谈
                                    </li>
                                    <li>
                                        <svg class="icon" aria-hidden="true">
                                            <use xlink:href="#icon-yunxu">
                                            </use>
                                        </svg>
                                        广告投放
                                    </li>
                                    <li>
                                        <svg class="icon" aria-hidden="true">
                                            <use xlink:href="#icon-yunxu">
                                            </use>
                                        </svg>
                                        反馈建议
                                    </li>
                                    <li>
                                        <svg class="icon" aria-hidden="true">
                                            <use xlink:href="#icon-yunxu">
                                            </use>
                                        </svg>
                                        投稿求职
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="contact-wrap c-item f-item">
                    <div class="c-box f-box b2-radius">
                        <div class="contact">
                            <div class="ct-items f-items flex f-2">
                                <div class="ct-item f-item">
                                    <div class="ct-box f-box b2-radius">
                                        <h3 class="ct-title">
                                            站长微信
                                        </h3>
                                        <div class="about-contact-wechat about-contact-item">
                                            <div class="ac-ewm b2-radius">
                                                <img src="<?php echo esc_url($wechat_qr); ?>" 
 alt="微信二维码" loading="lazy">
                                            </div>
                                            <h3 class="ac-title">
                                                微信公众号：
                                                <em class="clr_orange">
                                                    <?php echo esc_html($wechat_name); ?>
                                                </em>
                                            </h3>
                                            <h4 class="ac-subtitle">
                                                <?php echo esc_html($wechat_desc); ?>
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="ct-item f-item">
                                    <div class="ct-box f-box b2-radius">
                                        <h3 class="ct-title">
                                            客服QQ
                                        </h3>
                                        <div class="about-contact-qq about-contact-item">
                                            <div class="ac-qq acqq-use-2 b2-radius">
                                                <img src="https://img.alicdn.com/imgextra/i4/2210123621994/O1CN01ktb3Xt1QbIpCVXsnD_!!2210123621994.png" 
 alt="QQ图标" loading="lazy">
                                                <div class="qq-btns">
                                                    <a href="<?php echo esc_url($qq_link); ?>" 
 target="_blank" class="btn btn-blue b2-radius">
                                                        <?php echo esc_html($qq_button_text); ?>
                                                    </a>
                                                </div>
                                            </div>
                                            <h3 class="ac-title">
                                                QQ：
                                                <em class="clr_blue">
                                                    <?php echo !empty($qq_number) ? esc_html($qq_number) : '暂未公布'; ?>
                                                </em>
                                            </h3>
                                            <h4 class="ac-subtitle">
                                                商务号添加请务必说明来意
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<footer class="footer">
    <div class="container-fluid container-footer">
        <ul class="list-inline">
            <li class="hidden-xs" style="max-width: 300px;">
            </li>
            <li style="max-width: 550px;">
                <div class="footer-contact mt10 hidden-xs">
                </div>
            </li>
            <li class="hidden-xs">
            </li>
        </ul>
    </div>
</footer>
<script>
  // 原页面的自定义 JS 交互也粘贴进来
  document.addEventListener('DOMContentLoaded', function () {
    // 获取DOM元素
    const cMenus = document.querySelectorAll('.c-menus .c-menu');
    const toggleWechat = document.querySelector('.toggle-wechat');
    const toggleQQ = document.querySelector('.toggle-qq');

    // 默认显示第一个c-menu，并显示toggle-wechat
    cMenus[0].classList.add('active');
    toggleWechat.style.display = 'block';

    // 监听c-menu的点击事件
    cMenus.forEach((cMenu, index) => {
      cMenu.addEventListener('click', () => {
        // 切换active类
        cMenus.forEach((cMenu) => cMenu.classList.remove('active'));
        cMenu.classList.add('active');

        // 切换toggle-wechat和toggle-qq的显示
        if (index === 0) {
          toggleWechat.style.display = 'block';
          toggleQQ.style.display = 'none';
        } else if (index === 1) {
          toggleWechat.style.display = 'none';
          toggleQQ.style.display = 'block';
        }
      });
    });
  });
</script>

<?php get_sidebar(); ?>
<?php comments_template('/template/comments.php', true); ?>
<?php get_footer(); ?>

<?php 
//文章版权声明7种样式
function copyright_style($content) {
    // 修改：不再在文章内容中显示版权声明，改为通过JS在操作按钮后显示
    // 直接返回原内容，版权声明将通过 copyright_after_post_actions 函数显示
    return $content;
}

// 修改版权声明显示位置 - 在点赞、收藏、分享按钮之后显示
add_filter('the_content', 'copyright_style');

// 在文章底部操作按钮后显示版权声明
function copyright_after_post_actions() {
    // 只在单篇文章页面显示版权声明
    if (!is_single()) {
        return;
    }
    
    $options = get_option('XuWbk');
    
    // 检查是否启用版权声明
    $copyright_enable = isset($options['copyright_enable']) ? $options['copyright_enable'] : false;
    if (!$copyright_enable) {
        return;
    }
    
    $selected_style = isset($options['copyright_style']) ? $options['copyright_style'] : 'style1';
    
    // 根据选择的样式显示对应的版权声明
    $copyright_content = '';
    switch ($selected_style) {
        case 'style1':
            $copyright_content = '
<div class="posts-copyright"><div class="entry-copyright px12">
        <span class="badg badg-sm mr3 c-yellow">1</span> 如果您喜欢本站，<a href="/" style="color:#a102ff">        <i class="wpcom-icon menu-item-icon">
                <svg aria-hidden="true"><use xlink:href="#icon-security-color"></use></svg>
            </i>点击这儿
      </a>赞助下本站，感谢支持！<br>
        <span class="badg badg-sm mr3 c-red">2</span> 可能会帮助到你：
        <a href="/" style="color:#00aeff" title="开发工具" target="_blank">
            <i class="wpcom-icon menu-item-icon">
                <svg aria-hidden="true"><use xlink:href="#icon-user-auth"></use></svg>
            </i> 开发工具
        </a> | 
        <a href="/" style="color:red" title="解压资源" target="_blank">
            <i class="wpcom-icon menu-item-icon">
                <svg aria-hidden="true"><use xlink:href="#icon-ontop-color"></use></svg>
            </i> 解压资源
        </a> | 
        <a href="/" style="color:#ffbe02" title="进站必看" target="_blank">
            <i class="wpcom-icon menu-item-icon">
                <svg aria-hidden="true"><use xlink:href="#icon-comment-color"></use></svg>
            </i> 进站必看
        </a><br>
      <span class="badg badg-sm mr3 c-purple">3</span> 如若转载，请注明文章出处：
        <script>var url = window.location.href;document.write(document.URL);</script><br>
        <span class="badg badg-sm mr3 c-blue">4</span> 本站内容观点不代表本站立场，并不代表本站赞同其观点和对其真实性负责<br>    
        <span class="badg badg-sm mr3 c-green">5</span> 若作商业用途，请联系原作者授权，若本站侵犯了您的权益请
        <a href="https://wpa.qq.com/msgrd?v=3&uin=1234567&site=qq&menu=yes" style="color:red" title="联系站长qq123456" target="_blank">
            <i class="wpcom-icon menu-item-icon">
                <svg aria-hidden="true"><use xlink:href="#icon-qq-color"></use></svg>
            </i> 联系站长
        </a> 进行删除处理
        <br>
      <span class="badg badg-sm mr3">6</span> 本站所有内容均来源于网络，仅供学习与参考，请勿商业运营，严禁从事违法、侵权等任何非法活动，否则后果自负<br>
    </div></div>';
            break;
            
        case 'style2':
            $copyright_content = '    <div class="single-bottom-html mg-b box b2-radius">
    <fieldset
        style="border: 8px dashed; background: #ffffff; padding: 10px; border-radius: 5px; line-height: 1.5em; color: #595959;"
        data-mce-style="border: 2px dashed; background: #ffffff; padding: 10px; border-radius: 5px; line-height: 1.5em; color: #595959;">
        <legend style="color: #ffffff; width: 30%; text-align: center; background-color: #e8b235; border-radius: 5px;"
            align="center"
            data-mce-style="color: #ffffff; width: 30%; text-align: center; background-color: #e8b235; border-radius: 8px;">
            重要声明</legend>
        <fieldset
            style="border: 1px dashed #e8b235; padding: 10px; border-radius: 5px; line-height: 2em; font-size: 12px; color: #bdbdbd; text-align: center;"
            data-mce-style="border: 1px dashed #e8b235; padding: 10px; border-radius: 5px; line-height: 2em; font-size: 12px; color: #bdbdbd; text-align: center;">
            <p style="font-size: 12px; text-align: center;" data-mce-style="font-size: 12px; text-align: center;"><span
                    style="color: #000000;" data-mce-style="color: #000000;">本站资源大多来自网络，如有侵犯你的权益请联系管理员</span><span
                    style="color: #000000;" data-mce-style="color: #000000;">E-mail:</span><span style="color: #ff6600;"
                    data-mce-style="color: #ff6600;">123456@qq.com</span> <span style="color: #000000;"
                    data-mce-style="color: #000000;">我们会第一时间进行审核删除。站内资源为网友个人学习或测试研究使用，未经原版权作者许可,禁止用于任何商业途径！请在下载24小时内删除！</span>
            </p>
            <hr>
            <p style="font-size: 12px; text-align: center;" data-mce-style="font-size: 12px; text-align: center;"><span
                    style="color: #000000;" data-mce-style="color: #000000;">如果遇到</span><span style="color: #ff0000;"
                    data-mce-style="color: #ff0000;">付费</span><span style="color: #000000;"
                    data-mce-style="color: #000000;">才可</span><span style="color: #33cccc;"
                    data-mce-style="color: #33cccc;">观看</span><span style="color: #000000;"
                    data-mce-style="color: #000000;">的文章，建议升级</span><span style="color: #ff0000;"
                    data-mce-style="color: #ff0000;">终身VIP。</span><span style="color: #000000;"
                    data-mce-style="color: #000000;">全站所有资源</span><span style="color: #ff0000;"
                    data-mce-style="color: #ff0000;">"<span style="color: #3366ff;"
                        data-mce-style="color: #3366ff;">任意下免费看</span>"。</span><span style="color: #ff9900;"
                    data-mce-style="color: #ff9900;">本站资源少部分采用</span><span style="color: #00ccff;"
                    data-mce-style="color: #00ccff;">7z压缩，</span><span style="color: #33cccc;"
                    data-mce-style="color: #33cccc;">为防止有人压缩软件不支持7z格式</span><span style="color: #cc99ff;"
                    data-mce-style="color: #cc99ff;">，7z</span><span style="color: #000000;"
                    data-mce-style="color: #000000;">解压，建议下载</span><span style="color: #cc99ff;"
                    data-mce-style="color: #cc99ff;"><em>7-zip</em></span><span style="color: #cc99ff;"
                    data-mce-style="color: #cc99ff;">，zip、rar</span><span style="color: #000000;"
                    data-mce-style="color: #000000;">解压，建议下载</span><span style="color: #cc99ff;"
                    data-mce-style="color: #cc99ff;"><em>WinRAR</em></span><span style="color: #000000;"
                    data-mce-style="color: #000000;">。</span></p>
        </fieldset>
    </fieldset>
</div>';
            break;
            
        case 'style3':
            $copyright_content = '    
    <head>
        <style type="text/css">
            .post-copyright {
                box-shadow: 2px 2px 5px;
                line-height: 2;
                position: relative;
                margin: 40px 0 10px;
                padding: 10px 16px;
                border: 1px solid var(--light-grey);
                transition: box-shadow .3s ease-in-out;
                overflow: hidden;
                border-radius: 12px !important;
                background-color: var(--main-bg-color);
            }
    
            .post-copyright:before {
                position: absolute;
                right: -26px;
                top: -120px;
                content: "\\f25e";
                font-size: 200px;
                font-family: "FontAwesome";
                opacity: .2;
            }
    
            .post-copyright__title {
                font-size: 22px;
            }
    
            .post-copyright_type {
                font-size: 18px;
                color: var(--theme-color)
            }
    
            .post-copyright .post-copyright-info {
                padding-left: 6px;
                font-size: 15px;
            }
    
            .post-copyright-m-info .post-copyright-a,
            .post-copyright-m-info .post-copyright-c,
            .post-copyright-m-info .post-copyright-u {
                display: inline-block;
                width: fit-content;
                padding: 2px 5px;
                font-size: 15px;
            }
    
            .muted-3-color {
                color: var(--main-color);
            }
    
            /*手机优化*/
            @media screen and (max-width:800px) {
                .post-copyright-m-info {
                    display: none
                }
            }
        </style>
    </head>
    
    <body>
        <div class="post-copyright" style="max-width:800px;margin:0 auto;">
            <div class="post-copyright__title" style="margin:10px 10px"><span class="post-copyright_title"><strong>
                        <script>document.write(document.title);</script>
                    </strong></span></div>
            <div class="post-copyright__type" style="margin:10px 10px"><span class="post-copyright_type">本文链接：
                    <script>var url = window.location.href; document.write(document.URL);</script>
                </span></div>
            <div class="post-copyright-m">
                <div class="post-copyright-m-info">
                    <div class="post-copyright-a">
                        <strong>文章作者</strong>
                        <div class="post-copyright-cc-info">
                            <strong><a href="/">' . get_bloginfo('name') . '</a></strong>
                        </div>
                    </div>
                    <div class="post-copyright-c" style="margin:10px 20px">
                        <strong>隐私政策</strong>
                        <div class="post-copyright-cc-info">
                            <strong><a href="/privacy" target="_blank">PrivacyPolicy</a></strong>
                        </div>
                    </div>
                    <div class="post-copyright-u" style="margin:10px 20px">
                        <strong>用户协议</strong>
                        <div class="post-copyright-cc-info">
                            <strong><a href="/protocol" target="_blank">UseGenerator</a></strong>
                        </div>
                    </div>
                    <div class="post-copyright-c" style="margin:10px 20px">
                        <strong>许可协议 </strong>
                        <div class="post-copyright-cc-info">
                            <strong><a href="https://creativecommons.org/licenses/by-nc-sa/4.0/" target="_blank">NC-SA 4.0</a></strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>';
            break;
            
        case 'style4':
            $copyright_content = '    
    <div class="posts-copyright"><div class="box">
    <b class="lurenfen"><p>本站收集的资源仅供内部学习研究软件设计思想和原理使用，学习研究后请自觉删除，请勿传播，因未及时删除所造成的任何后果责任自负。</p>
<p>如果用于其他用途，请购买正版支持作者，谢谢！若您认为「TFBKW.COM」发布的内容若侵犯到您的权益，请联系站长邮箱:123456@qq.com 进行删除处理。</p>
本站资源大多存储在云盘，如发现链接失效，请联系我们，我们会第一时间更新。
   </b>
  </div>
<style type="text/css"> 
.box
{
  position: relative;
  padding: 10px; 
  width: 100%;
  height: 100%;
  display: flex;
  justify-content: center;
  align-items: center;
  overflow: hidden;
  border-radius: 20px;
}
.box:before
{
  content: \'\';
  position: absolute;
  width: 150%;
  height: 50%;
  background: linear-gradient(315deg,#00ccff,#d400d4);
  animation: animate 6s linear infinite
}
@keyframes animate 
{
  0%
  {
    transform: rotate(0deg);
  }
  100%
  {
    transform: rotate(360deg);
  }
}
.box:after
{
  content: \'\';
  position: absolute;
  inset : 6px;
  background: var(--body-bg-color);
  border-radius: 15px;
  z-index: 2;
}
.lurenfen
{
  position: relative;
  font-weight: normal;
  color: #2997f7;
  z-index: 4;
  margin:15px;
}

</style></div>';
            break;
            
        case 'style5':
            $copyright_content = '
<div class="mt10 mb20">
    <style>
.tfbkw-copyright-container *{margin:0;padding:0;box-sizing:border-box;font-family:"PingFang SC","Microsoft YaHei",sans-serif}.tfbkw-copyright-container .tfbkw-copyright-link i{display:inline-block;font-style:normal}.tfbkw-copyright-container{background:rgba(0,0,0,0.7);backdrop-filter:blur(10px);border-radius:20px;padding:40px;max-width:100%;width:100%;box-shadow:0 10px 30px rgba(0,0,0,0.5);position:relative;overflow:hidden;border:1px solid rgba(255,255,255,0.1);margin:20px 0;transform-style:preserve-3d;transition:transform 0.2s ease-out}.tfbkw-copyright-container::before{content:"";position:absolute;top:-50%;left:-50%;width:200%;height:200%;background:linear-gradient(45deg,rgba(255,0,150,0.3),rgba(0,255,255,0.3),rgba(255,200,0,0.3));z-index:-1;animation:tfbkw-rotate 15s linear infinite}.tfbkw-copyright-container::after{content:"";position:absolute;inset:5px;background:rgba(10,8,35,0.9);border-radius:16px;z-index:-1}.tfbkw-copyright-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:30px;position:relative}.tfbkw-logo{display:flex;align-items:center}.tfbkw-logo-icon{width:50px;height:50px;background:linear-gradient(135deg,#6a11cb 0%,#2575fc 100%);border-radius:12px;display:flex;justify-content:center;align-items:center;margin-right:15px;font-size:24px;color:white;box-shadow:0 0 20px rgba(106,17,203,0.5);animation:tfbkw-pulse 3s infinite;position:relative;overflow:hidden}.tfbkw-logo-icon::before{content:"";position:absolute;width:100%;height:100%;background:radial-gradient(circle,rgba(255,255,255,0.3) 0%,rgba(255,255,255,0) 70%);animation:logo-particles 4s linear infinite}@keyframes logo-particles{0%{transform:scale(0.8);opacity:0.5}50%{transform:scale(1.2);opacity:0.8}100%{transform:scale(0.8);opacity:0.5}}.tfbkw-logo-text{font-size:28px;font-weight:bold;display:inline-block;position:relative;transform-style:preserve-3d;perspective:1000px;animation:float 6s ease-in-out infinite}.tfbkw-logo-text span{display:inline-block;background:linear-gradient(135deg,#ff7e5f,#feb47b,#6a11cb,#2575fc);-webkit-background-clip:text;background-clip:text;color:transparent;background-size:300% 300%;animation:tfbkw-gradient-shift 8s ease infinite;text-shadow:0 0 10px rgba(255,126,95,0.3),0 0 20px rgba(255,126,95,0.2),0 0 30px rgba(255,126,95,0.1);transition:all 0.3s ease}.tfbkw-logo-text:hover span{background-position:100% 50%;text-shadow:0 0 15px rgba(255,126,95,0.6),0 0 30px rgba(255,126,95,0.4),0 0 45px rgba(255,126,95,0.2)}.tfbkw-logo-text::before{content:attr(data-text);position:absolute;top:0;left:0;width:100%;height:100%;color:#ff7e5f;filter:blur(10px);opacity:0.5;z-index:-1;animation:neon-pulse 2s ease-in-out infinite alternate}@keyframes neon-pulse{from{opacity:0.3;filter:blur(8px)}to{opacity:0.7;filter:blur(12px)}}.tfbkw-logo-text::after{content:"";position:absolute;top:-10px;left:-10px;width:calc(100% + 20px);height:calc(100% + 20px);border:2px solid rgba(255,126,95,0.2);border-radius:8px;animation:border-pulse 4s ease-in-out infinite;box-shadow:0 0 15px rgba(255,126,95,0.1)}@keyframes border-pulse{0%{transform:scale(1);border-color:rgba(255,126,95,0.2)}50%{transform:scale(1.05);border-color:rgba(255,126,95,0.4)}100%{transform:scale(1);border-color:rgba(255,126,95,0.2)}}@keyframes float{0%{transform:translateY(0px) rotateX(0deg) rotateY(0deg)}25%{transform:translateY(-5px) rotateX(2deg) rotateY(-2deg)}50%{transform:translateY(0px) rotateX(0deg) rotateY(0deg)}75%{transform:translateY(5px) rotateX(-2deg) rotateY(2deg)}100%{transform:translateY(0px) rotateX(0deg) rotateY(0deg)}}.tfbkw-particles{position:absolute;width:100%;height:100%;pointer-events:none;z-index:0}.tfbkw-particle{position:absolute;background:rgba(255,126,95,0.6);border-radius:50%;animation:particle-move 10s linear infinite}@keyframes particle-move{0%{transform:translate(0,0);opacity:0}10%{opacity:0.8}90%{opacity:0.8}100%{transform:translate(var(--x),var(--y));opacity:0}}.tfbkw-copyright-content{color:rgba(255,255,255,0.85);line-height:1.8;font-size:16px;text-align:justify;position:relative;z-index:2}.tfbkw-copyright-content p{margin-bottom:15px;animation:content-fade-in 1s ease-out forwards;opacity:0;transform:translateY(10px);transition:all 0.3s ease}.tfbkw-copyright-content p:hover{transform:translateY(-2px);color:rgba(255,255,255,1)}.tfbkw-copyright-content p:nth-child(1){animation-delay:0.1s}.tfbkw-copyright-content p:nth-child(2){animation-delay:0.2s}.tfbkw-copyright-content p:nth-child(3){animation-delay:0.3s}@keyframes content-fade-in{to{opacity:1;transform:translateY(0)}}.tfbkw-highlight{color:#ff7e5f;font-weight:bold;text-shadow:0 0 10px rgba(255,126,95,0);transition:text-shadow 0.3s ease;animation:highlight-pulse 2s infinite alternate}@keyframes highlight-pulse{from{text-shadow:0 0 5px rgba(255,126,95,0.5)}to{text-shadow:0 0 15px rgba(255,126,95,0.8)}}.tfbkw-highlight:hover{text-shadow:0 0 10px rgba(255,126,95,0.8)}.tfbkw-copyright-links{display:flex;justify-content:center;margin-top:30px;flex-wrap:wrap;gap:15px}.tfbkw-copyright-link{position:relative;overflow:hidden;background:rgba(255,255,255,0.1);border:1px solid rgba(255,255,255,0.2);color:white;padding:10px 20px;border-radius:50px;text-decoration:none;font-weight:500;transition:all 0.3s ease;display:flex;align-items:center;gap:8px;box-shadow:0 0 10px rgba(255,255,255,0.1);background:linear-gradient(135deg,rgba(255,255,255,0.1),rgba(255,255,255,0.05))}.tfbkw-copyright-link:nth-child(1){border-color:rgba(106,17,203,0.5);background:linear-gradient(135deg,rgba(106,17,203,0.1),rgba(37,117,252,0.1))}.tfbkw-copyright-link:nth-child(2){border-color:rgba(0,255,255,0.5);background:linear-gradient(135deg,rgba(0,255,255,0.1),rgba(0,150,255,0.1))}.tfbkw-copyright-link:nth-child(3){border-color:rgba(255,200,0,0.5);background:linear-gradient(135deg,rgba(255,200,0,0.1),rgba(255,150,0,0.1))}.tfbkw-copyright-link:nth-child(4){border-color:rgba(255,0,150,0.5);background:linear-gradient(135deg,rgba(255,0,150,0.1),rgba(200,0,255,0.1))}.tfbkw-copyright-link::before{content:"";position:absolute;top:-50%;left:-50%;width:200%;height:200%;background:linear-gradient( to right,rgba(255,255,255,0) 0%,rgba(255,255,255,0.2) 50%,rgba(255,255,255,0) 100% );transform:rotate(30deg);animation:shine 3s infinite}@keyframes shine{0%{transform:translateX(-100%) rotate(30deg)}100%{transform:translateX(100%) rotate(30deg)}}.tfbkw-copyright-link:hover{transform:translateY(-3px);box-shadow:0 5px 15px rgba(255,255,255,0.2)}.tfbkw-copyright-link i{transition:transform 0.5s ease}.tfbkw-copyright-link:hover i{transform:rotate(360deg) scale(1.2)}.tfbkw-copyright-footer{margin-top:30px;text-align:center;color:rgba(255,255,255,0.6);font-size:14px;display:flex;justify-content:center;align-items:center;gap:10px;flex-wrap:wrap;position:relative;overflow:hidden;padding:10px;border-radius:8px}.tfbkw-copyright-footer::before{content:"";position:absolute;top:0;left:0;width:100%;height:100%;background:linear-gradient(90deg,rgba(255,255,255,0.02),rgba(255,255,255,0.05),rgba(255,255,255,0.02));z-index:-1;animation:footer-glow 4s infinite}@keyframes footer-glow{0%,100%{background-position:-100% 0}50%{background-position:100% 0}}.tfbkw-copyright-footer span{position:relative;transition:all 0.3s ease;padding:5px 10px}.tfbkw-copyright-footer span:hover{color:white;transform:translateY(-2px)}.tfbkw-copyright-footer span:nth-child(odd){color:rgba(255,255,255,0.3);animation:blink 2s infinite}@keyframes blink{0%,100%{opacity:0.3}50%{opacity:0.8}}.tfbkw-copyright-footer span:nth-child(3){color:#2575fc;text-shadow:0 0 10px rgba(37,117,252,0.5)}.tfbkw-copyright-footer span:nth-child(5){color:#ff7e5f;text-shadow:0 0 10px rgba(255,126,95,0.5)}.tfbkw-copyright-footer::after{content:"";position:absolute;bottom:0;left:0;width:100%;height:2px;background:linear-gradient(90deg,rgba(106,17,203,0),rgba(106,17,203,0.5),rgba(37,117,252,0.5),rgba(0,255,255,0.5),rgba(255,200,0,0.5),rgba(255,126,95,0.5),rgba(255,0,150,0.5),rgba(106,17,203,0) );background-size:200% 100%;animation:footer-line 10s linear infinite}@keyframes footer-line{0%{background-position:-200% 0}100%{background-position:200% 0}}.tfbkw-copyright-badge{background:linear-gradient(45deg,#ff7e5f,#feb47b);color:#000;padding:3px 10px;border-radius:5px;font-weight:bold;font-size:12px;letter-spacing:1px}@keyframes tfbkw-rotate{0%{transform:rotate(0deg)}100%{transform:rotate(360deg)}}@keyframes tfbkw-pulse{0%{box-shadow:0 0 0 0 rgba(106,17,203,0.7)}70%{box-shadow:0 0 0 10px rgba(106,17,203,0)}100%{box-shadow:0 0 0 0 rgba(106,17,203,0)}}@keyframes tfbkw-gradient-shift{0%{background-position:0% 50%}50%{background-position:100% 50%}100%{background-position:0% 50%}}.tfbkw-decoration{position:absolute;z-index:1;opacity:0.4}.tfbkw-decoration.circle{width:100px;height:100px;border:3px solid #ff7e5f;border-radius:50%;top:-40px;right:-40px;animation:tfbkw-rotate 30s linear infinite}.tfbkw-decoration.triangle{width:0;height:0;border-left:50px solid transparent;border-right:50px solid transparent;border-bottom:100px solid #2575fc;bottom:-50px;left:-50px;transform:rotate(45deg);animation:tfbkw-rotate 25s linear infinite reverse}.tfbkw-copyright-container{transition:transform 0.5s cubic-bezier(0.25,0.46,0.45,0.94)}.tfbkw-copyright-container:hover{transform:perspective(1000px) rotateY(2deg) rotateX(-2deg)}@media (max-width:768px){.tfbkw-copyright-container{padding:30px 20px}.tfbkw-copyright-header{flex-direction:column;text-align:center;gap:20px}.tfbkw-logo{justify-content:center}.tfbkw-logo-text{font-size:24px}.tfbkw-copyright-content{font-size:14px}.tfbkw-copyright-footer{font-size:12px}}
    </style>
    <div class="tfbkw-copyright-container">
        <div class="tfbkw-decoration circle">
        </div>
        <div class="tfbkw-decoration triangle">
        </div>
        <div class="tfbkw-copyright-header">
            <div class="tfbkw-logo">
                <div class="tfbkw-logo-icon">
                    
                </div>
                <div class="tfbkw-logo-text" data-text="有事没事常联系-腾飞">
                    <span>
                       ' . get_bloginfo('name') . '-统一解压密码-www.tfbkw.com
                    </span>
                </div>
            </div>
            <div class="tfbkw-copyright-badge">
                版权声明
            </div>
        </div>
        <div class="tfbkw-copyright-content">
            <p>
                <span class="tfbkw-highlight">
                    ' . get_bloginfo('name') . '
                </span>
                本网站所有内容，包括但不限于文字、图片、音频、视频、软件、程序、以及网页版式设计等。
            </p>
            <p>
                本网站部分内容转载自互联网，转载目的在于传递更多信息，并不代表本网站赞同其观点和对其真实性负责。如有侵权行为，请联系我们，我们将及时处理。
            </p>
            <p>
                对于用户通过本网站上传、发布或传送的任何内容，用户应保证其为著作权人或已取得合法授权，并且该内容不会侵犯任何第三方的合法权益。如果第三方提出关于著作权的异议，本网站有权删除相关的内容并保留追究用户法律责任的权利。
            </p>
        </div>
        <div class="tfbkw-copyright-links">
            <a href="/" class="tfbkw-copyright-link">
                <i>
                    📝
                </i>
                免责声明
            </a>
            <a href="/" class="tfbkw-copyright-link">
                <i>
                    🔒
                </i>
                关于我们
            </a>
            <a href="/" class="tfbkw-copyright-link">
                <i>
                    📩
                </i>
                成为邻居
            </a>
            <a href="mailto: 1234566@qq.com" class="tfbkw-copyright-link">
                <i>
                    ⚖️
                </i>
                侵权举报
            </a>
        </div>
        <div class="tfbkw-copyright-footer">
            <span>
                © 2025
            </span>
            <span>
                ' . get_bloginfo('name') . ' 版权所有
            </span>
            <span>
                |
            </span>
            <span>
                备案号： 豫ICP备1234567号-1
            </span>
            <span>
                |
            </span>
            <span>
                个人博客：' . get_bloginfo('name') . '
            </span>
        </div>
    </div>
        </div>';
            break;
            
        case 'style6':
            $copyright_content = '
<style>
.copyright-notice-box {
    margin: 25px 0;
    isolation: isolate;
    contain: content;
}

.copyright-notice-box fieldset {
    border: 2px dashed rgba(0, 140, 255, 0.5);
    padding: 25px;
    border-radius: 15px;
    background: rgba(248, 250, 255, 0.95);
    backdrop-filter: blur(5px);
    box-shadow: 0 5px 20px rgba(0, 140, 255, 0.1);
    margin: 0;
    position: relative;
    overflow: hidden;
}

.copyright-notice-box fieldset::before {
    content: \'\';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, #008cff, #ff5f89);
}

.copyright-notice-box legend {
    font-weight: bold;
    color: #008cff;
    padding: 0 15px;
    font-size: 16px;
    background: rgba(248, 250, 255, 0.95);
    border-radius: 8px;
    border: 1px solid rgba(0, 140, 255, 0.2);
    box-shadow: 0 2px 8px rgba(0, 140, 255, 0.15);
}

.copyright-notice-box ul {
    list-style: none;
    padding: 0;
    margin: 20px 0 0 0;
}

.copyright-notice-box li {
    padding: 12px 0;
    border-bottom: 1px solid rgba(0, 140, 255, 0.1);
    position: relative;
    padding-left: 35px;
    transition: all 0.3s ease;
}

.copyright-notice-box li:last-child {
    border-bottom: none;
}

.copyright-notice-box li::before {
    content: "▶";
    position: absolute;
    left: 0;
    top: 12px;
    color: #008cff;
    font-size: 12px;
    transition: transform 0.3s ease;
}

.copyright-notice-box li:hover::before {
    transform: translateX(5px);
}

.copyright-notice-box li:hover {
    background: rgba(0, 140, 255, 0.05);
    padding-left: 40px;
}

.copyright-notice-box strong {
    color: #008cff;
    font-weight: 600;
}

.copyright-notice-box em {
    color: #ff5f89;
    font-style: normal;
    font-weight: 500;
}

.copyright-notice-box a {
    color: #008cff;
    text-decoration: none;
    border-bottom: 1px dotted rgba(0, 140, 255, 0.5);
    transition: all 0.3s ease;
}

.copyright-notice-box a:hover {
    color: #ff5f89;
    border-bottom-style: solid;
}

@media (max-width: 768px) {
    .copyright-notice-box fieldset {
        padding: 20px 15px;
    }
    
    .copyright-notice-box legend {
        font-size: 14px;
        padding: 0 10px;
    }
    
    .copyright-notice-box li {
        padding: 10px 0;
        padding-left: 30px;
        font-size: 14px;
    }
}
</style>

<div class="copyright-notice-box">
    <fieldset>
        <legend>📜 版权声明与使用须知</legend>
        <ul>
            <li><strong>原创内容：</strong>本站原创内容未经授权，禁止转载、摘编、复制或建立镜像。</li>
            <li><strong>转载内容：</strong>部分内容转载自网络，版权归原作者所有，如有侵权请联系删除。</li>
            <li><strong>使用限制：</strong>仅供学习交流使用，<em>禁止商业用途</em>，违者后果自负。</li>
            <li><strong>链接标注：</strong>转载时请注明出处：<a href="javascript:void(0);" onclick="navigator.clipboard.writeText(window.location.href);alert(\'链接已复制到剪贴板\');">复制当前链接</a></li>
            <li><strong>免责声明：</strong>本站不承担因使用内容而产生的任何法律责任或经济损失。</li>
        </ul>
    </fieldset>
</div>';
            break;
            
        case 'style7':
            $copyright_content = '
<style>
.copyright-modern-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 20px;
    padding: 30px;
    margin: 30px 0;
    color: white;
    position: relative;
    overflow: hidden;
    box-shadow: 0 15px 35px rgba(102, 126, 234, 0.3);
    transform: translateZ(0);
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

.copyright-modern-card:hover {
    transform: translateY(-5px) scale(1.02);
    box-shadow: 0 20px 40px rgba(102, 126, 234, 0.4);
}

.copyright-modern-card::before {
    content: "";
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    animation: float-bubble 8s ease-in-out infinite;
}

.copyright-modern-card::after {
    content: "";
    position: absolute;
    bottom: -30px;
    left: -30px;
    width: 100px;
    height: 100px;
    background: rgba(255,255,255,0.05);
    border-radius: 50%;
    animation: float-bubble 6s ease-in-out infinite reverse;
}

@keyframes float-bubble {
    0%, 100% { transform: translate(0, 0) scale(1); }
    33% { transform: translate(30px, -30px) scale(1.1); }
    66% { transform: translate(-20px, 20px) scale(0.9); }
}

.copyright-header {
    display: flex;
    align-items: center;
    margin-bottom: 25px;
    position: relative;
    z-index: 2;
}

.copyright-icon {
    width: 60px;
    height: 60px;
    background: rgba(255,255,255,0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 20px;
    font-size: 28px;
    backdrop-filter: blur(10px);
    border: 2px solid rgba(255,255,255,0.3);
    animation: pulse-icon 3s ease-in-out infinite;
}

@keyframes pulse-icon {
    0%, 100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(255,255,255,0.4); }
    50% { transform: scale(1.05); box-shadow: 0 0 0 10px rgba(255,255,255,0); }
}

.copyright-title h3 {
    margin: 0;
    font-size: 24px;
    font-weight: 700;
    text-shadow: 0 2px 4px rgba(0,0,0,0.3);
}

.copyright-title p {
    margin: 5px 0 0 0;
    opacity: 0.9;
    font-size: 14px;
}

.copyright-content {
    position: relative;
    z-index: 2;
    background: rgba(255,255,255,0.1);
    backdrop-filter: blur(10px);
    border-radius: 15px;
    padding: 20px;
    border: 1px solid rgba(255,255,255,0.2);
}

.copyright-item {
    display: flex;
    align-items: flex-start;
    margin-bottom: 15px;
    transition: transform 0.3s ease;
}

.copyright-item:hover {
    transform: translateX(5px);
}

.copyright-item:last-child {
    margin-bottom: 0;
}

.copyright-item-icon {
    width: 24px;
    height: 24px;
    background: rgba(255,255,255,0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 15px;
    flex-shrink: 0;
    font-size: 12px;
}

.copyright-item-text {
    flex: 1;
    line-height: 1.6;
}

.copyright-item-text strong {
    color: #ffd700;
    font-weight: 600;
}

.copyright-footer {
    margin-top: 25px;
    padding-top: 20px;
    border-top: 1px solid rgba(255,255,255,0.2);
    text-align: center;
    position: relative;
    z-index: 2;
}

.copyright-footer-text {
    opacity: 0.8;
    font-size: 14px;
    margin-bottom: 15px;
}

.copyright-actions {
    display: flex;
    gap: 15px;
    justify-content: center;
    flex-wrap: wrap;
}

.copyright-btn {
    background: rgba(255,255,255,0.2);
    border: 1px solid rgba(255,255,255,0.3);
    color: white;
    padding: 8px 16px;
    border-radius: 20px;
    text-decoration: none;
    font-size: 13px;
    transition: all 0.3s ease;
    backdrop-filter: blur(5px);
}

.copyright-btn:hover {
    background: rgba(255,255,255,0.3);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}

@media (max-width: 768px) {
    .copyright-modern-card {
        padding: 20px;
        margin: 20px 0;
    }
    
    .copyright-header {
        flex-direction: column;
        text-align: center;
        margin-bottom: 20px;
    }
    
    .copyright-icon {
        margin-right: 0;
        margin-bottom: 15px;
    }
    
    .copyright-title h3 {
        font-size: 20px;
    }
    
    .copyright-content {
        padding: 15px;
    }
    
    .copyright-actions {
        flex-direction: column;
        align-items: center;
    }
    
    .copyright-btn {
        width: 100%;
        text-align: center;
    }
}
</style>

<div class="copyright-modern-card">
    <div class="copyright-header">
        <div class="copyright-icon">©</div>
        <div class="copyright-title">
            <h3>版权保护声明</h3>
            <p>尊重原创，保护知识产权</p>
        </div>
    </div>
    
    <div class="copyright-content">
        <div class="copyright-item">
            <div class="copyright-item-icon">📝</div>
            <div class="copyright-item-text">
                <strong>原创保护：</strong>本站所有原创内容均受著作权法保护，未经许可禁止转载或商业使用
            </div>
        </div>
        
        <div class="copyright-item">
            <div class="copyright-item-icon">🔗</div>
            <div class="copyright-item-text">
                <strong>转载规范：</strong>如需转载，请注明出处并保留原文链接，不得删改内容
            </div>
        </div>
        
        <div class="copyright-item">
            <div class="copyright-item-icon">⚠️</div>
            <div class="copyright-item-text">
                <strong>免责声明：</strong>本站仅提供学习交流平台，内容观点不代表本站立场
            </div>
        </div>
        
        <div class="copyright-item">
            <div class="copyright-item-icon">📧</div>
            <div class="copyright-item-text">
                <strong>侵权处理：</strong>如发现侵权内容，请及时联系我们，将在第一时间处理
            </div>
        </div>
    </div>
    
    <div class="copyright-footer">
        <div class="copyright-footer-text">
            © 2025 ' . get_bloginfo('name') . ' — 用心创造价值，用爱分享知识
        </div>
        <div class="copyright-actions">
            <a href="javascript:void(0);" onclick="navigator.clipboard.writeText(window.location.href);alert(\'链接已复制\');" class="copyright-btn">📋 复制链接</a>
            <a href="/" class="copyright-btn">🏠 返回首页</a>
            <a href="mailto:admin@example.com" class="copyright-btn">📧 联系我们</a>
        </div>
    </div>
</div>';
            break;
            
        default:
            $copyright_content = '<div class="posts-copyright"><div class="entry-copyright px12">版权声明：本文为原创内容，转载请注明出处。</div></div>';
            break;
    }
    
    echo '<div class="copyright-after-actions" style="margin-top: 20px;">' . $copyright_content . '</div>';
}

// 通过 JavaScript 将版权声明移动到正确的位置
function copyright_relocate_script() {
    if (!is_single()) {
        return;
    }
    ?>
    <script>
    function relocateCopyright() {
        // 查找版权声明元素
        var copyrightDiv = document.querySelector('.copyright-after-actions');
        if (!copyrightDiv) {
            console.log('未找到版权声明元素');
            return;
        }
        
        // 查找当前主题的操作按钮区域
        var actionBar = document.querySelector('.post-actions');
        
        // 查找具体的按钮元素
        var shareButton = document.querySelector('.action-share');
        var likeButton = document.querySelector('.action-like');
        var favoriteButton = document.querySelector('.action-favorite');
        
        console.log('查找结果 - actionBar:', !!actionBar, 'shareButton:', !!shareButton, 'likeButton:', !!likeButton, 'favoriteButton:', !!favoriteButton);
        
        // 按优先级查找插入位置
        var targetElement = null;
        
        if (actionBar) {
            // 优先使用操作按钮区域，这样版权声明会显示在所有按钮的下方
            targetElement = actionBar;
            console.log('使用操作按钮区域作为目标');
        } else if (shareButton) {
            targetElement = shareButton;
            console.log('使用分享按钮作为目标');
        } else if (likeButton) {
            targetElement = likeButton;
            console.log('使用点赞按钮作为目标');
        } else if (favoriteButton) {
            targetElement = favoriteButton;
            console.log('使用收藏按钮作为目标');
        } else {
            // 如果都找不到，查找文章内容区域的结尾
            var contentArea = document.querySelector('.entry-content, .post-content, .article-content, .single-content');
            if (contentArea) {
                targetElement = contentArea;
                console.log('使用内容区域作为目标');
            }
        }
        
        if (targetElement) {
            // 将版权声明移动到目标元素的后面
            targetElement.parentNode.insertBefore(copyrightDiv, targetElement.nextSibling);
            console.log('版权声明已成功移动到操作按钮下方');
        } else {
            // 最后的备选方案：查找文章区域
            var articleArea = document.querySelector('article, .post, .article');
            if (articleArea) {
                articleArea.appendChild(copyrightDiv);
                console.log('版权声明已添加到文章区域末尾');
            } else {
                console.log('未找到合适的位置插入版权声明');
            }
        }
    }
    
    // 确保页面完全加载后再执行
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', relocateCopyright);
    } else {
        relocateCopyright();
    }
    
    // 延迟执行，确保动态内容也加载完成
    setTimeout(relocateCopyright, 1000);
    </script>
    <?php
}

// 复制提醒功能已移至 functions.php，实现全站复制提醒

// 在页面底部预先输出版权声明，然后通过JS移动到正确位置
add_action('wp_footer', 'copyright_after_post_actions');
add_action('wp_footer', 'copyright_relocate_script');
// copyright_copy_reminder 已移至 functions.php，实现全站复制提醒
?>
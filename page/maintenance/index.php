<?php
// 设置前缀和图片路径
$prefix = 'XuWbk';
$imagepath = get_stylesheet_directory_uri() . '/assets/images/';

// 获取维护模式配置
$options = get_option('XuWbk');
$maintenance_title = isset($options['maintenance_title']) ? $options['maintenance_title'] : '网站维护中';
$maintenance_time = isset($options['maintenance_time']) ? $options['maintenance_time'] : date('Y-m-d H:i:s', strtotime('+24 hours'));
$maintenance_logo = isset($options['maintenance_logo']) ? $options['maintenance_logo'] : $imagepath . 'logo.png';
$maintenance_desc = isset($options['maintenance_desc']) ? $options['maintenance_desc'] : '网站正在进行系统升级，请稍后访问...';
$maintenance_redirectUrl = isset($options['maintenance_redirectUrl']) ? $options['maintenance_redirectUrl'] : '';
$maintenance_redirectUrlName = isset($options['maintenance_redirectUrlName']) ? $options['maintenance_redirectUrlName'] : '';
$maintenance_copyright = isset($options['maintenance_copyright']) ? $options['maintenance_copyright'] : get_bloginfo('name');
$maintenance_beian = isset($options['maintenance_beian']) ? $options['maintenance_beian'] : '';
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, target-densityDpi=device-dpi" />
    <title><?php echo get_bloginfo('name').' - '.$maintenance_title; ?></title>
    <link rel="stylesheet" type="text/css" href="<?php echo get_stylesheet_directory_uri(); ?>/page/maintenance/css/style.css"/>
    <script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/page/maintenance/js/script.js"></script>
	<script type="text/javascript">
		var countDownDate = new Date("<?php echo $maintenance_time; ?>").getTime();
	</script>
</head>

<body class="white-font">
    <div class="hidden_overflow gradient_blue">
	<div class="container">
        <div class="count-block">
            <div class="head-area">
                <a href="<?php echo home_url(); ?>" class="logo mob_logo"><img src="<?php echo $maintenance_logo; ?>" alt=""></a>
                <h2 class="time-left-txt"><?php echo $maintenance_title; ?></h2>
            </div>
            <div class="middle-area">
                <div class="countdown-row">
                    <a href="<?php echo home_url(); ?>" class="logo"><img src="<?php echo $maintenance_logo; ?>" alt=""></a>
                    <div class="counting-row">
                        <div class="slot-type">
                            <span class="num" id="day">00</span>
                            <span class="param">天</span>
                        </div
                        ><div class="slot-type">
                            <span class="num" id="hour">00</span>
                            <span class="param">小时</span>
                        </div
                        ><div class="slot-type">
                            <span class="num" id="min">00</span>
                            <span class="param">分钟</span>
                        </div
                        ><div class="slot-type">
                            <div class="num _INVISIBLE_" id="second">00</div>
                            <span class="param"></span>
                        </div>
                    </div>
                    <div class="seconds-holder">
                        <div class="circle-holder">
                            <div class="dark_digit IE_HIDE">
                                <img src="<?php echo get_stylesheet_directory_uri(); ?>/page/maintenance/css/secondwhite.svg" class="round" alt="">
                            </div>
                            <svg class="dark_digit" width="100%" height="100%">
                                <g id="clipPath">
                                    <image xlink:href="<?php echo get_stylesheet_directory_uri(); ?>/page/maintenance/css/secondwhite.svg" width="100%" height="100%" transform="" class="round" id="digitalsecond" alt="">
                                        <animateTransform attributeName="transform"
                                        attributeType="XML"
                                        type="rotate"
                                        dur="10s"
                                        repeatCount="indefinite"/>
                                    </image>
                                </g>
                                <defs>
                                    <clipPath id="hero-clip">
                                        <rect x="94%" y="47.2%" fill="#ff0000" width="110" height="64"/>
                                    </clipPath>
                                </defs>
                            </svg>
                            <div class="down_opacity_circle">
                                <img src="<?php echo get_stylesheet_directory_uri(); ?>/page/maintenance/css/secondtrans_.svg" class="round" id="digitalsecond" alt="">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="countdown-caption">
                    <?php echo $maintenance_desc; ?>
                </div>
            </div>
            <footer>
                <p class="copyright-txt">
				    <?php if ($maintenance_redirectUrl):?>
                        <?php if ($maintenance_redirectUrlName):?>
                            <?php echo "本站临时站点: " . "<a href=" . $maintenance_redirectUrl.">". "『". $maintenance_redirectUrlName ."』". "</a><br><br>";?>
                        <?php else: ?>
                            <?php echo "本站临时站点: " . "<a href=" . $maintenance_redirectUrl.">"."点击跳转". "</a><br><br>";?>
                        <?php endif;?>
                    <?php endif;?>
                    <?php if ($maintenance_copyright){echo $maintenance_copyright;}?>&nbsp;&nbsp;<?php if ($maintenance_beian){echo '<a class="c-footer-1-nav-link" href="http://beian.miit.gov.cn/" target="_blank" rel="noopener">'. $maintenance_beian .'</a>';}?>
                </p>
            </footer>
        </div>
    </div>
</div>
</body>
</html>

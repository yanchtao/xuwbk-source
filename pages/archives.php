<?php

/**
 * Template name: Zbfox-文章归档
 * Description:   A archives page for displaying article statistics and monthly updates.
 */

// 获取分类统计信息并缓存
function get_category_statistics() {
    return get_cached_data('category_stats', function() {
        $categories = get_categories(['hide_empty' => false]);
        $data = [];
        foreach ($categories as $category) {
            $data[] = ['value' => $category->count, 'name' => $category->name];
        }
        return json_encode($data);
    });
}

// 获取文章热力图数据并缓存
function get_heatmap_data() {
    return get_cached_data('heatmap_data', function() {
        global $wpdb;
        // 使用SQL查询一次性获取所有必要的数据，以减少循环中的函数调用
        $query = "
            SELECT DATE(post_date) AS date, COUNT(*) AS count
            FROM {$wpdb->posts}
            WHERE post_type = 'post' AND post_status = 'publish'
            GROUP BY DATE(post_date)
            ORDER BY date ASC
        ";
        $results = $wpdb->get_results($query, ARRAY_A);

        // 填充缺失的日期
        if (!empty($results)) {
            $start_date = $results[0]['date'];
            $end_date = end($results)['date'];
            $current_date = strtotime($start_date);
            $heatmap_data = [];

            while (date('Y-m-d', $current_date) <= $end_date) {
                $formatted_date = date('Y-m-d', $current_date);
                $count = 0;

                foreach ($results as $result) {
                    if ($result['date'] === $formatted_date) {
                        $count = $result['count'];
                        break;
                    }
                }

                $heatmap_data[] = [$formatted_date, $count];
                $current_date = strtotime('+1 day', $current_date);
            }

            return json_encode($heatmap_data);
        } else {
            return json_encode([]);
        }
    });
}

// 获取用户统计数据并缓存
function get_user_vip_statistics() {
    return get_cached_data('user_vip_stats', function() {
        // 如果有更高效的获取会员级别的方式，请替换zib_get_user_vip_level()
        $users = get_users(['fields' => ['ID']]);
        $user_counts = [
            'normal' => 0,
            'vip_1' => 0,
            'vip_2' => 0
        ];

        foreach ($users as $user) {
            $vip_level = zib_get_user_vip_level($user->ID);

            switch ($vip_level) {
                case 1:
                    $user_counts['vip_1']++;
                    break;
                case 2:
                    $user_counts['vip_2']++;
                    break;
                default:
                    $user_counts['normal']++;
                    break;
            }
        }

        $chart_data = [
            ['value' => $user_counts['normal'], 'name' => '普通用户'],
            ['value' => $user_counts['vip_1'], 'name' => _pz('pay_user_vip_1_name')],
            ['value' => $user_counts['vip_2'], 'name' => _pz('pay_user_vip_2_name')]
        ];

        return json_encode($chart_data);
    });
}

// 使用瞬态API缓存数据以减少数据库查询次数
function get_cached_data($transient_key, $fetch_callback) {
    if (false === ($data = get_transient($transient_key))) {
        $data = call_user_func($fetch_callback);
        set_transient($transient_key, $data, DAY_IN_SECONDS); // 缓存一天
    }
    return $data;
}

// 渲染小部件
function render_archives_widgets($type = 'day') {
    // 初始化变量
    $icons = [
        'day' => ['icon' => 'fa fa-birthday-cake', 'color' => 'c-blue-2', 'title' => '运营时间'],
        'post' => ['icon' => '#icon-post', 'color' => 'c-green', 'title' => '文章总量'],
        'comment' => ['icon' => '#icon-comment-color', 'color' => 'c-yellow', 'title' => '评论总量'],
        'user' => ['icon' => '#icon-user-color-2', 'color' => 'c-blue', 'title' => '注册用户']
    ];

    // 根据传入的参数类型获取统计数据并构建HTML
    switch ($type) {
        case 'day':
            // 尝试获取ID为1的用户注册时间作为运营时间起点
            $first_user = get_userdata(1);
            if ($first_user && !empty($first_user->user_registered)) {
                $start_time = strtotime($first_user->user_registered);
            } else {
                // 如果获取不到用户1的数据，则回退到站点设置时间或者当前时间
                $start_time = strtotime(get_option('siteorigin_setting_time')) ?: time();
            }
            $statistic = esc_html(floor((time() - $start_time) / (60 * 60 * 24))) . ' 天';
            break;
        case 'post':
            $statistic = esc_html(wp_count_posts()->publish);
            break;
        case 'comment':
            $approved_comments = get_comment_count()['approved'];
            $pending_comments = get_comment_count()['moderated'];
            $spam_comments = get_comment_count()['spam'];
            $trash_comments = get_comment_count()['trash'];
            $statistic = esc_html($approved_comments + $pending_comments + $spam_comments + $trash_comments);
            break;
        case 'user':
            $statistic = esc_html(count_users()['total_users']);
            break;
        default:
            return; // 如果类型不匹配，则不输出任何内容
    }

    // 输出HTML结构
    echo '<div style="flex: 1;" class="zib-widget flex1">';
    echo '<div class="muted-color em09 mb6">' . $icons[$type]['title'] . '统计</div>';
    echo '<div class="flex jsb">';
    echo '<span class="font-bold ' . $icons[$type]['color'] . ' em12">' . $statistic . '</span>';
    
    if (strpos($icons[$type]['icon'], '#') === 0) {
        // 使用SVG图标
        echo '<svg class="em14 ' . $icons[$type]['color'] . '" aria-hidden="true"><use xlink:href="' . $icons[$type]['icon'] . '"></use></svg>';
    } else {
        // 使用Font Awesome图标
        echo '<i class="' . $icons[$type]['icon'] . ' ' . $icons[$type]['color'] . ' em14"></i>';
    }
    
    echo '</div></div>';
}

// 在适当的地方调用这些函数以获取所需的数据
$json_data = get_category_statistics();
$json_heatmap_data = get_heatmap_data();
$json_chart_data = get_user_vip_statistics();

function Grace_archives_scripts() {
    // 注册并加载ECharts库
    wp_enqueue_script('echarts', 'https://cdn.jsdelivr.net/npm/echarts/dist/echarts.min.js', array(), null, true);

    // 注册自定义JS文件
    wp_enqueue_script('archives-script', 'https://huliku.com/wp-content/themes/zbfox/js/archives.js', array('echarts'), null, true);

    // 使用wp_localize_script将PHP变量传递给JavaScript
    $data_to_js = array(
        'vipChartData' => json_decode(get_user_vip_statistics(), true),
        'postChartData' => json_decode(get_category_statistics(), true),
        'heatmapData' => json_decode(get_heatmap_data(), true)
    );
    wp_localize_script('archives-script', 'chartData', $data_to_js);
}
add_action('wp_enqueue_scripts', 'Grace_archives_scripts');

// 获取头部样式
get_header();
$post_id = get_queried_object_id();
$header_style = zib_get_page_header_style($post_id);
?>

<!-- 页面主体 -->
<main class="container">
    <div class="content-wrap">
        <div class="content-layout">
            <?php if ($header_style != 1) { echo zib_get_page_header($post_id);} ?>
            <div class="theme-box radius8">
                <?php if ($header_style == 1) { echo zib_get_page_header($post_id);} ?>
                <article>
                        <div id="post" class="zib-widget" style="height:450px;"></div>
                        <div id="time" class="zib-widget" style="height:300px;"></div>
                        <div class="zib-widget">
                            <div class="title-theme mb20">文章归档<div class="pull-right em09 mt3"><a class="but c-blue radius" href="javascript:;" id="toggleAll">展开全部</a></div></div>
                            <div data-nav="posts" class="theme-box wp-posts-content">
                                <?php
                                    $previous_year = 0;
                                    $previous_month = 0;
                                    $collapse_id = 0;

                                    // 获取所有文章
                                    $myposts = get_posts('numberposts=-1&orderby=post_date&order=DESC');

                                    foreach ($myposts as $post) :
                                        setup_postdata($post);

                                        $year = mysql2date('Y', $post->post_date);
                                        $month = mysql2date('n', $post->post_date);

                                        if ($year != $previous_year || $month != $previous_month) :
                                            // 如果不是第一个月，则关闭上一个折叠面板
                                            if ($collapse_id > 0) {
                                                echo '</div></div></div>';
                                            }

                                            // 开始新的月份折叠面板
                                            $collapse_id++;
                                            echo '<div class="wp-block-zibllblock-collapse">';
                                            echo '<div class="panel" data-theme="panel" data-isshow="false">';
                                            echo '<div class="panel-heading collapsed" href="#collapse_' . esc_attr($collapse_id) . '" data-toggle="collapse" aria-controls="collapseExample" aria-expanded="false">';
                                            echo '<i class="fa fa-plus"></i><strong class="biaoti">' . get_the_time('Y年n月') . '</strong>';
                                            echo '</div>';
                                            echo '<div class="collapse" id="collapse_' . esc_attr($collapse_id) . '" aria-expanded="false" style="height: 0px;">';
                                            echo '<div class="panel-body">';
                                        endif;

                                        // 使用 add_shortcode_postsbox 函数输出单篇文章内容
                                        echo do_shortcode('');

                                        $previous_year = $year;
                                        $previous_month = $month;
                                    endforeach;

                                    // 关闭最后一个折叠面板（如果有）
                                    if ($collapse_id > 0) {
                                        echo '</div></div></div>';
                                    }

                                    wp_reset_query();
                                    wp_reset_postdata();
                                    ?>
                            </div>
                        </div>
                </article>

            </div>
            <?php comments_template('/template/comments.php', true); ?>
        </div>
    </div>
    
    <div class="sidebar">
        <div class="zib-widget" id="vip-user" style="height: 400px;"></div>
        <div class="flex ab jsb col-ml6 pointer">
            <?php render_archives_widgets('day'); ?>
            <?php render_archives_widgets('post'); ?>
        </div>

        <div class="flex ab jsb col-ml6 pointer">
            <?php render_archives_widgets('comment'); ?>
            <?php render_archives_widgets('user'); ?>
        </div>
    </div>
</main>

<script type="text/javascript">

</script>

<?php
// 加载页脚
get_footer();
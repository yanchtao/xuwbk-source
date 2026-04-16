/**
 * 前台用户管理插件脚本
 */
(function($) {
    'use strict';

    // 初始化
    function init() {
        bindEvents();
    }

    // 绑定事件
    function bindEvents() {
        // 监听表单提交
        $(document).on('submit', '#zibll-user-manage-form', handleFormSubmit);

        // 永久会员复选框联动
        $(document).on('change', 'input[name="vip_permanent"]', handlePermanentChange);

        // VIP等级变化时的处理
        $(document).on('change', 'select[name="vip_level"]', handleVipLevelChange);

        // 资产输入框聚焦效果
        $(document).on('focus', '.asset-input', function() {
            $(this).closest('.asset-item').addClass('focused');
        });

        $(document).on('blur', '.asset-input', function() {
            $(this).closest('.asset-item').removeClass('focused');
        });

        // 用户搜索
        $(document).on('click', '#um-search-btn', handleUserSearch);
        $(document).on('keypress', '#um-search-input', function(e) {
            if (e.which === 13) {
                handleUserSearch();
            }
        });
    }

    // 处理用户搜索
    function handleUserSearch() {
        var keyword = $('#um-search-input').val().trim();
        var $btn = $('#um-search-btn');
        var $results = $('#um-search-results');

        if (!keyword) {
            showMessage('请输入搜索关键词', 'warning');
            return;
        }

        $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');
        $results.html('<div class="text-center padding-lg"><i class="fa fa-spinner fa-spin em2x"></i></div>');

        $.ajax({
            url: xuwbk_user_manage.ajax_url,
            type: 'POST',
            data: {
                action: 'xuwbk_user_manage_search',
                keyword: keyword
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $results.html(response.data.html);
                } else {
                    $results.html('<div class="text-center c-red padding-lg">' + (response.data.msg || '搜索失败') + '</div>');
                }
            },
            error: function() {
                $results.html('<div class="text-center c-red padding-lg">请求失败，请重试</div>');
            },
            complete: function() {
                $btn.prop('disabled', false).html('<i class="fa fa-search"></i> 搜索');
            }
        });
    }

    // 处理表单提交
    function handleFormSubmit(e) {
        e.preventDefault();

        var $form = $(this);
        var $btn = $form.find('button[type="submit"]');
        var originalText = $btn.html();

        // 禁用按钮，显示加载状态
        $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin mr6"></i>保存中...');

        $.ajax({
            url: xuwbk_user_manage.ajax_url,
            type: 'POST',
            data: $form.serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    showMessage(response.data.msg, 'success');

                    // 关闭弹窗
                    if (response.data.hide_modal) {
                        setTimeout(function() {
                            $('.modal').modal('hide');
                        }, 500);
                    }

                    // 重定向到作者页面而不是刷新
                    if (response.data.redirect) {
                        setTimeout(function() {
                            window.location.href = response.data.redirect;
                        }, 1200);
                    }
                    // 兼容旧的reload参数
                    else if (response.data.reload) {
                        setTimeout(function() {
                            // 检查当前URL是否有效
                            if (window.location.pathname === '/user/user-manage') {
                                // 如果在无效URL，重定向到首页
                                window.location.href = home_url();
                            } else {
                                location.reload();
                            }
                        }, 1200);
                    }
                } else {
                    showMessage(response.data.msg || '操作失败', 'danger');
                }
            },
            error: function() {
                showMessage('请求失败，请重试', 'danger');
            },
            complete: function() {
                $btn.prop('disabled', false).html(originalText);
            }
        });
    }

    // 处理永久会员复选框变化
    function handlePermanentChange() {
        var $modal = $(this).closest('.user-manage-modal');
        var $dateInput = $modal.find('input[name="vip_exp_date"]');

        if ($(this).is(':checked')) {
            $dateInput.val('').prop('disabled', true);
        } else {
            $dateInput.prop('disabled', false);
        }
    }

    // 处理VIP等级变化
    function handleVipLevelChange() {
        var $modal = $(this).closest('.user-manage-modal');
        var $dateInput = $modal.find('input[name="vip_exp_date"]');
        var $permanentCheckbox = $modal.find('input[name="vip_permanent"]');

        if ($(this).val() == '0') {
            $dateInput.val('').prop('disabled', true);
            $permanentCheckbox.prop('checked', false).prop('disabled', true);
        } else {
            $permanentCheckbox.prop('disabled', false);
            if (!$permanentCheckbox.is(':checked')) {
                $dateInput.prop('disabled', false);
            }
        }
    }

    // 显示消息提示 - 使用主题notyf函数
    function showMessage(msg, type) {
        if (typeof notyf === 'function') {
            notyf(msg, type);
        } else {
            alert(msg);
        }
    }

    // DOM Ready
    $(document).ready(init);

})(jQuery);

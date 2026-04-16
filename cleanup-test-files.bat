@echo off
chcp 65001 >nul
echo =========================================
echo XuWbk 主题测试文件清理工具
echo =========================================
echo.

set THEME_DIR=G:\xuwbk\phpstudy_pro\WWW\127.0.0.2\wp-content\themes\XuWbk
echo 清理目录: %THEME_DIR%
echo.

set /a DELETED_COUNT=0

echo 开始清理测试文件...
echo.

REM 删除PHP测试脚本
echo 1. 删除PHP测试脚本...
if exist "%THEME_DIR%\test-download-style.php" (
    del "%THEME_DIR%\test-download-style.php"
    echo    ✓ 已删除: test-download-style.php
    set /a DELETED_COUNT+=1
)

if exist "%THEME_DIR%\调试-布局错位.php" (
    del "%THEME_DIR%\调试-布局错位.php"
    echo    ✓ 已删除: 调试-布局错位.php
    set /a DELETED_COUNT+=1
)

if exist "%THEME_DIR%\验证CEO风格显示.php" (
    del "%THEME_DIR%\验证CEO风格显示.php"
    echo    ✓ 已删除: 验证CEO风格显示.php
    set /a DELETED_COUNT+=1
)

if exist "%THEME_DIR%\optimize-performance.php" (
    del "%THEME_DIR%\optimize-performance.php"
    echo    ✓ 已删除: optimize-performance.php
    set /a DELETED_COUNT+=1
)

echo.
echo 2. 删除开发调试文档...

REM 删除开发文档
if exist "%THEME_DIR%\快速修复-CEO风格.md" (
    del "%THEME_DIR%\快速修复-CEO风格.md"
    echo    ✓ 已删除: 快速修复-CEO风格.md
    set /a DELETED_COUNT+=1
)

if exist "%THEME_DIR%\修复-内容页错位.md" (
    del "%THEME_DIR%\修复-内容页错位.md"
    echo    ✓ 已删除: 修复-内容页错位.md
    set /a DELETED_COUNT+=1
)

if exist "%THEME_DIR%\最终验证-CEO风格.md" (
    del "%THEME_DIR%\最终验证-CEO风格.md"
    echo    ✓ 已删除: 最终验证-CEO风格.md
    set /a DELETED_COUNT+=1
)

if exist "%THEME_DIR%\CEO风格-问题诊断.md" (
    del "%THEME_DIR%\CEO风格-问题诊断.md"
    echo    ✓ 已删除: CEO风格-问题诊断.md
    set /a DELETED_COUNT+=1
)

if exist "%THEME_DIR%\MANUAL-OPTIMIZATION-GUIDE.md" (
    del "%THEME_DIR%\MANUAL-OPTIMIZATION-GUIDE.md"
    echo    ✓ 已删除: MANUAL-OPTIMIZATION-GUIDE.md
    set /a DELETED_COUNT+=1
)

if exist "%THEME_DIR%\PERFORMANCE-OPTIMIZATION-GUIDE.md" (
    del "%THEME_DIR%\PERFORMANCE-OPTIMIZATION-GUIDE.md"
    echo    ✓ 已删除: PERFORMANCE-OPTIMIZATION-GUIDE.md
    set /a DELETED_COUNT+=1
)

echo.
echo 3. 删除冗余备份文件...

REM 删除备份文件
if exist "%THEME_DIR%\core\functions\component\xuwbk_dasong\assets\js\xuwbk-dasong.js.backup" (
    del "%THEME_DIR%\core\functions\component\xuwbk_dasong\assets\js\xuwbk-dasong.js.backup"
    echo    ✓ 已删除: xuwbk-dasong.js.backup
    set /a DELETED_COUNT+=1
)

if exist "%THEME_DIR%\core\functions\component\xuwbk_download\xuwbk_download.css" (
    del "%THEME_DIR%\core\functions\component\xuwbk_download\xuwbk_download.css"
    echo    ✓ 已删除: xuwbk_download.css (旧样式)
    set /a DELETED_COUNT+=1
)

echo.
echo =========================================
echo 清理完成！
echo.
echo 总共删除文件数: %DELETED_COUNT%
echo.
echo 建议操作:
echo   1. 检查网站功能是否正常
echo   2. 清除浏览器和WordPress缓存
echo   3. 查看 CLEANUP-REPORT.md 获取详细报告
echo =========================================
echo.
pause

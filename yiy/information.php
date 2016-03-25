<?php
/**
 * 米壱屋お知らせページ
 *  各種お知らせエントリへのリンクページ
 */
require_once('config.php');
require_once('Smarty.class.php');

$smarty = new Smarty();
$smarty->template_dir = DIR_TEMPLATE;
$smarty->compile_dir  = DIR_COMPILE;

$smarty->assign('title', '米壱屋お知らせ');
$smarty->display('information.tpl');
?>

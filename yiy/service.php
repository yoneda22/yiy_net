<?php
/**
 * 米壱屋サービス紹介ページ
 *  簡単な導入もここ
 */
require_once('config.php');
require_once('Smarty.class.php');

$smarty = new Smarty();
$smarty->template_dir = DIR_TEMPLATE;
$smarty->compile_dir  = DIR_COMPILE;

$smarty->assign('title', '米壱屋サービス');
$smarty->display('service.tpl');
?>


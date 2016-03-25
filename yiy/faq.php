<?php
/**
 * 米壱屋紹介ページ
 *  FAQ形式で
 */
require_once('config.php');
require_once('Smarty.class.php');

$smarty = new Smarty();
$smarty->template_dir = DIR_TEMPLATE;
$smarty->compile_dir  = DIR_COMPILE;

$smarty->assign('title', '米壱屋紹介');
$smarty->display('faq.tpl');
?>

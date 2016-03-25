<?php
/**
 * スキルタグIF
 */
require_once 'config.php';
require_once STG_DB_ . 'UserDao.php';
require_once STG_DB_ . 'ProjectDao.php';
require_once STG_DEBUG;
require_once STG_LOG;
require_once 'Auth/Auth.php';
require_once 'Smarty.class.php';

// リクエストログ出力
$logger =& LogSkillTag::getInstance();
$logger->info(__FILE__ . " START");

// プロジェクト情報取得
$pDao = new ProjectDao;
$skilltag = $pDao->getSkillTag($_REQUEST['user_id']);

// 画面出力
$smarty = new Smarty();
$smarty->template_dir = DIR_TEMPLATE;
$smarty->compile_dir  = DIR_COMPILE;
$smarty->assign('title', 'スキルタグ');
$smarty->assign('skilltag', $skilltag);
$smarty->display('skilltag_base.tpl');

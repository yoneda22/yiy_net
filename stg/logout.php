<?php
/**
 * スキルタグIF
 */
require_once 'config.php';
require_once STG_DB_ . 'UserDao.php';
require_once STG_DEBUG;
require_once STG_LOG;
require_once 'Auth/Auth.php';

// リクエストログ出力
$logger =& LogSkillTag::getInstance();
$logger->info(__FILE__ . " START");


// パラメタチェック(サンプルモード)
// 認証情報取得
$auth = new Auth("MDB2", getAuthParams(), "", false);
if ($auth->getAuth()) {
    $auth->logout();
} else {
    $logger->info(__FILE__ . " " . "未認証のユーザがアクセスしました。");
}
header("HTTP/1.1 301 Moved Permanently");
header("Location: " . URL_HOST);

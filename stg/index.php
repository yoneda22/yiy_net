<?php
/**
 * スキルタグTOPページ
 */
require_once 'config.php';
require_once 'Auth/Auth.php';
require_once STG_DEBUG;
require_once STG_DB_ . 'UserDao.php';
require_once 'Smarty.class.php';

// 認証情報取得
$auth = new Auth("MDB2", getAuthParams(), "", false);
$auth->start();
$loginMessage = '';
if ($auth->getAuth()){
    $dao = new UserDao;
    $profile = $dao->getProfileByAddress($auth->getUsername());
} else {
   if (isset($_REQUEST['before'])) {
       $loginMessage = "メールアドレスもしくはパスワードが間違っています。";
   }
}

$smarty = new Smarty();
$smarty->template_dir = DIR_TEMPLATE;
$smarty->compile_dir  = DIR_COMPILE;

$smarty->assign('title', 'スキルタグTOP');
$smarty->assign('js_fname', 'index.js');
$smarty->assign('login', $auth->getAuth());
$smarty->assign('login_message', $loginMessage);
if (isset($profile))
$smarty->assign('user_name', $profile['name']);
$smarty->display('index.tpl');

?>

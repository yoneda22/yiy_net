<?php
/**
 * スキルタグユーザ登録ページ
 */
require_once('config.php');
require_once(STG_LOG);
require_once(STG_DEBUG);
require_once(STG_WEBHELPER);
require_once(STG_DB_ . "UserDao.php");
require_once(STG_DB_ . "MasterAddressDao.php");
require_once('Smarty.class.php');

$logger =& LogSkillTag::getInstance();
$logger->debug(Debug::getStringVarDump($_REQUEST));

// パラメタチェック
$ret =  validates($_REQUEST);
if ($ret !== TRUE) {
    $logger->err(__METHOD__ . " " . "不正なアクセスを検出(0)");
    header("HTTP/1.1 403 Forbidden");
    return;
}
// ユーザ情報取得
$dao = new UserDao;
$profile = $dao->getProfileByAddress(WebServiceHelper::simpleUnMaskParameter($_REQUEST['k']));
if (empty($profile)) {
    $logger->err(__METHOD__ . " " . "不正なアクセスを検出(1)");
    header("HTTP/1.1 403 Forbidden");
    return;
}
// 住所のoption要素を取得
$dao = new MasterAddressDao;
$address = $dao->getListForOption();
$options = WebServiceHelper::getOptions($address, 13);

// 画面出力
$smarty = new Smarty();
$smarty->template_dir = DIR_TEMPLATE;
$smarty->compile_dir  = DIR_COMPILE;

$smarty->assign('title', 'スキルタグユーザ登録');
$smarty->assign('js_fname', 'regist.js');
$smarty->assign('mailaddress', WebServiceHelper::simpleUnMaskParameter($_REQUEST['k']));
$smarty->assign('id', $profile['id']);
$smarty->assign('options', $options);
$smarty->display('regist.tpl');

// 入力チェック
function validates($req)
{
    // null
    if (empty($req['k'])) return false;

    // 仮登録状態か？
    $dao = new UserDao;
    if (!$dao->isTemp(WebServiceHelper::simpleUnMaskParameter($req['k']))) return false;

    return true;
}

?>

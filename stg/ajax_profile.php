<?php
/**
 * ajaxIF プロフィール編集
 */
require_once 'config.php';
require_once STG_DB_ . 'UserDao.php';
require_once STG_DEBUG;
require_once STG_LOG;
require_once 'Auth/Auth.php';
require_once 'Smarty.class.php';

// パラメタのキー
define("APR_KEY_ID",       "id");
define("APR_KEY_NAME" ,    "name");
define("APR_KEY_BIRTHDAY", "birthday");
define("APR_KEY_ADDRESS",  "address");
define("APR_KEY_STATION",  "station");

// リクエストログ出力
$logger =& LogSkillTag::getInstance();
$logger->info(__FILE__ . " START");

$ret = true;

// パラメタチェック(サンプルモード)
$sampleMode = 0;
if (isset($_REQUEST['sample']) && $_REQUEST['sample'] == '1') {
    $sampleMode = 1;
    $mailaddress = "sample@s.yoneichiya.net";
} else {
    // 認証情報取得
    $auth = new Auth("MDB2", getAuthParams(), "", false);
    if ($auth->getAuth()) {
        $mailaddress = $auth->getUsername();
    } else {
        $logger->info(__FILE__ . " " . "未認証のユーザがアクセスしました。");
        $ret = "再ログインしてください。";
    }
}

// リクエストパラメータチェック
$logger->debug("REQUEST=" . Debug::getStringVarDump($_REQUEST));
if ($ret === TRUE) $ret = validates($_REQUEST);

// プロフィール編集
if ($ret === TRUE) $ret = updateProfile($_REQUEST);

// プロフィール情報取得
$dao = new UserDao;
$profile = $dao->getProfileByAddress($mailaddress);
if (empty($profile)) {
    $ret = false;
}
 
// プロフィール一覧生成
if (isset($profile)) {
    $smarty = new Smarty();
    $smarty->template_dir = DIR_TEMPLATE;
    $smarty->compile_dir  = DIR_COMPILE;
    $smarty->assign('profile', $profile);
    $list = $smarty->fetch('profile_list.tpl');
}

// 結果送信
if ($ret === TRUE) {
   $json_array = array('result' => 'OK', 'list' => $list);
} else {
   $json_array = array('result' => 'NG', 'message' => $ret);
}
header(HEAD_JSON);
$json = json_encode($json_array);
echo $json;

// 処理結果ログ出力
$logger->info(__FILE__ . " END");

// 入力チェック
function validates($req)
{
    // null?
    if (empty($req[APR_KEY_ID]))       return "FATAL_SERVER_ERROR(1)";
    if (empty($req[APR_KEY_NAME]))     return "FATAL_SERVER_ERROR(2)";
    if (empty($req[APR_KEY_BIRTHDAY])) return "FATAL_SERVER_ERROR(3)";
    if (empty($req[APR_KEY_ADDRESS]))  return "FATAL_SERVER_ERROR(4)";
    if (empty($req[APR_KEY_STATION]))  return "FATAL_SERVER_ERROR(5)";

    return true;
}
// ユーザ編集
function updateProfile($req)
{
    $dao = new UserDao;
    $ret = $dao->updateUser(
             $req[APR_KEY_ID], $req[APR_KEY_NAME], $req[APR_KEY_BIRTHDAY], $req[APR_KEY_ADDRESS], $req[APR_KEY_STATION]
    );
    if ($ret !== TRUE) {
        $ret = "FATAL_SERVER_ERROR(DB)";
    }
    return $ret;
}

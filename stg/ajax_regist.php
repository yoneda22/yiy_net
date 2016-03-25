<?php
/**
 * ajaxIF ユーザ登録
 */
require_once('config.php');
require_once(STG_DB_ . "UserDao.php");
require_once(STG_DEBUG);
require_once(STG_LOG);

// パラメタのキー
define("ARG_KEY_ID",       "id"); 
define("ARG_KEY_NAME" ,    "name");
define("ARG_KEY_PASSWORD", "password");
define("ARG_KEY_BIRTHDAY", "birthday");
define("ARG_KEY_ADDRESS",  "address");
define("ARG_KEY_STATION",  "station");

// リクエストログ出力
$logger =& LogSkillTag::getInstance();
$logger->info(__FILE__ . " START");

$ret = true;

// リクエストパラメータチェック
$logger->debug(Debug::getStringVarDump($_REQUEST));
$ret = validates($_REQUEST);

// プロフィール編集
if ($ret === TRUE) $ret = updateUser($_REQUEST);

// 結果送信
header(HEAD_DEFAULT);
if ($ret === TRUE) {
   echo "OK";
} else {
   echo $ret;
}

// 処理結果ログ出力
$logger->info(__FILE__ . " END");

// 入力チェック
function validates($req)
{
    // null?
    if (empty($req[ARG_KEY_ID]))       return "FATAL_SERVER_ERROR(1)";
    if (empty($req[ARG_KEY_NAME]))     return "FATAL_SERVER_ERROR(2)";
    if (empty($req[ARG_KEY_PASSWORD])) return "FATAL_SERVER_ERROR(3)";
    if (empty($req[ARG_KEY_BIRTHDAY])) return "FATAL_SERVER_ERROR(4)";
    if (empty($req[ARG_KEY_ADDRESS]))  return "FATAL_SERVER_ERROR(5)";
    if (empty($req[ARG_KEY_STATION]))  return "FATAL_SERVER_ERROR(6)";

    return true;
}
// ユーザ編集
function updateUser($req)
{
    $dao = new UserDao;
    $ret = $dao->updateUser(
             $req[ARG_KEY_ID], $req[ARG_KEY_NAME], $req[ARG_KEY_BIRTHDAY], $req[ARG_KEY_ADDRESS], $req[ARG_KEY_STATION], $req[ARG_KEY_PASSWORD]
    );
    if ($ret !== TRUE) {
        $ret = "FATAL_SERVER_ERROR(DB)";
    }
    return $ret;
}

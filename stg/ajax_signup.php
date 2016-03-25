<?php
/**
 * ajaxIF サインアップ開始
 */
require_once('config.php');
require_once(STG_MAIL);
require_once(STG_DB_ . "UserDao.php");
require_once(STG_DEBUG);
require_once(STG_LOG);

define("ASU_KEY_ADDRESS" , "address"); // パラメタのキー

$logger =& LogSkillTag::getInstance();

// リクエストログ出力
$logger->info(__FILE__ . " START");

$ret = true;

// リクエストパラメータチェック
$logger->debug(Debug::getStringVarDump($_REQUEST));
$ret = validates($_REQUEST);

// 仮ユーザ情報挿入
if ($ret === TRUE) $ret = insertSignUpUser($_REQUEST[ASU_KEY_ADDRESS]);

// メール送信
if ($ret === TRUE) $ret = sendSignUpMail($_REQUEST[ASU_KEY_ADDRESS]);

// 結果送信
header(HEAD_DEFAULT);
if ($ret === TRUE) {
   echo "OK";
} else {
   echo $ret;
}

// 処理結果ログ出力
$logger->info(__FILE__ . " END");

function validates($req)
{
    // null
    if (!array_key_exists(ASU_KEY_ADDRESS, $req) || empty($req[ASU_KEY_ADDRESS])) {
        return "FATAL_SERVER_ERROR";
    }

    // 登録済みか?
    $dao = new UserDao;
    if ($dao->isMember($req[ASU_KEY_ADDRESS])) {
        return "このメールアドレスは、既に登録されています。";
    }

    return true;
}

function insertSignUpUser($address)
{
    $dao = new UserDao;
    if ($dao->isTemp($address)) {
        // 既に仮登録されているので、登録処理を行なわない
        return true;
    }

    $ret = $dao->insertSignUpUser($address);
    if ($ret !== TRUE) {
        $ret = "FATAL_SERVER_ERROR(DB)";
    }
    return $ret;
}

function sendSignUpMail($address)
{
    $mail = new MailSkillTag;
    $ret = $mail->sendSignUpMail($address);
    if ($ret !== TRUE) {
        $ret = "FATAL_SERVER_ERROR(MAIL)";
    }
    return $ret;
}
?>

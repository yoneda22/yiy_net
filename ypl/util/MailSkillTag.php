<?php
/**
 * スキルタグ メールクラス
 */
require_once('Mail.php');
require_once('/var/www/yiy_net/ypl/log/LogSkillTag.php');
require_once('/var/www/yiy_net/ypl/util/WebServiceHelper.php');

class MailSkillTag {

    private $_logger = null;

    function __construct()
    {
        $this->_logger =& LogSkillTag::getInstance();
    }

    /**
     *
     *
     * @return true:成功、false:失敗
     */
    function sendSignUpMail($recipient)
    {
        $title = 'スキルタグ仮登録ご案内';
        $key = WebServiceHelper::simpleMaskParameter($recipient);
        $body =<<<MAIL_BODY
スキルタグへの仮登録を受け付けました。
以下のリンクをクリックして、ユーザ登録を完了してください。

http://s.yoneichiya.net/regist.php?k={$key}


＊このメールには返信できません。


================================
スキルタグ
http://s.yoneichiya.net/
================================
MAIL_BODY;

        $mail =& Mail::factory('sendmail');
        $headers = array(
            "To"      => $recipient,
            "From"    => "SKILLTAG SYSTEM <sys@yoneichiya.net>",
            "Subject" => mb_convert_encoding($title, "ISO-2022-JP", "auto")
        );
        $body = mb_convert_encoding($body, "ISO-2022-JP", "auto");
        $ret = $mail -> send($recipient, $headers, $body);
        if (PEAR::isError($ret)) {
            $this->_logger->err(__METHOD__ . " " . $ret->getMessage());
            return false;
        }

        return true;
    }

}

?>

<?php
/**
 * 実行ログ出力クラス
 */
require_once('Log.php');

class LogSkillTag {

    static function &getInstance()
    {
        $conf = array('timeFormat'=>'%Y/%m/%d %H:%M:%S');
        $logger =& Log::factory('file', '/var/log/application/skilltag.log', 'STAG', $conf, PEAR_LOG_DEBUG);

        return $logger;
    }
}
?>

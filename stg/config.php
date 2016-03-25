<?php
require_once('/var/www/yiy_net/ypl/util/spyc.php');

// 共通定数
define('HEAD_DEFAULT', "Content-Type: text/html; charset=UTF-8");
define('HEAD_JSON',    "Content-Type: application/json; charset=UTF-8");
define('HEAD_PDF',     "Content-Type: application/pdf");
define('HEAD_OCTET',   "Content-Type: application/octet-stream");

// スキルタグ定数
define('DIR_TEMPLATE', './templates');
define('DIR_COMPILE',  './templates/templates_c');

define('URL_HOST'   , 'http://s.yoneichiya.net/');
define('URL_INDEX'  , 'index.php');
define('URL_REGIST' , 'regist.php');
define('URL_DETAIL',  'detail.php');
define('URL_DL',      'download.php');
define('URL_LOGOUT',  'logout.php');

define('URL_HOME', 'http://yoneichiya.net/');

define('DOCUMENT_ROOT', '/var/www/yiy_net/stg/');
define('TMP_ROOT',      '/var/www/yiy_net/tmp/');
define('YPL_ROOT',      '/var/www/yiy_net/ypl/');
define('STG_DB_',       YPL_ROOT . 'db/');
define('STG_LOG',       YPL_ROOT . 'log/LogSkillTag.php');
define('STG_DEBUG',     YPL_ROOT . 'util/Debug.php');
define('STG_MAIL',      YPL_ROOT . 'util/MailSkillTag.php');
define('STG_WEBHELPER', YPL_ROOT . 'util/WebServiceHelper.php');

function getAuthParams()
{
    // read config
    $config = spyc::YAMLLoad('/var/www/yiy_net/cnf/setting.yaml');
    $user = $config['production']['db']['user'];
    $password = $config['production']['db']['password'];

    return array(
        "dsn" => "mysql://{$user}:{$password}@localhost:3306/skilltag",
        "table" => "user",
        "usernamecol" => "mailaddress",
        "passwordcol" => "password"
    );
}
?>

<?php
/**
 * ダウンロードIF
 */
require_once 'config.php';
require_once STG_DB_ . 'UserDao.php';
require_once STG_DEBUG;
require_once STG_LOG;
require_once 'Auth/Auth.php';

// リクエストログ出力
$logger =& LogSkillTag::getInstance();
$logger->info(__FILE__ . " START");

$ret = true;

// パラメタチェック(サンプルモード)
$sampleMode = false;
if (isset($_REQUEST['k']) && $_REQUEST['k'] == 'sample') {
    $sampleMode = true;
    $mailaddress = "sample@s.yoneichiya.net";
} else {
    // 認証情報取得
    $auth = new Auth("MDB2", getAuthParams(), "", false);
    if ($auth->getAuth()) {
        $mailaddress = $auth->getUsername();
    } else {
        $logger->info(__FILE__ . " " . "未認証のユーザがアクセスしました。");
        header("HTTP/1.1 403 Forbidden");
        return;
    }
}

// プロフィール情報取得
$dao = new UserDao;
$profile = $dao->getProfileByAddress($mailaddress);
if (empty($profile)) {
    $ret = false;
}

// リクエストパラメータチェック
$logger->debug("REQUEST=" . Debug::getStringVarDump($_REQUEST));

if (empty($_REQUEST['m'])) $ret = false;
if ($ret === TRUE) {

    if ($_REQUEST['m'] == 'st') {
       // JPGファイル生成
       $createFilePath = createJPG($profile['id']);
       $downloadFilename = 'skilltag.jpg';
    } else {
       // HTMLファイル生成
       $html = createHtmlSkillSheet($profile);
       // PDFファイル生成
       $createFilePath = createPDF($profile['id'], $html);
       $downloadFilename = 'skillsheet.pdf';
    }

    // PDF読み込み
    $read_data = file_get_contents($createFilePath);     

    // スキルタグ読み込み
    header(HEAD_OCTET);
    header("Content-Disposition: attachment; filename=\"{$downloadFilename}\"");
    header('Content-Length: '. filesize($createFilePath));

    echo $read_data;
}

$logger->info(__FILE__ . " END");

function createHtmlSkillSheet($profile) {

    global $logger;

    // ファイル確認
    $targetFilePath = "/var/www/yiy_net/tmp/{$profile['id']}_ss.html";
    $logger->debug("htmlPath=" . $targetFilePath);
    if (file_exists($targetFilePath)) unlink($targetFilePath);

    // プロジェクト情報読み込み
    require_once STG_DB_ . 'ProjectDao.php';
    require_once STG_DB_ . 'SkillDao.php';
    require_once STG_WEBHELPER;
    $pDao = new ProjectDao;
    $projects = $pDao->getProjectsForSkillSheet($profile['id']);
    $sDao = new SkillDao;
    $profile_skill = $sDao->getProfileSkills($profile['id']);

    // 年齢計算
    $profile['age'] = WebServiceHelper::getAge($profile['birthday']);

    // ファイル生成
    require_once 'Smarty.class.php';
    $smarty = new Smarty();
    $smarty->template_dir = DIR_TEMPLATE;
    $smarty->compile_dir  = DIR_COMPILE;
    $smarty->assign('create_at', date('Y年m月d日'));
    $smarty->assign('profile', $profile);
    $smarty->assign('profile_skill', $profile_skill);
    $smarty->assign('projects', $projects['projects']);
    $smarty->assign('exp',      $projects['exp']);
    file_put_contents($targetFilePath, $smarty->fetch('skillsheet.tpl'));

    return $targetFilePath;   
}

function createPDF($user_id, $html) {

    global $logger;

    $targetFilePath = "/var/www/yiy_net/tmp/{$user_id}_ss.pdf";
    $command = "/usr/bin/xvfb-run /usr/bin/wkhtmltopdf --page-size A4 {$html} {$targetFilePath}";
    //$logger->debug(__METHOD__ . " command=" . $command);
    exec($command);
    //$logger->debug(__METHOD__ . " result=" . Debug::getStringVarDump($result));
    return $targetFilePath;
}

function createJPG($user_id) {

    global $logger;

    $targetFilePath = "/var/www/yiy_net/tmp/{$user_id}_st.jpg";
    $command = "/usr/bin/xvfb-run /usr/local/bin/wkhtmltoimage --crop-h 328 --crop-w 534 http://s.yoneichiya.net/skilltag.php?user_id={$user_id} {$targetFilePath}";
    //$logger->debug(__METHOD__ . " command=" . $command);
    exec($command);
    //$logger->debug(__METHOD__ . " result=" . Debug::getStringVarDump($result));
    return $targetFilePath;

}

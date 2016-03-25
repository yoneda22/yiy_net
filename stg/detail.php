<?php
/**
 * スキルタグユーザ登録ページ
 */
require_once 'config.php';
require_once STG_LOG;
require_once STG_DEBUG;
require_once STG_WEBHELPER;
require_once STG_DB_ . 'UserDao.php';
require_once STG_DB_ . 'ProjectDao.php';
require_once STG_DB_ . 'MasterAddressDao.php';
require_once STG_DB_ . 'MasterSkillDao.php';
require_once 'Auth/Auth.php';
require_once 'Smarty.class.php';

$logger =& LogSkillTag::getInstance();
$logger->debug(Debug::getStringVarDump($_REQUEST));

// パラメタチェック(サンプルモード)
$sampleMode = 0;
if (isset($_REQUEST['k']) && $_REQUEST['k'] == 'sample') {
    $sampleMode = 1;
    $mailaddress = "sample@s.yoneichiya.net";
} else {
    // 認証情報取得
    $auth = new Auth("MDB2", getAuthParams(), "", false);
    $auth->setExpire(12 * 60 * 60);
    if ($auth->getAuth()) {
        $mailaddress = $auth->getUsername();
    } else {
        $logger->info(__FILE__ . " " . "未認証のユーザがアクセスしました。");
        header("HTTP/1.1 301 Moved Permanently");
        header("Location: " . URL_HOST);
        return;
    }
}

// ユーザ情報取得
$dao = new UserDao;
$profile = $dao->getProfileByAddress($mailaddress);
if (empty($profile)) {
    $logger->err(__METHOD__ . " " . "不正なアクセスを検出");
    header("HTTP/1.1 403 Forbidden");
    return;
}

// プロジェクト情報取得
$pDao = new ProjectDao;
$projects = $pDao->getProjects($profile['id']);
$skilltag = $pDao->getSkillTag($profile['id']);
//Debug::varDumpHtml($projects);

// ユーザスキル情報取得

// 住所のoption要素を取得
$maDao = new MasterAddressDao;
$address = $maDao->getListForOption();
$options = WebServiceHelper::getOptions($address);

// 期間option用をを取得
$term_options = WebServiceHelper::getOptionsYM3();

// スキルタグパレット取得
$dao = new MasterSkillDao;
$skill_tag = $dao->getList();
$pallet = WebServiceHelper::getSkillTagPallet($skill_tag);
$skills = WebServiceHelper::getSkillTagMaster($skill_tag);

// 画面出力
$smarty = new Smarty();
$smarty->template_dir = DIR_TEMPLATE;
$smarty->compile_dir  = DIR_COMPILE;

$smarty->assign('title', 'スキルタグユーザページ');
$smarty->assign('js_fname', 'detail.js');
$smarty->assign('profile', $profile);
$smarty->assign('projects', $projects);
$smarty->assign('projects_count', count($projects));
$smarty->assign('skilltag', $skilltag);
$smarty->assign('options', $options);
$smarty->assign('term_options', $term_options);
$smarty->assign('pallet', $pallet);
$smarty->assign('skills', $skills);
$smarty->assign('sample', $sampleMode);
$smarty->display('detail.tpl');

?>

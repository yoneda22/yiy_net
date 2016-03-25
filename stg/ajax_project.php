<?php
/**
 * ajaxIF プロフィール編集
 */
require_once 'config.php';
require_once STG_DB_ . 'ProjectDao.php';
require_once STG_DB_ . 'SkillDao.php';
require_once STG_DB_ . 'MasterSkillDao.php';
require_once STG_DEBUG;
require_once STG_LOG;
require_once STG_WEBHELPER;
require_once 'Auth/Auth.php';
require_once 'Smarty.class.php';

// パラメタのキー
define("APJ_KEY_ID",        "id");
define("APJ_KEY_USER_ID",   "user_id");
define("APJ_KEY_AVAILABLE", "available");
define("APJ_KEY_NAME" ,     "name");
define("APJ_KEY_NOTE" ,     "note");
define("APJ_KEY_START" ,    "start");
define("APJ_KEY_END" ,      "end");
define("APJ_KEY_SKILLS" ,   "skills");

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

// 削除モード
if (!empty($_REQUEST['delete'])) {
    $ret = deleteProject($_REQUEST['id']);
} else {
    // 追加編集モード
    if ($ret === TRUE) $ret = validates($_REQUEST);
    // プロジェクト情報登録
    if ($ret === TRUE) $ret = registeProject($_REQUEST);
}

// プロジェクト情報取得
$pDao = new ProjectDao;
$projects = $pDao->getProjects($_REQUEST[APJ_KEY_USER_ID]);
$userstag = $pDao->getSkillTag($_REQUEST[APJ_KEY_USER_ID]);

// スキルテンプレート取得
$dao = new MasterSkillDao;
$skill_tag = $dao->getList();
$skills = WebServiceHelper::getSkillTagMaster($skill_tag);

// プロジェクト一覧生成
$smarty = new Smarty();
$smarty->template_dir = DIR_TEMPLATE;
$smarty->compile_dir  = DIR_COMPILE;
$smarty->assign('projects', $projects);
$smarty->assign('skills', $skills);
$list = $smarty->fetch('project_list.tpl');
// スキルタグ生成
$smarty = new Smarty();
$smarty->template_dir = DIR_TEMPLATE;
$smarty->compile_dir  = DIR_COMPILE;
$smarty->assign('skilltag', $userstag);
$skilltag = $smarty->fetch('skilltag.tpl');

// 結果送信
if ($ret === TRUE) {
   $json_array = array('result' => 'OK', 'list' => $list, 'skilltag' => $skilltag, 'projects_count' => count($projects));
} else {
   $json_array = array('result' => 'NG', 'message' => $ret);
}
header(HEAD_JSON);
$json = json_encode($json_array);
echo $json;

$logger->info(__FILE__ . " END");

// 入力チェック
function validates($req) {
    // null?
    if (empty($req[APJ_KEY_USER_ID])) return "FATAL_SERVER_ERROR(1)";
    if (empty($req[APJ_KEY_NAME]))    return "FATAL_SERVER_ERROR(2)";
    if (empty($req[APJ_KEY_START]))   return "FATAL_SERVER_ERROR(3)";
    if (empty($req[APJ_KEY_END]))     return "FATAL_SERVER_ERROR(4)";

    return true;
}

// プロジェクト情報削除
function deleteProject($project_id) {
    // プロジェクト情報削除
    $pDao = new ProjectDao;
    $ret = $pDao->deleteLogical($project_id);
    if ($ret === FALSE) return "FATAL_SERVER_ERROR(DB4)";

    // スキル情報削除
    $sDao = new SkillDao;
    $ret = $sDao->deleteLogical($project_id);
    if ($ret === FALSE) return "FATAL_SERVER_ERROR(DB5)";

    return true;
}

// プロジェクト情報登録
function registeProject($req) {
    // プロジェクト情報登録
    $pDao = new ProjectDao;
    $ret = $pDao->replaceInto(
            $req[APJ_KEY_ID],
            $req[APJ_KEY_USER_ID],
            $req[APJ_KEY_NAME],
            $req[APJ_KEY_NOTE],
            $req[APJ_KEY_START],
            $req[APJ_KEY_END],
            $req[APJ_KEY_AVAILABLE]
    );
    if ($ret === FALSE) return "FATAL_SERVER_ERROR(DB1)";

    $project_id = $req[APJ_KEY_ID];
    if (empty($req[APJ_KEY_ID])) $project_id = mysql_insert_id();

    // スキル情報登録
    $sDao = new SkillDao;
    if (!empty($req[APJ_KEY_SKILLS])) {
        $ret = $sDao->insertSkills($req[APJ_KEY_USER_ID], $project_id, $req[APJ_KEY_SKILLS]);
        if ($ret === FALSE) return "FATAL_SERVER_ERROR(DB2)";
    } else {
        $ret = $sDao->delete($project_id);
        if ($ret === FALSE) return "FATAL_SERVER_ERROR(DB3)";
    }

    return true;
}

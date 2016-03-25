<?php
/**
 * 米壱屋TOPページ
 */
require_once('config.php');
require_once('Smarty.class.php');

/** 更新履歴の表示件数		*/
define("HISTORY_MAX", 5);

// 更新履歴取得
$array = array();
$res = @file_get_contents(URL_RSS);
$res = str_replace('<dc:date>', '<date>', $res);
$res = str_replace('</dc:date>', '</date>', $res);
if (!$res === false) {
    $rss = simplexml_load_string($res); //var_dump($rss);
    for ($index = 0; $index < count($rss->item); $index++) {
        //var_dump($rss->item[$index]);
        if (!empty($rss->item[$index]->title) && preg_match("/^PR:/", $rss->item[$index]->title) == 0) {
           $array[] = array(
                   'title' => $rss->item[$index]->title,
                   'date'  => date('Y.m.d',strtotime($rss->item[$index]->date)),
                   'link'  => $rss->item[$index]->link
           );
        }
        if (count($array) >= HISTORY_MAX) break;
    }
}
//var_dump($array);
$smarty = new Smarty();
$smarty->template_dir = DIR_TEMPLATE;
$smarty->compile_dir  = DIR_COMPILE;

$smarty->assign('title', '米壱屋TOP');
$smarty->assign('array_history', $array);
$smarty->display('index.tpl');
?>

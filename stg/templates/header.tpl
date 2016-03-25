<!DOCTYPE html>
<html lang="ja-JP">
<head>
<meta charset="utf-8">
<meta name="keywords" content="***">
<meta name="description" content="***">

<title>{$title}</title>

<!-- ビューポート設定 -->
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- 電話番号自動認識機能 -->
<meta name="format-detection" content="telephone=no">

<!-- IE8以下にhtml5を対応させる（PCのみ） -->
<!--[if lt IE 9]>
<script src="./js/IE9.js"></script>
<![endif]-->

<!-- CSS -->
<link rel="stylesheet" href="./css/reset.css"         type="text/css">
<link rel="stylesheet" href="./css/common.css"        type="text/css">
<link rel="stylesheet" href="./css/asset.css"         type="text/css">
<link rel="stylesheet" href="./css/skilltag.css"      type="text/css">
<link rel="stylesheet" href="./css/jquery-ui.min.css" type="text/css">
<link rel="stylesheet" href="./css/ex-jquery-ui.css"  type="text/css">
<!-- JS -->
<script src="./js/jquery.min.js"></script>
<script src="./js/jquery-ui.min.js"></script>
<script src="./js/yjl.js"></script>
<script src="./js/{$js_fname}"></script>
<script type="text/javascript">
{literal}
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-37326905-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
{/literal}
</script>
<!-- ↑↑↑ head要素ここまで ↑↑↑ -->
</head>
<body>
<div id="TOP">
<div id="gHeader">
        <a href="{$smarty.const.URL_HOST}"><img src="img/logo.png" width="614" alt="スキルタグ"></a>
        <h1 class="dNone">スキルタグ</h1>
</div>

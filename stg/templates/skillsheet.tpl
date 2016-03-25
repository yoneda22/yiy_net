<!DOCTYPE html>
<html lang="ja-JP">
<head>
<meta charset="utf-8">

<title>>職務経歴書</title>
<style type="text/css">
{literal}
#content {
    font-size: 17px;
    font-family: "IPA Pゴシック","IPA PGothic";
    width: 960px;
    margin: 2% auto;
    border-width:1px;
    border-color:#000000; 
}
h2 {
    text-align: center;
    text-decoration:underline;
}
#create_at {
    text-align: right;
}
table {
    width: 100%;
    border: solid 1px #000000; border-collapse: collapse;
}
th {
    padding: 5px;
    width: 140px;
    text-align: left;
    background-color: #DEDEDE;
    border: solid 1px #000000;
}
td {
    padding: 5px;
    border: solid 1px #000000;
}
#last {
    text-align: right;
}
#logo {
    text-align: center;
    font-size: 12px;
    color: #6E6E6E;
}
{/literal}
</style>
</head>
<body>

<div id="content">
    <h2>職務経歴書</h2>
    <div id="create_at">{$create_at}</div>
    <h3>■概要</h3>
    <table id="profile">
        <tr>
            <th>氏名</th>
            <td>{$profile.name}</td>
        </tr>
        <tr>
            <th>年齢</th>
            <td>{$profile.age}歳</td>
        </tr>
        <tr>
            <th>居住地</th>
            <td>{$profile.address_label}</td>
        </tr>
        <tr>
            <th>最寄駅</th>
            <td>{$profile.station}</td>
        </tr>
        <tr>
            <th>経験年数</th>
            <td>{$exp/12|floor}年{$exp%12}ヵ月</td>
        </tr>
        <tr>
            <th>経験言語・環境</th>
            <td>{foreach from=$profile_skill key=mykey item=skill}{if $skill.skill_category != 2}{$skill.skill_name}, {/if}{/foreach}</td>
        </tr>
        <tr>
            <th>経験DB</th>
            <td>{foreach from=$profile_skill key=mykey item=skill}{if $skill.skill_category == 2}{$skill.skill_name}, {/if}{/foreach}</td>
        </tr>
    </table>

    <h3>■職務経歴</h3>
{foreach from=$projects key=mykey item=project}
    <table id="project">
        <tr>
            <th>期間</th>
            <td>{$project.start_label} ～ {$project.end_label}</td>
        </tr>
        <tr>
            <th>業務内容</th>
            <td>{$project.name}<br />{$project.note}</td>
        </tr>
        <tr>
            <th>OS</th>
            <td>{foreach from=$project.skills key=mykey item=skill}{if $skill.skill_category == 3}{$skill.skill_name}, {/if}{/foreach}</td>
        </tr>
        <tr>
            <th>言語</th>
            <td>{foreach from=$project.skills key=mykey item=skill}{if $skill.skill_category == 1}{$skill.skill_name}, {/if}{/foreach}</td>
        </tr>
        <tr>
            <th>DB</th>
            <td>{foreach from=$project.skills key=mykey item=skill}{if $skill.skill_category == 2}{$skill.skill_name}, {/if}{/foreach}</td>
        </tr>
        <tr>
            <th>その他の<br />利用ソフトウェア</th>
            <td>{foreach from=$project.skills key=mykey item=skill}{if $skill.skill_category >= 4}{$skill.skill_name}, {/if}{/foreach}</td>
        </tr>
    </table>
    <br />
{/foreach}
    <br/>
    <div id="last">以上</div>
    <br />
    <div id="logo">スキルタグ (http://s.yoneichiya.net)</div>
</div>

</body>
</html>

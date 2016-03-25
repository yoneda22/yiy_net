{include file='header.tpl' title=$title}
<div id="contents">
    <h2 class="title">更新履歴</h2>
    <div id="rireki">
        <img src="img/rireki_line_top.png" width="565" height="6" alt="" />
        <div id="rireki_detail">
            <dl>
{foreach from=$array_history item=history}
                <dt>{$history.date}</dt>
                <dd>{$history.title}</a></dd>
{/foreach}
            </dl>
        </div>
        <img src="img/rireki_line_bottom.png" width="565" height="6" alt="" />
        <div id="more">
            <a href="{$smarty.const.URL_LIST}?"><img src="img/more.png" width="153" height="31" alt="もっとみる" /></a>
        </div>
    </div>

    <h2 class="title mt30">製作物</h2>
    <ul class="contents_detail">
        <li>
            <dl>
                <dt><h3 class="titleSub">スキルタグ</h3></dt>
                <dd class="txCenter"><a href="{$smarty.const.URL_STAG}"><img src="img/s_banner.png"/></a></dd>
                <dd></dd>
            </dl>
            <dl>
                <dt><h3 class="titleSub">米壱屋</h3></dt>
                <dd class="txCenter"><img src="img/banner.png" height="80px" /></dd>
                <dd></dd>
            </dl>
        </li>
    </ul>
</div>
{include file='footer.tpl'}

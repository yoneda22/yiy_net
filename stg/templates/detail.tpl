{include file='header.tpl' title=$title js_fname=$js_fname}

<!-- ↓↓↓↓↓メインコンテンツ↓↓↓↓↓ -->
<div id="contents">
    <div id="profile">
        <h2 class="pl10">
            <img src="img/profile.png" width="198" height="40" alt="プロフィール">
            <span class="dNone">プロフィール</span>
        </h2>
        <div id="profile-list">
{include file='profile_list.tpl' title=$profile}
        </div>
        <div class="tRight mt10">
            <button id="imageBtn04" class="imghover">編集する</button>
        </div>
    </div>
    <div id="history">
        <h2 class="pl10">
            <img src="img/history.png" width="77" height="40" alt="経歴">
            <span class="dNone">経歴</span>
        </h2>
        <div id="project-list">
{include file='project_list.tpl' projects=$projects}
        </div>
        <div class="tRight mt10">
            <button id="imageBtn06" class="imghover">新規追加</button>
        </div>
    </div>

    <!-- スキルタグ　タグ用のCSSはskilltag.css内に記述 -->
    <!-- ノーマル：class="normal"　var赤：class="red"　var青：class="blue" -->
    <div id="skilltag-area">
{include file='skilltag.tpl' skilltag=$skilltag}
    </div>

    <table class="mt40 mb40 w100p tCenter">
        <tr>
            <td><a id="dl_st" href="{$smarty.const.URL_DL}?m=st{if $sample == 1}&k=sample{/if}"><button id="imageBtn10" class="imghover">スキルタグダウンロード</button></a></td>
            <td><a id="dl_ss" href="{$smarty.const.URL_DL}?m=ss{if $sample == 1}&k=sample{/if}"><button id="imageBtn11" class="imghover">職務経歴書をダウンロード</button></a></td>
        </tr>
    </table>
    <input type="hidden" id="projects_count" value="{$projects_count}" />

</div>
<!-- ↑↑↑メインコンテンツここまで↑↑↑ -->

<!-- profile dialog -->
<div id="profile-dialog" class="popup">
    <!-- 名前入力 -->
    <div  class="box mt5 fwB">
        名前(16文字まで)：<input type="text" id="form-name" name="name" style="ime-mode:active" class="w240">
    </div>
    <!-- 生年月日 -->
    <div  class="box mt5 fwB">
        生年月日：<input type="text" id="datepicker" name="birthday" class="w100"> 例: 1980-01-01
    </div>
    <!-- 住所 -->
    <div  class="box mt5 fwB">
        住所：<select id="form-address" name="todoufu" class="w120">
{$options}
        </select>
    </div>
    <!-- 最寄り駅入力 -->
    <div  class="box mt5 fwB">
        最寄り駅(32文字まで)：<input type="text" id="form-station" name="eki" style="ime-mode:active" class="w200">　例：JR山手線渋谷駅
    </div>

    <!-- 変更するボタン -->
    <div class="tRight mt20">
        <input type="submit" value="" id="imageBtn09" class="imghover" alt="変更する">
    </div>
</div>

<!-- project dialog -->
<div id="project-dialog" class="popup">
    <div>*は必須項目です</div>
    <input type="hidden" id="f-project-id" name="f-projectr-id" value="0" />
    <!-- 削除 -->
    <div class="box mt5 fwB" id="div-project-delete" style="display:none">
        <label><input type="checkbox" name="delete" id="f-project-delete" /> 削除</label>
    </div>
    <!-- 名前入力 -->
    <div class="box mt5 fwB">
        利用*：
        <input type="radio" name="print" id="f-project-yes" value="1" class="vTop" checked>する
        <input type="radio" name="print" id="f-project-no" value="0" class="vTop ml20">しない
    </div>
    <!-- 期間（開始） -->
    <div class="box mt5 fwB">
        期間（開始）*：
        <select id="f-project-start" class="w180">
{$term_options}
        </select>
    </div>
    <!-- 期間（開始） -->
    <div class="box mt5 fwB">
        期間（終了）*：
        <select id="f-project-end" class="w180">
            <option value="now">進行中</option>
{$term_options}
        </select>
    </div>
    <!-- プロジェクト名 -->
    <div class="box mt5 fwB">
        プロジェクト名(32文字まで)*：<input type="text" id="f-project-name" name="name" style="ime-mode:active" class="w240">
    </div>
    <!-- プロジェクト概要 -->
    <div class="box mt5 fwB">
        プロジェクト概要(1024文字まで)：<br />
        <textarea id="f-project-note" maxlength="1024" rows="6" cols="80"></textarea>
    </div>
    <!-- スキル -->
    <div class="box mt5 fwB">
        {$pallet}
    </div>

    <!-- 送信ボタン -->
    <div class="tRight mt20">
        <input type="submit" value="" id="imageBtn08" class="imghover" alt="確定する">
    </div>
</div>
<input type="hidden" id="sample" value="{$sample}" />
{include file='footer.tpl'}

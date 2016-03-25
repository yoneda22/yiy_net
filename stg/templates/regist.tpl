{include file='header.tpl' title=$title js_fname=$js_fname}
<!-- ↓↓↓↓↓メインコンテンツ↓↓↓↓↓ -->
<div id="contents" class="w80p m0auto">

<div id="signUp">

    <div>ユーザ作成を完了するためには、下記のフォームに必要事項を入力して、<br />「アカウント作成」ボタンを押してください。<br \><br /></div>
    <div>＊すべて、必須項目です。</div>

    <!-- メールアドレス -->
    <div  class="box mt5 fwB">
        メールアドレス：<input type="text" name="mailaddress" style="ime-mode:inactive" class="w360" value="{$mailaddress}" disabled>
    </div>
    <!-- パスワード -->
    <div  class="box mt5 fwB">
        パスワード(半角英数32文字まで)：<input type="password" id="form-password" name="password" style="ime-mode:inactive" class="w240">
    </div>
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
</div>

  <!-- 送信ボタン -->
  <div class="tRight mt20">
    <input type="submit" value="" id="imageBtn02" class="imghover" alt="アカウント作成">
  </div>

  <input type="hidden" id="form-id" name="id" value="{$id}" />
</div>
<!-- ↑↑↑メインコンテンツここまで↑↑↑ -->

<!-- 登録Dialog Form -->
<div id="regist-dialog" class="popup">

    <div>下記の内容でアカウントを作成しますか？</div>

    <!-- 入力内容 -->
    <div  class="box mt5 fwB">
        <div> メールアドレス：{$mailaddress}</div>
        <div> パスワード：********</div>
        <div> 名前：<span id="input-name"></span></div>
        <div> 生年月日：<span id="input-birthday" ></span></div>
        <div> 住所：<span id="input-address"></span></div>
        <div> 最寄り駅：<span id="input-station"></span></div>
    </div>

    <!-- 送信ボタン -->
    <div class="tCenter mt10">
        <input type="submit" value="" id="imageBtn03" class="imghover" alt="送信">
    </div>

</div>

<!-- Form end -->

{include file='footer.tpl'}

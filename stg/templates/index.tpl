{include file='header.tpl' title=$title js_fname=$js_fname}

<!-- ↓↓↓↓↓メインコンテンツ↓↓↓↓↓ -->
<div id="contents" class="clearFix">

    <div id="intro">
        <img src="img/intro.gif" width="649" height="613" alt="1.まずはアカウントを作成！メールアドレスを登録しよう！ 2.経歴を入力しよう！ 3.スキルタグと業務経歴書がダウンロードできる！">

        <!-- タグサンプル群 -->
<!--
        <table id="tagSample">
            <tr>
                <td><img src="img/tag_dummy.png" alt=""></td>
                <td><img src="img/tag_dummy.png" alt=""></td>
                <td><img src="img/tag_dummy.png" alt=""></td>
            </tr>
            <tr>
                <td><img src="img/tag_dummy.png" alt=""></td>
                <td><img src="img/tag_dummy.png" alt=""></td>
                <td><img src="img/tag_dummy.png" alt=""></td>
            </tr>
        </table>
-->
    </div>

    <div id="menu">
{if $login_message != ''}
        <div style="color:red">{$login_message}<br /><br /></div>
{/if}
{if !$login}
        <!-- ログイン -->
        <form  method="POST" action="index.php">
            <ul>
                <li>
                    <dl>
                        <dt>メールアドレス</dt>
                        <dd><input type="email" name="username" style="ime-mode:inactive" class="w240"></dd>
                        <dt class="mt10">パスワード</dt>
                        <dd><input type="password" name="password" style="ime-mode:inactive" class="w240"></dd>
                    </dl>
                </li>
                <li class="tCenter mt10">
                    <input type="submit" value="" id="imageBtn01" class="imghover" alt="ログイン">
                </li>
                <li class="tCenter mt20">
                    <dl>
                        <dt>新しくアカウントを作る方はコチラ</dt>
                        <dd><a id="signup-button" href="#"><img src="img/btn10.png" width="184" height="34" alt="新規無料登録" class="imghover"></a></dd>
                    </dl>
                </li>
            </ul>
            <input type="hidden" name="before" value="loginform" />
        </form>
{else}
        <div>ようこそ、<br />{$user_name}さん</div>
        <br />
        <div>ユーザーページは<a href="{$smarty.const.URL_DETAIL}">こちら</a></div>
        <br />
        <div>ログアウトは<a href="{$smarty.const.URL_LOGOUT}">こちら</a></div>
{/if}

        <!-- スキルタグサンプルリンク -->
        <div class="mt30">
            <a href="{$smarty.const.URL_DETAIL}?k=sample"><img src="img/btn_skiltag_sample.png" width="251" alt="スキルタグサンプル" class="imghover"></a>
        </div>

        <!-- google ad（仮） -->
        <div class="mt20"><!--
            <img src="img/adsense_185665_adformat-text_250x250_ja.png" width="250" height="250" alt=""> -->
        </div>
    </div>

</div>
<!-- ↑↑↑メインコンテンツここまで↑↑↑ -->

<!-- 新規登録PopUp Form -->
<div id="signup-dialog" class="popup">

    <!-- メールアドレス入力 -->
    <div  class="box mt5 fwB">
        メールアドレス入力：<input id="email" type="email" name="email" style="ime-mode:inactive" class="w300">
    </div>

    <!-- 利用規約 -->
    <div id="privacyPolicy">
        <p>■利用規約</p>
        <p>本サイト「スキルタグ」（以下、本サイトと記載）</p>
        <p>本サイトは現在開発中です。下記の利用規約は、予告なく変更される可能性があります。</p>
        <br />
        <p>■総則</p>
        <p>本サイトにユーザ登録した利用者は、本サイトで提供される各種のサービスを利用することができます</p>
        <p>・ユーザ登録</p>
        <p>ユーザ登録時に入力されたメールアドレスは、本サイトへの登録完了に必要な情報の送信に利用されます。</p>
        <p>その他、運営者からの連絡にも利用されます。利用者は、確実に受け取れるメールアドレスを登録する必要があります。</p>
        <p>・禁止事項</p>
        <p>利用者は、本サイトに第三者の権利やプライバシーの侵害、誹謗中傷につながる恐れにある情報や、公序良俗に反する恐れのある情報を登録することはできません。</p>
        <p>運営者は、独自の判断によって使用者が登録した情報を削除、もしくは利用者のユーザ登録を抹消することがあります。</p>
        <p>利用者が登録した情報について、利用者は、運営者から修正もしくは削除の要請があった場合には、速やかにその要請に従う必要があります。</p>
        <p>・著作権</p>
        <p>本サイトで提供されている著作物の権利は、運営者が保持します。</p>
        <p>・免責</p>
        <p>本サイトは、無保証です。サービスを利用したユーザのいかなる損害についても、運営者は責任を負いません。</p>
        <p>サービスそのもの、もしくは利用者が入力したデータに関しても、運営者は一切保証しません。</p>
        <p>サービスは、事前の予告なく、中断もしくは終了することがあります。</p>
        <p>利用者は、あくまで自らの責任において本サービスを利用するものとし、本サービスの利用によってひき起こされる結果について自ら責任を負うこととします。</p>
        <p>本サービスに関係して、利用者と第三者との間に起こった紛争について、利用者は自ら解決に努めるものとします。</p>
        <p>・履歴</p>
        <p>2012年12月20日 策定
    </div>

    <!-- 説明文 -->
    <div class="fs13 mt10">
        <p>送信ボタンを押下すると、メールが届きます。利用規約を確認し、同意するにチェックを入れてください。</p>
        <p>届いたメール本文中のURLをクリックして、新規登録画面へ移動してください。</p>
    </div>

    <!-- 同意ボタン -->
    <div class="tCenter mt10 fwB">
        <input type="checkbox" name="all" id="consent" class="vTop"><label for="consent">同意する</label>
    </div>

    <!-- 送信ボタン -->
    <div class="tCenter mt10">
        <input type="submit" value="" id="imageBtn03" class="imghover" alt="送信">
    </div>
</div>
<!-- Form end -->

{include file='footer.tpl'}

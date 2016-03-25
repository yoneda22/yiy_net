/**
 * スキルタグ TOPページ向け js
 * 前提として jQuery.js 1.7.x. 以上が必要
 */
$(document).ready(function(){
    // 新規登録ダイアログ
    $('#signup-dialog').dialog({
        autoOpen: false,
        title: 'アカウント作成',
        closeOnEscape: false,
        modal: true,
        width: 600,
    });
    // 新規登録ボタン
    $('#signup-button').click(function(){
       $('#signup-dialog').dialog('open');
    });
    // 規約確認ボタン
    $('#consent').click(function(){
　　　　// 送信ボタン表示 / 非表示切り替え
        if ($('#consent').is(':checked')) {
            buttonImageUnMask('#imageBtn03');
        } else {
            buttonImageMask('#imageBtn03');
        }
    });
    // 送信ボタン
    buttonImageMask('#imageBtn03');    // マスク状態がデフォルト
　　$('#imageBtn03').click(function(){
        // チェック状態を確認
        if (!$('#consent').is(':checked')) {
            // NOP
            return;
        }
        var address = $('#email').val();
        // 入力チェック
        ret = validateSignUp(address);
        if (ret != true) {
            alert(ret);
        }
        // メールアドレス送信
        ajax_mailaddress(address);
    });
});

// 入力チェック
function validateSignUp(val) {

    if (isEmpty(val)) {
         return "メールアドレスを入力してください。";
    }
    if (!isMailAddress(val)) {
         return "正しいメールアドレスを入力してください。";
    }
    return true;
}

// メールアドレス送信
function ajax_mailaddress(address) {

    var param = "address=" + address;
    $.ajax({
        type: "POST",
        data: param,
        url: "ajax_signup.php",
        async: false,
        success: function (data, dataType) {
            if (data != "OK") alert(data);
            $('#signup-dialog').dialog('close');
        },
        error: function () {
            alert("しばらくしてから、再度お試しください。");
            $('#signup-dialog').dialog('close');
        },
    });
}

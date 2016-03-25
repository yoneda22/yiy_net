/**
 * スキルタグ ユーザ登録ページ向け js
 * 前提として jQuery.js 1.7.x. 以上が必要
 */
$(document).ready(function(){

    // カレンダー部品
    $("#datepicker").datepicker({
        dateFormat: "yy-mm-dd",
        maxDate: "y m d",
        minDate: "-100y",
        changeMonth: true,
        changeYear: true,
        yearRange: "c-50:c+50"
    });
    // アカウント登録ダイアログ
    $('#regist-dialog').dialog({
        autoOpen: false,
        title: 'プロフィール登録',
        closeOnEscape: false,
        modal: true,
        width: 600
    });
    // アカウント生成ボタン
    $('#imageBtn02').click(function(){
        // validate
        var ret = validateRegist($('#form-name').val(), $('#form-password').val(), $('#datepicker').val(), $('#form-station').val());
        if (ret != true) {
            alert(ret);
            return;
        }
        // dialog表示
        $('#input-name').html($('#form-name').val());
        $('#input-birthday').html($('#datepicker').val());
        $('#input-address').html($('#form-address :selected').text());
        $('#input-station').html($('#form-station').val());
        $('#regist-dialog').dialog('open');
    });
    // 送信ボタン
    $('#imageBtn03').click(function(){
        // プロフィール送信
        ajaxProfile(
                $('#form-id').val(),
                $('#form-name').val(),
                $('#form-password').val(),
                $('#datepicker').val(),
                $('#form-address').val(),
                $('#form-station').val()
        );
    });
});

// 入力チェック
function validateRegist(name, password, birthday, station) {
    // 名前
    if (isEmpty(name)) return "名前を入力してください。";
    if (!isInRangeLength(name, 16)) return "名前は16文字以内で入力してください。";
    // パスワード
    if (isEmpty(password)) return "パスワードを入力してください。";
    if (!isInRangeLength(password, 32)) return "パスワードは32文字以内で入力してください。";
    if (!isOnlyHalfWidthAlphaNumeric(password)) return "パスワードは半角英数で入力してください。";
    // 生年月日
    if (isEmpty(birthday)) return "生年月日を入力してください。";
    if (!isMySQLFormatYMD(birthday)) return "生年月日は[YYYY-MM-DD]形式で入力してください。";
    // 最寄駅
    if (isEmpty(station)) return "最寄駅を入力してください";
    if (!isInRangeLength(station, 32)) return "最寄駅は32文字以内で入力してください。";

    return true;
}

// プロフィール送信
function ajaxProfile(id, name, password, birthday, address, station) {
    $.ajax({
        type: "POST",
        data: {id: id, name: name, password: password, birthday: birthday, address: address, station: station},
        url: "ajax_regist.php",
        async: false,
        success: function (data, dataType) {
            if (data != "OK") alert(data);
            $('#regist-dialog').dialog('close');
            location.href="index.php";
        },
        error: function () {
            alert("しばらくしてから、再度お試しください。");
            $('#regist-dialog').dialog('close');
        },
    });
}

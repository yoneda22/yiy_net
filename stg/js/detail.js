/**
 * スキルタグ ユーザページ向け js
 * 前提として jQuery.js 1.7.x. 以上が必要
 */
$(document).ready(function(){
    if ($('#projects_count').val() <= 0) {
        downloadButtonMask();
    }
// ------------------------------------
//  プロフィール編集
// -------------------------------------
    // カレンダー部品
    $("#datepicker").datepicker({
        dateFormat: "yy-mm-dd",
        maxDate: "y m d",
        minDate: "-100y",
        changeMonth: true,
        changeYear: true,
        yearRange: "c-30:c+30"
    });
    // プロフィール編集ダイアログ
    $('#profile-dialog').dialog({
        autoOpen: false,
        title: 'プロフィール編集',
        closeOnEscape: false,
        modal: true,
        width: 800
    });
    // プロフィール編集ボタン
    $('#imageBtn04').click(function(){
        $('#form-name').val($('#profile_name').html());
        $('#datepicker').val($('#profile_birthday').html());
        $('#form-address').val($('#profile_address_value').val());
        $('#form-station').val($('#profile_station').html());
        $('#profile-dialog').dialog('open');
    });
    // プロフィール編集ダイアログ、変更ボタン
    $('#imageBtn09').click(function(){
        // validate
        var ret = validateProfile($('#form-name').val(), $('#datepicker').val(), $('#form-station').val());
        if (ret != true) {
            alert(ret);
            return;
        }
        // 送信
        ajaxProfile(
                $('#user_id').val(),
                $('#form-name').val(),
                $('#datepicker').val(),
                $('#form-address').val(),
                $('#form-station').val(),
                $('#sample').val()
        );
    });
// ------------------------------------
//  プロジェクト編集
// -------------------------------------
    // プロジェクト編集ダイアログ
    $('#project-dialog').dialog({
        autoOpen: false,
        title: 'プロジェクト編集',
        closeOnEscape: false,
        modal: true,
        width: 800
    });
    // プロジェクト新規追加ボタン
    $('#imageBtn06').live("click", function() {
        // ダイアログ初期化
        $('#f-project-id').val(0);
        $('#f-project-yes').val(['1']);
        $('#f-project-name').val('');
        $('#f-project-note').val('');
        $('#f-project-start').val('');
        $('#f-project-end').val('');
        initSkillsPallet();
        // 削除ボタンを非表示に
        $('#div-project-delete').hide();
        $('#project-dialog').dialog('open');
    });
    // スキルパレット
    selectTabPallet("program");
    $('#tab_program').click(function(){selectTabPallet("program");});
    $('#tab_db').click(function(){selectTabPallet("db");});
    $('#tab_os').click(function(){selectTabPallet("os");});
    $('#tab_tools').click(function(){selectTabPallet("tools");});
    $('#tab_middle').click(function(){selectTabPallet("middle");});
    // 確定ボタン
    $('#imageBtn08').live("click", function(){
        // validate
        var ret = validateProject($('#f-project-name').val(), $('#f-project-note').val(), $('#f-project-start').val(), $('#f-project-end').val());
        if (ret != true) {
            alert(ret);
            return;
        }
        // 利用情報取得
        var available = 0;
        if ($('#f-project-yes').attr("checked")) available = 1;
        // スキルタグ情報取得
        var skills = getCheckboxSkills();
        // 削除モード取得
        var deleteFlag = 0;
        if ($('#f-project-delete').attr("checked")) deleteFlag = 1;
        // 送信
        ajaxProject(
                $('#f-project-id').val(),
                $('#user_id').val(),
                available,
                $('#f-project-name').val(),
                $('#f-project-note').val(),
                $('#f-project-start').val(),
                $('#f-project-end').val(),
                skills,
                deleteFlag,
                $('#sample').val()
        );
    });
    // プロジェクト編集ボタン
    $('.project_edit').live("click", function() {
        var project_id = $(this).val();
        // 削除チェックボックスを表示
        $('#div-project-delete').show();
        $('#f-project-delete').removeAttr('checked');
        // ダイアログに値をロード
        $('#f-project-id').val(project_id);
        if ($('#ava_' + project_id).attr('checked') == 'checked') {
            $('#f-project-yes').val(['1']);
        } else{
            $('#f-project-no').val(['0']);
        }
        $('#f-project-name').val($('#name_' + project_id).val());
        $('#f-project-note').val($('#note_' + project_id).val());
        $('#f-project-start').val($('#start_' + project_id).val());
        $('#f-project-end').val($('#end_' + project_id).val());
        loadSkillsPallet($('#skills_' + project_id).val());
        $('#project-dialog').dialog('open');
    });
});

// ダウンロードボタンをマスク
function downloadButtonMask() {
    buttonImageMask('#imageBtn10');
    buttonImageMask('#imageBtn11');
    $('#dl_st').attr('href', 'javascript:void(0)');
    $('#dl_ss').attr('href', 'javascript:void(0)');
}
// ダウンロードボタンを復旧
function downloadButtonUnMask() {
    buttonImageUnMask('#imageBtn10');
    buttonImageUnMask('#imageBtn11');
    var sample = '';
    if ($('#sample').val() == 1) sample = '&k=sample';
    $('#dl_st').attr('href', 'download.php?m=st' + sample);
    $('#dl_ss').attr('href', 'download.php?m=ss' + sample);
}
// スキル情報をパレットに読み込み
function loadSkillsPallet(skills) {
    initSkillsPallet();
    var temp = skills.replace(/,$/, '');
    var skillArray = temp.split(',');
    for (index = 0; index < skillArray.length; index++) {
        $("#cb_" + skillArray[index]).attr('checked', 'checked');
    }
}
// スキルタグパレット初期化
function initSkillsPallet() {
    var skillArray = $('#f-project-skills').val().split(',');
    for (index = 0; index < skillArray.length; index++) {
        $("#cb_" + skillArray[index]).removeAttr('checked');
    }
}

// スキルタグ情報取得
function getCheckboxSkills() {
    var skillArray = $('#f-project-skills').val().split(',');
    var output = '';
    for (index = 0; index < skillArray.length; index++) {
         if ($("#cb_" + skillArray[index]).attr('checked')) {
             output += skillArray[index] + ',';
         }
    }
    return output.replace(/,$/, '');
}

// スキルパレットタブ選択
function selectTabPallet(category) {
    unselect("program");
    unselect("db");
    unselect("os");
    unselect("tools");
    unselect("middle");
    $('#tab_' + category).html("<span class=\"cBlue\">" + category + "</span>");
    $('#skill_' + category).show();
}

// スキルパレットタブ未選択
function unselect(category) {
    $('#tab_' + category).html("<a href=\"javascript:void(0);\">" + category + "</a>");
    $('#skill_' + category).hide();
}

// プロフィール入力チェック
function validateProfile(name, birthday, station) {
    // 名前
    if (isEmpty(name)) return "名前を入力してください。";
    if (!isInRangeLength(name, 16)) return "名前は16文字以内で入力してください。";
    // 生年月日
    if (isEmpty(birthday)) return "生年月日を入力してください。";
    if (!isMySQLFormatYMD(birthday)) return "生年月日は[YYYY-MM-DD]形式で入力してください。";
    // 最寄駅
    if (isEmpty(station)) return "最寄駅を入力してください";
    if (!isInRangeLength(station, 32)) return "最寄駅は32文字以内で入力してください。";

    return true;
}

// プロフィール送信
function ajaxProfile(id, name, birthday, address, station, sample) {
    $.ajax({
        type: "POST",
        data: {id: id, name: name, birthday: birthday, address: address, station: station, sample: sample},
        url: "ajax_profile.php",
        async: false,
        success: function (data, dataType) {
            if (data.result != "OK") {
                alert(data.message);
                return;
            }
            // リスト更新
            $('#profile-list').html(data.list);
            $('#profile-dialog').dialog('close');
        },
        error: function () {
            alert("しばらくしてから、再度お試しください。");
            $('#profile-dialog').dialog('close');
        },
    });
}

// プロジェクト入力チェック
function validateProject(name, note, start, end) {

    // プロジェクト名
    if (isEmpty(name)) return "プロジェクト名を入力してください。";
    if (!isInRangeLength(name, 32)) return "プロジェクト名は32文字以内で入力してください。";
    // プロジェクト概要
    if (!isInRangeLength(note, 1024)) return "プロジェクト概要は1024文字以内で入力してください。";
    // 期間
    if (start > end) return "プロジェクト期間は、開始が終了より未来の時間を設定しないでください。";

    return true;
}
// プロジェクト送信
function ajaxProject(id, userId, available, name, note, start, end, skills, deleteFlag, sample) {
    $.ajax({
        type: "POST",
        data: {id: id, user_id: userId, available: available, name: name, note: note, start: start, end: end, skills: skills, delete: deleteFlag, sample: sample},
        url: "ajax_project.php",
        async: false,
        success: function (data, dataType) {
            if (data.result != "OK") {
                alert(data.message);
                return;
            }
            // リスト更新
            //$('#project-list').html(data.list);
            $('#project-list').empty();
            $('#project-list').append(data.list);
            $('#skilltag-area').html(data.skilltag);
            if (data.projects_count == 0) {
                downloadButtonMask();
            } else {
                downloadButtonUnMask();
            }
            $('#project-dialog').dialog('close');
        },
        error: function () {
            alert("しばらくしてから、再度お試しください。");
            $('#project-dialog').dialog('close');
        },
    });
}

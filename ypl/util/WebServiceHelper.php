<?php
/**
 * WEB関連処理ユーティリティクラス
 */

class WebServiceHelper {

    /**
     * GETパラメータの簡易隠ぺい
     * 
     * @note simplseUnMaskParameter
     */
    static function simpleMaskParameter($val)
    {
        return "A" . base64_encode("_" . $val) . "A";
    }

    static function simpleUnMaskParameter($val)
    {
        $val = substr($val, 1);
        $val = rtrim($val, "A");
        $val = base64_decode($val);
        return substr($val, 1);
    }

    /**
     * 指定された文字列から、option要素を生成する
     *
     * @param $array array('value' => 1, 'label' => '北海道')
     * @param $selected 選択状態にするvalue値
     */
    static function getOptions($array, $selected=null)
    {
        $options = '';
        foreach ($array as $val) {

            $temp = '';
            if ($selected != null && $val['value'] == $selected) {
                $temp = 'selected';
            }
            $options .= "<option value=\"{$val['value']}\" {$temp}>{$val['label']}</option>\n";

        }
        return $options;
    }

    /**
     * YYYYMMN を YYYY年MM月上旬に変換
     *
     * @param $val 日付(2012120) 
     * @return 変換文字列(2012年12月上旬)
     */
    static function convertYM3($val)
    {
        if(empty($val)) return;

        if($val == 'now') return "進行中";

        $year  = substr($val, 0, 4);
        $month = substr($val, 4, 2);
        $num   = substr($val, 6, 1);
        switch ($num) {
            case 0:
                $num = '上旬';
                break;
            case 1:
                $num = '中旬';
                break;
            case 2:
                $num = '下旬';
                break;
        }
        return "{$year}年{$month}月{$num}";
    }

    /**
     * 
     */
    static function getOptionsYM3($selected=null, $year=null, $month=null, $lastyear=1960)
    {
        if (empty($year))  $year  = date('Y');
        if (empty($month)) $month = date('n');

        $options = '';
        while (true) {
            $temp = sprintf('%02d', $month);
            $options .= "<option value=\"{$year}{$temp}2\">{$year}年{$month}月下旬</option>\n";             
            $options .= "<option value=\"{$year}{$temp}1\">{$year}年{$month}月中旬</option>\n";             
            $options .= "<option value=\"{$year}{$temp}0\">{$year}年{$month}月上旬</option>\n";             

            $month--;

            if ($year == $lastyear && $month == 0) {
                break;
            }

            if ($month == 0) {
                $month = 12;
                $year--;
            }
        }
        return $options;
    }

    /**
     *
     */
    static function getSkillTagPallet($array)
    {
        $array_program = array();
        $array_db      = array();
        $array_os      = array();
        $array_tools   = array();
        $array_middle  = array();
        foreach ($array as $skill) {
            $checkbox = "<td><input type=\"checkbox\" id=\"cb_{$skill['id']}\"><label for=\"{$skill['id']}\">{$skill['name']}</label></td>";
            switch ($skill['category']) {
                case 1:
                    $array_program[] = $checkbox;
                    break;
                case 2:
                    $array_db[] = $checkbox;
                    break;
                case 3:
                    $array_os[] = $checkbox;
                    break;
                case 4:
                    $array_tools[] = $checkbox;
                    break;
                case 5:
                    $array_middle[] = $checkbox;
                    break;
            }

        }
        //5列ごとに区切る
        $skill_program = self::separateTr($array_program, 5);
        $skill_db      = self::separateTr($array_db,      5);
        $skill_os      = self::separateTr($array_os,      5);
        $skill_tools   = self::separateTr($array_tools,   5);
        $skill_middle  = self::separateTr($array_middle,  5);

        $pallet =<<<PALLET_HTML
    <table id="skillInputTab">
        <tr>
            <td><div id="tab_program"><a href="javascript:void(0);">program</a></div></td>
            <td><div id="tab_db"><a href="javascript:void(0);">db</a></div></td>
            <td><div id="tab_os"><a href="javascript:void(0);">os</a></div></td>
            <td><div id="tab_tools"><a href="javascript:void(0);">tools</a></div></td>
            <td><div id="tab_middle"><a href="javascript:void(0);">middle</a></div></td>
        </tr>
    </table>
    <table id="skill_program" class="skillInput" style="display:none">
        {$skill_program}
    </table>
    <table id="skill_db" class="skillInput" style="display:none">
        {$skill_db}
    </table>
    <table id="skill_os" class="skillInput" style="display:none">
        {$skill_os}
    </table>
    <table id="skill_tools" class="skillInput" style="display:none">
        {$skill_tools}
    </table>
    <table id="skill_middle" class="skillInput" style="display:none">
        {$skill_middle}
    </table>
PALLET_HTML;

        return $pallet;
    }

    /**
     *
     */
    static function separateTr($array, $num)
    {
        $index = 1;
        $trtd = "<tr>\n";
        foreach ($array as $option) {
            $trtd .= $option;
            if ($index%$num == 0) {
                $trtd .= "</tr>\n<tr>\n";
            }
            $index++;
        }
        return $trtd . "</tr>\n";
    }

    /**
     * スキルタグＩＤを文字列で取得
     */
    static function getSkillTagMaster($array) {
        $output = '';
        foreach ($array as $skill) {
            $output .= $skill['id'] . ",";
        }
        return rtrim($output, ",");
    }

    /**
     * 年齢計算（YYYY-MM-DD)
     */
    static function getAge($val)
    {
        $array = split('-', $val);
        $stamp = $array[0] . $array[1] . $array[2];

        return (int)((date('Ymd')-$stamp)/10000);
    }
}
?>

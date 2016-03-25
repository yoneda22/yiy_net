<?php
/**
 * デバック時のユーティリティ
 */

class Debug
{
    /** デバックモード	*/
    const DEBUG_MODE = 'ON';
    const DEBUG_ON   = 'ON';

    /**
     * pre要素で囲んで、var_dumpの結果を表示する
     */
    static function varDumpHtml($var, $name="")
    {
       if (self::DEBUG_MODE != self::DEBUG_ON) return;
       echo "<pre>\n";
       if (!empty($name)) echo "--{$name}------------------\n";
       var_dump($var);
       echo "</pre>\n";
    }

    /**
     * var_dumpの出力を文字列として取得する
     */
    static function getStringVarDump($var)
    {
        ob_start();
        var_dump($var);
        $str = ob_get_contents();
        ob_end_clean();
        return rtrim($str);
    }
}

<?php
/**
 * Database SkillTag Dao
 */
require_once 'MDB2.php';
require_once '/var/www/yiy_net/ypl/log/LogSkillTag.php';  //TODO 今後の課題
require_once '/var/www/yiy_net/ypl/util/spyc.php';

/**
 * スキルタグデータベース接続抽象クラス
 */
abstract class AbstractSkillTagDao {

    /** 接続インスタンス	*/
    protected $_db = null;
    /** ロガー			*/
    protected $_logger = null;

    function __construct()
    {
        $this->_logger =& LogSkillTag::getInstance();

        $config = spyc::YAMLLoad('/var/www/yiy_net/cnf/setting.yaml');
        $user = $config['production']['db']['user'];
        $password = $config['production']['db']['password'];
        $dsn = "mysql://{$user}:{$password}@localhost:3306/skilltag";

        $mdb2 =& MDB2::factory($dsn);
        if (PEAR::isError($mdb2)) {
            $this->_logger->err($mdb2->getMessage());
        }

        $mdb2->setFetchMode(MDB2_FETCHMODE_ASSOC);
        $mdb2->loadModule('Extended');

        $this->_db = $mdb2;
    }

    function __destruct()
    {
        if (isset($this->_logger)) unset($this->_logger);
        if (isset($this->_db)) $this->_db->disconnect();
    }

    protected function getTimestampNow()
    {
        return date('Y-m-d H:i:s');
    }

    /**
     * 汎用クエリ実行
     */
    protected function _query($sql)
    {
        // クエリ実行
        $res =& $this->_db->query($sql);
        if (PEAR::isError($res)) {
            $this->_logger->err(__METHOD__ . " " . $res->getMessage());
            $this->_logger->err(Debug::getStringVarDump($res));
            return false;
        }
        $result = $res->fetchAll();
        $this->_logger->debug($sql);
        return $result;
    }
}
?>

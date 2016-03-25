<?php
/**
 * skilltag.m_address
 */
require_once('AbstractSkillTagDao.php');
require_once('/var/www/yiy_net/ypl/log/LogSkillTag.php');
/**
 * m_address Daoクラス
 */
class MasterAddressDao extends AbstractSkillTagDao
{
    const TABLE_NAME = 'm_address';

    public function getListForOption()
    {
        // sql
        $sql =<<<SELECT_SQL
SELECT
    id   AS value,
    name AS label
FROM
    m_address
ORDER BY id ASC
SELECT_SQL;

        // クエリ実行
        $res =& $this->_db->query($sql);
        if (PEAR::isError($res)) {
            $this->_logger->err(__FILE__ . " " . $res->getMessage());
            return false;
        }
        $result = $res->fetchAll();
        //$this->_logger->debug(Debug::getStringVarDump($result));
        return $result;
    }

}
?>

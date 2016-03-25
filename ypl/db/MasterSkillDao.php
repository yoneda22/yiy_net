<?php
/**
 * skilltag.m_skill
 */
require_once('AbstractSkillTagDao.php');
require_once('/var/www/yiy_net/ypl/log/LogSkillTag.php');
/**
 * m_address Daoクラス
 */
class MasterSkillDao extends AbstractSkillTagDao
{
    const TABLE_NAME = 'm_skill';

    public function getList()
    {
        // sql
        $sql =<<<SELECT_SQL
SELECT
    id       AS id,
    category AS category,
    name     AS name
FROM
    m_skill
ORDER BY category ASC, name ASC
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

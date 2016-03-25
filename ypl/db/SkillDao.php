<?php
/**
 * skilltag.project
 */
require_once('AbstractSkillTagDao.php');
require_once('/var/www/yiy_net/ypl/log/LogSkillTag.php');
/**
 * project Daoクラス
 */
class SkillDao extends AbstractSkillTagDao
{
    const TABLE_NAME = 'skill';

    private function _select($where=null, $limit=null, $offset=null)
    {
        // sql
        $sql =<<<SELECT_SQL
SELECT
    s.id          AS id,
    s.user_id     AS user_id,
    s.project_id  AS project_id,
    s.skill_id    AS skill_id,
    m.name        AS skill_name,
    m.category    AS skill_category
FROM
    skill s,
    m_skill m
WHERE
    s.skill_id = m.id
    AND s.delete_flag = 0
SELECT_SQL;

        if (isset($where)) $sql .= $where;

        // クエリ実行
        $res =& $this->_db->query($sql);
        if (PEAR::isError($res)) {
            $this->_logger->err(__METHOD__ . " " . $res->getMessage());
            $this->_logger->err(Debug::getStringVarDump($res));
            return false;
        }
        $result = $res->fetchAll();
        //$this->_logger->debug(Debug::getStringVarDump($result));
        return $result;
    }

    /**
     * スキル情報取得
     */
    public function getProjectSkills($project_id)
    {
        $where = " AND s.project_id = " . $this->_db->quote($project_id, 'integer');
        $result = $this->_select($where);

        return $result;
    }

    /**
     * ユーザスキル情報取得
     */
    public function getProfileSkills($user_id)
    {
        $sql =<<<SQL
SELECT DISTINCT
    s.user_id     AS user_id,
    s.skill_id    AS skill_id,
    m.name        AS skill_name,
    m.category    AS skill_category
FROM
    skill s,
    m_skill m
WHERE
    s.skill_id = m.id
    AND s.delete_flag = 0
    AND s.user_id = {$user_id}
SQL;

        $result = $this->_query($sql);

        return $result;
    }

    /**
     * スキル挿入
     *
     * @param $address メールアドレス
     * @retrun プロジェクトID
     */
    public function insert($user_id, $project_id, $skill_id)
    {
        $timestamp = $this->getTimestampNow();
        $values = array(
                'user_id'    => $user_id,
                'project_id' => $project_id,
                'skill_id'   => $skill_id,
        );
        $types = array(
                'integer', 'integer', 'integer'
        );

        $affectedRows = $this->_db->extended->autoExecute(
                self::TABLE_NAME, $values, MDB2_AUTOQUERY_INSERT, null, $types
        );
        if (PEAR::isError($affectedRows)) {
            $this->_logger->debug(Debug::getStringVarDump($affectedRows));
            $this->_logger->err(__METHOD__ . " " . $affectedRows->getMessage());
            return false;
        }
        //$this->_logger->debug(Debug::getStringVarDump($affectedRows));
        return true;
    }

    /**
     * スキル情報削除
     */
    public function delete($project_id)
    {
        $res =& $this->_db->query("DELETE FROM skill WHERE project_id = {$project_id}");
        /*
        $this->_logger->debug(__METHOD__ . " START(project=" . $project_id . ")");
        $sth = $this->_db->extended->autoPrepare(
                 self::TABLE_NAME, null, MDB2_AUTOQUERY_DELETE, 'project_id = '. $this->_db->quote($project_id, 'integer'));
        */
        if (PEAR::isError($res)) {
            $this->_logger->debug(Debug::getStringVarDump($res));
            $this->_logger->err(__METHOD__ . " " . $res->getMessage());
            return false;
        }
        //$this->_logger->debug(__METHOD__ . " " . Debug::getStringVarDump($res));
        return true;
    }

    /**
     * スキル登録(delete-insert)
     */
    function insertSkills($user_id, $project_id, $skills)
    {
        $this->_logger->debug(__METHOD__ . " " . Debug::getStringVarDump($project_id));

        // 削除
        $ret = $this->delete($project_id);
        if ($ret !== TRUE) return false;
        // 挿入
        $skillArray = split(',', $skills);
        foreach ($skillArray as $skill_id) {
            $ret = $this->insert($user_id, $project_id, $skill_id);
            if ($ret !== TRUE) return false;
        }
        return true;
    }

    /**
     * スキルの論理削除
     */
    public function deleteLogical($project_id)
    {
        if (empty($project_id)) return false;

        $where = 'project_id = ' . $this->_db->quote($project_id, 'integer');
        $affectedRows = $this->_db->extended->autoExecute(
               self::TABLE_NAME, array('delete_flag' => 1), MDB2_AUTOQUERY_UPDATE, $where, array('integer')
        );
        if (PEAR::isError($affectedRows)) {
            $this->_logger->debug(Debug::getStringVarDump($affectedRows));
            $this->_logger->err(__METHOD__ . " " . $affectedRows->getMessage());
            return false;
        }

        return true;
    }
}
?>

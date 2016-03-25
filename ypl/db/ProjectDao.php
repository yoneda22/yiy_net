<?php
/**
 * skilltag.project
 */
require_once('AbstractSkillTagDao.php');
require_once('SkillDao.php');
require_once('/var/www/yiy_net/ypl/log/LogSkillTag.php');
require_once('/var/www/yiy_net/ypl/util/WebServiceHelper.php');
/**
 * project Daoクラス
 */
class ProjectDao extends AbstractSkillTagDao
{
    const TABLE_NAME = 'project';

    private function _select($where=null, $limit=null, $offset=null)
    {
        // sql
        $sql =<<<SELECT_SQL
SELECT
    p.id             AS project_id,
    p.user_id        AS user_id,
    p.name           AS name,
    p.note           AS note,
    p.start          AS start,
    p.end            AS end,
    p.available_flag AS available_flag
FROM
    project p
WHERE
    p.delete_flag = 0 {$where}
ORDER BY
    p.end DESC, p.id DESC
SELECT_SQL;

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
     * プロジェクト情報取得
     */
    public function getProjects($user_id)
    {
        // プロジェクト情報取得
        $where = " AND p.user_id = " . $this->_db->quote($user_id, 'integer');
        $result = $this->_select($where);

        // スキル情報をプラス
        $output = array();
        $dao = new SkillDao;
        foreach ($result as $row) {
            $row['skills']      = $dao->getProjectSkills($row['project_id']);
            $row['start_label'] = WebServiceHelper::convertYM3($row['start']);
            $row['end_label']   = WebServiceHelper::convertYM3($row['end']);
            $output[] = $row;
        }

        return $output;
    }
    
    /**
     * プロジェクト挿入
     */
    public function insert($user_id, $name, $note, $start, $end, $available)
    {
        $timestamp = $this->getTimestampNow();
        $values = array(
                'user_id'        => $user_id,
                'name'           => $name,
                'note'           => $note,
                'start'          => $start,
                'end'            => $end,
                'available_flag' => $available,
                'delete_flag'    => 0,
                'create_at'      => $timestamp,
                'update_at'      => $timestamp
        );
        $types = array(
                'integer', 'text', 'text', 'text', 'text', 'integer', 'integer', 'timestamp', 'timestamp'
        );

        $affectedRows = $this->_db->extended->autoExecute(
                self::TABLE_NAME, $values, MDB2_AUTOQUERY_INSERT, null, $types
        );
        if (PEAR::isError($affectedRows)) {
            $this->_logger->debug(Debug::getStringVarDump($affectedRows));
            $this->_logger->err(__METHOD__ . " " . $affectedRows->getMessage());
            return false;
        }
        return mysql_insert_id();
    }

    /**
     * プロジェクト編集
     */
    public function update($id, $user_id, $name, $note, $start, $end, $available)
    {
        $timestamp = $this->getTimestampNow();
        $values = array (
                'user_id'        => $user_id,
                'name'           => $name,
                'note'           => $note,
                'start'          => $start,
                'end'            => $end,
                'available_flag' => $available,
                'update_at'      => $timestamp
        );
        $types = array('integer', 'text', 'text', 'text', 'text', 'text', 'integer', 'timestamp');

        $where = 'id = ' . $this->_db->quote($id, 'integer');

        $affectedRows = $this->_db->extended->autoExecute(
                self::TABLE_NAME, $values, MDB2_AUTOQUERY_UPDATE, $where, $types
        );
        if (PEAR::isError($affectedRows)) {
            $this->_logger->debug(Debug::getStringVarDump($affectedRows));
            $this->_logger->err(__METHOD__ . " " . $affectedRows->getMessage());
            return false;
        }

        return true;
    }

    /**
     * プロジェクト挿入／編集
     */
    public function replaceInto($id, $user_id, $name, $note, $start, $end, $available)
    {
        $ret = true;
        if (empty($id)) {
            $ret = $this->insert($user_id, $name, $note, $start, $end, $available);
        } else {
            $ret = $this->update($id, $user_id, $name, $note, $start, $end, $available);
        }
        return $ret;
    }

    /**
     * プロジェクトの論理削除
     */
    public function deleteLogical($project_id)
    {
        if (empty($project_id)) return false;

        $where = 'id = ' . $this->_db->quote($project_id, 'integer');
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

    /**
     * 経歴書向け、プロジェクト情報取得
     * 　＋スキルをカテゴリ別にまとめる
     *   ＋経験年数を計算する
     * @return array ('projects' => プロジェクト情報, 'exp' => 経験年数)
     */
    public function getProjectsForSkillSheet($user_id)
    {
        $return_array = array();
 
        // プロジェクト情報取得
        $return_array['projects'] = $this->getProjects($user_id);

        // プロジェクト期間最大取得
        $result = $this->_query("SELECT max(end) AS max FROM project WHERE user_id = {$user_id};");
        $max = $result[0]['max'];
        // プロジェクト期間最少取得
        $result = $this->_query("SELECT min(start) AS min  FROM project WHERE user_id = {$user_id};");
        $min = $result[0]['min'];         
        // 経験年数取得
        //$return_array['exp'] = ($max_y-$min_y)*12 + ($max_m-$min_m);
        $return_array['exp'] = $this->_getMonth($min, $max);

        return $return_array;
    }

    function _getMonth($start, $end) {

        $start_y = substr($start, 0, 4);
        $start_m = substr($start, 4, 2);

        if ($end == 'now') {
            $end_y = date('Y');
            $end_m = date('n');
        } else {
            $end_y = substr($end, 0, 4);
            $end_m = substr($end, 4, 2);
        }
        return ($end_y-$start_y)*12 + ($end_m-$start_m);
    }

    /**
     * スキルタグ情報取得
     */
    function getSkillTag($user_id)
    {
        $sql =<<<SQL
SELECT
    p.start AS start,
    p.end   AS end,
    m.name  AS skill_name
FROM
    project p,
    skill s,
    m_skill m
WHERE
    p.id  = s.project_id
    AND s.skill_id = m.id
    AND p.delete_flag = 0
    AND p.available_flag = 1
    AND p.user_id = {$user_id}
ORDER BY p.end DESC
SQL;
        $result = $this->_query($sql);

        $output = array();
        foreach ($result as $row) {
            $name = $row['skill_name'];
            // 期間取得
            $num = $this->_getMonth($row['start'], $row['end']);
            // 格納
            if (array_key_exists($name, $output)) {
                $output[$name] += $num;
            } else {
                $output[$name] = $num;
            }
        }

        // 期間を変換
        foreach ($output as $name => $value) {
	    if ($value >= 5*12) {
                $size = 6;
            } elseif ($value < 5*12 &&  $value >= 3*12) {
                $size = 5;
            } elseif ($value < 3*12 &&  $value >= 1*12) {
                $size = 4;
            } elseif ($value < 1*12 &&  $value >= 6) {
                $size = 3;
            } elseif ($value < 6    &&  $value >= 3) {
                $size = 2;
            } else {
                $size = 1;
            }
            $output[$name] = 'size' . $size;
        }

        return $output;
    }
}
?>

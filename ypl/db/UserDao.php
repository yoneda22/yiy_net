<?php
/**
 * skilltag.user
 */
require_once('AbstractSkillTagDao.php');
require_once('/var/www/yiy_net/ypl/log/LogSkillTag.php');
/**
 * user Daoクラス
 */
class UserDao extends AbstractSkillTagDao
{
    const STATUS_TEMP   = 2;
    const STATUS_MEMBER = 1;
    const STATUS_DELETE = 0;

    const TABLE_NAME = 'user';

    private function _select($where=null, $limit=null, $offset=null)
    {
        // sql
        $sql =<<<SELECT_SQL
SELECT
    u.id          AS id,
    u.mailaddress AS mailaddress,
    u.password    AS password,
    u.name        AS name,
    u.birthday    AS birthday,
    u.address     AS address_value,
    m.name        AS address_label,
    u.station     AS station,
    u.status      AS status
FROM
    user u,
    m_address m
WHERE
    u.address = m.id
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
        $this->_logger->debug(Debug::getStringVarDump($result));
        return $result;
    }

    /**
     * ユーザプロフィール取得
     */
    public function getProfileByAddress($address)
    {
        $where = " AND mailaddress = " . $this->_db->quote($address, 'text');

        $result = $this->_select($where);
        if (empty($result[0])) {
            $this->_logger->err(__METHOD__ . " " . " ユーザプロフィール取得失敗");
            return false;
        }

        return $result[0];
    }

    /**
     * 登録ユーザか？
     *
     * @param $address メールアドレス
     * @return true: 登録ユーザ、false: 登録ユーザでない
     */
    public function isMember($address)
    {
        $where  = " AND status != 0";
        $where .= " AND status = 1";
        $where .= " AND mailaddress = " . $this->_db->quote($address, 'text');
        $result = $this->_select($where);
        if (empty($result)) return false;

        return true;
    }

    /**
     * 仮登録ユーザか？
     *
     * @param $address メールアドレス
     * @return true: 仮登録ユーザ、false: 仮登録ユーザでない
     */
    public function isTemp($address)
    {
        $where  = " AND status != 0";
        $where .= " AND status = 2";
        $where .= " AND mailaddress = " . $this->_db->quote($address, 'text');
        $result = $this->_select($where);
        if (empty($result)) return false;

        return true;
    }

    /**
     * 仮登録ユーザ挿入
     *
     * @param $address メールアドレス
     */
    public function insertSignUpUser($address)
    {
        $timestamp = $this->getTimestampNow();
        $values = array (
                'mailaddress' => $address,
                'status'      => 2,
                'create_at'   => $timestamp,
                'update_at'   => $timestamp
        );

        $affectedRows = $this->_db->extended->autoExecute(
                self::TABLE_NAME, $values, MDB2_AUTOQUERY_INSERT, null, array('text', 'integer', 'timestamp', 'timestamp')
        );
        if (PEAR::isError($affectedRows)) {
            $this->_logger->debug(Debug::getStringVarDump($affectedRows));
            $this->_logger->err(__METHOD__ . " " . $affectedRows->getMessage());
            return false;
        }

        return true;
    }

    /**
     * プロフィール編集
     *
     * @param $id ユーザID
     * @param $name ユーザ名
     * @param $password パスワード
     * @param $birthday 誕生日
     * @param $address 居住地
     * @param $station 最寄駅
     * @return true:編集成功、false:編集失敗
     */
    public function updateUser($id, $name, $birthday, $address, $station, $password=null)
    {
        $values = array (
                'name'      => $name,
                'birthday'  => $birthday,
                'address'   => $address,
                'station'   => $station,
                'status'    => 1,
                'update_at' => $this->getTimestampNow()
        );
        $types = array('text', 'timestamp', 'integer', 'text', 'integer', 'timestamp');
        // option clumn
        if (isset($password)) {
            $values['password'] = md5($password);
            $types[] = 'text';
        }

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

}
?>

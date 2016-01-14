<?php
/**
 * Created by PhpStorm.
 * User: wangjie
 * Date: 2016/1/13
 * Time: 22:36
 */

namespace Admin\Model;


class DbMysqlInterfaceImpModel implements DbMysqlInterfaceModel
{
    /**
     * DB connect
     *
     * @access public
     *
     * @return resource connection link
     */
    public function connect()
    {
        // TODO: Implement connect() method.
        echo 'connect...';
        exit;
    }

    /**
     * Disconnect from DB
     *
     * @access public
     *
     * @return viod
     */
    public function disconnect()
    {
        echo 'disconnect...';
        exit;
        // TODO: Implement disconnect() method.
    }

    /**
     * Free result
     *
     * @access public
     * @param resource $result query resourse
     *
     * @return viod
     */
    public function free($result)
    {
        echo 'free...';
        exit;
        // TODO: Implement free() method.
    }

    /**
     * Execute simple query
     *
     * @access public
     * @param string $sql SQL query
     * @param array $args query arguments
     *
     * @return resource|bool query result
     */
    public function query($sql, array $args = array())
    {
        $tmpSQL = $this->biudSQL(func_get_args());
        return M()->execute($tmpSQL);
        // TODO: Implement query() method.
    }

    /**
     * Insert query method
     *
     * @access public
     * @param string $sql SQL query
     * @param array $args query arguments
     *
     * @return int|false last insert id
     */
    public function insert($sql, array $args = array())
    {
        $params = func_get_args();
        $sql = array_shift($params);
        $table_name = array_shift($params);
        $sql = str_replace('?T', $table_name, $sql);
        $params = array_shift($params);
        $tmpSQL = '';
        foreach ($params as $key => $val) {
            $tmpSQL .= "`$key`='$val',";
        }

        $tmpSQL = rtrim($tmpSQL, ',');
        $sql = str_replace('?%', $tmpSQL, $sql);
        $model = M();
        $result = $model->execute($sql);
        if ($result) {
            return $model->getLastInsID();
        } else {
            return false;
        }
    }

    /**
     * Update query method
     *
     * @access public
     * @param string $sql SQL query
     * @param array $args query arguments
     *
     * @return int|false affected rows
     */
    public function update($sql, array $args = array())
    {
        echo 'update...';
        exit;
        // TODO: Implement update() method.
    }

    /**
     * Get all query result rows as associated array
     *
     * @access public
     * @param string $sql SQL query
     * @param array $args query arguments
     *
     * @return array associated data array (two level array)
     */
    public function getAll($sql, array $args = array())
    {
        echo 'getAll...';
        exit;
        // TODO: Implement getAll() method.
    }

    /**
     * Get all query result rows as associated array with first field as row key
     *
     * @access public
     * @param string $sql SQL query
     * @param array $args query arguments
     *
     * @return array associated data array (two level array)
     */
    public function getAssoc($sql, array $args = array())
    {
        echo 'getAssoc...';
        exit;
        // TODO: Implement getAssoc() method.
    }

    /**
     * Get only first row from query
     *
     * @access public
     * @param string $sql SQL query
     * @param array $args query arguments
     *
     * @return array associated data array
     */
    public function getRow($sql, array $args = array())
    {
        $tmpSQL = $this->biudSQL(func_get_args());
        //执行sql
        $result = M()->query($tmpSQL);
        //返回其所需的一维数组
        return $result[0];
    }

    /**
     * Get first column of query result
     *
     * @access public
     * @param string $sql SQL query
     * @param array $args query arguments
     *
     * @return array one level data array
     */
    public function getCol($sql, array $args = array())
    {
        echo 'getCol...';
        exit;
        // TODO: Implement getCol() method.
    }

    /**
     * Get one first field value from query result
     *
     * @access public
     * @param string $sql SQL query
     * @param array $args query arguments
     *
     * @return string field value
     */
    public function getOne($sql, array $args = array())
    {
        $params = func_get_args();
        $sql = array_shift($params);
        $sql = str_replace('?F', $params[0], $sql);
        $sql = str_replace('?T', $params[1], $sql);
        $result = M()->query($sql);
        $row = $result[0];
        $val = array_values($row);
        return $val[0];
    }

    private function biudSQL($params)
    {
        //弹出sql模板
        $sql = array_shift($params);
        //使用正则表达式分割sql模板
        $result = preg_split('/\?[FTN]/', $sql);
        $tmpSQL = '';
        //遍历分割后的数组,并拼接sql语句
        foreach ($result as $key => $val) {
            $tmpSQL .= $val . $params[$key];
        }
        return $tmpSQL;
    }
}
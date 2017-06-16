<?php
namespace Tool;
use mysqli;

/**
 * Created by PhpStorm.
 * User: megatron
 * Date: 2017/6/15
 * Time: 下午6:13
 */
class Model
{
    public static $link = NULL;

    protected $table = NULL;
    //初始化表信息
    private $opt;

    public static $sqls = [];

    public function __construct($table = NULL)
    {
        $this->table = is_null($table) ? C('DB_PREFIX') . $this->table : C('DB_PREFIX') . $table;
        //连接数据库
        $this->connect();
        //初始化sql信息
        $this->_opt();
    }

    private function connect()
    {
        if (is_null(self::$link)) {
            $database = C('DB_DATABASE');
            if (empty($database)) halt('请配置数据库');
            self::$link = new Mysqli(C('DB_HOST'), C('DB_USER'), C('DB_PASSWORD'), $database, C('DB_PORT'));
            if (self::$link->connect_error) halt('数据库连接失败:' . self::$link->error);
        }
    }

    private function _opt()
    {
        $this->opt = [
            'field' => '*',
            'where' => '',
            'group' => '',
            'having' => '',
            'order' => '',
            'limit' => ''
        ];
    }

    public function field($field){
        $this->opt['field'] = $field;
        return $this;
    }

    public function where($where){
        $this->opt['where'] = " WHERE ".$where;
        return $this;
    }

    public function group($group){
        $this->opt['group'] = " GROUP BY ".$group;
        return $this;
    }

    public function having($having){
        $this->opt['having'] = " HAVING ".$having;
        return $this;
    }

    public function order($order){
        $this->opt['order'] = " ORDER BY ".$order;
        return $this;
    }

    public function limit($limit){
        $this->opt['limit'] = " LIMIT ".$limit;
        return $this;
    }

    public function query($sql)
    {
        self::$sqls[] = $sql;
        $result       = self::$link->query($sql);
        if (self::$link->errno) halt('mysql错误:' . self::$link->error . '<br/>SQL:' . $sql);
        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        $result->free();
        $this->_opt();
        return $rows;
    }

    public function all()
    {
        $sql = "SELECT " . $this->opt['field'] . " FROM " . $this->table . $this->opt['where'] . $this->opt['group'] . $this->opt['having'] . $this->opt['order'] . $this->opt['limit'];
        return $this->query($sql);
    }

    public function find(){
        $data = $this->limit(1)->all();
        return current($data);
    }

    public function execute($sql){
        self::$sqls[] = $sql;
        $bool = self::$link->query($sql);
        $this->_opt();
        if(is_object($bool)){
            halt('请使用query查询sql');
        }

        if($bool){
            return self::$link->insert_id ? self::$link->insert_id : self::$link->affected_rows;
        }else{
            halt('mysql错误:' . self::$link->error . '<br/>SQL:' . $sql);
        }
    }

    public function delete(){
        if(empty($this->opt['where'])) halt('请使用where条件');
        $sql = "DELETE FROM ".$this->table.$this->opt['where'];
        return $this->execute($sql);
    }

    private function safeStr($str){
        if(get_magic_quotes_gpc()){
            $str = stripslashes($str);
        }
        return self::$link->real_escape_string($str);
    }

}
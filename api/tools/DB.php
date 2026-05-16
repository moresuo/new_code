<?php
    include_once __DIR__ ."/database.config.php";
    class DB {
        private $conn;
        function __construct() {
            $this -> conn = new mysqli(DB_HOST,DB_USER,DB_PASSWORD,DB_DATABASE,DB_PORT);
            if ($this -> conn -> connect_errno) {
                echo json_encode([
                    "status" => $this -> conn -> connect_errno,
                    "msg" => "数据库错误: ". $this -> conn -> connect_error
                ]);
            }
            // MySQL 设置编码
            $this -> conn -> set_charset(DB_CHAR);
        }

        function close() {
            $this -> conn -> close();
        }

        function __destruct(){
            $this -> close();
        }

        function insert($sql) {
            // 执行SQL语句
            $this -> conn  -> query($sql);
            if ($this -> conn -> errno) {
                return $this -> conn -> error;
            } else {
                // 判断执行SQL语句的结果 $conn -> affected_rows 发生变化的行数
                if($this -> conn  -> affected_rows) {
                    return true;
                }else {
                    return false;
                }
            } 
        }

        function delete($sql) {
            // 执行SQL语句
            $this -> conn  -> query($sql);
            if ($this -> conn -> errno) {
                return $this -> conn -> error;
            } else {
                // 判断执行SQL语句的结果 $conn -> affected_rows 发生变化的行数
                if($this -> conn  -> affected_rows) {
                    return true;
                }else {
                    return false;
                }
            } 
        }

        function update($sql) {
            // 执行SQL语句
            $this -> conn  -> query($sql);
            if ($this -> conn -> errno) {
                return $this -> conn -> error;
            } else {
                // 判断执行SQL语句的结果 $conn -> affected_rows 发生变化的行数
                if($this -> conn  -> affected_rows) {
                    return true;
                }else {
                    return false;
                }
            } 
        }

        function selectOne($sql) {
            // 执行SQL语句
            $result = $this -> conn  -> query($sql);
            if ($this -> conn -> errno) {
                return $this -> conn -> error;
            } else {
                // 判断执行SQL语句的结果
                if($result -> num_rows) {
                    // 将查询结果转化为 关联数组
                    $data = $result -> fetch_assoc();
                    return $data;
                } else {
                    return false;
                }
            }
        }

        function getLastInsertId() {
            return $this->conn->insert_id;
        }

        function selectALL($sql) {
            // 执行SQL语句
            $result = $this -> conn  -> query($sql);
            if ($this -> conn -> errno) {
                return $this -> conn -> error;
            } else {
                // 判断执行SQL语句的结果
                if($result -> num_rows) {
                    $data = [];
                    // 将查询结果遍历添加到数组转化为 关联数组
                    while($row = $result -> fetch_assoc()) {
                        array_push($data,$row);
                    }
                    return $data;
                } else {
                    return false;
                }
            }
        }
    }
?>
<?php

class Model {

    public $null = NULL;
    //連接資料庫
    private function getDB() {
        $host = 'localhost';
        $dbuser = $_ENV['DB_USERNAME'];
        $dbpassword = $_ENV['DB_PASSWORD'];
        $dbname = $_ENV['DB_NAME'];
        $link = mysqli_connect($host, $dbuser, $dbpassword, $dbname);
        mysqli_query($link, "SET NAMES 'utf8mb4'");

        return $link;
    }
    //執行sql，返回結果集
    public function execute($sql) {
        $link = $this->getDB();
        $list = mysqli_query($link, $sql);
        if (is_object($list)) {
            $result = array();
            while ($row = mysqli_fetch_assoc($list)) {
                $result[] = $row;
            }
            return $result;
        }
        $id = mysqli_insert_id($link);
        $affected_rows = mysqli_affected_rows($link);
        $affect = ($affected_rows) ? true : false;
        return ($id > 0) ? $id : $affect;
    }
    public function insert($line, $table = NULL) {
        if ($table) {
            return "INSERT INTO $table (" . implode(',', array_keys($line)) . ") VALUES (\"" . implode('","', $line) . "\")";
        }
        return "INSERT INTO $this->table (" . implode(',', array_keys($line)) . ") VALUES (\"" . implode('","', $line) . "\")";
    }
    public function update($line, $table = NULL) {
        $str = "";
        $i = 0;
        foreach ($line as $kname => $kvalue) {
            $str .= $kname . "= '" . $kvalue . "'";
            $i++;
            if ($i < count($line)) {
                $str .= ",";
            }
        }
        if ($table) {
            return "UPDATE $table SET " . $str;
        }
        return "UPDATE $this->table SET " . $str;
    }
    public function delete($table = NULL) {
        if ($table) {
            return "DELETE FROM $table";
        }
        return "DELETE FROM $this->table";
    }
    public function select($table, $arr = NULL) {
        if ($arr) {
            return "SELECT " . implode(',', $arr) . " FROM $table";
        } else {
            return "SELECT * FROM $table";
        }
    }
    public function where($kname, $comparator, $kvalue) {
        return " WHERE $kname $comparator '$kvalue'";
    }
    public function and($kname, $comparator, $kvalue) {
        if ($kvalue == $this->null) {
            return " AND $kname IS NULL";
        }
        return " AND $kname $comparator '$kvalue'";
    }
    public function orwhere($kname, $operator, $kvalue) {
        return " OR $kname $operator '$kvalue'";
    }
    public function orderby($kname, $sort) {
        return " ORDER BY $kname $sort";
    }
    public function naturaljoin($table) {
        return " NATURAL JOIN $table";
    }
    public function leftjoin($table, $kname, $comparator, $kvalue) {
        return " LEFT JOIN ON $table $kname $comparator '$kvalue'";
    }
}

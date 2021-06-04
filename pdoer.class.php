<?php
 /*
  PDOer v1.0
  Developed By: Gian Karlo A. Orozco
  Requires PHP 7 or higher with PDO MySQL Support
 */

 ini_set('display_errors', 1);
 ini_set('display_startup_errors', 1);
 ini_set('memory_limit','-1');
 ini_set('session.gc_maxlifetime', 86400);
 error_reporting(E_ALL);
 //error_reporting(E_ERROR | E_PARSE);

 session_set_cookie_params(86400);
 session_start();

 date_default_timezone_set("Asia/Manila");
 $today = date("Y-m-d");
 $today2 = date("H:i");

 $sql_details = array(
    "host" => "",
    "port" => "",
    "user" => "",
    "pass" => "",
    "db"   => "",
 );

 PDOer::pdo_connection();

 class PDOer {

     public static function pdo_connection() {
         global $sql_details;

         /*$s_host = $sql_details["host"];
         $s_port = $sql_details["port"];
         $s_username = $sql_details["user"];
         $s_password = $sql_details["pass"];
         $s_dbname = $sql_details["db"];*/

         $s_dbname = $sql_details["db"];

         try {
             //$connect = new PDO("mysql:host=$s_host;port=$s_port;dbname=$s_dbname",$s_username,$s_password);
             $connect = new PDO("sqlite:".$s_dbname);
             return $connect;
         }
         catch(PDOException $e) {
             echo "<script>window.location='error.html'</script>";
             return 0;
         }
     }

     public static function pdo_exec(String $sql,$binds = array()) {
         $default = self::pdo_connection()->prepare($sql);
         $default->execute($binds);

         return $default;
     }

     public static function pdo_exec_select(int $fetch_style,String $table,Array $columns,$addtlStmt = "",$binds = array()) {
         $sql = "select ".implode(",",$columns)." from ".$table." $addtlStmt";

         $select = self::pdo_connection()->prepare($sql);
         $select->execute($binds);

         $result = array();

         while ($display = $select->fetch($fetch_style)) {
             array_push($result,$display);
         }

         return $result;
     }

     public static function pdo_exec_select_all(int $fetch_style,String $table,$addtlStmt = "",$binds = array()) {
         $sql = "select * from ".$table." $addtlStmt";

         $select = self::pdo_connection()->prepare($sql);
         $select->execute($binds);

         $result = array();

         while ($display = $select->fetch($fetch_style)) {
             array_push($result,$display);
         }

         return $result;
     }

     public static function pdo_exec_insert(String $table,$binds = array()) {
         $bindings = array_keys($binds);
         $values = array_values($binds);
         $columns = array();

         foreach ($bindings as $bind_name) {
             $column_name = str_replace(":","",$bind_name);
             array_push($columns,$column_name);
         }

         $sql = "insert into ".$table."(".
         implode(",",$columns).") values (".
         implode(",",$bindings).")";

         $insert = self::pdo_connection()->prepare($sql);
         $insert->execute($binds);

         return $insert;
     }

     public static function pdo_exec_update(String $table,$binds = array(),$addtlStmt = "",$binds2 = array()) {
         $bindings = array_keys($binds);
         $values = array_values($binds);
         $columns = array();

         foreach ($bindings as $bind_name) {
             $column_name = str_replace(":","",$bind_name)." = ".$bind_name;
             array_push($columns,$column_name);
         }

         $binds = array_merge($binds,$binds2);

         $sql = "update ".$table." set ".
         implode(",",$columns)." ".$addtlStmt;

         $update = self::pdo_connection()->prepare($sql);
         $update->execute($binds);

         return $update;
     }

     public static function pdo_exec_delete(String $table,$adtlStmt = "",$binds = array()) {
         $sql = "delete from ".$table." ".$adtlStmt;

         $delete = self::pdo_connection()->prepare($sql);
         $delete->execute($binds);
         
         return $delete;
     }

 }
?>
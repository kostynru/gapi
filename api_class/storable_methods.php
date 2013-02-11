<?php
//There's class of storable procedures.
final class Storable {
    public function __construct(){
        $procedure = $_GET['q'];
        $token = $_GET['token'];
        $time = time();
        $get = $_GET;
        unset($get['q']);
        unset($get['token']);
        $query = "INSERT INTO s_procedures(method, token, time) VALUES('$procedure', '$token', '$time')";
        mysql_query($query) or die(mysql_error());
    }
    public function is_expired($token){
        $query = "SELECT * FROM s_procedures WHERE token = '$token'";
        $time = time();
        $result = mysql_query($query);
        while($row = mysql_fetch_assoc($result)){
            $exp = $row['time'];
        }
        if($time-$exp > 3600){
            $query = "DELETE FROM s_procedures WHERE token = '$token'";
            mysql_query($query) or die(mysql_error());
            return true;
        }
        else {
            return false;
        }
    }

}
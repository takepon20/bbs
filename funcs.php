<?php
// htmlspecialcharsを短くする
function h($value){
    return htmlspecialchars($value,ENT_QUOTES);
}

// DBへ接続
function db_connect(){
    $db = new mysqli('localhost','root','','min_bbs');
	if(!$db){
		die('$db->error');
    }
    return $db;
}   

?>
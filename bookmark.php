<?php
require "include/bittorrent.php";
dbconn();

//Send some headers to keep the user's browser from caching the response.
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT" ); 
header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" ); 
header("Cache-Control: no-cache, must-revalidate" ); 
header("Pragma: no-cache" );
header("Content-Type: text/xml; charset=utf-8");

$torrentid = 0 + $_POST['torrentid'];
if(isset($CURUSER))
{
	$res_bookmark = sql_query("SELECT * FROM bookmarks WHERE torrentid=" . sqlesc($torrentid) . " AND userid=" . sqlesc($CURUSER[id]));
	if (mysql_num_rows($res_bookmark) > 0){
		sql_query("DELETE FROM bookmarks WHERE torrentid=" . sqlesc($torrentid) . " AND userid=" . sqlesc($CURUSER['id'])) or sqlerr(__FILE__,__LINE__);
		$Cache->delete_value('user_'.$CURUSER['id'].'_bookmark_array');
		echo "deleted";
		}
	else{
		sql_query("INSERT INTO bookmarks (torrentid, userid,time) VALUES (" . sqlesc($torrentid) . "," . sqlesc($CURUSER['id']) . "," . TIMENOW . ")") or sqlerr(__FILE__,__LINE__);
		$Cache->delete_value('user_'.$CURUSER['id'].'_bookmark_array');
		echo "added";
	}
}
else echo "failed";
?>

<?php
error_reporting(-1);
header('Content-Type: text/html; charset=utf-8');
include_once('funcs.php');
	echo getChatArray('chat.txt');
?>

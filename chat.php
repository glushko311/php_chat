<?php
error_reporting(-1);
header('Content-Type: text/html; charset=utf-8');
include_once('./funcs.php');

?>

<!DOCTYPE html>
<html>
	<head>
		<title>Super chat</title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	</head>
	<body>
	<?php

	session_start();
	if(empty($_SESSION['reg'])){
		 include_once("./views/authMain.phtml");
	}?>

	<?php
	if(!empty($_SESSION['reg']) ){
		$regStatus = $_SESSION['reg'];
		if(!empty($_SESSION['reg']) && $_SESSION['reg'] == 'success'){
			$regInfo = $_SESSION['success'];
			include_once("./views/authAfterLogIn.phtml");

		}elseif(!empty($_SESSION['reg']) && $_SESSION['reg'] == 'fail'){
			$regInfo = $_SESSION['fail'];
			include_once("./views/authMain.phtml");
			include_once("./views/messageAuthFail.phtml");
		}
	}

	?>
		<script src = "./js/chat.js"></script>
	</body>
</html>
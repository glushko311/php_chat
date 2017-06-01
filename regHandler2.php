<?php
error_reporting(-1);
header('Content-Type: text/html; charset=utf-8');

include_once "funcs.php";

init();

function init() {
	$funList = ["extractNValidateParams","findClient"];
	$stack = ["params" => params()];
	$res = pathThrougFunList($stack,$funList);
	$path2GoBack = explode('?', $_SERVER['HTTP_REFERER'])[0];
		session_start();
	if($res["state"] == "success") {
		$_SESSION['reg'] = "success";
		$_SESSION['success'] = $res['success'];
		$_SESSION['email'] = $res['email'];

		header("Location: ".$path2GoBack);
	}else {
		$_SESSION['reg'] = "fail";
		$_SESSION['fail'] = $res['error'];

		header("Location: ".$path2GoBack);
	}
}

/* 3 */
function findClient(array $stack) {
	$mainCfg = include_once "main.cfg.php";
	$params = $stack['extParams'];
	$login = $params['userLogin'];
//	$pass = $params['userPass'];
	$email = $params['email'];
	$foundClient = false;
	foreach ($mainCfg['usersDB'] as $user) {
		if($user['login'] == $login) {
			$foundClient = true;
			break;
		}
	}
	if($foundClient) {
		$stack['state'] = "error";
		$stack['error'] = "Пользователь с логином {$login} уже существует";
	}else {
		$stack['state'] = "success";
		$stack['success'] = $login;
		$stack['email'] = $email;
			$mes = $login.'@mes@'.$_POST['about'].'@mes_end@';
			$chat = fopen('chat.txt', 'a');
		fwrite($chat, $mes);
		fclose($chat);

	}

	return $stack;
}

function params() {
	return array(
		'method' => "POST",
		'paramList' => array(
			array(
				"name" => "userLogin",
				"require" => true,
				"validation" => array(
					"regEx" => "/^[абвгдеёжзийклмнопрстуфхцчшщьыъэюяАБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЬЫЪЭЮЯa-zA-Z0-9_\s\!\?\.]{3,40}$/"
				),
				"filter" => array("clearSpaceFilter")
			),
			array(
				"name" => "email",
				"require" => true,
				"validation" => array(
					"regEx" => "/^([0-9a-zA-Z]([-.\w]*[0-9a-zA-Z])*@([0-9a-zA-Z][-\w]*[0-9a-zA-Z]\.)+[a-zA-Z]{2,9})$/"
				),
				"filter" => array()
			),
			array(
				"name" => "about",
				"require" => true,
				"validation" => array(
					"regEx" => "/^[абвгдеёжзийклмнопрстуфхцчшщьыъэюяАБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЬЫЪЭЮЯa-zA-Z0-9_\-\,\'\"\+\.\!\s]{0,350}$/"
				),
				"filter" => array()
			),
		)
	);

}




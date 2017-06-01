<?php
error_reporting(-1);
header('Content-Type: text/html; charset=utf-8');
	
function pathThrougFunList(array $stack,array $funList) {
	if(isset($stack['state']) && $stack['state'] == "success" && isset($stack['success'])) {
		return array("success" => $stack['success'], "state" => "success", "email" => $stack['email']);
	}elseif(isset($stack['state']) && $stack['state'] == "error" && isset($stack['error'])){
		return array("error" => $stack['error'], "state" => "error");
	}elseif(count($funList) == 0) {
		return ['state' => 'error', 'error' => 'Вы исчерпали все попытки =)'];
	}
	$fun = array_shift($funList);
	$resultStack = $fun($stack);
	return pathThrougFunList($resultStack,$funList);
}


function extractNValidateParams(array $stack) {
	$params = $stack['params'];
	if($_SERVER['REQUEST_METHOD'] == $params['method']) {
		$extParams = extractParams($params);
		$errMsg = checkParams($extParams, "Параметры обязательные к заполнению: ");
		if($errMsg == "") {
			$validParams = validateParams($extParams,$params['paramList']);
			$errMsg = checkParams($validParams, "Параметры не прошли валидацию: ");
			if($errMsg == "") {
				$stack['extParams'] = $validParams;
			}else {
				$stack['state'] = "error";
				$stack['error'] = $errMsg;	
			}
		}else {
			$stack['state'] = "error";
			$stack['error'] = $errMsg;
		}
	}
	return $stack;
}


function validateParams(array $params, array $paramList) {
	$acc = array();
	foreach ($params as $prName => $prVal) {
		$prValidations = [];
		foreach ($paramList as $p) {
			if($p['name'] == $prName) {
				$prValidations = $p['validation'];
				break;
			}
		}
		$acc[$prName] = $prVal;
		foreach ($prValidations as $type => $pattern) {
			if(!validate($type,$pattern,$prVal)){
				$acc[$prName] = ["error"=>"empty"];
				break;
			}
		}
	}
	return $acc;
}

function validate($type,$pattern,$val) {
	$res = true;
	switch($type) {
		case "regEx": $res = (preg_match($pattern, $val)) ? true : false; break;
	}
	return $res;
}

function extractParams(array $params) {
	$incData = ($params['method'] == "POST") ? $_POST : $_GET ;
	$acc = array();
	foreach ($params['paramList'] as $param) {
		if(empty($incData[$param['name']]) && $param['require'] == true) {
			$acc[$param['name']] = ['error' => "empty"];
			continue;
		}elseif(empty($incData[$param['name']]) && $param['require'] == true) {
			continue;
		}
		$tmpParam = $incData[$param['name']];
		if(count($param['filter']) > 0) {
			foreach ($param['filter'] as $filterFun) {
				$tmpParam = $filterFun($tmpParam);
			}
		}
		$acc[$param['name']] = $tmpParam;
	}

	return $acc;
}


function checkParams(array $params, $msg) {
	$res = "";
	foreach ($params as $paramName => $paramValue) {
		if(is_array($paramValue) && !empty($paramValue['error'])) {
			$res = ($res == "") ? $msg : $res;
			$res .= $paramName.", ";
		}
	}
	return $res;
}


function clearSpaceFilter($arg) {
	return trim($arg);
}

function getChatArray($fileName){
	$chatFile = fopen($fileName, 'r');
	$chatString = file_get_contents('chat.txt');
	$mesageArray = explode('@mes_end@',$chatString);
	$messageStr = '';
	foreach(array_reverse($mesageArray) as $mesItem){
		if(!empty($mesItem) && $mesItem!=''){
			$name = explode('@mes@', $mesItem)[0];
			$message = explode('@mes@', $mesItem)[1];
			$messageStr.="<p style='width:80%;'><span style='color: purple;'>{$name}</span>: {$message}</p>";
		}
	}
	return $messageStr;

}


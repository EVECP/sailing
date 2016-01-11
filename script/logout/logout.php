<?php
	if (!isset($_SESSION)){
		session_start();
	}
	unset($_SESSION['user']);
	$url_referer = '';
	if (isset($_SERVER["HTTP_REFERER"])){
		$url_referer = $_SERVER["HTTP_REFERER"] . ($_SERVER["QUERY_STRING"] == '' ? '' : '?' . $_SERVER["QUERY_STRING"]);
	}else{
		$url_referer = '../../';
	}
	header('Location: ' . $url_referer);
?>
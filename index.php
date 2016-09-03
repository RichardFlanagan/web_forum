<?php
	session_start();
	require '../../Slim/Slim.php';
	\Slim\Slim::registerAutoloader();

	$app = new \Slim\Slim();
	$app->config('debug', true);
	$app->config('./templates');

	$siteTitle='answer4me';
	$siteSlogan='a discussion site for students';
	$headerImage='images/banner.png';
	$projectPath='/richard/';
	$theme = 6;
	
	require './lib/mongo.php';
	require './routes/pages.php';
	require './routes/userAPI.php';
	require './routes/adminAPI.php';

	$app->run();
?>
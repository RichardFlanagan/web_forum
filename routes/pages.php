<?php
	
	$app->get('/', function () use ($app, $siteTitle, $siteSlogan, $headerImage, $projectPath, $theme) {
		db_logServerAction(isset($_SESSION['user']) ? $_SESSION['user'] : '', 'pageload', $projectPath);

		if (isset($_SESSION['user'])) {
			$app->render('pagetemplate.php', array(
				'pageTitle' => $siteTitle,
				'siteTitle' => $siteTitle, 
				'siteSlogan' => $siteSlogan,
				'headerImage' => $headerImage,
				'projectPath' => $projectPath,
				'theme' => $theme,
				'pageContent' => '/frontpage.php',
				'menu' => true,
				'active' => 'frontpage'
			));
		} else {
			$app->redirect($projectPath.'login/');
		}
	});

	$app->get('/login(/)', function () use ($app, $siteTitle, $siteSlogan, $headerImage, $projectPath, $theme) {
		db_logServerAction(isset($_SESSION['user']) ? $_SESSION['user'] : '', 'pageload', $projectPath.'login/');


		$app->render('pagetemplate.php', array(
			'pageTitle' => $siteTitle.' : login',
			'siteTitle' => $siteTitle, 
			'siteSlogan' => $siteSlogan,
			'headerImage' => $headerImage,
			'projectPath' => $projectPath,
			'theme' => $theme,
			'pageContent' => '/login.php',
			'menu' => false,
			'active' => ''
		));
	});

	$app->get('/logout(/)', function () use ($app, $siteTitle, $siteSlogan, $headerImage, $projectPath, $theme) {
		db_logServerAction(isset($_SESSION['user']) ? $_SESSION['user'] : '', 'logout', $projectPath.'logout/');

		$_SESSION = array();
		session_destroy();
		$app->redirect($projectPath.'login/');
	});

	$app->get('/register(/)', function () use ($app, $siteTitle, $siteSlogan, $headerImage, $projectPath, $theme) {
		db_logServerAction(isset($_SESSION['user']) ? $_SESSION['user'] : '', 'pageload', $projectPath.'register/');

		$app->render('pagetemplate.php', array(
			'pageTitle' => $siteTitle.' : register',
			'siteTitle' => $siteTitle, 
			'siteSlogan' => $siteSlogan,
			'headerImage' => $headerImage,
			'projectPath' => $projectPath,
			'theme' => $theme,
			'pageContent' => '/register.php',
			'menu' => false,
			'active' => ''
		));
	});

	$app->get('/create(/)', function () use ($app, $siteTitle, $siteSlogan, $headerImage, $projectPath, $theme) {
		db_logServerAction(isset($_SESSION['user']) ? $_SESSION['user'] : '', 'pageload', $projectPath.'create/');

		if (isset($_SESSION['user'])) {
			$app->render('pagetemplate.php', array(
				'pageTitle' => $siteTitle.' : create post',
				'siteTitle' => $siteTitle, 
				'siteSlogan' => $siteSlogan,
				'headerImage' => $headerImage,
				'projectPath' => $projectPath,
				'theme' => $theme,
				'pageContent' => '/createpost.php',
				'menu' => true,
				'active' => 'createpost'
			));
		} else {
			$app->redirect($projectPath.'login/');
		}
	});

	$app->get('/post/:post_slug(/)', function ($post_slug) use ($app, $siteTitle, $siteSlogan, $headerImage, $projectPath, $theme) {
		db_logServerAction(isset($_SESSION['user']) ? $_SESSION['user'] : '', 'pageload', $projectPath.'post/'.$post_slug.'/');

		if (isset($_SESSION['user'])) {
			$res = db_getPost($post_slug);

			if ($res["ok"] != 1){
				$app->response->setStatus(400); // Bad request
				$app->response->setBody('bad request');
			} else if(is_null($res["retval"])){
				 $app->notFound();
			} else{
				$app->render('pagetemplate.php', array(
					'pageTitle' => $siteTitle.' : post',
					'siteTitle' => $siteTitle, 
					'siteSlogan' => $siteSlogan,
					'headerImage' => $headerImage,
					'projectPath' => $projectPath,
					'theme' => $theme,
					'pageContent' => '/post.php',
					'menu' => true,
					'active' => '',
					'data' => $res["retval"]
				));
			}			
		} else {
			$app->redirect($projectPath.'login/');
		}
	});


	$app->get('/user/:username(/)', function ($username) use ($app, $siteTitle, $siteSlogan, $headerImage, $projectPath, $theme) {
		db_logServerAction(isset($_SESSION['user']) ? $_SESSION['user'] : '', 'pageload', $projectPath.'user/'.$username.'/');

		if (isset($_SESSION['user'])) {
			$username = sanitizeString($username);
			$res = db_getUserContent($username);

			if ($res["ok"] != 1){
				$app->response->setStatus(400); // Bad request
				$app->response->setBody('bad request');
			} else if(is_null($res["retval"])){
				$app->response->setStatus(400); // Bad request
				$app->response->setBody('user not found');
			} else{
				$app->render('pagetemplate.php', array(
					'pageTitle' => $siteTitle.' : ',
					'siteTitle' => $siteTitle, 
					'siteSlogan' => $siteSlogan,
					'headerImage' => $headerImage,
					'projectPath' => $projectPath,
					'theme' => $theme,
					'pageContent' => '/content.php',
					'menu' => true,
					'active' => '',
					'data' => json_encode($res["retval"])
				));
			}

		} else {
			$app->redirect($projectPath.'login/');
		}
	});


	$app->get('/account(/)', function () use ($app, $siteTitle, $siteSlogan, $headerImage, $projectPath, $theme) {
		db_logServerAction(isset($_SESSION['user']) ? $_SESSION['user'] : '', 'pageload', $projectPath.'account/');

		if (isset($_SESSION['user'])) {
			$res = db_getUserDetails($_SESSION['user']);

			if ($res["ok"] != 1){
				$app->response->setStatus(400); // Bad request
				$app->response->setBody('bad request');
			} else if(is_null($res["retval"])){
				$app->response->setStatus(400); // Bad request
				$app->response->setBody('user not found');
			} else{
				$app->render('pagetemplate.php', array(
					'pageTitle' => $siteTitle.' : account',
					'siteTitle' => $siteTitle, 
					'siteSlogan' => $siteSlogan,
					'headerImage' => $headerImage,
					'projectPath' => $projectPath,
					'theme' => $theme,
					'pageContent' => '/account.php',
					'menu' => true,
					'active' => 'account',
					'data' => $res["retval"]
				));
			}
		} else {
			$app->redirect($projectPath.'login/');
		}
	});


	$app->get('/content(/)', function () use ($app, $siteTitle, $siteSlogan, $headerImage, $projectPath, $theme) {
		db_logServerAction(isset($_SESSION['user']) ? $_SESSION['user'] : '', 'pageload', $projectPath.'content/');

		if (isset($_SESSION['user'])) {
			$res = db_getUserContent($_SESSION['user']);

			if ($res["ok"] != 1){
				$app->response->setStatus(400); // Bad request
				$app->response->setBody('bad request');
			} else if(is_null($res["retval"])){
				$app->response->setStatus(400); // Bad request
				$app->response->setBody('user not found');
			} else{
				$app->render('pagetemplate.php', array(
					'pageTitle' => $siteTitle.' : my content',
					'siteTitle' => $siteTitle, 
					'siteSlogan' => $siteSlogan,
					'headerImage' => $headerImage,
					'projectPath' => $projectPath,
					'theme' => $theme,
					'pageContent' => '/content.php',
					'menu' => true,
					'active' => 'content',
					'data' => json_encode($res["retval"])
				));
			}
		} else {
			$app->redirect($projectPath.'login/');
		}
	});


	$app->get('/admin(/)', function () use ($app, $siteTitle, $siteSlogan, $headerImage, $projectPath, $theme) {
		db_logServerAction(isset($_SESSION['user']) ? $_SESSION['user'] : '', 'pageload', $projectPath.'admin/');

		if (isset($_SESSION['user'])) {
			$res = db_getUserAccessLevel($_SESSION['user']);

			if ($res["ok"] != 1){
				$app->response->setStatus(400); // Bad request
				$app->response->setBody('bad request');
			} else if(is_null($res["retval"])){
				$app->response->setStatus(400); // No such user
				$app->response->setBody('user not found');
			} else if($res["retval"] < 100){
				$app->response->setStatus(403); // Not authorized
				$app->response->setBody('user not authorized');
			}else{
				$app->render('pagetemplate.php', array(
					'pageTitle' => $siteTitle.' : admin',
					'siteTitle' => $siteTitle, 
					'siteSlogan' => $siteSlogan,
					'headerImage' => $headerImage,
					'projectPath' => $projectPath,
					'theme' => $theme,
					'pageContent' => '/admin.php',
					'menu' => true,
					'active' => 'admin'
				));
			}
		} else {
			$app->redirect($projectPath.'login/');
		}
	});

?>
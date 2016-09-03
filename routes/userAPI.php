<?php
	$app->group('/api', function () use ($app, $projectPath) {
		
		$app->post('/login(/)', function () use ($app, $projectPath) {
			db_logServerAction(isset($_SESSION['user']) ? $_SESSION['user'] : '', 'api call', $projectPath.'api/login/');

			$username = $app->request->params("username");
			$password = $app->request->params("password");
			$res = db_checkLogin($username, $password);

			if($res == 0){
				$app->response->setStatus(401); // Unauthorized
				$app->response->setBody('unauthorized');
			} 
			else if($res == 1){
				$_SESSION['user'] = $username;
				$app->redirect($projectPath);
			} 
			else {
				$app->response->setStatus(400); // Bad request
				$app->response->setBody('bad request');
			}
		});


		$app->post('/register(/)', function () use ($app, $projectPath) {
			db_logServerAction(isset($_SESSION['user']) ? $_SESSION['user'] : '', 'api call', $projectPath.'api/register/');

			$res = db_registerUser($app->request->params());

			if($res == 1){
				$app->response->setStatus(200); // ok
			}
			else if($res == 0){
				$app->response->setStatus(401); // User already exists
				$app->response->setBody('user already exists');
			}
			else {
				$app->response->setStatus(400); // Bad request
				$app->response->setBody('bad request');
			}
		});


		$app->post('/create(/)', function () use ($app, $projectPath) {
			db_logServerAction(isset($_SESSION['user']) ? $_SESSION['user'] : '', 'api call', $projectPath.'api/create/');

			if (isset($_SESSION['user'])) {
				$title = $app->request->params("title");
				$content = $app->request->params("content");
				$res = db_createPost($title, $content, $_SESSION['user']);

				if($res == 1){
					$app->response->setStatus(200); // ok
				}
				else {
					$app->response->setStatus(400); // Bad request
					$app->response->setBody('bad request');
				}
			} else{
				$app->response->setStatus(401); // Unauthorized
				$app->response->setBody('unauthorized');
			}
		});


		$app->post('/getposts(/)', function () use ($app, $projectPath) {
			db_logServerAction(isset($_SESSION['user']) ? $_SESSION['user'] : '', 'api call', $projectPath.'api/getposts/');
			if (isset($_SESSION['user'])) {
				$res = db_getPosts();

				if($res["ok"] == 1){
					$app->response->setStatus(200); // ok
					$app->response->setBody(json_encode($res['retval']));
				}
				else {
					$app->response->setStatus(400); // Bad request
					$app->response->setBody('bad request');
				}
			} else{
				$app->response->setStatus(401); // Unauthorized
				$app->response->setBody('unauthorized');
			}
		});


		$app->post('/comment(/)', function () use ($app, $projectPath) {
			db_logServerAction(isset($_SESSION['user']) ? $_SESSION['user'] : '', 'api call', $projectPath.'api/comment/');

			if (isset($_SESSION['user'])) {
				$content = $app->request->params("content");
				$slug = $app->request->params("slug");
				$res = db_createComment($content, $slug, $_SESSION['user']);

				if($res == 1){
					$app->response->setStatus(200); // ok
				}
				else {
					$app->response->setStatus(400); // Bad request
					$app->response->setBody('bad request');
				}
			} else{
				$app->response->setStatus(401); // Unauthorized
				$app->response->setBody('unauthorized');
			}
		});


		$app->group('/account', function () use ($app, $projectPath) {
			
			$app->post('/update(/)', function () use ($app, $projectPath) {
				db_logServerAction(isset($_SESSION['user']) ? $_SESSION['user'] : '', 'api call', $projectPath.'api/account/update/');

				if (isset($_SESSION['user'])) {
					$res = db_updateAccount($_SESSION['user'], $app->request->params());
					if($res == 1){
						$app->response->setStatus(200); // ok
					}
					else {
						$app->response->setStatus(400); // Bad request
						$app->response->setBody('bad request');
					}
				} else{
					$app->response->setStatus(401); // Unauthorized
					$app->response->setBody('unauthorized');
				}
			});


			$app->post('/delete(/)', function () use ($app, $projectPath) {
				db_logServerAction(isset($_SESSION['user']) ? $_SESSION['user'] : '', 'api call', $projectPath.'api/account/delete/');

				if (isset($_SESSION['user'])) {
					$res = db_deleteAccount($_SESSION['user']);
					if($res == 1){
						$app->redirect($projectPath.'logout/');
					}
					else {
						$app->response->setStatus(400); // Bad request
						$app->response->setBody('bad request');
					}
				} else{
					$app->response->setStatus(401); // Unauthorized
					$app->response->setBody('unauthorized');
				}
			});

		});

	});

?>
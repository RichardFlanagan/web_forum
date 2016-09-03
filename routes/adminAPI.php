<?php
	
	$app->group('/api', function () use ($app, $projectPath) {
		
		$app->group('/admin', function () use ($app, $projectPath) {
		
			$app->post('/getusers(/)', function () use ($app, $projectPath) {
				db_logServerAction(isset($_SESSION['user']) ? $_SESSION['user'] : '', 'api call', $projectPath.'api/admin/getusers/');
				
				if (isset($_SESSION['user'])) {
					$access = db_getUserAccessLevel($_SESSION['user']);

					if ($access["ok"] != 1){
						$app->response->setStatus(400); // Bad request
						$app->response->setBody('bad request');
					} else if(is_null($access["retval"])){
						$app->response->setStatus(400); // No such user
						$app->response->setBody('user not found');
					} else if($access["retval"] < 100){
						$app->response->setStatus(403); // Not authorized
						$app->response->setBody('user not authorized');
					}else{
						$app->response->setStatus(200);
						$app->response->setBody(json_encode(db_getUsersStats()));
					}
				} else {
					$app->redirect($projectPath.'login/');
				}

			});


			$app->post('/getserverlogs(/)', function () use ($app, $projectPath) {
				db_logServerAction(isset($_SESSION['user']) ? $_SESSION['user'] : '', 'api call', $projectPath.'api/admin/getserverlogs/');
				
				if (isset($_SESSION['user'])) {
					$access = db_getUserAccessLevel($_SESSION['user']);

					if ($access["ok"] != 1){
						$app->response->setStatus(400); // Bad request
						$app->response->setBody('bad request');
					} else if(is_null($access["retval"])){
						$app->response->setStatus(400); // No such user
						$app->response->setBody('user not found');
					} else if($access["retval"] < 100){
						$app->response->setStatus(403); // Not authorized
						$app->response->setBody('user not authorized');
					}else{
						$app->response->setStatus(200);
						$app->response->setBody(json_encode(db_getServerLogs()));
					}
				} else {
					$app->redirect($projectPath.'login/');
				}

			});


			$app->post('/getusertypes(/)', function () use ($app, $projectPath) {
				db_logServerAction(isset($_SESSION['user']) ? $_SESSION['user'] : '', 'api call', $projectPath.'api/admin/getusertypes/');
				
				if (isset($_SESSION['user'])) {
					$access = db_getUserAccessLevel($_SESSION['user']);

					if ($access["ok"] != 1){
						$app->response->setStatus(400); // Bad request
						$app->response->setBody('bad request');
					} else if(is_null($access["retval"])){
						$app->response->setStatus(400); // No such user
						$app->response->setBody('user not found');
					} else if($access["retval"] < 100){
						$app->response->setStatus(403); // Not authorized
						$app->response->setBody('user not authorized');
					}else{
						$app->response->setStatus(200);
						$app->response->setBody(json_encode(db_getUserTypes()));
					}
				} else {
					$app->redirect($projectPath.'login/');
				}

			});


			$app->post('/getpoststats(/)', function () use ($app, $projectPath) {
				db_logServerAction(isset($_SESSION['user']) ? $_SESSION['user'] : '', 'api call', $projectPath.'api/admin/getpoststats/');
				
				if (isset($_SESSION['user'])) {
					$access = db_getUserAccessLevel($_SESSION['user']);

					if ($access["ok"] != 1){
						$app->response->setStatus(400); // Bad request
						$app->response->setBody('bad request');
					} else if(is_null($access["retval"])){
						$app->response->setStatus(400); // No such user
						$app->response->setBody('user not found');
					} else if($access["retval"] < 100){
						$app->response->setStatus(403); // Not authorized
						$app->response->setBody('user not authorized');
					}else{
						$app->response->setStatus(200);
						$app->response->setBody(json_encode(db_getPostStats()));
					}
				} else {
					$app->redirect($projectPath.'login/');
				}

			});


			$app->post('/getactivitydata(/)', function () use ($app, $projectPath) {
				db_logServerAction(isset($_SESSION['user']) ? $_SESSION['user'] : '', 'api call', $projectPath.'api/admin/getactivitydata/');
				
				if (isset($_SESSION['user'])) {
					$access = db_getUserAccessLevel($_SESSION['user']);

					if ($access["ok"] != 1){
						$app->response->setStatus(400); // Bad request
						$app->response->setBody('bad request');
					} else if(is_null($access["retval"])){
						$app->response->setStatus(400); // No such user
						$app->response->setBody('user not found');
					} else if($access["retval"] < 100){
						$app->response->setStatus(403); // Not authorized
						$app->response->setBody('user not authorized');
					}else{
						$app->response->setStatus(200);
						$app->response->setBody(json_encode(db_getActivityGraphData()));
					}
				} else {
					$app->redirect($projectPath.'login/');
				}

			});


			$app->post('/promote(/)', function () use ($app, $projectPath) {
				db_logServerAction(isset($_SESSION['user']) ? $_SESSION['user'] : '', 'api call', $projectPath.'api/admin/promote/');
				
				if (isset($_SESSION['user'])) {
					$access = db_getUserAccessLevel($_SESSION['user']);

					if ($access["ok"] != 1){
						$app->response->setStatus(400); // Bad request
						$app->response->setBody('bad request1');
					} else if(is_null($access["retval"])){
						$app->response->setStatus(400); // No such user
						$app->response->setBody('user not found');
					} else if($access["retval"] < 100){
						$app->response->setStatus(403); // Not authorized
						$app->response->setBody('user not authorized');
					}else{
						$username = $app->request->params("username");
						$promote = $app->request->params("promote");

						$res = db_promoteUser($username, $promote);

						if($res["ok"] != 1){
							$app->response->setStatus(400); // Bad request
							$app->response->setBody('bad request2');
						} else{
							$app->response->setStatus(200);
						}
					}
				} else {
					$app->redirect($projectPath.'login/');
				}

			});

		});

	});

?>
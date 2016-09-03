<?php
	$mongo = new MongoClient();
	$db = $mongo->dev;

	function sanitizeString($str){
		$str = trim(addslashes(htmlspecialchars(strip_tags((string) $str))));
		return $str;
	}

	function db_checkLogin($username, $password){
		$username = sanitizeString($username);
		$password = sanitizeString($password);

		if(empty($username)  || is_null($username) || empty($password) || is_null($password)){
			return -1;
		} else{
			
			global $db;
			$procedure = 
				'function(username, password) { 
					return db.users.count({ "username": username, "password": password }); 
				}';
			$args = array($username, $password);
			$response = $db->execute($procedure, $args);

			return $response["retval"];
		}
	}

	function db_registerUser($args){
		foreach ($args as &$arg) {
			$arg = sanitizeString($arg);
			if (empty($arg) || is_null($arg)){
				return -1;
			}
		}

		$userExists = db_checkLogin($args["username"], $args["password"]);
		if($userExists == 1){
			return 0; // User already exists
		} else if($userExists != 0){
			return -1; // Bad request
		} else {

			global $db;
			$procedure = 
				'function(args) { 
					var user_type = db.user_types.findOne({
						"user_type_name":"user"
					});

					var res = db.users.insert({
						"username": args.username,
						"password": args.password,
						"email": args.email,
						"first_name": args.firstname,
						"last_name": args.lastname,
						"date_of_birth": args.dateofbirth,
						"registration_time": (new Date).getTime(),
						"user_type": user_type._id,
					});
					return res.nInserted;
				}';

			$response = $db->execute($procedure, array($args));
			return ($response["retval"]);
		}
	}

	function db_createPost($title, $content, $user){
		$title = sanitizeString($title);
		$content = sanitizeString($content);
		$user = sanitizeString($user);
		$slug = md5(uniqid($user, true));
		$post_time = microtime(true)*1000;

		if (empty($title) || is_null($title)){
			return -1;
		} else if (is_null($content)){
			return -1;
		} else if (empty($user) || is_null($user)){
			return -1;
		} else{

			global $db;
			$procedure = 
				'function(title, content, user, slug, post_time) { 
					var res = db.posts.insert({
						"title": title,
						"content": content,
						"author": user,
						"slug": slug,
						"post_time": post_time
					});
					return res.nInserted;
				}';

			$response = $db->execute($procedure, array($title, $content, $user, $slug, $post_time));
			return ($response["retval"]);
		}
	}

	function db_getPosts(){
		global $db;
		$procedure = 
			'function() { 
				var res = db.posts.find().sort({
					"post_time": -1
				}).limit(20).toArray();
				return res;
			}';

		$response = $db->execute($procedure, array());
		return $response;
	}

	function db_getPost($post_slug){
		$post_slug = sanitizeString($post_slug);

		global $db;
		$procedure = 
			'function(slug) { 
				var post = db.posts.findOne({
					"slug": slug
				});
				if(post){
					var comments = db.comments.find({
						"slug":post.slug
					}).sort({
						"post_time":1
					}).toArray();
					post.comments = comments;
				}
				return post;
			}';

		$response = $db->execute($procedure, array($post_slug));
		return $response;
	}

	function db_createComment($content, $slug, $user){
		$content = sanitizeString($content);
		$slug = sanitizeString($slug);
		$user = sanitizeString($user);
		$post_time = microtime(true)*1000;

		global $db;
		$procedure = 
			'function(content, slug, user, post_time) { 
				var res = db.comments.insert({
					"content": content,
					"author": user,
					"slug": slug,
					"post_time": post_time
				});
				return res.nInserted;
			}';

		$response = $db->execute($procedure, array($content, $slug, $user, $post_time));
		return ($response["retval"]);
	}

	function db_getUserDetails($username){
		$username = sanitizeString($username);

		global $db;
		$procedure = 
			'function(username) { 
				var user = db.users.findOne({
					"username": username
				},
				{ 
					"password":0,
					"_id":0
				});
				return user;
			}';

		$response = $db->execute($procedure, array($username));
		return $response;
	}

	function db_updateAccount($username, $args){
		foreach ($args as &$arg) {
			$arg = sanitizeString($arg);
			if (is_null($arg)){
				return -1;
			}
		}

		$userExists = db_checkLogin($username, $args["password"]);

		if($userExists != 1){
			return 0; // User doesn't exist or unauthorized 
		} else {

			global $db;
			$procedure = 
				'function(username, args) {
					var res = -1;
					if(args.newpassword != ""){
						res = db.users.update(
							{
								"username":username
							},
							{
								$set:{
									"password": args.newpassword,
									"email": args.email,
									"first_name": args.firstname,
									"last_name": args.lastname,
									"date_of_birth": args.dateofbirth
								}
							}
						);
					} else{
						res = db.users.update(
							{
								"username":username
							},
							{
								$set:{
									"email": args.email,
									"first_name": args.firstname,
									"last_name": args.lastname,
									"date_of_birth": args.dateofbirth
								}
							}
						);
					}
					return res.nInserted;
				}';

			$response = $db->execute($procedure, array($username, $args));
			return $response["ok"];
		}
	}

	function db_getUserContent($username){
		global $db;
		$procedure = 
			'function(username) {
				var posts = db.posts.find({
						"author":username
					}).sort({
						"post_time": -1
					}).toArray();
				var comments = db.comments.find({
						"author":username
					}).sort({
						"post_time": -1
					}).toArray();

				return {"username":username, "posts":posts, "comments":comments};
			}';

		$response = $db->execute($procedure, array($username));
		return $response;
	}

	function db_getUserAccessLevel($username){
		global $db;
		$procedure = 
			'function(username) {
				var user = db.users.findOne({
						"username":username
					});
				var user_type = db.user_types.findOne({
						"_id":user.user_type
					});

				return user_type.access_level;
			}';

		$response = $db->execute($procedure, array($username));
		return $response;
	}

	function db_getUsersStats(){
		global $db;
		$procedure = 
			'function() {
				var users = db.users.find({}, {"password":0, "_id":0}).sort({"username":1}).toArray();
				var user_types = db.user_types.find().toArray();

				for(var i=0; i<users.length; i++){
					var user_type = db.user_types.findOne({"_id":users[i].user_type});
					users[i].user_type = user_type;

					var postCount = db.posts.count({"author":users[i].username});
					users[i].post_count = postCount;

					var commentCount = db.comments.count({"author":users[i].username});
					users[i].comment_count = commentCount;
				}

				return users;
			}';

		$response = $db->execute($procedure, array());
		return $response["retval"];
	}

	function db_logServerAction($username, $action, $target){
		$username = sanitizeString($username);
		$action = sanitizeString($action);
		$target = sanitizeString($target);

		global $db;
		$procedure = 
			'function(username, action, target) {
				var res = db.server_logs.insert({
					"username": username,
					"action": action,
					"target": target,
					"time": (new Date).getTime()
				});
				return res;
			}';

		$response = $db->execute($procedure, array($username, $action, $target));
		return $response["ok"];
	}

	function db_getServerLogs(){
		global $db;
		$procedure = 
			'function() {
				var logs = db.server_logs.find().toArray();
				
				for(var i=0; i<logs.length; i++){
					if(logs[i].username != ""){
						var user = db.users.findOne({"username":logs[i].username});
						if(user != null){
							var user_type = db.user_types.findOne({"_id":user.user_type});
							logs[i].user_type = user_type.user_type_name;
							logs[i].access_level = user_type.access_level;
						} else{
							logs[i].user_type = "";
							logs[i].access_level = 0;
						}	
						
					} else {
						logs[i].user_type = "";
						logs[i].access_level = 0;
					}
				}
					
				return logs;
			}';

		$response = $db->execute($procedure, array());
		return $response["retval"];
	}

	function db_getUserTypes(){
		global $db;
		$procedure = 
			'function() {
				var user_types = db.user_types.find().toArray();
				
				for(var i=0; i<user_types.length; i++){
					var count = db.users.count({"user_type":user_types[i]._id});
					user_types[i].count = count;
				}

				return user_types;
			}';

		$response = $db->execute($procedure, array());
		return $response["retval"];
	}

	function db_getPostStats(){
		global $db;
		$procedure = 
			'function() {
				var posts = db.posts.find({},{"_id":0, "content":0}).toArray();
				
				for(var i=0; i<posts.length; i++){
					var commentCount = db.comments.count({"slug":posts[i].slug});
					posts[i].comment_count = commentCount;
				}

				return posts;
			}';

		$response = $db->execute($procedure, array());
		return $response["retval"];
	}

	function db_getActivityGraphData(){
		global $db;
		$procedure = 
			'function() {
				var userRegDates = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
				var users = db.users.find({},{"registration_time":1}).toArray();
				for(var i=0; i<users.length; i++){
					var month = new Date(users[i].registration_time).getUTCMonth();
					userRegDates[month] += 1;
				}

				var postDates = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
				var posts = db.posts.find({},{"post_time":1}).toArray();
				for(var i=0; i<posts.length; i++){
					var month = new Date(posts[i].post_time).getUTCMonth();
					postDates[month] += 1;
				}

				var commentDates = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
				var comments = db.comments.find({},{"post_time":1}).toArray();
				for(var i=0; i<comments.length; i++){
					var month = new Date(comments[i].post_time).getUTCMonth();
					commentDates[month] += 1;
				}

				return {"users":userRegDates, "posts":postDates, "comments":commentDates};
			}';

		$response = $db->execute($procedure, array());
		return $response["retval"];
	}

	function db_promoteUser($username, $promote){
		$username = sanitizeString($username);
		$promote = ($promote=="false"||!$promote) ? $promote=false : $promote=true;

		global $db;
		$procedure = 
			'function(username, promote) {
				var user = db.users.findOne({"username":username});

				if(user == null){
					return 0;
				} else{
					var userType;
					if(promote){
						userType = db.user_types.findOne({"user_type_name":"administrator"});
					} else{
						userType = db.user_types.findOne({"user_type_name":"user"});
					}
					var res = db.users.update(
						{
							"username":username
						}, 
						{
							$set:{
								"user_type":userType._id
							}
						}
					);

					return res;
				} 
			}';

		$response = $db->execute($procedure, array($username, $promote));
		return $response;
	}

	function db_deleteAccount($username){
		global $db;
		$procedure = 
			'function(username) {
				return db.users.remove({"username":username});
			}';

		$response = $db->execute($procedure, array($username));
		return $response["ok"];
	}
?>
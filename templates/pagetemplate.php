<!DOCTYPE html>
<html lang="en">
	<head>
		<title><?php echo $pageTitle; ?></title>

		<link rel="stylesheet" type="text/css" href=<?php echo $projectPath."lib/jquery-ui/jquery-ui.$theme/jquery-ui.css"; ?>>
		<link rel="stylesheet" type="text/css" href=<?php echo $projectPath."lib/jquery-ui/jquery-ui.$theme/jquery-ui.structure.css"; ?>>
		<link rel="stylesheet" type="text/css" href=<?php echo $projectPath."lib/jquery-ui/jquery-ui.$theme/jquery-ui.theme.css"; ?>>

		<script type="text/javascript" src=<?php echo $projectPath."lib/jquery/jquery.js"; ?>></script>
		<script type="text/javascript" src=<?php echo $projectPath."lib/jquery-ui/jquery-ui.$theme/jquery-ui.min.js"; ?>></script>

		<script>
			function addErrorPopup(header, message, empty){
				$("#errorDiv").empty();
				$("#errorDiv").append(''+
					'<div class="ui-widget ui-state-error ui-corner-all errorPopup">'+
						'<p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>'+
						'<strong>'+header+':</strong> '+message+'</p>'+
					'</div>');
			}
		</script>

		<style>
			#mainDiv {
				width: 700px;
				margin-left:auto;
				margin-right:auto;
				margin-top:10px;
				margin-bottom:20px;
			}
			#headerDiv {
				width: 100%;
				height: 120px; 
				margin-left:auto;
				margin-right:auto;
				margin-bottom: 1.0em; 
			}
			#headerImage {
				width: 100px;
				height: 100px;
				position: relative;
				float: left;
				margin: 10px;
			}
			#header {
				position: relative;
				top: -5px;
				font-weight: bold;
				font-family: Trebuchet MS,Tahoma,Verdana,Arial,sans-serif;
				font-size: 2.0em;
			}
			#subheader {
				position: relative;
				top: -10px;
				font-weight: bold;
				font-family: Trebuchet MS,Tahoma,Verdana,Arial,sans-serif;
				font-size: 1.5em;
			}
			#menuBarDiv {
				width: 100%;
				height: 40px;
				margin-left:auto;
				margin-right:auto;
				margin-bottom: 1.0em;
				line-height: 40px;
			}
			#errorDiv {
				width: 100%;
				padding: 0 .7em;
				margin-left:auto;
				margin-right:auto;
				margin-bottom: 1.0em;
			}
			#contentDiv {
				width: 100%;
				margin-left:auto;
				margin-right:auto;
				padding-bottom: 20px;
			}


			li {
				float: left;
				border-right:1px solid #bbb;
				padding: 0px;
				margin: 0px;
				padding-left: 10px;
				padding-right: 10px;
			}

			li:last-child {
				border-right: none;
				border-left:1px solid #bbb;
			}

			li a {
				display: block;
				width: 100%;
				height: 100%;
				margin: 0px;
				
				text-align: center;
				text-decoration:underline;
			}

			li:hover:not(.active) {
				background-color: #ddd;
			}

			.active {
				text-decoration:none;
				background-color: #ddd;
			}

			ul {
				list-style-type: none;
				margin: 0;
				padding: 0;
				overflow: hidden;
			}

			footer {
				text-align: center;
			}

		</style>

	</head>
	<body>
		<div id="mainDiv">
			<div id="headerDiv" class="ui-widget-content ui-corner-all">
				<img id="headerImage" src=<?php echo $projectPath.$headerImage; ?>>
				<h1 id="header"><?php echo $siteTitle; ?></h1>
				<h3 id="subheader"><?php echo $siteSlogan; ?></h3>
			</div>

<?php 
	if ($menu){
		$frontpageActive = $createPostActive = $myContentActive = $myAccountActive = $adminActive = "";
		switch($active){
			case "frontpage": $frontpageActive = "active"; break;
			case "createpost": $createPostActive = "active"; break;
			case "myposts": $myContentActive = "active"; break;
			case "account": $myAccountActive = "active"; break;
			case "admin": $adminActive = "active"; break;
		}

		$adminItem = '';
		$accessLevel = db_getUserAccessLevel($_SESSION["user"])["retval"];
		if(is_null($accessLevel) || $accessLevel >= 100){
			$adminItem = '<li class="'.$adminActive.'"><a href="'.$projectPath.'admin/" class="ui-widget '.$adminActive.'">Admin</a></li>';
		}

		$menuBarHtml = '
			<div id="menuBarDiv" class="ui-widget-content ui-corner-all">
				<ul>
					<li class="'.$frontpageActive.'"><a href="'.$projectPath.'" class="ui-widget '.$frontpageActive.'">Front Page</a></li>
					<li class="'.$createPostActive.'"><a href="'.$projectPath.'create/" class="ui-widget '.$createPostActive.'">Create Post</a></li>
					<li class="'.$myContentActive.'"><a href="'.$projectPath.'content/" class="ui-widget '.$myContentActive.'">My Content</a></li>
					<li class="'.$myAccountActive.'"><a href="'.$projectPath.'account/" class="ui-widget '.$myAccountActive.'">My Account</a></li>
					'.$adminItem.'
					<ul style="float:right;">
						<li><a href="'.$projectPath.'logout/" class="ui-widget">Logout</a></li>
					</ul>
				</ul>
			</div>';
		echo $menuBarHtml;
	}
?>

			<div id="errorDiv"></div>

			<?php include $pageContent; ?>
		</div>
	</body>

	<footer class="ui-widget">
		<small>&copy; Richard Flanagan : A00193644</small>
	</footer>
</html>
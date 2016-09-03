<!-- SCRIPTS -->
<script>
	function loginClickEvent(event){
		var username = $("#username").val();
		var password = $("#password").val();
		if(username == ""){
			addErrorPopup("Error", "Please enter a username");
		}
		else if(password == ""){
			addErrorPopup("Error", "Please enter a password");
		} 
		else{
			$("#errorDiv").empty();

			var loginRequest = $.ajax({
				url: ""+<?php echo $projectPath;?>+"api/login/",
				method: "POST",
				data: { 
					username: username,
					password: password
				}
			});

			loginRequest.done(function(data, textStatus, jqXHR) {
				window.location = ""+<?php echo $projectPath;?>;
			});

			loginRequest.fail(function(jqXHR, textStatus, errorThrown) {
				addErrorPopup("Error", "Request could not be sent: "+errorThrown);
			});
		}
	}

	function registerClickEvent(event){
		window.location = ""+<?php echo $projectPath;?>+"register/";
	}

	function initGuiObjects(){
		$("#login").button().click(function(event) { loginClickEvent(event); });
		$("#register").button().click(function(event) { registerClickEvent(event); });
		$("#username").addClass("ui-corner-all");
		$("#password").addClass("ui-corner-all");
	}

	$(document).ready(function(){
		initGuiObjects();
	});
</script>


<!-- STYLES -->
<style>
	#loginDiv { 
		width: 200px; 
		padding: 0.5em; 
		text-align:center; 
		margin-left:auto;
		margin-right:auto;
	}
	.loginDivItem { 
		margin-bottom: 1.0em; 
	}
</style>


<!-- HTML -->
<div id="loginDiv" class="ui-widget-content ui-corner-all">
	<h3 class="ui-widget">
		Welcome!
	</h3>
	<input type="button" id="register" value="Register" class="ui-widget loginDivItem"/>

	<input type="text" id="username" placeholder="username" class="ui-widget loginDivItem"/>
	<input type="password" id="password" placeholder="password" class="ui-widget loginDivItem"/>
	<input type="button" id="login" value="Login" class="ui-widget loginDivItem"/>
</div>
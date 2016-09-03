<!-- SCRIPTS -->
<script>
	function registerClickEvent(event){
		var firstname = $("#firstname").val().trim();
		var lastname = $("#lastname").val().trim();
		var username = $("#username").val().trim();
		var email = $("#email").val().trim();
		var password = $("#password").val().trim();
		var dateofbirth = $("#datepicker").val().trim();

		var emailRegex = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;


		if(firstname == ""){
			addErrorPopup("Error", "Please enter a valid first name");
		} 
		else if(lastname == ""){
			addErrorPopup("Error", "Please enter a valid last name");
		}
		else if(username == ""){
			addErrorPopup("Error", "Please enter a valid username");
		}
		else if(email == "" || !emailRegex.test(email)){
			addErrorPopup("Error", "Please enter a valid email");
		}
		else if(password == ""){
			addErrorPopup("Error", "Please enter a valid password");
		}
		else if(dateofbirth == ""){
			addErrorPopup("Error", "Please enter a valid date of birth");
		}
		else {
			$("#errorDiv").empty();

			var registerRequest = $.ajax({
				url: ""+<?php echo $projectPath;?>+"api/register/",
				method: "POST",
				data: { 
					firstname: firstname,
					lastname: lastname,
					username: username,
					email: email,
					password: password,
					dateofbirth: dateofbirth
				}
			});

			registerRequest.done(function(data, textStatus, jqXHR) {
				window.location = ""+<?php echo $projectPath;?>;
			});

			registerRequest.fail(function(jqXHR, textStatus, errorThrown) {
				addErrorPopup("Error", "Request could not be sent: "+errorThrown);
			});
		}
	}

	function initGuiObjects(){

		$("#register").button().click(function(event) { registerClickEvent(event); });

		$("#firstname").addClass("ui-corner-all");
		$("#lastname").addClass("ui-corner-all");
		$("#username").addClass("ui-corner-all");
		$("#email").addClass("ui-corner-all");
		$("#password").addClass("ui-corner-all");
		$("#datepicker").datepicker({
			minDate: new Date(1900, 0, 1),
			maxDate: 0,
			changeMonth: true,
			changeYear: true,
			dateFormat: "yy-mm-dd"
		});
		$("#password").addClass("ui-corner-all");
	}

	$(document).ready(function(){
		initGuiObjects();
	});
</script>


<!-- STYLE -->
<style>
	#formDiv { 
		width: 400px; 
		padding: 0.5em; 
		text-align:center; 
		margin-left:auto;
		margin-right:auto;
	}
	.formDivItem { 
		margin-bottom: 1.0em; 
	}
	.clear {
		clear:both;
	}
	.inputboxLabel {
		text-align: right;
		width: 150px;
		float: left;
	}
	.inputbox {
		width: 200px;
		float: right;
	}
</style>


<!-- HTML -->
<div id="formDiv" class="ui-widget-content ui-corner-all">
	<form>
		<h3 class="ui-widget clear">
			Create your account!
		</h3>

		<p class="ui-widget clear">
			<label class="inputboxLabel" for="username">Username:</label>
			<input type="text" id="username" placeholder="username" class="formDivItem inputbox"/>
		</p>

		<p class="ui-widget clear">
			<label class="inputboxLabel" for="email">E-mail:</label>
			<input type="email" id="email" placeholder="email" class="formDivItem inputbox"/>
		</p>

		<p class="ui-widget clear">
			<label class="inputboxLabel" for="firstname">First Name:</label>
			<input type="text" id="firstname" placeholder="first name" class="formDivItem inputbox"/>
		</p>

		<p class="ui-widget clear">
			<label class="inputboxLabel" for="lastname">Last Name:</label>
			<input type="text" id="lastname" placeholder="last name" class="formDivItem inputbox"/>
		</p>

		<p class="ui-widget clear">
			<label class="inputboxLabel" for="datepicker">Date Of Birth:</label>
			<input type="text" id="datepicker" placeholder="date/of/birth" class="formDivItem inputbox ui-corner-all">
		</p>

		<p class="ui-widget clear">
			<label class="inputboxLabel" for="password">Password:</label>
			<input type="password" id="password" placeholder="password" class="formDivItem inputbox"/>
		</p>
		
		<p class="ui-widget clear">
			<input type="button" id="register" value="Register" class="formDivItem"/>
		</p>

	</form>
</div>
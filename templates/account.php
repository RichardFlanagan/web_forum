<!-- SCRIPTS -->
<script>
	function updateClickEvent(event){
		var firstname = $("#firstname").val().trim();
		var lastname = $("#lastname").val().trim();
		var email = $("#email").val().trim();
		var password = $("#password").val().trim();
		var newpassword = $("#newpassword").val().trim();
		var dateofbirth = $("#datepicker").val().trim();

		var emailRegex = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

		if(firstname == ""){
			firstname = "<?php echo $data["first_name"]; ?>";
		} 
		if(lastname == ""){
			lastname = "<?php echo $data["last_name"]; ?>";
		}
		if(email == ""){
			email = "<?php echo $data["email"]; ?>";
		}
		if(dateofbirth == ""){
			dateofbirth = "<?php echo $data["date_of_birth"]; ?>";
		}
		if(newpassword == ""){
			newpassword = "";
		}


		if(email == "" || !emailRegex.test(email)){
			addErrorPopup("Error", "Please enter a valid email");
		}
		else if(password == ""){
			addErrorPopup("Error", "Please enter your password");
		}
		 else{
			$("#errorDiv").empty();

			var updateRequest = $.ajax({
				url: ""+<?php echo $projectPath;?>+"api/account/update/",
				method: "POST",
				data: { 
					firstname: firstname,
					lastname: lastname,
					email: email,
					password: password,
					newpassword: newpassword,
					dateofbirth: dateofbirth
				}
			});

			updateRequest.done(function(data, textStatus, jqXHR) {
				location.reload();
			});

			updateRequest.fail(function(jqXHR, textStatus, errorThrown) {
				addErrorPopup("Error", "Request could not be sent: "+errorThrown);
			});
		}
	}

	function deleteClickEvent(event){
		var deleteRequest = $.ajax({
			url: ""+<?php echo $projectPath;?>+"api/account/delete/",
			method: "POST",
			data: {}
		});
		deleteRequest.done(function(data, textStatus, jqXHR) {
			window.location = ""+<?php echo $projectPath;?>+"login/";
		});

		deleteRequest.fail(function(jqXHR, textStatus, errorThrown) {
			addErrorPopup("Error", "Request could not be sent: "+errorThrown);
		});
	}

	function initGuiObjects(){
		$("#update").button().click(function(event) { updateClickEvent(event); });
		$("#delete").button().click(function(event) { deleteClickEvent(event); });

		$("#firstname").addClass("ui-corner-all");
		$("#lastname").addClass("ui-corner-all");
		$("#username").addClass("ui-corner-all");
		$("#email").addClass("ui-corner-all");
		$("#password").addClass("ui-corner-all");
		$("#newpassword").addClass("ui-corner-all");
		$("#registertime").addClass("ui-corner-all");
		$("#datepicker").datepicker({
			minDate: new Date(1900, 0, 1),
			maxDate: 0,
			changeMonth: true,
			changeYear: true,
			dateFormat: "yy-mm-dd"
		});
	}

	$(document).ready(function(){
		initGuiObjects();
	});
</script>


<!-- STYLE -->
<style>
	#detailsForm { 
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
<div id="contentDiv" class="ui-widget-content ui-corner-all">
	<form id="detailsForm">
		<h3 class="ui-widget clear">
			Your account details
		</h3>

		<p class="ui-widget clear">
			<label class="inputboxLabel" for="username">Username:</label>
			<input type="text" id="username" value="<?php echo $data["username"]; ?>" class="formDivItem inputbox" disabled/>
		</p>

		<p class="ui-widget clear">
			<label class="inputboxLabel" for="registertime">Member since:</label>
			<input type="text" id="registertime" value="<?php echo date('Y-m-d', $data["registration_time"]/1000); ?>" class="formDivItem inputbox" disabled/>
		</p>

		<p class="ui-widget clear">
			<label class="inputboxLabel" for="email">E-mail:</label>
			<input type="email" id="email" placeholder="<?php echo $data["email"]; ?>" class="formDivItem inputbox"/>
		</p>

		<p class="ui-widget clear">
			<label class="inputboxLabel" for="firstname">First Name:</label>
			<input type="text" id="firstname" placeholder="<?php echo $data["first_name"]; ?>" class="formDivItem inputbox"/>
		</p>

		<p class="ui-widget clear">
			<label class="inputboxLabel" for="lastname">Last Name:</label>
			<input type="text" id="lastname" placeholder="<?php echo $data["last_name"]; ?>" class="formDivItem inputbox"/>
		</p>

		<p class="ui-widget clear">
			<label class="inputboxLabel" for="datepicker">Date Of Birth:</label>
			<input type="text" id="datepicker" placeholder="<?php echo $data["date_of_birth"]; ?>" class="formDivItem inputbox ui-corner-all">
		</p>

		<p class="ui-widget clear">
			<label class="inputboxLabel" for="password">Old Password:</label>
			<input type="password" id="password" placeholder="old password" class="formDivItem inputbox"/>
		</p>

		<p class="ui-widget clear">
			<label class="inputboxLabel" for="newpassword">New Password:</label>
			<input type="password" id="newpassword" placeholder="new password" class="formDivItem inputbox"/>
		</p>
		
		<p class="ui-widget clear">
			<input type="button" id="update" value="Update Details" class="formDivItem"/>
			<input type="button" id="delete" value="Delete Account" class="formDivItem"/>
		</p>

	</form>
</div>
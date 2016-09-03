<!-- INCLUDES -->
<link rel="stylesheet" type="text/css" href=<?php echo $projectPath."lib/datatables/datatables.css"; ?>>
<script type="text/javascript" src=<?php echo $projectPath."lib/datatables/datatables.js"; ?>></script>
<script type="text/javascript" src=<?php echo $projectPath."lib/chart/chart.js"; ?>></script>


<!-- SCRIPTS -->
<script>
	function promoteUserClickEvent(event, promote){
		var username = "";
		if(promote){
			username = $("#promoteUserInput").val();
		} else {
			username = $("#demoteUserInput").val();
		}

		if(username == ""){
			addErrorPopup("Error", "Please enter a username");
		} else{
			$("#errorDiv").empty();console.log(username);

			var promoteRequest = $.ajax({
				url: ""+<?php echo $projectPath;?>+"api/admin/promote/",
				method: "POST",
				data: { 
					username: username,
					promote: promote
				}
			});

			promoteRequest.done(function(data, textStatus, jqXHR) {
				location.reload();
			});

			promoteRequest.fail(function(jqXHR, textStatus, errorThrown) {
				addErrorPopup("Error", "Request could not be sent: "+errorThrown);
			});
		}
	}

	function initGuiObjects(){
		$("#contentDiv").tabs();

		$("#promoteUserButton").button().click(function(event) { promoteUserClickEvent(event, true); });
		$("#promoteUserInput").addClass("ui-corner-all");
		$("#promoteUserButton").addClass("ui-corner-all");

		$("#demoteUserButton").button().click(function(event) { promoteUserClickEvent(event, false); });
		$("#demoteUserInput").addClass("ui-corner-all");
		$("#demoteUserButton").addClass("ui-corner-all");

		getUserData();
		getUserTypesData();
		getServerLogs();
		getPostData();
		getActivityData();
	}

	function getUserData(){
		var dataRequest = $.ajax({
			url: ""+<?php echo $projectPath;?>+"api/admin/getusers/",
			method: "POST",
			data: {}
		});

		dataRequest.done(function(data, textStatus, jqXHR) {
			data = JSON.parse(data);

			for(var i=0; i<data.length; i++){
				var regDate = new Date(data[i].registration_time);
				data[i].registration_time = regDate.getFullYear()+'-'+(regDate.getMonth()+1)+'-'+regDate.getDate();
			}

			$('#userTable').DataTable({
				jQueryUI: true,
				processing: true,
				pagingType: "numbers",
				pageLength: 20,
				data: data,
				columns: [
					{ title: "Username", data:"username" },
					{ title: "User Type", data:"user_type.user_type_name" },
					{ title: "Access Level", data:"user_type.access_level" },
					{ title: "E-mail", data:"email" },
					{ title: "First Name", data:"first_name" },
					{ title: "Last Name", data:"last_name" },
					{ title: "Date Of Birth", data:"date_of_birth" },
					{ title: "Registration Time", data:"registration_time" },
					{ title: "Posts", data:"post_count" },
					{ title: "Comments", data:"comment_count" }
				]
			});
		});

		dataRequest.fail(function(jqXHR, textStatus, errorThrown) {
			addErrorPopup("Error", "Request could not be sent: "+errorThrown);
		});
	}

	function getServerLogs(){
		var dataRequest = $.ajax({
			url: ""+<?php echo $projectPath;?>+"api/admin/getserverlogs/",
			method: "POST",
			data: {}
		});

		dataRequest.done(function(data, textStatus, jqXHR) {
			data = JSON.parse(data);

			for(var i=0; i<data.length; i++){
				var timestamp = new Date(data[i].time);
				data[i].time = timestamp.toISOString();
			}

			$('#serverLogTable').DataTable({
				jQueryUI: true,
				processing: true,
				pagingType: "numbers",
				pageLength: 20,
				data: data,
				columns: [
					{ title: "id", data:"_id.$id" },
					{ title: "Username", data:"username" },
					{ title: "User Type", data:"user_type" },
					{ title: "Access Level", data:"access_level" },
					{ title: "Action", data:"action" },
					{ title: "Target", data:"target" },
					{ title: "Time", data:"time" }
				]
			});
		});
	}

	function getUserTypesData(){
		var dataRequest = $.ajax({
			url: ""+<?php echo $projectPath;?>+"api/admin/getusertypes/",
			method: "POST",
			data: {}
		});

		dataRequest.done(function(data, textStatus, jqXHR) {
			data = JSON.parse(data);

			$('#userTypesTable').DataTable({
				jQueryUI: true,
				processing: true,
				pagingType: "numbers",
				pageLength: 20,
				data: data,
				columns: [
					{ title: "id", data:"_id.$id" },
					{ title: "Name", data:"user_type_name" },
					{ title: "Access Level", data:"access_level" },
					{ title: "Count", data:"count" }
				]
			});
		});

		dataRequest.fail(function(jqXHR, textStatus, errorThrown) {
			addErrorPopup("Error", "Request could not be sent: "+errorThrown);
		});
	}

	function getPostData(){
		var dataRequest = $.ajax({
			url: ""+<?php echo $projectPath;?>+"api/admin/getpoststats/",
			method: "POST",
			data: {}
		});

		dataRequest.done(function(data, textStatus, jqXHR) {
			data = JSON.parse(data);

			$('#postStatsTable').DataTable({
				jQueryUI: true,
				processing: true,
				pagingType: "numbers",
				pageLength: 20,
				data: data,
				columns: [
					{ title: "Slug", data:"slug" },
					{ title: "Title", data:"title" },
					{ title: "Author", data:"author" },
					{ title: "Comment Count", data:"comment_count" }
				]
			});
		});

		dataRequest.fail(function(jqXHR, textStatus, errorThrown) {
			addErrorPopup("Error", "Request could not be sent: "+errorThrown);
		});
	}

	function getActivityData(){
		var dataRequest = $.ajax({
			url: ""+<?php echo $projectPath;?>+"api/admin/getactivitydata/",
			method: "POST",
			data: {}
		});

		dataRequest.done(function(data, textStatus, jqXHR) {
			data = JSON.parse(data);
			chart(data);
		});

		dataRequest.fail(function(jqXHR, textStatus, errorThrown) {
			addErrorPopup("Error", "Request could not be sent: "+errorThrown);
		});
	}

	function chart(data){
		var data = {
		    labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
		    datasets: [
		        {
		            label: "User Registrations",
		            fillColor: "rgba(100,100,255,0.2)",
		            strokeColor: "rgba(100,100,255,1)",
		            pointHighlightFill: "#fff",
		            pointHighlightStroke: "rgba(100,100,255,1)",
		            data: data.users
		        },
		        {
		            label: "Posts",
		            fillColor: "rgba(100,155,100,0.2)",
		            strokeColor: "rgba(100,155,100,1)",
		            pointHighlightFill: "#fff",
		            pointHighlightStroke: "rgba(100,155,100,1)",
		            data: data.posts
		        },
		        {
		            label: "Comments",
		            fillColor: "rgba(255,100,100,0.2)",
		            strokeColor: "rgba(255,100,100,1)",
		            pointHighlightFill: "#fff",
		            pointHighlightStroke: "rgba(255,100,100,1)",
		            data: data.comments
		        }
		    ]
		};

		var ctx = $("#siteActivityChart").get(0).getContext("2d");
		var myChart = new Chart(ctx).Bar(data, {});
		$("#legendDiv").append(myChart.generateLegend());
	}

	$(document).ready(function(){
		
		initGuiObjects();
	});
</script>

<!-- STYLE -->
<style>
	.divItem {
		text-align: left;
		margin-bottom: 10px;
		width: 100%;
		height: 65px;
		line-height: 35px;
		text-indent: 10px;
	}

	th, tr {
		font-size: smaller;
	}

	div.fg-buttonset .fg-button {
		margin-left:1px; 
		margin-right:1px; 
	}
	div.fg-buttonset .fg-button:active {
		margin-left:1px; 
		margin-right:1px;
	}
	div.fg-buttonset .fg-button:first-child {
		margin-left:1px; 
		margin-right:1px; 
	}
	div.fg-buttonset .fg-button:last-child {
		margin-left:1px; 
		margin-right:1px; 
	}
	div.fg-buttonset span .fg-button {
		margin-left:1px; 
		margin-right:1px; 
	}
	div.fg-buttonset span .fg-button:active {
		margin-left:1px; 
		margin-right:1px; 
	}
	#mainDiv {
		width:80%;
	}

	.actionDiv { 
		width: 200px; 
		padding: 0.5em; 
		text-align:center; 
		margin-left:auto;
		margin-right:auto;
		margin-bottom: 20px;
	}
	.clear {
		clear:both;
	}
</style>

<!-- HTML -->
<div id="contentDiv" style="" class="ui-widget-content ui-corner-all">
	<h3 id="heading" style="text-align:center;">Site Administration</h3>
	<ul>
		<li><a href="#users-tab" style="width:100px;">Users</a></li>
		<li><a href="#usertypes-tab" style="width:100px;">User Types</a></li>
		<li><a href="#poststats-tab" style="width:100px;">Posts</a></li>
		<li><a href="#server-tab" style="width:100px;">Server Log</a></li>
		<li><a href="#siteactivity-tab" style="width:100px;">Site Activity</a></li>
		<li><a href="#actions-tab" style="width:120px;">Admin Actions</a></li>
	</ul>
	<div id="users-tab">
		<table id="userTable" class="display" cellspacing="0" width="100%"></table>
	</div>
	<div id="usertypes-tab">
		<table id="userTypesTable" class="display" cellspacing="0" width="100%"></table>
	</div>
	<div id="poststats-tab">
		<table id="postStatsTable" class="display" cellspacing="0" width="100%"></table>
	</div>
	<div id="server-tab">
		<table id="serverLogTable" class="display" cellspacing="0" width="100%"></table>
	</div>
	<div id="siteactivity-tab">
		<canvas id="siteActivityChart" width="600" height="400"></canvas>
		<div id="legendDiv" style="width:100%;"></div>
	</div>
	<div id="actions-tab">
		<div class="ui-widget-content ui-corner-all actionDiv">
			<h3 class="ui-widget">Promote a user</h3>
			<input type="text" id="promoteUserInput" placeholder="username" class="ui-widget"/>
			<input type="button" id="promoteUserButton" value="Promote User" class="ui-widget"/>
		</div>
		<div class="ui-widget-content ui-corner-all actionDiv">
			<h3 class="ui-widget">Demote a user</h3>
			<input type="text" id="demoteUserInput" placeholder="username" class="ui-widget"/>
			<input type="button" id="demoteUserButton" value="Demote User" class="ui-widget"/>
		</div>
	</div>
</div>
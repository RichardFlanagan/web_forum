<!-- SCRIPTS -->
<script>
	function postClickEvent(event){
		var title = $("#title").val().trim();
		var content = $("#content").val().trim();

		if(title == ""){
			addErrorPopup("Error", "Please enter a post title");
		} 
		else {
			$("#errorDiv").empty();

			var postRequest = $.ajax({
				url: ""+<?php echo $projectPath;?>+"api/create/",
				method: "POST",
				data: { 
					title: title,
					content: content
				}
			});

			postRequest.done(function(data, textStatus, jqXHR) {
				window.location = ""+<?php echo $projectPath;?>+"/";
			});

			postRequest.fail(function(jqXHR, textStatus, errorThrown) {
				addErrorPopup("Error", "Request could not be sent: "+errorThrown);
			});
		}
	}

	function initGuiObjects(){

		$("#post").button().click(function(event) { postClickEvent(event); });

		$("#title").addClass("ui-corner-all");
		$("#content").addClass("ui-corner-all");
	}

	$(document).ready(function(){
		initGuiObjects();
	});
</script>


<!-- STYLE -->
<style>
	.postItem {
		text-align: left;
		margin-left: 5%;
		margin-bottom: 10px;
		width: 90%;
	}
</style>


<!-- HTML -->
<div id="contentDiv" class="ui-widget-content ui-corner-all">
	<form id="createPostForm">
		<h3 class="ui-widget postItem">
			Create your post!
		</h3>

		<input type="text" id="title" placeholder="My post title..." class="ui-widget postItem"/>

		<textarea id="content" class="ui-widget postItem" rows="10" wrap="soft" placeholder="My post content..." style="resize: none;"></textarea>
		
		<p class="ui-widget postItem" style="margin-top: 0px;">
			<input type="button" id="post" value="Post" class=""/>
		</p>
	</form>

</div>
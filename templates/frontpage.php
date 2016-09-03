<!-- SCRIPTS -->
<script>
	function initGuiObjects(){}

	function getPosts(){
		$("#errorDiv").empty();

		var postRequest = $.ajax({
			url: ""+<?php echo $projectPath;?>+"api/getposts/",
			method: "POST",
			data: {}
		});

		postRequest.done(function(data, textStatus, jqXHR) {
			data = JSON.parse(data);
			for (var i=0; i < data.length; i++) {
				$("#contentDiv").append(
					'<div class="ui-widget-content divItem">'+
						'<div style="height:30px;">'+
							'<a href="'+<?php echo $projectPath;?>+'post/'+data[i].slug+'/" class="ui-widget">'+data[i].title+'</a>'+
						'</div>'+
						'<div style="height:30px;color:#777;">'+
							' by '+
							'<a href="'+<?php echo $projectPath;?>+'user/'+data[i].author+'/" class="ui-widget">'+data[i].author+'</a>'+
							' on '+ new Date(data[i].post_time).toUTCString()+
						'</div>'+
					'</div>'
				);
			};
		});

		postRequest.fail(function(jqXHR, textStatus, errorThrown) {
			addErrorPopup("Error", "Request could not be sent: "+errorThrown);
		});
	}

	$(document).ready(function(){
		initGuiObjects();
		getPosts();
	});
</script>

<!-- STYLE -->
<style>
	.mainDivHeader {
		text-align: left;
		margin-left: 5%;
		margin-bottom: 10px;
		width: 90%;
		height: 35px;
		line-height: 35px;
		text-indent: 10px;
	}
	.divItem {
		text-align: left;
		margin-left: 5%;
		margin-bottom: 10px;
		width: 90%;
		height: 65px;
		line-height: 35px;
		text-indent: 10px;
	}

</style>

<!-- HTML -->
<div id="contentDiv" class="ui-widget-content ui-corner-all">
	<h3 class="ui-widget mainDivHeader">
		Most Recent Posts
	</h3>

	<!--div class="ui-widget-content divItem">
		<a href="" class="ui-widget">my first post</a>
		<img id="headerImage" style="width:31px; height:31px;top:2px;left:2px; margin:0px;"src=< ?php echo $projectPath.$headerImage; ?>>
	</div-->

</div>
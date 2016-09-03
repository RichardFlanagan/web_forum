<!-- SCRIPTS -->
<script>
	function commentClickEvent(event){
		var comment = $("#commentContent").val().trim();

		if(comment == ""){
			addErrorPopup("Error", "Nothing to post");
		} 
		else {
			$("#errorDiv").empty();

			var commentRequest = $.ajax({
				url: ""+<?php echo $projectPath;?>+"api/comment/",
				method: "POST",
				data: { 
					content: comment,
					slug: "<?php echo $data["slug"]; ?>"
				}
			});

			commentRequest.done(function(data, textStatus, jqXHR) {
				location.reload();
			});

			commentRequest.fail(function(jqXHR, textStatus, errorThrown) {
				addErrorPopup("Error", "Request could not be sent: "+errorThrown);
			});
		}
	}

	function initGuiObjects(){
		$("#comment").button().click(function(event) { commentClickEvent(event); });

		$("#info").append(
			"by "+
				"<a href='<?php echo $projectPath;?>user/<?php echo $data['author']; ?>/'>"+
					"<?php echo $data['author']; ?>"+
				"</a>"+
			" on " + new Date(<?php echo $data["post_time"]; ?>).toUTCString()
		);

		var comments = <?php echo stripslashes(json_encode($data['comments'])); ?>;		
		for (var i=0; i < comments.length; i++) {
			$("#commentListDiv").append(
				'<div class="ui-widget-content commentItem">'+
					'<div class="ui-widget" style="margin:10px 10px 0px 10px; text-align:justify; line-height:20px;">'+
						'<span style="font-style:italic; color:#777;">'+
							'['+ (i+1) +']'+
						'</span> '+ 
						comments[i].content+
					'</div>'+
					'<div style="height:30px; font-style:italic; text-indent:10px; color:#777;">'+
						' by '+
						'<a href="'+<?php echo $projectPath;?>+'user/'+comments[i].author+'/" class="ui-widget">'+
							comments[i].author+
						'</a>'+
						' on '+ new Date(comments[i].post_time).toUTCString()+
					'</div>'+
				'</div>'
			);
		}
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
	#postDiv {
		width: 100%;
		margin-left:auto;
		margin-right:auto;
		margin-top: 0px;
		padding-top: 0px;
		padding-bottom: 0px;
	}
	#commentDiv {
		width: 100%;
		margin-left:auto;
		margin-right:auto;
		margin-top: 20px;
		padding-top: 20px;
		padding-bottom: 5px;
	}
	#commentListDiv {
		width: 100%;
		margin-left:auto;
		margin-right:auto;
		margin-top: 20px;
		padding-top: 20px;
		padding-bottom: 5px;
	}
	.commentItem {
		text-align: left;
		margin-left: 5%;
		margin-bottom: 10px;
		width: 90%;
		line-height: 30px;
		background-color: white;
		background: white;
	}
</style>


<!-- HTML -->
<div id="contentDiv">

	<div id="postDiv" class="ui-widget-content ui-corner-all">
		<h2 id="title" class="ui-widget ui-corner-all postItem">
			<?php echo stripslashes($data["title"]); ?>
		</h2>

		<div id="info" class="ui-corner-all postItem" style="font-style:italic; color:#777;"></div>

		<p id="content" class="ui-widget ui-corner-all postItem" style="text-align: justify;">
			<?php echo stripslashes($data["content"]); ?>
		</p>
	</div>

	<div id="commentDiv" class="ui-widget-content ui-corner-all">
		<textarea id="commentContent" class="ui-widget postItem" rows="4" wrap="soft" placeholder="My comment..." style="resize: none; margin-bottom:0px;"></textarea>
		<p class="ui-widget postItem" style="margin-top: 0px;">
			<input type="button" id="comment" value="Post Comment" class=""/>
		</p>
	</div>

	<div id="commentListDiv" class="ui-widget-content ui-corner-all">

	</div>

</div>
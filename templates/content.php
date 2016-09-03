<!-- SCRIPTS -->
<script>
	function initGuiObjects(){
		var data = <?php echo stripslashes($data); ?>;

		$("#heading").append('User history for '+ data.username);

		$("#contentDiv").tabs();

		for (var i=0; i < data.posts.length; i++) {
			$("#posts-tab").append(
				'<div class="ui-widget-content divItem">'+
					'<div style="height:30px;">'+
						'<a href="'+<?php echo $projectPath;?>+'post/'+data.posts[i].slug+'/">'+ data.posts[i].title +'</a>'+
					'</div>'+
					'<div style="height:30px;color:#777;">'+
						' by '+
						'<a href="'+<?php echo $projectPath;?>+'user/'+data.posts[i].author+'/" class="ui-widget">'+data.posts[i].author+'</a>'+
						' on '+ new Date(data.posts[i].post_time).toUTCString()+
					'</div>'+
				'</div>'
			);
		}

		for (var i=0; i < data.comments.length; i++) {
			$("#comments-tab").append(
				'<div class="ui-widget-content divItem">'+
					'<div style="height:30px;">'+
						((data.comments[i].content.length>80) ? data.comments[i].content.substring(0,80)+' . . .' : data.comments[i].content)+
					'</div>'+
					'<div style="height:30px;color:#777;">'+
						' on '+
						'<a href="'+<?php echo $projectPath;?>+'post/'+data.comments[i].slug+'/">this post</a>'+
						' on '+ new Date(data.comments[i].post_time).toUTCString()+
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
	.divItem {
		text-align: left;
		margin-bottom: 10px;
		width: 100%;
		height: 65px;
		line-height: 35px;
		text-indent: 10px;
	}
</style>

<!-- HTML -->
<div id="contentDiv" class="ui-widget-content ui-corner-all">
	<h3 id="heading"></h3>
	<ul>
		<li><a href="#posts-tab" style="width:100px;">Posts</a></li>
		<li><a href="#comments-tab" style="width:100px;">Comments</a></li>
	</ul>
	<div id="posts-tab"></div>
	<div id="comments-tab"></div>
</div>
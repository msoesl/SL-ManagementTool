<?php
require_once ('import/headimportviews.php');
$project = new ProjectModel ( $_GET ['id'] );
$user;
if (isset ( $_SESSION ['user'] )) {
	$user = $_SESSION ['user'];
}
//anyone can proposed a solution (comment) as long as they have an account
$readOnly = (isset($user))? "false":"true";
?>

<div id="proposed_solution_widget"
	class="proposed-solution-widget standard-border float-center">
	<div data-theme="a" data-role="navbar" id="proposed_solution_comments"
		class="widget-header float-center">
		<ul>
			<li class = 'width-50'><a id="proposed-solution-tab" href="#" data-tab-class="ui-btn-active proposed-solution-area" data-theme="a">Proposed Solutions</a></li>
			<li class = 'width-50'><a id="forum-tab" href="#" data-tab-class="forum-area" data-theme="a">Accepted Solutions & Discussion</a></li>
		</ul>
	</div>
	<div id="proposed-solution-area" class="proposed-solution-area">
		<div id="commentSection" class="commentsBlock"></div>
	</div>
	<div id="forum-area" style="display: none;" class="forum-area">
		<?php include_once("ForumWidget.php");?>
	</div>
</div> 

<script>
	$(document).ready(function() {
		$('#forum-tab').click(function(){
			$('#proposed-solution-area').fadeOut(300, function(){
				$('#forum-area').fadeIn(300);
			});
		});

		$('#proposed-solution-tab').click(function(){
			$('#forum-area').fadeOut(300, function(){
				$('#proposed-solution-area').fadeIn(300);
			});
		});

		$("#commentSection").comments({
		    getCommentsUrl: "controllers/CommentsController.php?message=get"+"&id=<?php echo $project->getProjectId()?>",
		    postCommentUrl: "controllers/CommentsController.php?message=post"+"&id=<?php echo $project->getProjectId()?>",
		    deleteCommentUrl: "controllers/CommentsController.php?message=delete"+"&id=<?php echo $project->getProjectId()?>",
		    likeCommentUrl:"controllers/LikeCommentController.php",
		    reportCommentUrl:"controllers/ReportAsInappropriateController.php",
		    readOnly: false,
		    displayAvatar: true,
		    displayHeader: true,
            isLoggedIn:<?php echo isset($user)?"true":"false"?>,
		    callback: {
	            beforeDelete:function() {
	                 return confirm("Are you sure you want to delete comment?");
	            },
	            afterDelete: function (commentId) {
	                 console.log("Comment with id " + commentId + " has been deleted");
	            }
		    }
		});
	});
</script>
<?php
require_once('import/headimportviews.php');
//this widget requires a defined project

if(!isset($project)){
?>
    <script type="text/javascript">
        PageChanger.load404View();
    </script>
<?php 
    die;
}

$perms = false;
$user;
if (isset ( $_SESSION ['user'] )) {
    $user = $_SESSION ['user'];
    $perms = ProjectModel::getAccountPermissionsForProjectWithId($user->getAccountId(), $project->getProjectId());
}

$projectOwner = $project->getProjectOwner();
//only collaborators can have an account
$readOnly = !( isset($user) && $perms && ($perms->collaborator_permission > 0 || $projectOwner->getAccountId() == $user->getAccountId()));
?>

<div class="forum-toolbar">
    <?php if (!$readOnly) {?>
        <div class="edit-button-wrap new-thread-button">
            <a class="edit-button" href="#newThreadPopupDialog" title="Create New Thread"
                data-icon="edit" data-rel="popup" data-position-to="window"
                data-role="button" data-inline="true" data-transition="pop"
                data-corners="true" data-shadow="true" data-iconshadow="true"
                data-wrapperels="span" data-theme"d" aria-haspopup="true"
                aria-owns="#newThreadPopupDialog"><span class="btn-text"> New Thread</span></a>
        </div>
    <?php }?>

    <?php //any logged in user can subscribe 
     if (isset($user)){ ?>
        <div class="subscribe-button edit-button-wrap">
            <button class="" onclick="forum.subscribeToForum()" data-theme"d"><img id="forum-subscribe-image" class="sticky" src="<?php if(ForumModel::isUserSubscribedToForum($project->getProjectId(), $user->getAccountId())){ echo 'res/images/subscribed.png'; }else{ echo 'res/images/unsubscribed.png'; } ?>">
                <span id="forum-subscribe-text" class="btn-text"><?php if(ForumModel::isUserSubscribedToForum($project->getProjectId(), $user->getAccountId())){ echo "Unsubscribe from Forum"; }else{ echo "Subscribe to Forum"; } ?></span></button>
        </div>
    <?php } ?>

    <span class="num-threads-label">
        Currently hosting <b><?php echo ForumModel::getNumThreads($project->getProjectId()); ?></b> threads
        <br>
        <br>
        Showing threads <b id="thread-range-start">0</b> - <b id="thread-range-end">25</b>
    </span>
</div>

<div class='nav-row'>
	<span class='nav-options nav-options-large-screen'>
	    <a href="#" class='small-nav-button first' data-role='button' onclick="forum.getFirstThreads()">First</a>
	    <div class='small-spacing' style='display:inline-block;width:20px'></div>
	    <a href="#" class='big-nav-button previous' data-role='button' onclick="forum.getPreviousThreads()">Previous</a>
	    <div class='big-spacing' style='display:inline-block;width:50px'></div>
	    <a href="#" class='big-nav-button next' data-role='button' onclick="forum.getNextThreads()">Next</a>
	    <div class='small-spacing' style='display:inline-block;width:20px'></div>
	    <a href="#" class='small-nav-button last' data-role='button' onclick="forum.getLastThreads()">Last</a>  
	</span>
	<span class='nav-options nav-options-small-screen'>
		<div>
		    <a href="#" class='big-nav-button previous' data-role='button' onclick="forum.getPreviousThreads()">Previous</a>
			<div class='small-spacing' style='display:inline-block;width:20px'></div>
	    	<a href="#" class='big-nav-button next' data-role='button' onclick="forum.getNextThreads()">Next</a>
		</div>
		<div>
	   		<a href="#" class='small-nav-button first' data-role='button' onclick="forum.getFirstThreads()">First</a>
			<div class='big-spacing' style='display:inline-block;width:50px'></div>
	    	<a href="#" class='small-nav-button last' data-role='button' onclick="forum.getLastThreads()">Last</a>  
		</div>
	</span>
</div>

<div id="forum-thread-area" class="forum-area" data-role="collapsible-set" data-theme"d" data-content-theme="g">

</div>
<div class='nav-row'>
	<span class='nav-options nav-options-large-screen'>
	    <a href="#" class='small-nav-button first' data-role='button' onclick="forum.getFirstThreads()">First</a>
	    <div class='small-spacing' style='display:inline-block;width:20px'></div>
	    <a href="#" class='big-nav-button previous' data-role='button' onclick="forum.getPreviousThreads()">Previous</a>
	    <div class='big-spacing' style='display:inline-block;width:50px'></div>
	    <a href="#" class='big-nav-button next' data-role='button' onclick="forum.getNextThreads()">Next</a>
	    <div class='small-spacing' style='display:inline-block;width:20px'></div>
	    <a href="#" class='small-nav-button last' data-role='button' onclick="forum.getLastThreads()">Last</a>  
	</span>
	<span class='nav-options nav-options-small-screen'>
		<div>
		    <a href="#" class='big-nav-button previous' data-role='button' onclick="forum.getPreviousThreads()">Previous</a>
			<div class='small-spacing' style='display:inline-block;width:20px'></div>
	    	<a href="#" class='big-nav-button next' data-role='button' onclick="forum.getNextThreads()">Next</a>
		</div>
		<div>
	   		<a href="#" class='small-nav-button first' data-role='button' onclick="forum.getFirstThreads()">First</a>
			<div class='big-spacing' style='display:inline-block;width:50px'></div>
	    	<a href="#" class='small-nav-button last' data-role='button' onclick="forum.getLastThreads()">Last</a>  
		</div>
	</span>
</div>

<!-- new thread dialog -->
<div style='display: none'>
    <div data-role="popup" id="newThreadPopupDialog">
        <div data-role="header" title="Edit" data-theme="a"
            class="ui-corner-top ui-header ui-bar-a" role="banner">
            <h1 class="ui-title" role="heading" aria-level="1">New Thread</h1>
        </div>
        <div class='simple-padding-medium'>
            <form id = 'new_thread_form'
                data-ajax='false' method='POST'
                action = 'controllers/NewThreadController.php?project_id=<?php echo $project->getProjectId() ?>'>
                <span>Thread Title:</span><input type="text" name="new_thread" id="new_thread"></input>
                <span>Comment:</span><textarea type="textarea" name="new_comment" id='new_comment' data-theme"d"></textarea>
                <div class='ui-grid-a'>
                    <div class='ui-block-a'></div>
                    <div class='ui-block-b'>

                        <input type="submit" name="submit" value="Submit" data-theme"d">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">

    var forum = new ForumWidget(<?php echo ForumModel::getNumThreads($project->getProjectId()); ?>, <?php echo $project->getProjectId() ?>, <?php echo (isset($user)&& !$readOnly) ? 'true' : 'false' ?>,
     'forum-thread-area', <?php if(isset($user)){ echo $user->getAccountId(); }else{ echo '-1'; } ?> );
    forum.getFirstThreads();
</script>
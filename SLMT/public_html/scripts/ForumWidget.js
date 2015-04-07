//keep track of our thread offset, we start at 0 by default


function ForumWidget(numThreads, projectId, userExists, threadContainerId, userId){
    this.numThreads = numThreads,
    this.projectId = projectId,
    this.userExists = userExists;
    this.threadContainerId = '#'+threadContainerId;
    this.userId = userId;

    this.getFirstThreads = function(){
        this.threadOffset = 0;
        this.__doAjaxThreadPull();
    },

    this.getNextThreads = function(){
        this.threadOffset += (this.threadOffset <= (this.numThreads - 25)) ? 25 : 0;
        this.__doAjaxThreadPull();
    },

    this.getPreviousThreads = function(){
        this.threadOffset -= (this.threadOffset >= 25) ? 25 : 0;
        this.__doAjaxThreadPull();
    },

    this.getLastThreads = function(){
        this.threadOffset = this.numThreads - (this.numThreads%25);
        this.__doAjaxThreadPull();
    },

    this.__doAjaxThreadPull = function(){
        //update labels
        $('#thread-range-start').html(this.threadOffset + 1);
        $('#thread-range-end').html((this.threadOffset + 25 > this.numThreads ? this.numThreads : this.threadOffset + 25));

        var forumId = this.threadContainerId;
        var projectId = this.projectId;
        var userExists = this.userExists;

        $.post('controllers/ForumPageController.php?command=get&project_id='+this.projectId+'&offset='+this.threadOffset).done(function(result){
            var retJson = $.parseJSON(result);

            $(forumId + ' .thread-collapsible').remove();

            for(var i = 0; i < retJson.length; i++){
                var thread = retJson[i];
               
                if(userExists){
                $(forumId).append('<div class="thread-collapsible" thread-id="'+thread.id+'" data-role="collapsible">' +
                    '<h3 class="thread '+(thread.stickied==1 ? 'stickied ' : ' ')+(thread.disabled==1 ? 'disabled ' : ' ')+'" id="thread-'+thread.id+'">' +
                        '<img class="subscribe-icon" id="subscribe" onclick="forum.subscribeToThread('+thread.id+')" class="sticky" src="res/images/loading.gif">' +
                    	'<span class="thread-title '+(thread.disabled==1 ? 'disabled-thread' : '')+'">'+thread.title+'</span><br>' +
                        '<span class="thread-info"> posted by '+thread.username+' at '+thread.time+'</span><br>' +
                        '<span class="thread-info"> last comment made by '+thread.newestCommentUsername+' at '+thread.mostRecentCommentTime+'</span><br>' +
	                '</h3>' +
                    '<div class="proposed-solution-area">' +
                    '<img id="sticky" class="sticky" title="'+(thread.sticked==1 ? 'This thread is important' : 'Click to make this thread important' )+'" src="'+(thread.stickied==1 ? 'res/images/sticky.png' : 'res/images/nonsticky.png')+'" onclick="forum.stickyThread('+thread.id+')">' +
                    '<img id="disable" class="sticky" title="'+(thread.disabled==1 ? 'This thread is disabled' : 'This thread is enabled')+'" src="'+(thread.disabled==1 ? 'res/images/lock.png' : 'res/images/unlock.png')+'" onclick="forum.disableThread('+thread.id+')">' +
                        '<div id="'+thread.id+'" class="thread-comments-area commentsBlock"></div>' +
                    '</div>' +
                '</div>');
                }else{
                	$(forumId).append('<div class="thread-collapsible" thread-id="'+thread.id+'" data-role="collapsible">' +
                            '<h3 class="thread '+(thread.stickied==1 ? 'stickied ' : ' ')+(thread.disabled==1 ? 'disabled ' : ' ')+'" id="thread-'+thread.id+'">' +
                                '<img class="subscribe-icon" id="subscribe" onclick="forum.subscribeToThread('+thread.id+')" class="sticky" src="res/images/loading.gif">' +
                            	'<span class="thread-title '+(thread.disabled==1 ? 'disabled-thread' : '')+'">'+thread.title+'</span><br>' +
                                '<span class="thread-info"> posted by '+thread.username+' at '+thread.time+'</span><br>' +
                                '<span class="thread-info"> last comment made by '+thread.newestCommentUsername+' at '+thread.mostRecentCommentTime+'</span><br>' +
        	                '</h3>' +
                            '<div class="proposed-solution-area">' +
                            '<div id="'+thread.id+'" class="thread-comments-area commentsBlock"></div>' +
                            '</div>' +
                        '</div>');
                	
                }

                //trigger the check for the isSubscribed
                forum.isSubscribed(thread.id, userExists);
            }

            //trigger the container to recreate jquery mobile styles
            $(forumId).trigger('create');

            //add click listeners to pull comments
            $(".thread-collapsible").bind('expand', function(){
                var id = $(this).attr('thread-id');

                if($(this).find('.comments').length < 1){
                    $(this).find('.thread-comments-area').comments({
                        getCommentsUrl: "controllers/ThreadController.php?message=get"+"&id="+id+'&project_id='+projectId,
                        postCommentUrl: "controllers/ThreadController.php?message=post"+"&id="+id+'&project_id='+projectId,
                        deleteCommentUrl: "controllers/ThreadController.php?message=delete"+"&id="+id+'&project_id='+projectId,
                        likeCommentUrl:"controllers/LikeThreadCommentController.php",
                        reportCommentUrl:"controllers/ReportThreadAsInappropriateController.php",
                        readOnly: false, //todo figure this out dynamically
                        displayAvatar: true,
                        displayHeader: true,
                        isLoggedIn: userExists,
                        callback: {
                            beforeDelete:function() {
                                 return confirm("Are you sure you want to delete comment?");
                            },
                            afterDelete: function (commentId) {
                                 console.log("Comment with id " + commentId + " has been deleted");
                            }
                        }
                    });
                }
            });
        });
    },

    this.stickyThread = function(threadId){
       $.post('controllers/ThreadController.php?message=sticky&id='+threadId+'&project_id='+this.projectId).done(function(result){
            if(result == 1){
                $('[thread-id='+threadId +'] .proposed-solution-area').find('#sticky').attr('src', 'res/images/sticky.png');
            }else{
                $('[thread-id='+threadId +'] .proposed-solution-area').find('#sticky').attr('src', 'res/images/nonsticky.png');
            }
       });

        $('[thread-id='+threadId +'] .proposed-solution-area').find('#sticky').attr('src', 'res/images/loading.gif');
    },

    this.isSubscribed = function(threadId, userExists){
        if(userExists){
            $.post('controllers/ThreadController.php?message=isSubscribed&id='+threadId+'&project_id='+this.projectId).done(function(result){
                var isSubscribed = $.parseJSON(result);

                var img = isSubscribed ? 'res/images/subscribed.png' : 'res/images/unsubscribed.png';

                $('[thread-id='+threadId+']').find('#subscribe').attr('src', img);
            });
        }else{
            $('[thread-id='+threadId+']').find('#subscribe').remove();
        }
    }

    this.disableThread = function(threadId){
        $.post('controllers/ThreadController.php?message=disable&id='+threadId+'&project_id='+this.projectId).done(function(result){
            $('[thread-id='+threadId +'] .proposed-solution-area').find('#disable').attr('src', (result == 1 ? 'res/images/lock.png' : 'res/images/unlock.png'));
            $('[thread-id='+threadId +']').find('.thread-title').addClass('disabled-thread');

            $("[thread-id="+threadId+']').find('.thread-comments-area').children().remove();
            $("[thread-id="+threadId+']').find('.thread-comments-area').comments({
                getCommentsUrl: "controllers/ThreadController.php?message=get"+"&id="+threadId+'&project_id='+this.projectId,
                postCommentUrl: "controllers/ThreadController.php?message=post"+"&id="+threadId+'&project_id='+this.projectId,
                deleteCommentUrl: "controllers/ThreadController.php?message=delete"+"&id="+threadId+'&project_id='+this.projectId,
                likeCommentUrl:"controllers/LikeThreadCommentController.php",
                reportCommentUrl:"controllers/ReportThreadAsInappropriateController.php",
                readOnly: (result === 1) ? true : false,
                displayAvatar: true,
                displayHeader: true,
                isLoggedIn: this.userExists,
                callback: {
                    beforeDelete:function() {
                         return confirm("Are you sure you want to delete comment?");
                    },
                    afterDelete: function (commentId) {

                    }
                }
            });
        });        

        $('[thread-id='+threadId +'] .proposed-solution-area #sticky').find('#disable').attr('src', 'res/images/loading.gif');
    },

    this.subscribeToThread = function(threadId){
        $.post('controllers/ThreadController.php?message=subscribe&id='+threadId+'&project_id='+this.projectId).done(function(result){
            if(result == 1){
                $('[thread-id='+threadId +'] a .ui-btn-text').find('#subscribe').attr('src', 'res/images/subscribed.png');
            }else{
                $('[thread-id='+threadId +'] a .ui-btn-text').find('#subscribe').attr('src', 'res/images/unsubscribed.png');
            }
       });

        $('[thread-id='+threadId +'] a .ui-btn-text').find('#subscribe').attr('src', 'res/images/loading.gif');
    },

    this.subscribeToForum = function(){
        $.post('controllers/ForumController.php?message=subscribe&project_id='+this.projectId).done(function(result){
            if(result == 1){
                $('#forum-subscribe-image').attr('src','res/images/subscribed.png');
                $('#forum-subscribe-text').html('Unsubscribe from Forum');
            }else{
                $('#forum-subscribe-image').attr('src','res/images/unsubscribed.png');
                $('#forum-subscribe-text').html('Subscribe to Forum');
            }
        });

        $('#forum-subscribe-image').attr('src','res/images/loading.gif');
    }

    $('#new_thread_form').submit(function(e){
        var postData = $(this).serializeArray();
        var formURL = $(this).attr("action");
        $.ajax(
                {
                    url : formURL,
                    type: "POST",
                    data : postData,
                    success:function(data, textStatus, jqXHR) 
                    {
                        console.log(data);
                        $('[data-role=popup]').popup('close');
                        PageChanger.loadProjectView({'id': this.projectId});
                    },
                    error: function(jqXHR, textStatus, errorThrown) 
                    {
                        //if fails      
                    }
                });
            e.preventDefault(); //STOP default action
        }
    );
}
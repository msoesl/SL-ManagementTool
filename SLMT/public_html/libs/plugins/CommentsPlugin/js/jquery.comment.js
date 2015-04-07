(function ($) {
    var methods = {
        init: function (options) {
            var $this = this;
            var defaults = {
                getCommentsUrl: null,
                postCommentUrl: null,
                deleteCommentUrl: null,
                localization: {
                    headerText: "Comments",
                    commentPlaceHolderText: "Add a comment...",
                    sendButtonText: "Send",
                    replyButtonText: "Reply",
                    deleteButtonText: "Delete",
                    reportButtonText: "Inappropriate",
                    likeButtonText: "Like"
                },
                callback: {
                    beforeDelete: function () { return true; },
                    afterDelete: function () { },

                    beforeCommentAdd: function () { return true; },
                    afterCommentAdd: function () { },

                    beforeRefresh: function () { },
                    afterRefresh: function () { },

                    onGetError: function () { },
                    onPostError: function () { }
                },
                displayHeader: true,
                displayCount: true,
                loadWhenVisible: false,
                readOnly: false,
                displayAvatar: false,
                canLike:true,
                canFlag:true,
                likes:0,
                flags:0
                
            };

            options = $.extend(true, {}, defaults, options);
            this.data('data', {
                options: options
            });
            this.addClass("commentsBlock");

            if (options.displayHeader) {
                var header = "<div class='heading'><h4>" + options.localization.headerText;
                if (options.displayCount)
                    header += " (<span data-action=\"count\"></span>) ";
                header += "</h4> <div class='loadingIndicator'></div><div class=\"clearFix\"></div></div>";
                this.append(header);
            }

            if (options.isLoggedIn && !options.readOnly)
                this.append("<div class=\"newComment\">" +
                                "<form enctype=\"multipart/form-data\">" +
                                    "<textarea placeholder=\"" + options.localization.commentPlaceHolderText + "\" name=\"comment\" maxlength=\"2500\"></textarea>" +
                                    "<a href=\"javascript:void(0)\" class=\"btn sendComment\" data-action=\"send\"><span>" + options.localization.sendButtonText + "</span></a>" +
                                "</form>" +
                            "</div>");
            this.append("<ul class=\"comments\"></ul>");

            var $rootComment = this.find(".newComment").find("textarea");

            var $rootSendButton = $rootComment.parent().find("[data-action='send']");
            $rootSendButton.hide();

            $rootComment.focusout(function () {
                if ($.trim($(this).val()).length == 0) {
                    $rootSendButton.hide();
                    $(this).val("");
                }
            });

            $rootComment.focus(function () {
                $rootSendButton.show();
            });

            $rootSendButton.bind("click", function () {
                var $form = $(this).parents("form");
                var comment = $rootComment.val();
                if ($.trim(comment).length > 0) {
                    var formData = $form.serialize();
                    methods.postComment.call($this, formData);
                    $rootComment.val("");
                    $rootSendButton.hide();
                }
            });

            if (!options.loadWhenVisible)
                methods.refresh.call($this);
            else {
                var elemOffsetTop = $this.offset().top;
                var elemOffsetBottom = elemOffsetTop + $this.height();
                var windowScrollTop = $(window).scrollTop();
                if ((elemOffsetBottom <= windowScrollTop + $(window).height()) && (elemOffsetTop >= windowScrollTop)) {
                    methods.refresh.call($this);
                } else {
                    $(window).bind("scroll", function () {
                        windowScrollTop = $(window).scrollTop();
                        if ((elemOffsetBottom <= windowScrollTop + $(window).height()) && (elemOffsetTop >= windowScrollTop)) {
                            $(window).unbind("scroll");
                            methods.refresh.call($this);
                        }
                    });
                }
            }
        },

        count: function () {
            var $this = this;
            return $this.find(".comment").length;
        },

        refresh: function () {
            var $this = this;
            var $loadingIndicator = $this.find(".loadingIndicator");
            $loadingIndicator.show();
            var options = $this.data().data.options;
            options.callback.beforeRefresh();
            $.getJSON(options.getCommentsUrl, function (data) {
                $this.find(".comments").empty();
                $.each(data, function (index, comment) {
                    methods.bindComment.call($this, comment);
                });
                methods.bindEvents.call($this);
                $loadingIndicator.hide();
                $this.find(".heading h4 [data-action='count']").html($this.find(".comment").length);
                options.callback.afterRefresh();
            }).fail(function (data) {
                $loadingIndicator.hide();
                options.callback.onGetError();
            });
        },

        postComment: function (formData) {
            var $this = this;
            var options = $this.data().data.options;
            if (!options.callback.beforeCommentAdd())
                return;
            var $loadingIndicator = $this.find(".loadingIndicator");
            $loadingIndicator.show();
            $.post(options.postCommentUrl, formData, function (commentData) {
                if(typeof commentData === "string")
                {
                	commentData = $.parseJSON(commentData);
                }
            	$loadingIndicator.hide();
                methods.bindComment.call($this, commentData);
                methods.bindEvents.call($this);

                $this.find(".heading h4 [data-action='count']").html($this.find(".comment").length);
                options.callback.afterCommentAdd(commentData.Id);
            }).fail(function () {
                $loadingIndicator.hide();
                options.callback.onPostError();
            });
        },

        bindComment: function (comment) {
            var $container;
            //comment = $.parseJSON(comment);
            var options = this.data().data.options;
            if (comment.ParentId != null) {
                $container = this.find('[data-commentid=' + comment.ParentId + ']');
                if ($container.find(".reply_comments").length == 0) {
                    $container.append("<div class='reply_comments'><ul class='comments'></ul></div>");
                }
                $container = $container.children(".reply_comments").children(".comments");
            } else $container = this.children(".comments");

            if (comment.Comment != null)
                comment.Comment = comment.Comment.replace("<script>", "").replace("</script>", "");

            var commentContent = "<li class='comment' data-commentId='" + comment.Id + "'><div class=\"commentContent\">";
            if (options.displayAvatar) {
                commentContent += "<div class=\"avatar\">";
                if (comment.UserAvatar != null)
                    commentContent += "<img src=\"" + comment.UserAvatar + "\" />";
                else
                    commentContent += "<div class=\"defaultAvatar\"></div>";
                commentContent += "</div><div class=\"content avatarPadding\">";
            }
            else
                commentContent += "<div class=\"content\">";

            commentContent += "<p class='info'>" +
                                    "<span class='author'>" + comment.Author + "</span>" +
                              "</p>" +
                               "<p class='commentText'>" + comment.Comment + "</p>" +
                               "<p class='info'><time>" + comment.Date + "</time>";
            if (!options.readOnly)
            	if (typeof comment.CanReply != "boolean" || comment.CanReply) {
            		if (comment.canLike)
            			commentContent += "<a href='javascript:void(0)' data-action='like' numlikes="+comment.likes+">" + options.localization.likeButtonText + "</a> <label>("+comment.likes+")</label>";
            		else
            			commentContent += ' Liked ('+comment.likes+')';
            	}
                if (typeof comment.CanReply != "boolean" || comment.CanReply)
                    commentContent += "<a href='javascript:void(0)' data-action='replay'>" + options.localization.replyButtonText + "</a>";

            if (!options.readOnly && typeof comment.CanDelete == "boolean" && comment.CanDelete)
                commentContent += "<a href='javascript:void(0)' data-action='delete'>" + options.localization.deleteButtonText + "</a>";
            if (!options.readOnly) {
            	if (typeof comment.CanReply != "boolean" || comment.CanReply)
            		if (comment.canFlag)
            			commentContent += "<a href='javascript:void(0)' data-action='report' numflags="+comment.flags+">" + options.localization.reportButtonText + "</a><label> ("+comment.flags+")</label>";
            		else 
            			commentContent += ' Flagged ('+comment.flags+')';
            }
            commentContent += "</p></div></div></li>";
            $container.prepend(commentContent);
        },

        bindEvents: function () {
            var $this = this;
            var options = $this.data().data.options;
            $('#' + $this.attr('id') + ' [data-action="replay"]').unbind().bind("click", function () {
                methods.startCommentReplay.call($this, $(this));
            });
            
            $('#' + $this.attr('id') + ' [data-action="like"]').unbind().bind("click", function () {
            	var commentId = $(this.parentNode.parentElement.parentElement.parentElement).attr('data-commentid');
         
            	$(this).text('Liked');
            	var likes = $(this).attr('numlikes');
            	$(this).removeAttr('href');
            	$(this).unbind('click');
            	$(this).next().text('('+(likes*1+1)+')');
            	$.post( options.likeCommentUrl, { id:commentId } ).done(function(result){
            		console.log(result);
            	});
            });
            
            $('#' + $this.attr('id') + ' [data-action="report"]').unbind().bind("click", function () {
            	var commentId = $(this.parentNode.parentElement.parentElement.parentElement).attr('data-commentid');
            	$(this).text('Flagged');
            	var flags = $(this).attr('numflags');
            	$(this).removeAttr('href');
            	$(this).unbind('click');
            	$(this).next().text('('+(flags*1+1)+')');
            	$.post( options.reportCommentUrl, { id:commentId } ).done(function(result){console.log(result);alert("Thank you for your report. The project owner will be notified and asked to take action immediately.");});
            });

            $('#' + $this.attr('id') + ' [data-action="delete"]').unbind().bind("click", function () {
                if (!options.callback.beforeDelete($(this).parents("li.comment").data("commentid")))
                    return;
                var $loadingIndicator = $this.find(".loadingIndicator");
                $loadingIndicator.show();
                $test = $(this).parents("li.comment").data("commentid");
                $.post(options.deleteCommentUrl, { commentId: $(this).parents("li.comment").data("commentid") }, function (commentId) {
                    $("[data-commentid=" + commentId + "]").remove();
                    $this.find(".heading h4 [data-action='count']").html($this.find(".comment").length);
                    $loadingIndicator.hide();
                    options.callback.afterDelete(commentId);
                });
            });
        },

        startCommentReplay: function (btn) {
            var $this = this;
            var options = this.data().data.options;
            var $content = $(btn).parents("li.comment");
            $(btn).parent().after("<div class='contentBlockCommentAdd'>" +
                                    "<form enctype='multipart/form-data'>" +
                                        "<textarea cols='20' placeholder='" + options.localization.commentPlaceHolderText + "' rows='2' name='comment' maxlength='2500'></textarea>" +
                                        "<a href='javascript:void(0)' class='btn sendComment' data-action='send'><span>" + options.localization.sendButtonText + "</span></a>" +
                                    "</form>" +
                                   "</div>");
            $(btn).hide();
            var $replayComment = $($content).find("textarea");
            $replayComment.focus();

            $replayComment.focusout(function () {
                $(this).removeClass("focused");
                if ($.trim($(this).val()).length == 0) {
                    $content.find(".contentBlockCommentAdd").remove();
                    $(this).val("");
                    $(btn.show());
                }
            });

            $content.find('[data-action="send"]').bind("click", function () {
                var $form = $(this).parent();
                var comment = $form.find("textarea").val();
                if ($.trim(comment).length != 0) {
                    var formData = $form.serialize();
                    formData += "&parentId=" + $content.data("commentid");
                    methods.postComment.call($this, formData);
                }
                $(btn.show());
                $content.find(".contentBlockCommentAdd").remove();
            });
        }
    };

    $.fn.comments = function (method) {
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        } else {
            $.error('Method ' + method + ' doesn not exists in jQuery.comments');
            return null;
        }
    };
})(jQuery);
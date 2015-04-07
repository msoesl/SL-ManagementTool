var defaultSelection = "all_projects";
var prevSelection = "stage1_projects";

PreviewWidget = {
		
	onPreviewWidgetListItemClick : function(e){
		PageChanger.loadProjectView();
	},

	init : function(){
		previewWidget = $('#preview_results_container');
		projectView = $('#project_view');
		projectViewBody = $('#project_view_body');
		
		
		$("#navbar ul li").on("click", function(){
		    var newSelection = $(this).children("a").attr("data-tab-class");
		    $("."+prevSelection).addClass("ui-screen-hidden");
		    $("."+newSelection).removeClass("ui-screen-hidden");
		    prevSelection = newSelection;
		});
		
		$(".preview_widget_listview li").hover(
			    function(){
			    	$('#'+this.id+' .extra_project_details h1').hide();
			    	var parentHeight = $('#'+this.id+' .extra_project_details').parent().parent().height();
			    	$('#'+this.id+' .extra_project_details').animate({marginTop:'-='+Math.floor(parentHeight*.30)+'px'}, 150);
			    },
			    function(){ 
			    	var elementId = '#'+this.id;
			    	var parentHeight = $('#'+this.id+' .extra_project_details').parent().parent().height();
			    	$(elementId+' .extra_project_details').animate({marginTop:'+='+Math.floor(parentHeight*.30)+'px'}, 150,
			    		function(){
			    			$(elementId+ ' .extra_project_details h1').show();
			    		});
			    });
		$(".preview_widget_listview li div").click(
			    function(){
			    	$('#'+this.id+' .extra_project_details').animate({top:'-='+100+'px'});
			    },
			    function(){ 
			    	$('#'+this.id+' .extra_project_details').animate({top:'+='+100+'px'});
			    });
	}
	
	
}
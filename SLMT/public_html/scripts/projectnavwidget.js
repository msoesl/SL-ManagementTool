$(document).ready(function(){
	$("#slide-control-project-nav").bind("vclick", openNavMenu);
});

$(document).ready(function(){
	$("#slide-control-project-options").bind("vclick", openOptionsMenu);
});
			
function closeMenu(menuName){
	var shift = $("#" + menuName + "-menu-dynamic ul").width()*-1;

	if(menuName == 'project-nav'){
		$("#" + menuName + "-menu-dynamic").animate({marginLeft: shift + 'px'}, "slow", function(){
			$("#slide-control-project-nav .ui-btn-text").html("<img src='res/images/nav-stage.png'>");							
		});
		$("#slide-control-" + menuName).bind("vclick", openNavMenu);
		$("#slide-control-" + menuName).unbind("vclick", closeNavMenu);
	}
	else{
		$("#" + menuName + "-menu-dynamic").animate({marginRight: shift + 'px'}, "slow", function(){
			$("#slide-control-project-options .ui-btn-text").html("<img src='res/images/option-option.png'>");							
		});
		$("#slide-control-" + menuName).bind("vclick", openOptionsMenu);
		$("#slide-control-" + menuName).unbind("vclick", closeOptionsMenu);				
	}
}
		
function openMenu(menuName){
	var shift = $("#" + menuName + "-menu-dynamic ul").width();

	if(menuName == 'project-nav'){
		$("#" + menuName+ "-menu-dynamic").animate({marginLeft: 0 + 'px'}, "slow", function(){
			$("#slide-control-" + menuName + " .ui-btn-text").html("<img src='res/images/nav-hide.png'>");
		});	
		$("#slide-control-" + menuName).bind("vclick", closeNavMenu);
		$("#slide-control-" + menuName).unbind("vclick", openNavMenu);
	}
	else{
		$("#" + menuName+ "-menu-dynamic").animate({marginRight: 0 + 'px'}, "slow", function(){
			$("#slide-control-" + menuName + " .ui-btn-text").html("<img src='res/images/option-hide.png'>");
		});	
		$("#slide-control-" + menuName).bind("vclick", closeOptionsMenu);
		$("#slide-control-" + menuName).unbind("vclick", openOptionsMenu);		
	}
}

function openNavMenu(){
	openMenu("project-nav");
}

function closeNavMenu(){
	closeMenu("project-nav");	
}

function openOptionsMenu(){
	openMenu("project-options");	
}

function closeOptionsMenu(){
	closeMenu("project-options");
}
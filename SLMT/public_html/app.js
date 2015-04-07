$(document).ready(function() {
	var pg = PageChanger.init();
	
	$(".large_window_list_item").hover(
			function(){

			},
			function(){

			});
	
	
});

var overlay = new ItpOverlay("page-content");

function setRadio(what) {

    $("input[type='radio']").not($(what)).removeAttr("checked");
	 $(what).attr('checked', true);
       $("input[type='radio']").checkboxradio("refresh");
}
<?php
if (! isset ( $project )) {
	// Should probably switch this for now, but maybe replace this with 404
	echo 'you done goofed.';
	die ();
}
if ($isValidId && $project && $project->isValid ()) {
?>
<div>
<h2>Calendar</h2>
	<hr>
	<?php if($canEdit && isset($stage) && $stage == 2){?>
			<ul data-role="listview" data-inset="true" data-inline="true" data-theme="f">
				<li onclick="createEvent()">
				<a class="edit-button" href="#newEventPopupDialog"
				title="Edit" data-icon="edit" data-rel="popup"
				data-position-to="window" aria-haspopup="true"
				aria-owns="#newEventPopupDialog">Add Calendar Event</a>
				</li>
			</ul>
			
			<!-- New Event Dialog -->
			<div style='display: none'>
			    <div data-role="popup" id="newEventPopupDialog">
			        <div data-role="header" title="Edit" data-theme="f"
			            class="ui-corner-top ui-header ui-bar-a" role="banner">
			            <h1 class="ui-title">New Event</h1>
			        </div>
			        <div class='simple-padding-medium'>
			            <form id = 'new_event_form'
			                data-ajax='false' method='POST' 
			                action = 'controllers/NewEventController.php?project_id=<?php echo $project->getProjectId() ?>'>
			                <span>Event Title:</span><input type="text" name="new_event_title" id="new_event_title" required></input>
			                <span>Date:</span><input type="datetime-local" name="new_event_date" id="new_event_date" required></input>
			                <span>Description:</span><textarea name="new_event_description" id='new_event_description' data-theme="f" required></textarea>
			                <div class='ui-grid-a'>
			                    <div class='ui-block-a'></div>
			                    <div class='ui-block-b'>
			                        <input type="submit" name="submit" value="Submit" data-theme="f">
			                    </div>
			                </div>
			            </form>
			        </div>
			    </div>
			</div>
	<?php } ?>
	<div id="projectCalendar" class="eventCalendarPlugin eventCalendar-wrap">
		
	</div>
</div>
	<script>
	$('#projectCalendar').eventCalendar({
		  jsonDateFormat: 'human',
		  eventsjson: 'controllers/GetEventController.json.php?id='+<?php echo $_GET ['id']?>,
		  startWeekOnMonday: false,
		  showDescription: true,
		  cacheJson: false
		});

	function loadAttendingForm(eventId) {
		$('.ui-page').prepend('<div class="form-mask"></div>');
		TINY.box.show({mask:false,url:'views/AttendingEventWidget.php?eid='+eventId, 
			openjs:function(){
				$(document).trigger('create');
			},closejs:function(){
				$('.form-mask').remove();
			},width:Math.floor($(window).width()*.8),height:Math.floor($(window).height()*.8)})
	}

	function unjoin(id){
		$.post('controllers/UnjoinEventController.php?id='+id);
		//location.reload();
	}

	function join(id){
		$.post('controllers/JoinEventController.php?id='+id);
		//location.reload();
	}

	function cancel(id){
		$.post('controllers/RemoveEventController.php?id='+id);
		//location.reload();
	}
	</script>
	
	<script type="text/javascript">
	function createEvent(){
	    $.post('controllers/NewEventController.php?project_id='+<?php echo $project->getProjectId() ?>);
	 }
	</script>
<?php } else { ?>

That's not a valid Project!
<button onclick="PageChanger.loadHomepage()" data-theme="f">Go Home</button>

<?php } ?>
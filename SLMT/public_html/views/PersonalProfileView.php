<?php
require_once ('import/headimportviews.php');
session_start ();
$user = null;
if (isset ( $_SESSION ['user'] )) {
	$user = $_SESSION ['user'];
}

$isValidId = isset ( $_GET ['id'] );
$canEdit = isset ( $user ) && $isValidId && $user->getAccountId() == $_GET['id'];
$userProfile = null;


$isPrivate = 0;
if ($isValidId) {
	$userProfile = AccountModel::getAccountById ( $_GET['id'] );
	if(isset($userProfile)){
		$isPrivate = $userProfile->profile->is_private;
	}
}

if($isPrivate > 0 && $canEdit == false){
	$deny = false;
	if(!$user){
		$deny = true;
	} else if (!$user->hasFriend($_GET['id'])){
		$deny = true;
	}
	if($deny){
		?>
		<script type="text/javascript">
			PageChanger.loadMessageView({'messageType' : 'private_profile_access_denied'});
		</script>
		<?php
	}
}

if ($isValidId && $userProfile) {
	?>



	<?php
	function getStatus(){

		if(isset($_GET['status'])){// request successed
			if($_GET['status']=='0'){

				echo "<script type=\"text/javascript\">
				$('#personal-editing-confirmation-message').popup();
	            	$('#personal-editing-confirmation-message').popup(\"open\");
	            	
	            	if(typeof window.history.pushState == 'function') {
	            		var id = ".$_GET['id'] .";
	            		var url = \"#PersonalProfileView.php?id=\"+id;
        			window.history.pushState({}, \"Hide\",url);
   				}
	            </script>";
			}else{
				echo "<script type=\"text/javascript\">
				$('#personal-editing-error-message').popup();
	            	$('#personal-editing-error-message').popup(\"open\");
	            	
	            	if(typeof window.history.pushState == 'function') {
	            		var id = ".$_GET['id'] .";
	            		var url = \"#PersonalProfileView.php?id=\"+id;
        			window.history.pushState({}, \"Hide\",url);
   				}
	            </script>";
			}
				
		}
	}
	?>

<div id='status'>
<?php getStatus();?>
</div>

<div id="personal-profile-view" class="personal-profile-view">
	<div class='box-div standard-border standard-background'>
		<table id='header-table'>
			<tr>
				<td id='profile-pic-container'>
					<div class='standard-border'>
						<img id='personal-profile-pic'
							src='<?php echo $userProfile->profile->profile_pic_url . '?nocache=' . microtime(true)?>' />
					</div>
				</td>
				<?php //$userProfile = $user->getProfile();?>
				<td id='profile-content-center'>
					<h3 class='title'>
					<?php echo $userProfile->profile->firstname . ' ' . $userProfile->profile->lastname;?>
					</h3>
					<h3 class='sub-title'>
					<?php echo $userProfile->profile->city . ', ' . $userProfile->profile->state;?>
					</h3> <?php if($canEdit && !$userProfile->is_banned){ ?>
					<div class='button-container'>
						<a href="#popupDialog" data-rel="popup" data-position-to="window"
							data-role="button" data-inline="true" data-transition="pop"
							data-corners="true" data-shadow="true" data-iconshadow="true"
							data-wrapperels="span" data-theme="a" aria-haspopup="true"
							aria-owns="#popupDialog">Edit Picture</a>
							
							<a href="#PictureInformation" id="edit-info-wrap" data-rel="popup" data-position-to="window"
							data-role="button" data-inline="true" data-transition="pop"
							data-corners="true" data-shadow="true" data-iconshadow="true"
							data-wrapperels="span" data-theme="a" aria-haspopup="true"
							aria-owns="#popupDialog" data-icon="info" data-iconpos="left"></a>
							
							
					</div>
					<div class='button-container'>
						<a href="#passwordPopupDialog" data-rel="popup"
							data-position-to="window" data-role="button" data-inline="true"
							data-transition="pop" data-corners="true" data-shadow="true"
							data-iconshadow="true" data-wrapperels="span" data-theme="a"
							aria-haspopup="true" aria-owns="#passwordPopupDialog">Change
							Password</a>
					</div> <?php } else if ($userProfile->is_banned) {?>
					<h2>User Is Banned.</h2> <?php } else if ($user != null && !$user->hasReportedAccount($_GET['id'])) {
						if($user->hasPendingRequestForUser($_GET['id'])){?>
					<div style="width: 30%">
						<h3 class="yellow-background white-text simple-padding-xsmall" style='width:150px'>Friend
							request sent</h3>
					</div> <?php } 
					else if(!$user->hasFriend($_GET['id'])){?>
					<div style="width: 25%">
						<a data-role='button' style='width:150px'
							onclick='requestAddFriend(<?php echo $userProfile->id?>)'
							data-theme="a">Add as friend</a>
					</div> <?php }
					else{?>
					<div style="width: 25%">
						<a href="#remove-friend" data-role='button' data-rel='popup' style='width:150px'
							data-theme="a" data-icon='check'>Friends</a>
						<div data-role='popup' id='remove-friend'>
							<ul data-role='listview' data-inset="true" data-divider-theme='a'
								data-theme="a">
								<li data-role='list-divider' role='heading'>Friends</li>
								<li data-role='listitem'
									onclick='removeFriend(<?php echo $userProfile->id?>)'><a
									href="#">Remove Friend</a></li>
							</ul>
						</div>
					</div> <?php }?>
					<div style="width: 40%">
						<button onclick='reportUser(<?php echo $userProfile->id?>)'
							data-theme="a">Report this user</button>
					</div> <?php } else if ($user != null) {?>
					<p style='font-weight: bold; color: red'>You have reported this
						user.</p> <?php }?>
				</td>
				<td id='profile-content-right'><?php if($canEdit && !$userProfile->is_banned){ ?>
					<div class="edit-button-wrap">
						<a class="edit-button" href="#namePopupDialog" title="Edit"
							data-icon="edit" data-rel="popup" data-position-to="window"
							data-role="button" data-inline="true" data-transition="pop"
							data-corners="true" data-shadow="true" data-iconshadow="true"
							data-wrapperels="span" data-theme="a" aria-haspopup="true"
							aria-owns="#namePopupDialog"></a>
					</div> <?php } ?>
				</td>
			</tr>
		</table>
	</div>

	<table>
		<tr>
			<td class='width-half-personal-profile'>
				<div
					class='simple-padding-small box-div standard-border standard-background'
					id='about-me'>
					<table>
						<tr>
							<td id="sections-title">
								<h3 class='title'>About Me</h3>
							</td>
							<td><?php if($canEdit && !$userProfile->is_banned){ ?>
								<div class="edit-button-wrap">
									<a class="edit-button" href="#aboutMePopupDialog" title="Edit"
										data-icon="edit" data-rel="popup" data-position-to="window"
										data-role="button" data-inline="true" data-transition="pop"
										data-corners="true" data-shadow="true" data-iconshadow="true"
										data-wrapperels="span" data-theme="a" aria-haspopup="true"
										aria-owns="#aboutMePopupDialog"></a>
								</div> <?php } ?>
							</td>
						</tr>
					</table>
					<hr>
					<p>
					<?php echo $userProfile->profile->about_me;?>
					</p>
				</div>
				<div
					class='simple-padding-small box-div standard-border standard-background'
					id='skills'>
					<table>
						<tr>
							<td id="sections-title">
								<h3 class='title'>Skills</h3>
							</td>
							<td><?php if($canEdit && !$userProfile->is_banned){ ?>
								<div class="edit-button-wrap">
									<a class="edit-button" href="#skillsPopupDialog" title="Edit"
										data-icon="edit" data-rel="popup" data-position-to="window"
										data-role="button" data-inline="true" data-transition="pop"
										data-corners="true" data-shadow="true" data-iconshadow="true"
										data-wrapperels="span" data-theme="a" aria-haspopup="true"
										aria-owns="#skillsPopupDialog"></a>
								</div> <?php } ?>
							</td>
						</tr>
					</table>
					<hr>
					<table>
					<?php
					$skills = $userProfile->skills;
					foreach ( $skills as $skill ) {
						?>
						<tr>
							<td width="20">
								<div class='skills-button-container'>
									<button <?php if (!isset($user)){echo 'disabled';}?>
										onclick="loadEndorsementForm(<?php echo $skill->id?>)"
										data-theme="a" data-mini="true">
										<?php echo ORM::for_table('endorsement')->where('Account_has_Skills_id', $skill->id)->count();?>
									</button>
								</div>
							</td>
							<td>
								<div class='box-div standard-background '>
								<?php echo $skill->skill_name;?>
								</div>
							</td>
						</tr>
						<?php }?>
					</table>
				</div>
			</td>
			<td class='width-half-personal-profile'>
				<div
					class='simple-padding-small box-div standard-border standard-background'
					id='information'>
					<table>
						<tr>
							<td id="sections-title">
								<h3 class='title'>Information</h3>
							</td>
							<td><?php if($canEdit && !$userProfile->is_banned){ ?>
								<div class="edit-button-wrap">
									<a class="edit-button" href="#informationPopupDialog"
										title="Edit" data-icon="edit" data-rel="popup"
										data-position-to="window" data-role="button"
										data-inline="true" data-transition="pop" data-corners="true"
										data-shadow="true" data-iconshadow="true"
										data-wrapperels="span" data-theme="a" aria-haspopup="true"
										aria-owns="#informationPopupDialog"></a>
								</div> <?php } ?>
							</td>
						</tr>
					</table>
					<hr>
					<p>Email:</p>
					<p>
					<?php echo $userProfile->email_address;?>
					</p>
					<br>
					<p>Phone Number:</p>
					<p>
					<?php echo $userProfile->profile->contact_number;?>
					</p>
					<br>
					<p>Gender:</p>
					<p>
					<?php echo $userProfile->profile->gender;?>
					</p>
					<br>
					<p>Age:</p>
					<p>
					<?php echo $userProfile->profile->age;?>
					</p>
				</div>
				<div
					class='simple-padding-small box-div standard-border standard-background'
					id='projects'>
					<?php $projects = ProjectModel::getAllProjectsForAccountCollaboration ( $userProfile->id);?>					
					<h3 class='title'>Projects (<a href='' onclick='PageChanger.loadPersonalProjectsView({id:<?php echo $_GET['id'];?>})'><?php echo count($projects)?></a>)</h3>
					<hr>
					<ul data-theme="a" data-role="listview">
					<?php $count = 0;
					foreach ( $projects as $project ) {
						$count ++;
						if ($count == 7) {
							break;
						}
						$projectItself = new ProjectModel($project->Project_id);
						$projectTitle = ORM::for_table('account_title')->find_one($project->Account_Title_ID);
						$pic = ORM::for_table('project_banner')->where('project_id',$projectItself->getProjectId ())->find_one();
						
						$jsonObj = array ('id' => $projectItself->getProjectId () );
					
						?>
						<li id = 'large_window_list_item-<?php echo $projectItself->getProjectId()?>' style='background:url(<?php echo $pic->src . '?nocache=' . microtime(true)?>)' onclick='PageChanger.loadProjectView(<?php echo json_encode($jsonObj)?>)'>
							<a href="#">
								<div class='project-background-div'>
									<label class='project-title'><?php echo $projectItself->getProjectTitle();?>
									</label><br> <label class='personal-title'><?php echo $userProfile->firstname . ' ' . $userProfile->lastname;?>
								 </label>
								</div> </a>
						</li>
						<?php
					}

					if ($count == 0) {
						?>
						<p>
						<?php echo $userProfile->firstname . ' ' . $userProfile->lastname;?>
							is not actively involved in any projects at this time.
						</p>
						<?php }?>
					</ul>
				</div>
				<div
					class='simple-padding-small box-div standard-border standard-background'
					id='friends'>
					<?php $friends = array_slice($userProfile->friends, 0, 10);?>
					<h3 class='title'>Friends (<a href='' onclick='PageChanger.loadFriendsView({id:<?php echo $_GET['id'];?>})'><?php echo count($friends)?></a>)</h3>
						<hr>
					<div id='friend-viewer'>
						<ul data-theme="a" data-role="listview">
							<?php foreach ($friends as $friend) {
								$friendProfile=$friend->getProfile();?>
							<a href='' class='friend-preview'>
								<li class='friend'  data-theme="a" style='background:url(<?php echo $friend->getProfilePicUrl()?>); background-size:100%;'
									onclick='PageChanger.loadProfileView({id:<?php echo $friend->getAccountId();?>})'>			 
									 <div class='name-container'>
									 	<p><?php echo $friendProfile->firstname?></br><?php echo $friendProfile->lastname?> </p>
									 </div>
								</li>
							</a>
							<?php }?>
						
						</ul>
					</div>	
				</div>
			</td>
		</tr>
	</table>
</div>
<!-- profile picture dialog -->
<div style='display: none'>
	<div data-role="popup" id="PictureInformation">
		<div data-role="header" data-theme="a"
			class="ui-corner-top ui-header ui-bar-a" role="banner">
			<h1 class="ui-title" role="heading" aria-level="1">Picture Information</h1>
		</div>
		<div class='simple-padding-medium'>
				<p>- The maximum size is 5 MB. If your image is greater, a default image will be yours.
				<br> <br> 
				- The maximum width and height must be 400. If it is not, it will be resized.</p>
		</div>
	</div>
</div>
<!-- profile picture dialog -->
<div style='display: none'>
	<div data-role="popup" id="popupDialog">
		<div data-role="header" data-theme="a"
			class="ui-corner-top ui-header ui-bar-a" role="banner">
			<h1 class="ui-title" role="heading" aria-level="1">Change Profile
				Picture</h1>
		</div>
		<div class='simple-padding-medium'>
			<form id='profile-pic-form' enctype="multipart/form-data"
				data-ajax='false'
				action='controllers/PersonalProfilePicController.php' method='POST'>
				Select a photo: <input type="file" name="file" id='file'>
				<div class='ui-grid-a'>
					<div class='ui-block-a'></div>
					<div class='ui-block-b'>

						<input type="submit" name="submit" value="Submit" data-theme="a">
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- edit name dialog -->
<div class="name-dialog" style='display: none'>
	<div data-role="popup" id="namePopupDialog">
		<div data-role="header" data-theme="a"
			class="ui-corner-top ui-header ui-bar-a" role="banner">
			<h1 class="ui-title" role="heading" aria-level="1">Change Name and
				Location</h1>
		</div>
		<div class='simple-padding-medium'>
			<form id='header-edit-form' data-ajax='false' method='POST'
				action='controllers/SaveProfileController.php'>
				First Name: <input
					value="<?php echo $userProfile->profile->firstname ?>" type="text"
					name="firstname" id='firstname' data-theme="a"> Last Name: <input
					value="<?php echo $userProfile->profile->lastname ?>" type="text"
					name="lastname" id='lastname' data-theme="a"> City: <input
					value="<?php echo $userProfile->profile->city ?>" type="text"
					name="city" id='city' data-theme="a"> State: <select
					value="<?php echo $userProfile->profile->state ?>" type="text"
					name="state" id='state' data-theme="a">
					<option value="AL" <?php if($userProfile->profile->state =='AL') echo "selected=selected"?>>Alabama</option>
					<option value="AK" <?php if($userProfile->profile->state =='AK') echo "selected=selected"?>>Alaska</option>
					<option value="AZ"  <?php if($userProfile->profile->state =='AZ') echo "selected=selected"?>>Arizona</option>
					<option value="AR"  <?php if($userProfile->profile->state =='AR') echo "selected=selected"?>>Arkansas</option>
					<option value="CA"  <?php if($userProfile->profile->state =='CA') echo "selected=selected"?>>California</option>
					<option value="CO"  <?php if($userProfile->profile->state =='CO') echo "selected=selected"?>>Colorado</option>
					<option value="CT"  <?php if($userProfile->profile->state =='CT') echo "selected=selected"?>>Connecticut</option>
					<option value="DE" <?php if($userProfile->profile->state =='DE') echo "selected=selected"?>>Delaware</option>
					<option value="FL" <?php if($userProfile->profile->state =='FL') echo "selected=selected"?>>Florida</option>
					<option value="GA"  <?php if($userProfile->profile->state =='GA') echo "selected=selected"?>>Georgia</option>
					<option value="HI"  <?php if($userProfile->profile->state =='HI') echo "selected=selected"?>>Hawaii</option>
					<option value="ID"  <?php if($userProfile->profile->state =='ID') echo "selected=selected"?>>Idaho</option>
					<option value="IL"  <?php if($userProfile->profile->state =='IL') echo "selected=selected"?>>Illinois</option>
					<option value="IN"  <?php if($userProfile->profile->state =='IN') echo "selected=selected"?>>Indiana</option>
					<option value="IA"  <?php if($userProfile->profile->state =='IA') echo "selected=selected"?>>Iowa</option>
					<option value="KS"  <?php if($userProfile->profile->state =='KS') echo "selected=selected"?>>Kansas</option>
					<option value="KY"  <?php if($userProfile->profile->state =='KY') echo "selected=selected"?>>Kentucky</option>
					<option value="LA"  <?php if($userProfile->profile->state =='LA') echo "selected=selected"?>>Louisiana</option>
					<option value="ME"  <?php if($userProfile->profile->state =='ME') echo "selected=selected"?>>Maine</option>
					<option value="MD"  <?php if($userProfile->profile->state =='MD') echo "selected=selected"?>>Maryland</option>
					<option value="MA"  <?php if($userProfile->profile->state =='MA') echo "selected=selected"?>>Massachusetts</option>
					<option value="MI"  <?php if($userProfile->profile->state =='MI') echo "selected=selected"?>>Michigan</option>
					<option value="MN"  <?php if($userProfile->profile->state =='MN') echo "selected=selected"?>>Minnesota</option>
					<option value="MS" <?php if($userProfile->profile->state =='MS') echo "selected=selected"?>>Mississippi</option>
					<option value="MO"  <?php if($userProfile->profile->state =='MO') echo "selected=selected"?>>Missouri</option>
					<option value="MT"  <?php if($userProfile->profile->state =='MT') echo "selected=selected"?>>Montana</option>
					<option value="NE"  <?php if($userProfile->profile->state =='NE') echo "selected=selected"?>>Nebraska</option>
					<option value="NV"  <?php if($userProfile->profile->state =='NV') echo "selected=selected"?>>Nevada</option>
					<option value="NH"  <?php if($userProfile->profile->state =='NH') echo "selected=selected"?>>New Hampshire</option>
					<option value="NJ"  <?php if($userProfile->profile->state =='NJ') echo "selected=selected"?>>New Jersey</option>
					<option value="NM"  <?php if($userProfile->profile->state =='NM') echo "selected=selected"?>>New Mexico</option>
					<option value="NY"  <?php if($userProfile->profile->state =='NY') echo "selected=selected"?>>New York</option>
					<option value="NC"  <?php if($userProfile->profile->state =='NC') echo "selected=selected"?>>North Carolina</option>
					<option value="ND"  <?php if($userProfile->profile->state =='ND') echo "selected=selected"?>>North Dakota</option>
					<option value="OH"  <?php if($userProfile->profile->state =='OH') echo "selected=selected"?>>Ohio</option>
					<option value="OK"  <?php if($userProfile->profile->state =='OK') echo "selected=selected"?>>Oklahoma</option>
					<option value="OR"  <?php if($userProfile->profile->state =='OR') echo "selected=selected"?>>Oregon</option>
					<option value="PA"  <?php if($userProfile->profile->state =='PA') echo "selected=selected"?>>Pennsylvania</option>
					<option value="RI"  <?php if($userProfile->profile->state =='RI') echo "selected=selected"?>>Rhode Island</option>
					<option value="SC"  <?php if($userProfile->profile->state =='SC') echo "selected=selected"?>>South Carolina</option>
					<option value="SD"  <?php if($userProfile->profile->state =='SD') echo "selected=selected"?>>South Dakota</option>
					<option value="TN"  <?php if($userProfile->profile->state =='IN') echo "selected=selected"?>>Tennessee</option>
					<option value="TX"  <?php if($userProfile->profile->state =='TX') echo "selected=selected"?>>Texas</option>
					<option value="UT"  <?php if($userProfile->profile->state =='UT') echo "selected=selected"?>>Utah</option>
					<option value="VT"  <?php if($userProfile->profile->state =='VT') echo "selected=selected"?>>Vermont</option>
					<option value="VA"  <?php if($userProfile->profile->state =='VA') echo "selected=selected"?>>Virginia</option>
					<option value="WA"  <?php if($userProfile->profile->state =='WA') echo "selected=selected"?>>Washington</option>
					<option value="WV"  <?php if($userProfile->profile->state =='WV') echo "selected=selected"?>>West Virginia</option>
					<option value="WI"  <?php if($userProfile->profile->state =='WI') echo "selected=selected"?>>Wisconsin</option>
					<option value="WY"  <?php if($userProfile->profile->state =='WY') echo "selected=selected"?>>Wyoming</option>
				</select>
				<div class='ui-grid-a'>
					<div class='ui-block-a'></div>
					<div class='ui-block-b'>

						<input type="submit" name="submit" value="Submit" data-theme="a">
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- edit about me dialog -->
<div style='display: none'>
	<div data-role="popup" id="aboutMePopupDialog">
		<div data-role="header" title="Edit" data-theme="a"
			class="ui-corner-top ui-header ui-bar-a" role="banner">
			<h1 class="ui-title" role="heading" aria-level="1">Change Description</h1>
		</div>
		<div class='simple-padding-medium'>
			<form id='about-me-form' data-ajax='false' method='POST'
				action='controllers/SaveProfileController.php'>
				<textarea type="textarea" name="about_me" id='about_me'
					data-theme="a"><?php echo $userProfile->profile->about_me;?></textarea>
				<div class='ui-grid-a'>
					<div class='ui-block-a'></div>
					<div class='ui-block-b'>

						<input type="submit" name="submit" value="Submit" data-theme="a">
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- edit information dialog -->
<div style='display: none'>
	<div data-role="popup" id="informationPopupDialog">
		<div data-role="header" title="Edit" data-theme="a"
			class="ui-corner-top ui-header ui-bar-a" role="banner">
			<h1 class="ui-title" role="heading" aria-level="1">Change Contact
				Information</h1>
		</div>
		<div class='simple-padding-medium'>
			<form id='contact-info-form' data-ajax='false' method='POST'
				action='controllers/SaveProfileController.php'
				enctype="multipart/form-data">
				Email: <input value="<?php echo $user->getEmail();?>" type="email"
					name="email" id='email'> Phone Number: <input maxlength=10
					value="<?php echo $userProfile->profile->contact_number;?>"
					type="text" name="phone_number" id='phone_number'> Gender: <select
					name="gender" id='gender' data-theme="a">
					<option value="Female"  <?php if($userProfile->profile->gender =='Female') echo "selected=selected"?>>Female</option>
					<option value="Male"  <?php if($userProfile->profile->gender =='Male') echo "selected=selected"?>>Male</option>
				</select> Age: <input
					value="<?php echo $userProfile->profile->age;?>" type="number"
					name="age" id='age' min="0" data-theme="a">
				<div class='ui-grid-a'>
					<div class='ui-block-a'></div>
					<div class='ui-block-b'>

						<input type="submit" name="submit" value="Submit" data-theme="a">
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- edit skills dialog -->
<div style='display: none'>
	<div data-role="popup" id="skillsPopupDialog">
		<div data-role="header" title="Edit" data-theme="a"
			class="ui-corner-top ui-header ui-bar-a" role="banner">
			<h1 class="ui-title" role="heading" aria-level="1">Edit Your Skills</h1>
		</div>
		<div class='simple-padding-medium'>
			<table>
			<?php
			$skills = $userProfile->skills;
			foreach ( $skills as $skill ) {
				?>
				<tr>
					<td>
						<div class='box-div skill-name' style='margin-right: 1em;'>
						<?php echo $skill->skill_name;?>
						</div>
					</td>
					<td width="40">
						<div class='skills-button-container'>
							<a data-role='button' data-ajax='false'
								href='javascript:void(0);'
								onclick='removeSkill(<?php echo $_GET['id'];?>,<?php echo $skill->Skills_id?>)'
								data-theme="a" data-mini="true">Remove</a>
						</div>
					</td>
				</tr>
				<?php }?>
			</table>
			<br>
			<div id='add-a-skill-form'>
				<ul data-theme="a" data-role="listview" data-filter="true"
					data-filter-reveal="true"
					data-filter-placeholder="Choose a skill or enter a new one..."
					data-inset="true" id='add-skill-input'>
					<?php
					$skills = ORM::for_table ( 'skills' )->find_many ();
					foreach ( $skills as $skill ) {
						?>
					<li onclick='populateInput("<?php echo $skill->skill_name;?>")'><?php echo $skill->skill_name?>
					</li>
					<?php }?>
				</ul>
			</div>
			<br>
			<div class='ui-grid-a'>
				<div class='ui-block-a'></div>
				<div class='ui-block-b'>

					<input onclick='addSkill()' type="submit" name="submit" value="Add"
						data-theme="a">
				</div>
			</div>
		</div>
	</div>
</div>

<!-- password change dialog -->
<div style='display: none'>
	<div data-role="popup" id="passwordPopupDialog">
		<div data-role="header" title="Edit" data-theme="a"
			class="ui-corner-top ui-header ui-bar-a" role="banner">
			<div id="change-password-status-dialog"></div>
			<h1 class="ui-title" role="heading" aria-level="1">Change Password</h1>
		</div>
		<div class='simple-padding-medium'>
			<form data-ajax='false' id='password-change-form'
				action='controllers/PersonalProfilePasswordChangeController.php'
				method='POST' enctype="multipart/form-data">
				Current Password: <input value="" type="password"
					name="currentPassword" id='currentPassword'> New Password: <input
					value="" type="password" name="newPassword" id='newPassword'>
				Confirm New Password: <input value="" name="confirmNewPassword"
					id="confirm-password-input" type="password">
				<div id="password-change-status" style="color: red;"></div>
				<div class='ui-grid-a'>
					<div class='ui-block-a'></div>
					<div class='ui-block-b'>

						<input type="submit" name="submit" value="Submit" data-theme="a">
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- Personal Editing confirmation message Dialog -->
<div style='display: none'>
	<div data-role="popup" id="personal-editing-confirmation-message"
		data-dismissible="true">
		<div data-role="header" title="Create" data-theme="a"
			class="ui-corner-top ui-header ui-bar-a modalContent" role="banner">
			<h1 class="ui-title" role="heading" aria-level="1">Personal Profile
				Edit</h1>
		</div>

		<div class='simple-padding-medium' data-theme="a">
			<p>Your profile has been changed.</p>
		</div>
	</div>
</div>
<!-- Personal Editing error message Dialog -->
<div style='display: none'>
	<div data-role="popup" id="personal-editing-error-message"
		data-dismissible="true">
		<div data-role="header" title="Create" data-theme="a"
			class="ui-corner-top ui-header ui-bar-a modalContent" role="banner">
			<h1 class="ui-title" role="heading" aria-level="1">Personal Profile
				Edit</h1>
		</div>

		<div class='simple-padding-medium' data-theme="a">
			<p>Your profile has not been changed.</p>
		</div>
	</div>
</div>
<script>
	
	var populateInput = function(skillName) {
		$('#add-a-skill-form input').val(skillName);
		$('#add-skill-input').hide();
	}
	var removeSkill = function(profileId,skillId) {
		 $.post('controllers/RemoveSkillController.php?id='+profileId+'&skillid='+skillId).done(
		    	   function(result){
			    	  var data = $.parseJSON(result);
			    	   if (data.status == 'Success') {
							location.reload();
			    	   } else {
							alert('An error occurred while trying to delete this skill. Please contact your system administrator.');
			    	   }
			 	   }
				);
	}

	var addSkill = function () {
		   var value = $('#add-a-skill-form input').val();
	       $.post('controllers/AddSkillController.php?id='+<?php echo $userProfile->id?>+'&skillName='+value).done(
	    	   function(result){
			    	var data = $.parseJSON(result);
		    	   if (data.status == 'Success') {
						location.reload();
		    	   } else {
						alert('An error occurred while trying to delete this skill. Please contact your system administrator.');
		    	   }
		 	   }
			);
	   		
	}
	var reportUser = function(userId) {
			if (confirm('Are you sure you want to report this user?')) {
				$.post('controllers/UserReportController.php', {uid:userId})
					.done(function(data){
						console.log(data);
						data = $.parseJSON(data);
						if (data.status) {
							alert(data.status);
							location.reload();
						} else {
							alert(data.failure);
							location.reload();
						}
					});
			} else {
				alert('User was not banned');
			}
	}

	var requestAddFriend = function(userId){
		var json = {"uid":userId,"action":"requestAdd"};
		$.post('controllers/FriendshipController.php', json)
			.done(function(data){
				console.log(data);
				data = $.parseJSON(data);
				if(data.status){
					location.reload();
				} else { 
					alert("Failed to send request");
					location.reload();
				}
			});
	}

	var removeFriend = function(userId) { 
		var json = {"uid":userId,"action":"remove"};
		$.post('controllers/FriendshipController.php', json)
			.done(function(data){
				console.log(data);
				data = $.parseJSON(data);
				if(data.status){
					location.reload();
				} else { 
					alert("Failed to delete friend");
					location.reload();
				}
			});
	}
	$('#skills-form').submit(function(e)
			{
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
								PageChanger.loadProfileView();
							},
							error: function(jqXHR, textStatus, errorThrown) 
							{
								//if fails      
							}
						});
				e.preventDefault(); //STOP default action
			});
	$('#password-change-form').submit(function(e)
			{
				var postData = $(this).serializeArray();
				var formURL = $(this).attr("action");
				$.ajax(
						{
							url : formURL,
							type: "POST",
							data : postData,
							dataType: "json",
							success:function(data, textStatus, jqXHR) 
							{
								if(data.status === 'pass'){
									
									$('[data-role=popup]').popup('close');
									
								}else{
									
									$('#password-change-status').html('Password Change Failed');
								}
							}
						});
				e.preventDefault(); //STOP default action
			});

	function loadEndorsementForm(skillsId) {
		$('.ui-page').prepend('<div class="form-mask"></div>');
		TINY.box.show({mask:false,url:'views/EndorsementWidget.php?sid='+skillsId +'&pid='+<?php echo $_GET['id']?>, 
			openjs:function(){
				$(document).trigger('create');
			},closejs:function(){
				$('.form-mask').remove();
			},width:Math.floor($(window).width()*.8),height:Math.floor($(window).height()*.8)})
	}

	</script>

<?php }else{  ?>				
	<script type="text/javascript">
		PageChanger.loadMessageView({'messageType' : 'not_authorized'});
	</script>
<?php } ?>

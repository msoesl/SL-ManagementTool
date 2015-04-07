/**
 * This object contains functions that are of use in URL hashing.  It allows
 * the user to use the back/forward buttons and bookmark pages without
 * actually reloading the screen at all.
 *
 * To use this object, first call PageChanger.init() at some point shortly
 * after the document is ready.  This will initialize the window hashchange 
 * listener and inject the default content.
 *
 * The area that pages are loaded into is defined by _contentContainerId. 
 *
 * After initialization, feel free to call any of the other PUBLIC functions
 * in the object, which will load the content specified by the function name
 * into the content area.  These functions can be passed data that will
 * be passed to that particular screen.  This data is a JSON object array
 * in a form of key/value pairs.  
 *
 * Here's a usage example:
 *
 * var data = [{
 *	    "key": "key_string",
 *	    "value": "value_string"
 *  }, {
 *	    "key": "key_string_2"
 *	    "value": "value_string_2"
 *  }];
 *
 * PageChanger.loadPreviewWidget(data);
 *
 * This will load the preview widget page (previewWidget.php) with
 * data appended to the URL.
 *
 * It is important to note that what's inside the content container will be
 * DESTROYED when changing out the content.  This is to prevent possible
 * security issues of users accessing data they're not authorized to see.
 * The new content is always rebuilt from scratch.
 */
PageChanger = {

	/**
	 * @private
	 * Specifies the id of the content container.  This is
	 * the container that has its contents swapped out
	 * for each new hash.  This should never be modified.
	 */
	_contentContainerId: '#page-content',

	/**
	 * @private
	 * States that a dialog was opened.
	 */
	_dialogClosing: false,

	_loadIndex: 0,

	/**
	 * @public
	 * Call this function to initialize the window hashchange
	 * listener and load in the default content. It should
	 * only be called once on page load.
	 */
	init: function(){
		var me = this;

		//Bind our custom onHashChange event listener to the window
		$(window).bind('hashchange', function(e){ me._onHashChange(e);});

		$(window).bind('beforeunload', function(e){
			$('[data-role=popup]').popup('close');
		});

		//trigger a hash change event, which initializes our listener
		$(window).trigger('hashchange');
		
		return me;
	},

	/**
	 * @private
	 * The hashchange listener itself.  This is what is performed
	 * when the window's hash changes.
	 *
	 * Currently, this just empties the content container and loads
	 * the new source and data into it.
	 */
	_onHashChange: function(e){
		//get the url we need to load.  For our purposes, we assume
		//all src's are in the views directory.
		var page = $.param.fragment();
		//ignore Jquery Ui Hashing
		if(this._dialogClosing){
			this._dialogClosing = !this._dialogClosing;
			return;
		}

		//jquery UI dialog elements always contain ui, so let's check for that
		if(page.indexOf('ui') !== -1){
			//let's check the contentContainer to make sure the page refreshed
			if($.trim($(this._contentContainerId).html())){
				//doublecheck to ensure there's a popup active and we're not just crazy
				if($('.ui-popup-active').length > 0 || $('.pop').length > 0){
					this._dialogClosing = true;
					return;
				}
			}

			//if we got here, we refreshed the page with a dialog open
			page = page.replace('&ui-state=dialog', '');
		}


		//empty the content container, destroying anything inside
		$(this._contentContainerId).empty();

		//show loading spinner
		$(this._contentContainerId).append(this._getLoadingMarkup());

		var url = 'views/' + ((page === '') ? 'HomepageView.php' : page);

		var me = this;

		//load the new content into the content container
		$('#loading-'+this._loadIndex).load(url, function(){
			//detect if the load failed or not
			if($('.loading-spinner').length > 0){
				me.load('404.php');
				return;
			}
			$('#imageEditorDialog-popup').remove();
			$('#editProjectDetailsPopupDialog-popup').remove();
			$('#newThreadPopupDialog-popup').remove();
			
			$('#informationPopupDialog-popup').remove();
			$('#aboutMePopupDialog-popup').remove();
			
			$('#skillsPopupDialog-popup').remove();
			$('#passwordPopupDialog-popup').remove();
			
			$('#namePopupDialog-popup').remove();
			
			//trigger JQuery Mobile to reapply styles lost during the
			//load after the load is complete.
			$('#' + this.id).trigger('create');

			//fade new content in
			$('#' + this.id).css('opacity', '0');
			$('#' + this.id).animate({opacity: 1}, 500);
		});

		this._loadIndex++;
	},

	_getLoadingMarkup: function(){
		return '<div id="loading-'+ this._loadIndex + '">' +
			'<div class="loading-spinner">' +
				'<img src="res/images/spinner.gif"></img>' +
			'</div>' +
		'</div>';
	},

	/**
	 * @private
	 * Defines how we will parse the JSON data arrays
	 * given in the load functions of this object.  If
	 * no data is passed in, it will return an empty
	 * string.
	 */
	_parseData: function(data){
		if(data){
			var count = 0;
			var retVal = "";
			for (var key in data) {
				 if (data.hasOwnProperty(key)) {
					 if(count ==0) {
						 retVal += '?'+key+'='+data[key];
					 } else {
						 retVal += '&'+key+'='+data[key];
					 }
				 }
				 count++;
			}
			getRequest = retVal;
			return retVal;
		}else{
			return "";
		}
	},

	/**
	 * @public
	 * This function loads the preview widget into the default content,
	 * that is, what the user will see on the landing screen.  This is
	 * defined as a php file found at: 
	 *
	 * views/previewwidget.php
	 */
	loadPreviewWidget: function(data){
		var previewWidgetUrl = "previewwidget.php";
		this._dialogClosing = false;
		//add the hash which triggers an onHashChange event
		window.location.hash = '#'+previewWidgetUrl + this._parseData(data);
	},

	/**
	 * @public
	 * This function loads the project view into the default content,
	 * that is, what the user will see when looking at the specifics
	 * of a project.  This is defined as a php file found at:
	 *
	 * views/ProjectView.php
	 */
	loadProjectView: function(data){
		var projectViewUrl = "ProjectView.php";
		this._dialogClosing = false;
		var hash = '#'+projectViewUrl + this._parseData(data);
		
		//add the hash which triggers an onHashChange event
		window.location.hash = hash;
	},
	
	/**
	 * @public
	 * This function loads the project permissions view into the default content,
	 * that is, what the user will see when looking at the specifics
	 * of a project.  This is defined as a php file found at:
	 *
	 * views/ProjectPermissionsView.php
	 */
	loadProjectPermissionsView: function(data){
		var projectViewUrl = "ProjectPermissionsView.php";
		this._dialogClosing = false;
		var hash = '#'+projectViewUrl + this._parseData(data);
		
		//add the hash which triggers an onHashChange event
		window.location.hash = hash;
	},
	
	/**
	 * @public
	 * This function loads the LandingPageBannerRotator.php file into
	 * the main content area.
	 */
	loadBannerRotatorWidget: function(data) {
		var projectViewUrl = "LandingPageBannerRotator.php";
		this._dialogClosing = false;
		//add the hash which triggers an onHashChange event
		window.location.hash = '#'+projectViewUrl + this._parseData(data);
		
	},
	
	loadAdminBanView: function(data) {
		var projectViewUrl = "UserBanView.php";
		this._dialogClosing = false;
		//add the hash which triggers an onHashChange event
		window.location.hash = '#'+projectViewUrl + this._parseData(data);
	},
	
	/**
	 * @public 
	 * This function loads the HomepageView.php file into the main content area.
	 */
	loadHomepage : function (data) {
		var homepageUrl = "HomepageView.php";
		this._dialogClosing = false;
		window.location.hash = '#' + homepageUrl + this._parseData(data);
	},
	
	/**
	 * @public 
	 * This function loads the HomepageView.php file into the main content area.
	 */
	loadProfileView : function (data) {
		var profileUrl = "PersonalProfileView.php";
		//loadProfileView occurs from a UI Dialog, so we need change page to occur and dialog closing to be false.
		this._dialogClosing = false;
		window.location.hash = '#' + profileUrl + this._parseData(data);
	},

	/**
	 * @public 
	 * This function loads the HomepageView.php file into the main content area.
	 */
	loadAccountManagementView: function (data) {
		var profileUrl = "AccountManagmentView.php";
		//loadProfileView occurs from a UI Dialog, so we need change page to occur and dialog closing to be false.
		this._dialogClosing = false;
		window.location.hash = '#' + profileUrl + this._parseData(data);
	},

	load: function(url, data){
		this._dialogClosing = false;
		window.location.hash = '#' + url + this._parseData(data);
	},

	// we can add additional screens down here when it comes time to implement them.

	/**
	 * @public 
	 * This function loads the LearnMorePageView.php file into the main content area.
	 */
	loadLearnMorePage : function (data) {
		var learnMorePageUrl = "LearnMorePageView.php";
		this._dialogClosing = false;
		window.location.hash = '#' + learnMorePageUrl + this._parseData(data);
	},
	
	/**
	 * @public 
	 * This function loads the Trophy room into the content area.
	 */
	loadTrophyRoom : function (data) {
		var learnMorePageUrl = "TrophyRoom.php";
		this._dialogClosing = false;
		window.location.hash = '#' + learnMorePageUrl + this._parseData(data);
	},
	
	/**
	 * @public 
	 * This function loads the Problem Submission View into the content area.
	 */
	loadProblemSubmissionView : function (data) {
		var learnMorePageUrl = "ProblemSubmissionView.php";
		this._dialogClosing = false;
		window.location.hash = '#' + learnMorePageUrl + this._parseData(data);		
	},
	
	/**
	 * @public 
	 * This function loads the AccountCreation.php file into the main content area.
	 */
	loadaccountCreationView : function (data) {
		var accountCreationUrl = "AccountCreationView.php";
		//AccountCreation occurs from a UI Dialogue, so we need change page to occur and dialog closing to be false.
		this._dialogClosing = false;
		window.location.hash = '#' + accountCreationUrl + this._parseData(data);
	},
	
	/**
	 * @public 
	 * This function loads the AdminControlsConfigurationView.php file into the main content area.
	 */
	loadAdminControlsConfigurationView : function (data) {
		var accountCreationUrl = "AdminControlsConfigurationView.php";
		//AccountCreation occurs from a UI Dialogue, so we need change page to occur and dialogue closing to be false.
		this._dialogClosing = false;
		window.location.hash = '#' + accountCreationUrl + this._parseData(data);
	},

	/**
	 * @public 
	 * This function loads the AdminControlsConfigurationView.php file into the main content area.
	 */
	loadProblemSubmissionConfirmationView : function (data) {
		var accountCreationUrl = "ProblemSubmissionConfirmationView.php";
		//AccountCreation occurs from a UI Dialog, so we need change page to occur and dialog closing to be false.
		this._dialogClosing = false;
		window.location.hash = '#' + accountCreationUrl + this._parseData(data);
	},
	
	/**
	 * @public 
	 * This function loads the AdminControlsConfigurationView.php file into the main content area.
	 */
	loadMessageView : function (data) {
		var accountCreationUrl = "MessageView.php";
		//AccountCreation occurs from a UI Dialog, so we need change page to occur and dialog closing to be false.
		this._dialogClosing = false;
		window.location.hash = '#' + accountCreationUrl + this._parseData(data);
	},
	
	/**
	 * @public 
	 * This function loads the AccountSettingView.php file into the main content area.
	 */
	loadAccountSettingsView : function (data) {
		var accountSettingUrl = "AccountSettingsView.php";
		//AccountSeeting occurs from a UI Dialog, so we need change page to occur and dialog closing to be false.
		this._dialogClosing = false;
		window.location.hash = '#' + accountSettingUrl + this._parseData(data);
	},
	
	/**
	 * @public 
	 * This function loads the BannerManagementView.php file into the main content area.
	 */
	loadBannerManagementView : function (data) {
		var bannerUrl = "BannerManagementView.php";
		//AccountSeeting occurs from a UI Dialog, so we need change page to occur and dialog closing to be false.
		this._dialogClosing = false;
		window.location.hash = '#' + bannerUrl + this._parseData(data);
	},
	
	/**
	 * @public 
	 * This function loads the PreviewWidgetManagementView.php file into the main content area.
	 */
	loadPrevWidgetManagementView : function (data) {
		var bannerUrl = "PrevWidgetManagementView.php";
		//AccountSeeting occurs from a UI Dialog, so we need change page to occur and dialog closing to be false.
		this._dialogClosing = false;
		window.location.hash = '#' + bannerUrl + this._parseData(data);
	},
	
	/**
	 * @public 
	 * This function loads the AccountSettingView.php file into the main content area.
	 */
	loadForgottenPasswordView : function (data) {
		var accountSettingUrl = "ForgottenPasswordView.php";
		//AccountSeeting occurs from a UI Dialog, so we need change page to occur and dialog closing to be false.
		this._dialogClosing = false;
		window.location.hash = '#' + accountSettingUrl + this._parseData(data);
	},
	
	/**
	 * @public 
	 * This function loads the FriendRequestView.php file into the main content area.
	 */
	loadFriendRequestView : function (data) {
		var accountSettingUrl = "FriendRequestView.php";
		//AccountSeeting occurs from a UI Dialog, so we need change page to occur and dialog closing to be false.
		this._dialogClosing = false;
		window.location.hash = '#' + accountSettingUrl + this._parseData(data);
	},

	load404View: function(data){
		var pageUrl = "404.php";
		//AccountSeeting occurs from a UI Dialog, so we need change page to occur and dialog closing to be false.
		this._dialogClosing = false;
		window.location.hash = '#' + pageUrl + this._parseData(data);
	},
	
	/**
	 * @public 
	 * This function loads the FriendView.php file into the main content area.
	 */
	loadFriendsView : function (data) {
		var accountSettingUrl = "FriendsView.php";
		//AccountSeeting occurs from a UI Dialog, so we need change page to occur and dialog closing to be false.
		this._dialogClosing = false;
		window.location.hash = '#' + accountSettingUrl + this._parseData(data);
	},
	
	/**
	 * @public 
	 * This function loads the InviteView.php file into the main content area.
	 */
	loadInviteUserView : function (data) {
		var inviteUserUrl = "InviteUserView.php";
		//AccountSeeting occurs from a UI Dialog, so we need change page to occur and dialog closing to be false.
		this._dialogClosing = false;
		window.location.hash = '#' + inviteUserUrl + this._parseData(data);
	},
	
	/**
	 * @public 
	 * This function loads the ThinkStageProjectsView.php file into the main content area.
	 */
	loadThinkStageProjectsView : function (data) {
		var inviteUserUrl = "ThinkStageProjectsView.php";
		//AccountSeeting occurs from a UI Dialog, so we need change page to occur and dialog closing to be false.
		this._dialogClosing = false;
		window.location.hash = '#' + inviteUserUrl + this._parseData(data);
	},
	
	/**
	 * @public 
	 * This function loads the DoStageProjectsView.php file into the main content area.
	 */
	loadDoStageProjectsView : function (data) {
		var inviteUserUrl = "DoStageProjectsView.php";
		//AccountSeeting occurs from a UI Dialog, so we need change page to occur and dialog closing to be false.
		this._dialogClosing = false;
		window.location.hash = '#' + inviteUserUrl + this._parseData(data);
	},
	
	/**
	 * @public 
	 * This function loads the AchieveStageProjectsView.php file into the main content area.
	 */
	loadAchieveStageProjectsView : function (data) {
		var inviteUserUrl = "AchieveStageProjectsView.php";
		//AccountSeeting occurs from a UI Dialog, so we need change page to occur and dialog closing to be false.
		this._dialogClosing = false;
		window.location.hash = '#' + inviteUserUrl + this._parseData(data);
	},
	
	/**
	 * @public 
	 * This function loads the SuspendedUserEmailView.php file into the main content area.
	 */
	loadSuspendedUserEmailView : function (data) {
		var inviteUserUrl = "SuspendedUserEmailView.php";
		//AccountSeeting occurs from a UI Dialog, so we need change page to occur and dialog closing to be false.
		this._dialogClosing = false;
		window.location.hash = '#' + inviteUserUrl + this._parseData(data);
	},
	
	/**
	 * @public 
	 * This function loads the AllProjectsView.php file into the main content area.
	 */
	loadAllProjectsView : function (data) {
		var inviteUserUrl = "AllProjectsView.php";
		//AccountSeeting occurs from a UI Dialog, so we need change page to occur and dialog closing to be false.
		this._dialogClosing = false;
		window.location.hash = '#' + inviteUserUrl + this._parseData(data);
	},	

	/**
	 * @public 
	 * This function loads the Help view into the main content area.
	 */
	loadHelpView : function (data) {
		var inviteUserUrl = "HelpView.php";
		//AccountSeeting occurs from a UI Dialog, so we need change page to occur and dialog closing to be false.
		this._dialogClosing = false;
		window.location.hash = '#' + inviteUserUrl + this._parseData(data);
	},	

	/**
	 * @public 
	 * This function loads the Help view into the main content area.
	 */
	loadCreditsView : function (data) {
		var inviteUserUrl = "CreditsView.php";
		//AccountSeeting occurs from a UI Dialog, so we need change page to occur and dialog closing to be false.
		this._dialogClosing = false;
		window.location.hash = '#' + inviteUserUrl + this._parseData(data);
	},	

	/**
	 * @public 
	 * This function loads the AchieveStageProjectsView.php file into the main content area.
	 */
	loadProjectBannerManagementView : function (data) {
		var inviteUserUrl = "ProjectBannerManagementView.php";
		//AccountSeeting occurs from a UI Dialog, so we need change page to occur and dialog closing to be false.
		this._dialogClosing = false;
		window.location.hash = '#' + inviteUserUrl + this._parseData(data);
	},

	/*
	 * This function loads the PersonalProjectsView.php file into the main content area.
	 */
	loadPersonalProjectsView : function (data) {
		var inviteUserUrl = "PersonalProjectsView.php";
		//AccountSeeting occurs from a UI Dialog, so we need change page to occur and dialog closing to be false.
		this._dialogClosing = false;
		window.location.hash = '#' + inviteUserUrl + this._parseData(data);
	},	
	
	/**
	 * @public 
	 * This function loads the PersonalCompletedProjectsView.php file into the main content area.
	 */
	loadPersonalCompletedProjectsView : function (data) {
		var inviteUserUrl = "PersonalCompletedProjectsView.php";
		//AccountSeeting occurs from a UI Dialog, so we need change page to occur and dialog closing to be false.
		this._dialogClosing = false;
		window.location.hash = '#' + inviteUserUrl + this._parseData(data);
	},	
	
	/**
	 * @public 
	 * This function loads the PersonalDiscontinuedProjectsView.php file into the main content area.
	 */
	loadPersonalDiscontinuedProjectsView : function (data) {
		var inviteUserUrl = "PersonalDiscontinuedProjectsView.php";
		//AccountSeeting occurs from a UI Dialog, so we need change page to occur and dialog closing to be false.
		this._dialogClosing = false;
		window.location.hash = '#' + inviteUserUrl + this._parseData(data);
	},
	
	/**
	 * @public 
	 * This function loads the HelpPageManagementView.php file into the main content area.
	 */
	loadHelpPageManagementView : function (data) {
		var inviteUserUrl = "HelpPageManagementView.php";
		//AccountSeeting occurs from a UI Dialog, so we need change page to occur and dialog closing to be false.
		this._dialogClosing = false;
		window.location.hash = '#' + inviteUserUrl + this._parseData(data);
	}
}//end PageChanger


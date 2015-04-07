<?php
require_once ('import/headimportviews.php');
?>
<div class="white-background">
	<!-- Title of page -->
	<div class="learn-more-content learn-more-title">
		<h1>Service Management Tool</h1>
		<hr>
	</div>

	<!-- Four stage graphic -->
	<div class="learn-more-content" id="learn_more_four_step">
		<div id="learn_more_four_step_graphic">
			<p>
				<img src="res/images/learn_more_fourstage.png" style='width: 100%;'
					border="0" alt="Null">
			</p>
		</div>
		<div id="learn_more_four_step_content"></div>
	</div>

	<!-- Observe Section -->
	<div
		class="learn-more-content learn-more-header observe-background-color">
		<h1>Step 1: Observer</h1>
		<h2>How do you need to be different to achieve your goals?</h2>
	</div>
	<div class="learn-more-content" id="learn_more_observe">
		<div class="learn-more-left learn-more-text learn-more-text-left">
			<h3 style='color:#C0504E'>Listening</h3>
			<hr>
			<p style='font-size:14pt'>In order to be an effective observer you must have a deep commitment to listening intently to others. Listening also encompasses getting in touch with one's inner voice, and seeking to understand what one&#39;s body, spirit, and mind are communicating. </p>
			</br>
			<h3 style='color:#C0504E'>Empathy</h3>
			<hr>
			<p style='font-size:14pt'>People need to be accepted and recognized for their special and unique spirit. This is important for the observer as they should assume the good intention of colleagues even if ideas must be rejected.</p>
			</br>
			<h3 style='color:#C0504E'>Awareness</h3>
			<hr>
			<p style='font-size:14pt'>General awareness, and especially self-awareness strengthens the servant-leader. By observing the world around us we gain awareness of other&#39;s needs. Making a commitment to awareness can be scary but it is integral to being an effective servant-leader.</p>
		</div>
		<div class="learn-more-right learn-more-picture">
			<p>
				<img src="res/images/learn_more_observe.jpg"
					class="learn-more-image" border="0" alt="Null">
			</p>
		</div>
	</div>

	<!-- Think Section -->
	<div
		class="learn-more-content learn-more-header think-background-color">
		<h1>Step 2: Thinker</h1>
		<h2>Your way of being</h2>
	</div>
	<div class="learn-more-content" id="learn_more_think">
		<div class="learn-more-left learn-more-picture">
			<p>
				<img src="res/images/learn_more_think.jpg" class="learn-more-image"
					border="0" alt="Null">
			</p>
		</div>
		<div class="learn-more-right learn-more-text  learn-more-text-right">
			<h3 style='color:#9BBB58'>Conceptualization</h3>
			<hr>
			<p style='font-size:14pt'>Servant-leaders seek to nurture their abilities to &quot;dream great dreams.&quot; This creates a mindset that encourages thinking outside of the box. A balance must be found between conceptualization and day-to-day thinking in order to be an effective leader.</p>
			</br>
			<h3 style='color:#9BBB58'>Foresight</h3>
			<hr>
			<p style='font-size:14pt'>The intuitive mind of the servant-leader embraces foresight. This is the ability to take lessons from the past and apply them to potential issues that will happen in the future. Thinking of possible problems creates a proactive atmosphere.</p>
		</div>
	</div>

	<!-- Help Section -->
	<div class="learn-more-content learn-more-header help-background-color">
		<h1>Step 3: Doer</h1>
		<h2>Your actions</h2>
	</div>
	<div class="learn-more-content" id="learn_more_help">
		<div class="learn-more-left learn-more-text learn-more-text-left">
			<h3 style='color:#8064A1'>Healing</h3>
			<hr>
			<p style='font-size:14pt'>Healing oneself and others is a great strength of the servant-leader. Learning to heal is a powerful force for transformation and being an effective leader.</p>
			</br>
			<h3 style='color:#8064A1'>Persuasion</h3>
			<hr>
			<p style='font-size:14pt'>Servant-leaders rely on persuasion, rather than positional authority in making decisions. Convincing others instead of coercing compliance is one of the clearest distinctions between the traditional model of leadership and the servant-leader.</p>
		</div>
		<div class="learn-more-right learn-more-picture">
			<p>
				<img src="res/images/learn_more_help.jpg" class="learn-more-image"
					border="0" alt="Null">
			</p>
		</div>
	</div>

	<!-- Achieve Section -->
	<div
		class="learn-more-content learn-more-header achieve-background-color">
		<h1>Step 4: Achiever</h1>
		<h2>Your results</h2>
	</div>
	<div class="learn-more-content" id="learn_more_achieve">
		<div class="learn-more-left learn-more-picture">
			<p>
				<img src="res/images/learn_more_achieve.jpg"
					class="learn-more-image" border="0" alt="Null">
			</p>
		</div>
		<div class="learn-more-right learn-more-text learn-more-text-right">		
			<h3 style='color:#4AACC5'>Stewardship</h3>
			<hr>
			<p style='font-size:14pt'>When you have committed to serving the needs of others, stewardship has been achieved. The servant-leader uses stewardship to encourage others to work towards a greater good in society.</p>
			</br>
			<h3 style='color:#4AACC5'>Commitment to the Growth of People</h3>
			<hr>
			<p style='font-size:14pt'>Servant-leaders encourage their colleagues to grow professionally, personally, and spiritually. This commitment to every individual in an organization makes for a successful servant-leader.</p>
			</br>
			<h3 style='color:#4AACC5'>Building Community</h3>
			<hr>
			<p style='font-size:14pt'>Having a community feel in a group or workplace is key to being an effective servant-leader. Working collaboratively leads to a healthy organization. Leaders and workers that build relationships achieve a successful atmosphere.</p>
		</div>
	</div>

	<!-- Extra Section -->
	<div class="learn-more-content learn-more-title">
		<h2>Where do I go now?</h2>
		<hr>
	</div>
	<div class="learn-more-content" id="learn_more_extra">
		Extra content
		<table class="center-in-container">
			<tr>
				<td>
					<button onclick = 'PageChanger.loadProblemSubmissionView()' id="learn-more-create-button" data-theme="a">Create Project</button>
				</td>
				<td>
					<button id="learn-more-view-button" data-theme="a">View Existing Projects</button>
				</td>
			</tr>
		</table>
	</div>
</div>
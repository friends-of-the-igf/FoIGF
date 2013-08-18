<div id="ses-wrap">
	<% with MeetingSession %>
	<div class="row">
		<div class="span12">
			<h3>$Title</h3>
			<h4 class="subtext">
				$Date.Long - A <a>$Type.Name</a> on <a>$Topic.Name</a> in <a>$Meeting.Location.Name</a>
			</h4>
		</div>	
	</div>
	<div class="row">
		<div class="span8 main">	
			<% if Videos %>
			<% loop Videos %>
				<div class="video">
					$getVideo
				</div>
			<% end_loop %>
			<% end_if %>
			<div>
				<a class="btn"><b>Read full session transcript</b></a> <a class="btn"><b>View Original proposal</b></a>
			</div>
			<div class="content">
				$Content
			</div>
		</div>
		
		<div class="span4">
			<div class="row-fluid social-icons">    
				<div class="span3">
					 <span class='st_twitter_vcount' displayText='Tweet'></span>
				</div>
				<div class="span3">
					 <span class='st_facebook_vcount' displayText='Facebook'></span>
				</div>
				<div class="span3">
					   <span class='st_email_vcount' displayText='Email'></span>
				</div>
				<div class="span3">
					<img src="http://placehold.it/60x60">
				</div>
			</div>
			<div class="session-side">
				<h5>Tagged<h5/>
				<% loop TagsCollection %>
					<a href="$Link">$Tag</a><% if not Last %>,<% end_if %>
				<% end_loop %>
			</div>
			<div class="session-side">
				<% if Speakers %>
					<h5>Speakers</h5>
					<% loop Speakers %>
						<div class='row-fluid'>
							<div class='span3'>
								<img src="http://placehold.it/50x50">
							</div>
							<div class='span9'>
								$Name<br/>
								<a>$MeetingSessions.Count</a>
							</div>
						</div>
					<% end_loop %>
				<% end_if %>
			</div>
			<div class="sessions">
				<% if RelatedSessions %>
					<h5>Related Sessions</h5>
					<% loop RelatedSessions %>
						<% include Session %>
					<% end_loop %>
				<% end_if %>
			</div>
		</div>
	</div>
</div>
	<% end_with %>
</div>

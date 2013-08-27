<div id="ses-wrap">
	<% with MeetingSession %>
	<div class="row">
		<div class="span12">
			<h3>$Title</h3>
			<h4 class="subtext">
				$Date.Long - A <a href="$Type.Link">$Type.Name</a> on <a href="$Topic.Link">$Topic.Name</a> in <a href="$Meeting.Location.Link">$Meeting.Location.Name</a>
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
				<% if Transcript %> <a href="$Transcript.Link" class="btn"><b>Read full session transcript</b></a> 
				<% else_if  TranscriptContent %><% if Videos || Content %>  <a href="transcript/$ID" class="btn" target="_blank"><b>Read full session transcript</b></a> <% end_if %><% end_if %>
				<% if ProposalLink %><a href="$ProposalLink" class="btn" target="_blank"><b>View Original proposal</b></a><% end_if %>
			</div>
			<div class="content">
				<% if Content %>
					<h4>Agenda</h4>
					$Content
				<% else_if not Videos && not Content && $TranscriptContent %>
					<h4> Session Transcript </h4>
					$TranscriptContent
				<% end_if %>
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
				<div class="span3 view">
					<div class="view-count">
						<p class="text-center">
							<b>$Views</b><br />view<% if Views != 1 %>s<% end_if %>
						</p>
					</div>
				</div>
			</div>
			<div class="session-side">
				<% if TagsCollection %>
					<h5>Tagged</h5>
					<% loop TagsCollection %>
						<a href="$Link">$Tag</a><% if not Last %>,<% end_if %>
					<% end_loop %>
				<% end_if %>
			</div>
			<div class="session-side">
				<% if Speakers %>
					<h5>Speakers</h5>
					<% loop Speakers %>
						<div class='row-fluid speaker'>
							<div class='span3'>
								<% if ProfilePhoto %>
									$ProfilePhoto.CroppedImage(50,50)
								<% end_if %>
							</div>
							<div class='span9'>
								$Name<br/>
								<a>$MeetingSessions.Count Sessions</a>
							</div>
						</div>
					<% end_loop %>
				<% end_if %>
			</div>
			<div class="sessions">
				
					<h5>Related Sessions</h5>
					<% loop getRelatedSessions %>
						<% include Session %>
					<% end_loop %>
			
			</div>
		</div>
	</div>
</div>
	<% end_with %>
</div>

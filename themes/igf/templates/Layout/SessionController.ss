<div id="ses-wrap">
	<% with MeetingSession %>
	<div class="row">
		<div class="span12">
			<h3>$Title</h3>
			<h4 class="subtext">
				$Date.Long - A <a title="Search Session by $Type.Name" href="$Type.Link">$Type.Name</a> on <a title=" Search Session by $Topic.Name" href="$Topic.Link">$Topic.Name</a> in <a title="Search Session by $Meeting.Location.Name" href="$Meeting.Location.Link">$Meeting.Location.Name</a>
			</h4>
		</div>	
	</div>
	<div class="row">
		<div class="span8 main">	
			<% if Videos %>
			<div class="vid-nav">
				<ul>
					<% loop LangVideos %>
					<li class="<% if First %> current <% end_if %>" data-lang="$Language"><a>$Language</a></li>
					<% end_loop %>
				</ul>
			</div>
			<% loop LangVideos %>
				<div class="video-wrap $Language">
					<% loop Videos %>
						<div class='video'>
							$getVideo
						</div>
					<% end_loop %>
				</div>
			<% end_loop %>

			<% end_if %>
			<div>
				<% if Transcripts %>
					<% loop Transcripts %>
						<% if Transcript %> 
						<a title="Read full session transcript" href="$Transcript.Link" class="btn tran $Language.Name"><b>Read full session transcript</b></a> 
						<% else_if Content %>
							<% if Up.Videos || Up.Content %>  
								<a title="Read full session transcript" href="transcript/$ID" class="btn tran $Language.Name" target="_blank"><b>Read full session transcript</b></a> 
							<% end_if %>
						<% end_if %>
					<% end_loop %>
				<% end_if %>
				<% if ProposalLink %><a title="View Original proposal" href="$ProposalLink" class="btn" target="_blank"><b>View Original proposal</b></a>
				<% else_if Proposal %><a title="View Original proposal" href="$Proposal.Link" class="btn" target="_blank"><b>View Original proposal</b></a>
				<% else_if ProposalContent %><a title="View Original proposal" href="proposal/$ID" class="btn" target="_blank"><b>View Original proposal</b></a>
				<% end_if %>
				<% if Report %>
					<a title="View report" href="$Report.Link" class="btn" target="_blank"><b>View report</b></a>
				<% else_if ReportContent %>
					<a title="View report" href="report/$ID" class="btn" target="_blank"><b>View report</b></a>
				<% end_if %>
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
			<div class="row-fluid social-icons session-side">    
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
			<% if Top.isResearcher %>
				<div class="session-side">
					<h5>Open Calais Content Processing</h5>
					$Top.OpenCalaisForm
				</div>
			<% end_if %>
			<div class="session-side">
				<% if TagsCollection %>
					<h5>Tagged</h5>
					<% loop TagsCollection %>
						<a title="Search Session by $Tag" href="$Link">$Tag</a><% if not Last %>,<% end_if %>
					<% end_loop %>
				<% end_if %>
			</div>
			<% if Taggable %>
				<div class='add-tags'>
					$Top.TagForm
				</div>
			<% end_if %>
			<% if Organiser && $Top.SiteConfig.ShowOrganisers %>
				<h5>Organiser</h5>
				<% with Organiser %>
					<div class="session-side">
						<div class='row-fluid speaker'>
								<div class='span9'>
									<% if BioLink %>
										<a title="Search Session by $Name" class="no-dec" href="$Organiser.BioLink">$Name</a><br/>
									<% else %>
										$Name<br/>
									<% end_if %>
									<% if Organisation %><i>$Organisation.Title</i><br/><% end_if %>
									<a title="Search Session by $MeetingSessions.Count Sessions" href="$Link">$MeetingSessions.Count Sessions</a>
									
								</div>
							</div>
					</div>
				<% end_with %>
			<% end_if %>
			<div class="session-side">
				<% if Speakers %>
					<h5>Speakers</h5>
					<% loop Speakers %>
						<div class='row-fluid speaker'>
							<div class='span9'>
								<% if BioLink %>
									<a title="Search Session by $Name" class="no-dec" href="$Organiser.BioLink">$Name</a><br/>
								<% else %>
									$Name<br/>
								<% end_if %>
								<% if Organisation %><i>$Organisation.Title</i><br/><% end_if %>
								<a title="Search Session by $MeetingSessions.Count Sessions" href="$Link">$MeetingSessions.Count Sessions</a>
							</div>
						</div>
					<% end_loop %>
				<% end_if %>
			</div>
			<div class="sessions">
				<% if getRelatedSessions %>
					<h5>Related Sessions</h5>
					<% loop getRelatedSessions %>
						<% include Session %>
					<% end_loop %>
				<% end_if %>
			
			</div>

		</div>
	</div>
</div>
	<% end_with %>
</div>

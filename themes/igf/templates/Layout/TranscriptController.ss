<div id="ses-wrap">
	<% with MeetingSession %>
	<div class="row">
		<div class="span12">
			<h3>$Title</h3>
			<h4 class="subtext">
				$Date.Long - A <a title="Search Session by $Type.Name" href="$Type.Link">$Type.Name</a> on <a title="Search Session by $Topic.Name" href="$Topic.Link">$Topic.Name</a> in <a title="Search Session by $Meeting.Location.Name" href="$Meeting.Location.Link">$Meeting.Location.Name</a>
			</h4>
			<div class="row">
				
					<div class="span12"> Also available in: 
					<% loop Up.otherLanguages %>
						<% if Transcript %> 
							<a href="$Transcript.URL">$Language.Name</a><% if not Last %>, <% end_if %>
						<% else %>
							<a href="$Link">$Language.Name</a><% if not Last %>, <% end_if %>
						<% end_if %>
					<% end_loop %>
					</div>
			
				<div class="span10">
					<h5>Full Session Transcript</h5>
				</div>
				<div class="span2">
					<a title="Back to Session" href="$Link">Back to Session</a>
					<% end_with %>
				</div>
			</div>
			<% with Transcript %>
				$Content
			<% end_with %>
		</div>	
	</div>
	
</div>
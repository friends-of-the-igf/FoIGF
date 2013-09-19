<div id="ses-wrap">
	<% with MeetingSession %>
	<div class="row">
		<div class="span12">
			<h3>$Title</h3>
			<h4 class="subtext">
				$Date.Long - A <a title="$Type.Name" href="$Type.Link">$Type.Name</a> on <a title="$Topic.Name" href="$Topic.Link">$Topic.Name</a> in <a title="$Meeting.Location.Name" href="$Meeting.Location.Link">$Meeting.Location.Name</a>
			</h4>
			<div class="row">
				<div class="span10">
					<h5>Full Session Transcript</h5>
				</div>
				<div class="span2">
					<a title="Back to Session" href="$Link">Back to Session</a>
				</div>
			</div>
			$TranscriptContent
		</div>	
	</div>
	<% end_with %>
</div>
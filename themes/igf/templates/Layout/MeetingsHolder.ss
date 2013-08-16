
<div id="meetings-wrap" class="row thumbnails">
	<% if Meetings %>
	<% loop Meetings %>
	<div class="span3">
		<a href="$Link" class="thumbnail">
			<img src="http://placehold.it/400x300" />
			<div class="text-wrap">
				<h4>$Location.Name</h4>
				<p><span class="subtext"><b>$StartDate.Nice - $EndDate.Nice</b></span><br/>$MeetingSessions.Count sessions</p>
			</div>
		</a>
	</div>
	<% end_loop %>
	<% end_if %>
</div>

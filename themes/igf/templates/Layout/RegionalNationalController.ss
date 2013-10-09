<div id="Regional" data-url="$Link"> 
	<% if Regions %>
	<ul>
		<% loop Regions %>
		<li><a class="region" data-id="$ID" > $Title </a></li>
		<% end_loop %>
	</ul>
	<% end_if %>
	<div id="Regional-Meetings">
		<% with MeetingsData %>
		<% include RegionalMeetings %>
		<% end_with %>
	</div>
</div>


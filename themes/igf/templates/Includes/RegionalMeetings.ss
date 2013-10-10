<p><h3>$RegCount Regional and $NatCount National IGFs<% if $Region %> in $Region <% end_if %></h3></p>
<% if Meetings %>
	<div class='row-fluid'>
	<% loop Meetings %>
		<div class='span3 regional-tile <% if $MultipleOf(5) %> first <% end_if %> '>
			<% include RegionalMeetingTile %>
		</div>
	<% end_loop %>
	</div>
<% end_if %>
<% if OtherMeetings %>
<p><h3>Other Meetings</h3></p>
	<div class='row-fluid'>
	<% loop OtherMeetings %>
		<div class='span3 regional-tile <% if $MultipleOf(5) %> first <% end_if %> '>
			<% include RegionalMeetingTile %>
		</div>
	<% end_loop %>
	</div>
<% end_if %>
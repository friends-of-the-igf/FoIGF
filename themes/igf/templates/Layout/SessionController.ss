<div class="row">
	<% with MeetingSession %>
	<div class="span12">
		<h1>$Title</h1>
		<% if TagsCollection %><p>Tags: <% loop TagsCollection %><a href="$Link">$Tag</a> <% end_loop %></p><% end_if %>
	</div>
	<% end_with %>
</div>
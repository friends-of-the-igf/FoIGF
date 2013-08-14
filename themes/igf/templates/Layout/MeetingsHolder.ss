<div class="row">
	<% if Meetings %>
	<% loop Meetings %>
	<div class="span4">
		<p>$Title : $StartDate.Nice - $EndDate.Nice</p>
		<a href="$Link">Link</a>
	</div>
	<% end_loop %>
	<% end_if %>
</div>
<div class="row">
	<% if Sessions %>
	<% loop Sessions %>
	<div class="span4">
		<p>$Title - $Date.Nice</p>
		<a href="$Link">Link</a>
	</div>
	<% end_loop %>
	<% end_if %>
</div>
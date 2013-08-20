
<div class="row-fluid">
	<div id='filter-wrap' class="span3">
		<div class="filter">
			<h5>Filter Sessions by</h5>
			$FilterForm
		</div>
		<div class='tags'>
			<h5>View Sessions tagged</h5>
			 <% loop allTags %><a href="$Link" style="font-size: {$Size};">$Tag</a> <% end_loop %>
			</div>
	</div>
	<div class="sessions span9">
		<div class="heading">
			<% if isSearch %>
				<% with getCount %>
					<h3>$Sessions Sessions in $Meetings meetings match your criteria</h3>
				<% end_with %>
			<% end_if %>
		</div>
		<div class="row-fluid">
			<% if hasSessions %>
			<% loop getSessions %>
					<div class="col span4">
					 	<% loop Columns %>
					 		<% include Session %>
					 	<% end_loop %>
					 </div>
					<% end_loop %>
			<% end_if %>
		</div>
		<div class="row-fluid">
			<div class="offset4 span4 button">
				<a class="btn btn-primary">Show more</a>
			</div>
		</div>
	</div>
</div>
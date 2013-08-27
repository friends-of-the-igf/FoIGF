
<div class="row-fluid">
	<div id='filter-wrap' class="span3">
		<div class="filter">
			<h5 id="filter-form">Filter Sessions</h5>
			$FilterForm
		</div>
		<div class='tags'>
			<h5 id="tag-head">View Tags</h5>
			<div id="tag-list">
			 	<% loop allTags %><a href="$Link" style="font-size: {$Size};">$Tag</a> <% end_loop %>
			</div>
		</div>

	</div>
	<div class="sessions span9">
		<div class="heading">
			<% with getCount %>
				<h3>$Sessions Sessions in $Meetings Meetings match your criteria</h3>
			<% end_with %>
		</div>
		<div class="row-fluid">
			<div class="span12">
			<% if hasSessions %>
					<% loop getSessions %>
						<div class="col span4">
					 		<% loop Column %>
					 			<% include Session %>
					 		<% end_loop %>
					 	</div>
					<% end_loop %>
				<% end_if %>
			</div>	
		</div>
		<div class="row-fluid more">
			<div class=" span4 button">
				<a id="prev" class="btn btn-primary">Show Less</a>
			</div>
			<div class=" span4 button">
				<a id="first-next" class="btn btn-primary">Show More</a>
				<a id="last-prev" class="btn btn-primary">Show Less</a>
			</div>
			<div class=" span4 button">
				<a id="next" class="btn btn-primary"> Show More</a>
			</div>
		</div>
	</div>
</div>
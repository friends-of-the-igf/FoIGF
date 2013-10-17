
<div class="row-fluid">
	<div id='filter-wrap' class="span3">
		<div class="filter">
			<h5 id="filter-form">Filter Sessions <span class="arrow">&#9650</span></h5>
			$FilterForm
			<a title="Clear Filter" href="$URLSegment" class="underline clear"> clear filter </a>
			<div class='tags'>
			<h5 id="tag-head">View sessions by tag <span class="arrow">&#9660</span></h5>
			<div id="tag-list">
				<% loop popularTags %><a title="Search Session by $Tag" href="$Link" style="font-size: {$Size};" >$Tag</a> <% end_loop %>
			</div>
		</div>
	</div>
		
	</div>
	<div class="sessions span9">
		<div class="heading">
			<% with getCount %>
				<h3>$Sessions Sessions in $Meetings Meetings match your criteria</h3>
			<% end_with %>
		</div>
		<div id="sessions-paged" class="row-fluid" data-pages="$PageCount" data-filter={$getFilter}>
			<div class="span12">
			<% if getSessions %>
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
		<div class="more">
			<div class="row-fluid">
				<div class=" span4 button">
					<% if PreviousPage %>
					<a title="Previous Page" id="prev" class="btn btn-primary" href="$previousPage">Prev</a>
					<% end_if %>
				</div>
				<div class="offset4 span4 button">
					<% if NextPage %>
						<a title="Next" id="next" class="btn btn-primary" href="$nextPage">Next</a>
					<% end_if %>
				</div>
			</div>
			<div class="pagination pagination-centered">
				<ul class='pages'>
					
				</ul>
			</div>
		</div>

	</div>
</div>
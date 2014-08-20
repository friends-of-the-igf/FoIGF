
<div class="row-fluid">
	<div id='filter-wrap' class="span3">
			<% if CurrentKeyword %>
			<div class="current-search">
				<p>Searching for sessions with keywords:</p>
				<h4>"$CurrentKeyword"</h4>
				<% if CurrentTag %>
				<p>and with tag: </p>
				<h4>"$CurrentTag" <a href="#" class="clear-tag"><i class="fa fa-times-circle"></i></a></h4>
				<% end_if %>
			</div>
			<% else %>
			<% if CurrentTag %>
				<div class="current-search">
					<p>Searching for sessions with tag: </p>
					<h4>"$CurrentTag" <a href="#" class="clear-tag"><i class="fa fa-times-circle"></i></a></h4>
				</div>
			<% end_if %>
			<% end_if %>

		<div class="filter">
			<h5 id="filter-form">Filter Sessions <span class="arrow">&#9650</span></h5>
			$FilterForm
			<div class='tags'>
			<h5 id="tag-head">View sessions by tag <span class="arrow">&#9660</span></h5>
			<div id="tag-list">
				<% loop AllTags %><a title="Search Session by $Title" href="$Link" style="font-size: {$Size};" >$Title</a> <% end_loop %>
			</div>
		</div>
	</div>
		
	</div>
	<div class="sessions span9">
		<div class="heading">
				<h3><span id="s-count"></span> Sessions in <span id="m-count"></span> Meetings match your criteria</h3>	
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
		<% with getCount %>
			<div id="counts" data-sessions="$Sessions" data-meetings="$Meetings">
		<% end_with %>
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
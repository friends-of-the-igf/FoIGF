<div class="row-fluid topic-filt">
	<div class="span4">
		<% if Topics %>
			<ul>
				<li><a id="all" href='$Link'>All Sessions</a></li>
				<% loop Topics.Limit(4) %>
				<li><a class="topic" data-id="$ID" href='$Link'>$Name</a></li>
				<% end_loop %>
			</ul>
		<% end_if %>
	</div>
	<div class="span4">
		<% if Topics %>
			<ul>
				<% loop Topics.Limit(5, 5) %>
				<li><a class="topic" data-id="$ID"  href='$Link'>$Name</a></li>
				<% end_loop %>
			</ul>
		<% end_if %>
	</div>
	<div class="span4">
		<% if Topics %>
			<ul>
				<% loop Topics.Limit(5, 10) %>
				<li><a class="topic" data-id="$ID"  href='$Link'>$Name</a></li>
				<% end_loop %>
			</ul>
		<% end_if %>
	</div>
</div>
<div class="row-fluid">
	<div id='filter-wrap' class="span3">
		<div class="filter">
			<h5 id="filter-form">Filter Sessions <span class="arrow">&#9660</span></h5>
			$FilterForm
			<div class='tags'>
			<h5 id="tag-head">View sessions by tag <span class="arrow">&#9650</span></h5>
			<div id="tag-list">
				<% loop popularTags %><a href="$Link" style="font-size: {$Size};" >$Tag</a> <% end_loop %>
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
					<a id="prev" class="btn btn-primary">Prev</a>
				</div>
				<div class="offset4 span4 button">
					<a id="next" class="btn btn-primary">Next</a>
				</div>
			</div>
			<div class="pagination pagination-centered">
				<ul class='pages'>
					
				</ul>
			</div>
		</div>

	</div>
</div>
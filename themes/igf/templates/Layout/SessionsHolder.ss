
<div class="row-fluid">
	<div id='filter-wrap' class="span3">
		<div class="filter">
			$FilterForm
		</div>
	</div>
	<div class="sessions span9">
		<div class="heading">
			<h3>120 Sessions in 4 meetings match your criteria</h3>
		</div>
		<div class="row-fluid">
			<% if Sessions %>
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
				<a class="btn btn-primary">Show 18 more sessions</a>
			</div>
		</div>
	</div>
</div>
<div id="sessionholder">
	<div class="row">
		<div id='filter-wrap' class="span3">
			<div class="filter">
				$FilterForm
			</div>
		</div>
		<div id="session-wrap" class="span9">
			<div  class="sessions">
				<div class="heading">
					<h3>120 Sessions in 4 meetings match your criteria</h3>
				</div>
				<div class="ses">
					<% if Sessions %>
					<% loop getSessions %>
							<div class="col span3">
							 	<% loop Columns %>
							 		<% include Session %>
							 	<% end_loop %>
							 </div>
							<% end_loop %>
					<% end_if %>
				</div>
			</div>
		</div>
	</div>
</div>
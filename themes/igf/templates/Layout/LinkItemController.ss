<div id="ses-wrap">
	<% with Item %>
	<div class="row">
		<div class="span12">
			<h4>$Meeting.Title in <a title="Search Session by $Meeting.Location.Name" href="$Meeting.Location.Link">$Meeting.Location.Name</a></h4>
			<h5 class="subtext">
				$Meeting.StartDate.Format(d F) - $Meeting.EndDate.Long()
			</h5>
			<div class="row">
				<div class="span10">
					<h5>$Title</h5>
				</div>
				<div class="span2">
					<a title="Back to Meeting" href="$Meeting.Link">Back to Meeting</a>
				</div>
			</div>
			$Content
		</div>	
	</div>
	<% end_with %>
</div>
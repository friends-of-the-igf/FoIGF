<% with Meeting %>
<div class="row-fluid header">
	<div class="span2">
		<img src="http://placehold.it/150x150" />
	</div>
	<div class="span10">
		<h3>$Title</h3>
		<h4 class="subtext">$StartDate.Nice - $EndDate.Nice in <a>$Location.Name</a></h4>
	</div>		
</div>
<div class="row-fluid links">
	<div class="span3">
		<div class="row-fluid social">
			<div class="span4">
				<div>
					<a href="https://twitter.com/share" class="twitter-share-button" data-count="vertical">Tweet</a>
				</div>
			</div>
			<div class="span4">
				<div class="fb-like" data-href="http://developers.facebook.com/docs/reference/plugins/like" data-width="450" data-layout="box_count" data-show-faces="true" data-send="false"></div>
			</div>
			<div class="span4">
				<img src="http://placehold.it/60x60" />
			</div>
		</div>
	</div>
	<div class="span3">
		<h5>Meeting Information</h5>
		<a>Link One</a><br/>
		<a>Link One</a><br/>
		<a>Link One</a><br/>
	</div>
	<div class="span3">
		<h5>Topic's Covered</h5>
	</div>
	<div class="span3">
		<h5>Sessions tagged</h5>
	</div>
</div>

<div class="row-fluid">
	<div class="span8">
		<h3>$MeetingSessions.Count Session<% if $MeetingSessions.Count != 1 %>s<% end_if %></h3>
	</div>
	<div class="span4">
		<a href='$sessionLink' class="btn btn-primary">Filter Sessions</a>
	</div>
</div>
<div class="row-fluid">
	<div class="span12">
	<% if MeetingSessions %>
		<div class="row-fluid sessions">
			<% loop MeetingSessions %>
			<div class="span3">
				<% include Session %>
			</div>
			<% end_loop %>
		</div>
	<% end_if %>
	</div>
</div>
<% end_with %>
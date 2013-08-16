<% with Meeting %>
<div class="row">
	<div class="span12">
		<h1>$Title</h1>
	</div>
</div>
<div id="speakers-wrap" class="row">
	<div class="span9">
	<h3>$Speakers.Count Speaker<% if $Speakers.Count != 1 %>s<% end_if %></h3>
	<% if Speakers %>
		<div class="row thumbnails">
			<% loop Speakers %>
			<div class="span3">
				<a href="$Link" class="thumbnail">
					<img src="http://placehold.it/50x50" />
					<p>$Name</p>
					<p>20 sessions</p>
				</a>
			</div>
			<div class="span3">
				<a href="$Link" class="thumbnail">
					<img src="http://placehold.it/50x50" />
					<p>$Name</p>
					<p>20 sessions</p>
				</a>
			</div>
			<div class="span3">
				<a href="$Link" class="thumbnail">
					<img src="http://placehold.it/50x50" />
					<p>$Name</p>
					<p>20 sessions</p>
				</a>
			</div>
			<% end_loop %>
		</div>
	<% end_if %>
	</div>
	<div class="span3">
		<p>test</p>
		<% loop allTags %><p>$Tag - $Weight</p><% end_loop %>
	</div>
</div>
<div class="row-fluid">
	<div class="span8">
		<h3>$MeetingSessions.Count Session<% if $MeetingSessions.Count != 1 %>s<% end_if %></h3>
	</div>
	<div class="span4">
		<a href='#' class="btn btn-primary">Filter Sessions</a>
	</div>
</div>
<div class="row-fluid">
	<div class="span12">
	<% if MeetingSessions %>
		<div class="row-fluid">
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
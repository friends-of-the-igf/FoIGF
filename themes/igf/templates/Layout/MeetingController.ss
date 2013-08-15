<% with Meeting %>
<div class="row">
	<div class="span12">
		<h1>$Title</h1>
	</div>
</div>
<div id="speakers-wrap" class="row">
	<div class="span9">
	<h4>$Speakers.Count Speaker<% if $Speakers.Count != 1 %>s<% end_if %></h4>
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
					<img src="http://placehold.it/400x300" />
					<p>$Name</p>
					<p>20 sessions</p>
				</a>
			</div>
			<div class="span3">
				<a href="$Link" class="thumbnail">
					<img src="http://placehold.it/400x300" />
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
	</div>
</div>
<div class="row">
	<div class="span12">
		<h1>$Title</h1>
	</div>
</div>
<% end_with %>
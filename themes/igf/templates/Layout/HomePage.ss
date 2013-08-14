<div id="homepage">
	<div class="row content">
		<div class="span1 social">
			<img src="http://placehold.it/60x65">
			<img src="http://placehold.it/60x65">
		</div>
		<div class="span11 text">
		$Content
		</div>
	</div>
	<div class="twitter">
		<img src="http://placehold.it/940x130">
	</div>
	<div class="speakers">
		<div class="row heading">
			<div class="span8">
			<h3>Recently added speakers</h3>
			</div>
			<div class="span4 link">
				<a>See all speakers</a>
			</div>
		</div>
		<div>
			<% loop getSpeakers %>
				<% include Speakers %>
			<% end_loop %>
		</div>
	</div>
	<div class="sessions">
		<div class="row heading">
			<div class="span8">
			<h3>Recently added sessions</h3>
			</div>
			<div class="span4 link">
				<a>Browse Sessions</a>
			</div>
		</div>
		<div class="row ses">
			<% loop getSessions %>
			<div class="span3">
			 	<% loop col %>
			 		<% include Sessions %>
			 	<% end_loop %>
			 </div>
			<% end_loop %>
		</div>
	</div>
</div>
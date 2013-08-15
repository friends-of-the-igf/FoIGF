<div class="row-fluid content">
	<div class="span1 social">
		<div class="fb-like" data-href="http://developers.facebook.com/docs/reference/plugins/like" data-width="450" data-layout="box_count" data-show-faces="true" data-send="false"></div>
		<div>
			<a href="https://twitter.com/share" class="twitter-share-button" data-count="vertical">Tweet</a>
		</div>
	</div>
	<div class="span11 text">
	$Content
	</div>
</div>
<div class="sessions">
	<div class="row-fluid heading">
		<div class="span8">
		<h3>Recently added sessions</h3>
		</div>
		<div class="span4 link">
			<a href='$sessionLink' class="btn btn-primary">Browse Sessions</a>
		</div>
	</div>
	<div class="row-fluid ses">
		<% loop getSessions %>
		<div class="span3">
		 	<% loop Columns %>
		 		<% include Session %>
		 	<% end_loop %>
		 </div>
		<% end_loop %>
	</div>
</div>
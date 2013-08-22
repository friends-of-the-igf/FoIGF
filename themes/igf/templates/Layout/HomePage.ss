<div class="row-fluid content">
	<div class="span1">
		<span class='st_facebook_vcount' displayText='Facebook'></span>
		<span class='st_twitter_vcount' displayText='Tweet'></span>
	</div>
	<div class="span11 text">
	$Content
	</div>
</div>
<div class="row-fluid tags-wrap">
  <div class="span12 tags">
    <h5>Popular Topics</h5>
      <% loop allTags %><a href="$Link" style="font-size: {$Size};" >$Tag</a> <% end_loop %>
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
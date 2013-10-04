<div class="row-fluid content">
	<div class="span12 text">
	$Content
	</div>
</div>
<div class="row-fluid sub-main">
	<% if Topics %>
  	<div class="span6 topics">
  		<h3 class='text'>Browse sessions by topic</h3>
  		<ul>
	  		<% loop Topics %>
	  			<li><a href='$Link'>$Title</a></li>
	  		<% end_loop %>
	  	</ul>
	</div>
	<% end_if %>
	<div class="span6 featured">
		<% with FeaturedMeeting %>
		<div class="span4">
			$Image.CroppedImage(180,170)
		</div>
		<div class="span8">
			<h3 class='text'>"$Title"</h3>
			<h4>$StartDate.Format(j)-$EndDate.Long <% if Website %> | <a title="$Website" href="$Website">$Website</a><% end_if %> </h4>
		</div>
		<% end_with %>
	</div>
</div>
<div class="sessions">
	<div class="row-fluid heading">
		<div class="span8">
		<h3>Popular sessions</h3>
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
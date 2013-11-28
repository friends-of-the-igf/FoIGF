<div class="row-fluid content">
	<div class="span12 text">
	$Content
	</div>
</div>
<div class="row-fluid sub-main">
	<% if SiteConfig.ShowFeatured %>
		<% if Topics %>
	  	<div class="span4 topics box">
	  		<h3 class='text'>Browse sessions <span class="extra">by topic</span></h3>
	  		<ul>
		  		<% loop Topics %>
		  			<li><a title="Browse Sessions by $Title" href='$Link'>$Title</a></li>
		  		<% end_loop %>
		  	</ul>
		</div>
		<% end_if %>
			<% if FeaturedMeeting %>
			<div class="span4 featured box">
				<h3 class='text'>Next IGF</h3>
				<% with FeaturedMeeting %>
				<div class='logo'>
					<a title="View Meeting" href="$Link">
						$Image.CroppedImage(190,180)
					</a>
			
					<h4>$StartDate.Format(j)-$EndDate.Long <% if Website %> | <a title="Go to $Website" href="$Website">$Website</a><% end_if %> </h4>
				</div>
				<% end_with %>
			</div>
			<% end_if %>
		<div class="span4 official box">
	  		<h3 class='text'>Official IGF Website</h3>
	  		<div class='logo'>
	  			<a title="Go to the Official IGF Website" href="http://www.intgovforum.org">
					$OfficialLogo.CroppedImage(170,180)
				</a>
				<h4>Official Website | <a title="Go to the Official IGF Website" href="http://www.intgovforum.org"> www.intgovforum.org</a> </h4>
			</div>
	  	</div>
	<% else %>
		<% if Topics %>
	  	<div class="span6 topics box">
	  		<h3 class='text'>Browse sessions <span class="extra">by topic</span></h3>
	  		<ul>
		  		<% loop Topics %>
		  			<li><a title="Browse Sessions by $Title" href='$Link'>$Title</a></li>
		  		<% end_loop %>
		  	</ul>
		</div>
		<% end_if %>
		<div class="span6 official box">
	  		<h3 class='text'>Official IGF Website</h3>
	  		<div class='logo'>
	  			<a title="Go to the Official IGF Website" href="http://www.intgovforum.org">
					$OfficialLogo.CroppedImage(170,180)
				</a>
			
				<h4>Official Website | <a title="Go to the Official IGF Website" href="http://www.intgovforum.org"> www.intgovforum.org</a> </h4>
			</div>
	  	</div>
	<% end_if %>
</div>
<div class="sessions">
	<div class="row-fluid heading">
		<div class="span8">
		<h3>Most viewed sessions</h3>
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
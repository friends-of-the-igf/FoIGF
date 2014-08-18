<div class="session-tile thumbnail">
	<div class="session-blue">
	<a class="title" title="Go to $Title" href="$Link">$Title <i class="fa fa-arrow-circle-right fa-2x"></i></a>
	<% if getVideoThumb %>
		<div class="vidthumb">
			<a href="$Link">
				<img alt="Video" class="icon" width="40px" height="40px" src="{$ThemeDir}/images/youtube-play.png" />
				$getVideoThumb
			</a>	
		</div>
	<% else_if Video.WebcastCode %>
		<div class="vidthumb">
			<a href="$Link">
				<img alt="Video" class="icon" width="40px" height="40px" src="{$ThemeDir}/images/youtube-play.png" />
				<img alt="$Title" width="100%" height="100%" class="thumb" src="$Meeting.Image.URL" />
			</a>
		</div>
	<% end_if %>

	</div>
	<div class="text-wrap">
		<div>
			<p><a title="Search Session by $Type.Name" href="$Type.Link">$Type.Name</a> in <a title="Search Session by $Meeting.Location.City" href="$Meeting.Location.Link">$Meeting.Location.City</a> on <a title="Search Session by $Topic.Name" href="$Topic.Link">$Topic.Name</a></p>

			<a class="session-blue button" title="Go to $Title" href="$Link"><i class="fa fa-caret-right"></i> View Session </a>

			<p class="subtext small"><b>$Date.Long | $Views view<% if Views != 1 %>s<% end_if %></b></p>
		</div>
	</div>

	<% if Tags %>
		<div class="tags subtext">
			<b>Tagged:</b>
			<% loop Tags %>
				<a title="Search Session by $Title" href="$Link">$Title</a><% if not Last %>,<% end_if %>
			<% end_loop %>
		</div>
	<% end_if %>
</div>
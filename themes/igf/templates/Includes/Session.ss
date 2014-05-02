<div class="session-tile thumbnail">
	<% if getVideoThumb %>
		<div class="vidthumb">
			<a href="$Link">
				<img alt="Video" class="icon" width="40px" height="40px" src="{$ThemeDir}/images/youtube-play.png" />
				$getVideoThumb
			</a>	
		</div>
	<div class="text-wrap">
	<% else_if Video.WebcastCode %>
		<div class="vidthumb">
			<a href="$Link">
				<img alt="Video" class="icon" width="40px" height="40px" src="{$ThemeDir}/images/youtube-play.png" />
				<img alt="$Title" width="100%" height="100%" class="thumb" src="$Meeting.Image.URL" />
			</a>
		</div>
	<div class="text-wrap">
	<% else %>
	<div class="text-wrap nov">
	<% end_if %>
		<div>
			<p><b><a class="title" title="Go to $Title" href="$Link">$Title</a></b></p>		
			<p><a title="Search Session by $Type.Name" href="$Type.Link">$Type.Name</a> in <a title="Search Session by $Meeting.Location.City" href="$Meeting.Location.Link">$Meeting.Location.City</a> on <a title="Search Session by $Topic.Name" href="$Topic.Link">$Topic.Name</a></p>
			<p class="subtext small"><b>$Date.Long | $Views view<% if Views != 1 %>s<% end_if %></b></p>
		</div>
	</div>
	<% if TagsCollection %>
		<div class="tags subtext">
				<b>Tagged:</b>
				<% loop TagsCollection %>
					<a title="Search Session by $Tag" href="$Link">$Tag</a><% if not Last %>,<% end_if %>
				<% end_loop %>
		</div>
	<% end_if %>
</div>
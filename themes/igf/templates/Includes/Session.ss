<div class="session-tile thumbnail">
	<div class="vidthumb">
		<% if getVideoThumb %>
		<a href="$Link">
			<img class="icon" width="40px" height="40px" src="{$ThemeDir}/images/youtube-play.png" />
			$getVideoThumb
		</a>
		<% else %>
			<img width="100%"  src="http://placehold.it/224x130&text=+" />
		<% end_if %>
	</div>
	<div class="text-wrap">
		<p><b><a class="title" href="$Link">$Title</a></b></p>		
		<p><a href="$Type.Link">$Type.Name</a> in <a href="$Meeting.Location.Link">$Meeting.Location.City</a></p>
		<p class="subtext small"><b>$Date.Long | $Views view<% if Views != 1 %>s<% end_if %></b></p>
	</div>
	<div class="tags subtext">
			<b>Tagged:</b>
			<% loop TagsCollection %>
				<a href="$Link">$Tag</a><% if not Last %>,<% end_if %>
			<% end_loop %>
	</div>
</div>
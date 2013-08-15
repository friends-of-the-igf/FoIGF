<div class="session-tile">

	<div class="video">
		<% if getVideo %>
			$getVideo
		<% else %>
			<img width="100%" src="http://placehold.it/224x130">
		<% end_if %>
	</div>
	<div class="text-wrap">
		<div class="title">
			$Title
		</div>
		<div class="location">
			<a href>$Type</a> in <a href>$Location</a>
		</div>
		<div class="date">
			$Date | $View views
		</div>
		<div class="tags">
			Tagged: 
			<% loop TagsCollection %>
				<a href="$Link">$Tag</a><% if not Last %>,<% end_if %>
			<% end_loop %>
		</div>
	</div>

</div>
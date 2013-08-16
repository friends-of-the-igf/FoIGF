<div class="session-tile thumbnail">
		<div class="video">
			<% if getVideo %>
				$getVideo
			<% else %>
				<img width="100%" height="100%" src="http://placehold.it/224x130" />
			<% end_if %>
		</div>
		<div class="text-wrap">
			<p><b><a class="title" href="$Link">$Title</a></b></p>		
			<p><a href="#">$Type.Name</a> in <a href="#">$Meeting.Location.Name</a></p>
			<p class="subtext"><b>$Date | $View views</b></p>
			<p class="subtext light">
				Tagged: 
				<% loop TagsCollection %>
					<a href="$Link">$Tag</a><% if not Last %>,<% end_if %>
				<% end_loop %>
			</p>
	</div>

</div>
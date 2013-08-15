<div class="session-tile thumbnail">
		<div class="video">
			<% if getVideo %>
				$getVideo
			<% else %>
				<img width="100%" src="http://placehold.it/224x130" />
			<% end_if %>
		</div>
		<div class="text-wrap">
			<p><b><a href="$Link">$Title</a></b></p>		
			<p><a href="#">$Type</a> in <a href="#">$Location</a></p>
			<p><b>$Date | $View views</b></p>
			<p>
				Tagged: 
				<% loop TagsCollection %>
					<a href="$Link">$Tag</a><% if not Last %>,<% end_if %>
				<% end_loop %>
			</p>
	</div>

</div>
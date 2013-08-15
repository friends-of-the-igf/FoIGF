<div class="session-tile thumbnail">
	<% if getVideo %>
		$getVideo
	<% else %>
		<img width="100%" src="http://placehold.it/224x130" />
	<% end_if %>


	<p>$Title</p>		

	<p><a href="#">$Type</a> in <a href="#">$Location</a></p>


	<p>$Date | $View views</p>


	<p>Tagged: 
	<% loop TagsCollection %>
		<a href="$Link">$Tag</a><% if not Last %>,<% end_if %>
	<% end_loop %>
	</p>
</div>
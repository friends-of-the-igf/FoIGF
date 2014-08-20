<li class="tag">
	<a title="Search Session by $Tag.Title" href="$Tag.Link">$Tag.Title</a> 
	<span class="rate">
		<a class="up" title="This is a good tag" href="$MeetingSession.Link()/rateTag?r=1&id=$Tag.ID&session=$MeetingSession.ID">
			<i class="fa fa-arrow-up"></i>
		</a> 
		<a class="down" title="This is a bad tag" href="$MeetingSession.Link()/rateTag?id=$Tag.ID&session=$MeetingSession.ID">
			<i class="fa fa-arrow-down"></i>
		</a>
		<span class="rating" id="rating_{$Tag.ID}">$Rating</span>
	</span>
</li>
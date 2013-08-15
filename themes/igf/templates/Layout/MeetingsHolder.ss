<div class="row">
	<div class="span12 promo">
		<div class="row">
			<img src="http://placehold.it/180x180" class="span2" />
			<div class="span7">
				<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Eveniet, quisquam culpa consectetur at id deserunt sunt ut suscipit nulla error? Ratione in sequi qui nemo veritatis nesciunt expedita amet cum!</p>
			</div>
			<div class="span3 dark text-center">
				<p>
					22-25 October<br />
					<a href="#">Link</a><br />
					<a class="btn btn-primary" href="#">SUBMIT A PROPOSAL</a>
				</p>
			</div>
		</div>
	</div>
</div>
<div id="meetings-wrap" class="row thumbnails">
	<% if Meetings %>
	<% loop Meetings %>
	<div class="span3">
		<a href="$Link" class="thumbnail">
			<img src="http://placehold.it/400x300" />
			<p>$Title</p>
			<p>$StartDate.Nice - $EndDate.Nice<br/>20 sessions</p>
		</a>
	</div>
	<% end_loop %>
	<% end_if %>
</div>
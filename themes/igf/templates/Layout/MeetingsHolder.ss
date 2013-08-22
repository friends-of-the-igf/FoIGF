
<div id="meetings-wrap" class="row thumbnails">
	<% if Meetings %>
	<% loop Meetings %>

	<div class="span3<% if First || Modulus(4) == 1 %> first <% end_if %> <% if not First && Modulus(5) == 1 %> last <% end_if %>">
		<a href="$Link" class="thumbnail">
			<% if Image %>
		    $Image.SetSize(400,300)
		    <% else %>
		    <img src="http://placehold.it/400x300" />
		    <% end_if %>
			<div class="text-wrap">
				<h4>$Location.Name</h4>
				<p><span class="subtext"><b>$StartDate.Nice - $EndDate.Nice</b></span><br/>$MeetingSessions.Count sessions</p>
			</div>
		</a>
	</div>
	<% end_loop %>
	<% end_if %>
</div>

$BatchIDForm

<% if BatchProcess %>
	<table id="dvData">
		<tr>
			<th>
				Session ID
			</th>
			<th>
				Area
			</th>
			<th>
				Entity Type
			</th>
			<th>
				Entity 
			</th>
			<th>
				Relevance
			</th>
			<th>
				Count
			</th>
			<th>
				Social Tag (Social Tags Only)
			</th>
			<th>
				Tag Importance (Social Tags Only)
			</th>
			<th>
				Topic (Topics Only)
			</th>
			<th>
				Score (Topics Only)
			</th>
		</tr>
		<% loop Records %>
			<% loop Entities %>
				<% loop Types %>
					<% loop Entities %>
						<tr>
							<td>$Up.Up.Up.ID</td>
							<td>$Up.Up.Title</td>
							<td>$Up.Title</td>
							<td>$Value</td>
							<td>$Relevance</td>
							<td>$Count</td>
							<td>$Tag</td>
							<td>$Importance</td>
							<td>$Topic</td>
							<td>$Score</td>
						</tr>
					<% end_loop %>
				<% end_loop %>
			<% end_loop %>
		<% end_loop %>
	</table>
<% end_if %>

<% if MeetingSession %>
	<% with MeetingSession %>
	<p>You are viewing the entities extracted from the Session titled "<a href="$Link">$Title</a>" from the "<a href="$Meeting.Link">$Meeting.getYearLocation()</a>" meeting. <br/ >This was performed using the <a href="http://www.opencalais.com/">Open Calais Web Service.</a>
	</p>
	<% end_with %>
<% end_if %>

<% if Areas %>
<div class="tab-nav">
	<ul class="area-nav">
	<% loop Areas %>
			<li><a class="<% if First %> active <% end_if %>" href="#$Value" data-area="{$Value}" >$Title</a>
				
			</li>
	<% end_loop %>
	</ul>
</div>
<div class="tab-content" data-url="$Link">
<% loop Areas %>
	<div class="area <% if First %> active <% end_if %>" data-area="{$Value}" >
	<h2 id="{$Value}">$Title</h2>
	<% if Types %>
		<ul class="type-nav">
		<% loop Types %>	
				<li><a class="<% if First %> active <% end_if %>" href="#$Value" data-type="{$Value}" >$Title</a></li>
		<% end_loop %>
		</ul>
	<% end_if %>
			<% if Types %>
				<% loop Types %>
				<div class="type <% if First %> active <% end_if %>" data-type="{$Value}">
					<% if Value == Topics %>
						<% if Entities %>
							<% include TopicTable %>
						<% else %>
							<p>$Message</p>
						<% end_if %>
					<% else_if Value == SocialTags %>
						<% if Entities %>
							<% include TagTable %>
						<% else %>
							<p>$Message</p>
						<% end_if %>
					<% else %>
						<% include EntityTable %>
					<% end_if %>
				</div>
				<% end_loop %>
			<% else %>
				<p>$Message</p>
			<% end_if %>	
	</div>
<% end_loop %>
</div>
<% end_if %>
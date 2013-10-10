<div id="Regional" data-url="$Link"> 
	<div id="map">
		<img  src="$ThemeDir/images/map/map.png">
		<a id="africa" class='country'><img src="$ThemeDir/images/map/default/africa.png"></a>
		<a id="asia" class='country'><img src="$ThemeDir/images/map/default/asia.png"></a>
		<a id="south-america" class='country'><img src="$ThemeDir/images/map/default/south-america.png"></a>
		<a id="north-america" class='country'><img src="$ThemeDir/images/map/default/north-america.png"></a>

	</div>
	<% if Regions %>
	<ul>
		<% loop Regions %>
		<li><a class="region" data-id="$ID" > $Title </a></li>
		<% end_loop %>
	</ul>
	<% end_if %>
	<div id="Regional-Meetings">
		<% with MeetingsData %>
			<% include RegionalMeetings %>
		<% end_with %>
	</div>
</div>


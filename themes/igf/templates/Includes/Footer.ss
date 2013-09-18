<div id="footer">
	<div class="container">
		<div class="row-fluid">
			<div class="span3 f-section">
				<div class="element-outer">
					<div class="element-inner">
						<div class="element">
							<ul class="col-footer">
								<% loop $Menu(1).limit(2) %>
									<% if ClassName != MeetingsHolder %>
										<% if Pos == 2 || Pos == 5 %>
											<li class="buff"><a href="$Link" title="$Title.XML">$MenuTitle.XML</a></li>
										<% else %>
											<li><a href="$Link" title="$Title.XML">$MenuTitle.XML</a></li>
										<% end_if %>
									<% end_if %>
								<% end_loop %>
								<li><% if $SiteConfig.TwitterURL %><a href="$SiteConfig.TwitterURL" title="Tiwtter"><img src="{$ThemeDir}/images/twitter.png" class='social'></a><% end_if %><% if $SiteConfig.FacebookURL %><a href="$SiteConfig.FacebookURL" title="Facebook"><img src="{$ThemeDir}/images/fb.png" class='social'></a><% end_if %></li>			
							</ul>
						</div>
					</div>
				</div>
			
			</div>
				
			<div id="meeting-list" class="span5 f-section">
				<div class="element-outer">
					<div class="element-inner">
						<div class="element">
						<div class="meetings">
							<% if getMeetings %> 
							<span><a href="$meetingsLink">All Meetings</a></span>
							<ul class="col-footer">
								<% loop getMeetings %>
									<% if Odd %>
								
										<li><a class="left-col" href="$Link" title="$StartDate.Year() - $Location.City">$StartDate.Year() - $Location.City</a></li>
							
									<% else_if Even %>
							
										<li><a class="right-col" href="$Link" title="$StartDate.Year() - $Location.City">$StartDate.Year() - $Location.City</a></li>
								
									<% end_if %>
								<% end_loop %>
								</ul>
							<% end_if %>
						</div>
					</div>
				</div>
			</div>
		</div>
		
			
					
		
			<div class="offset2 span2 f-section">
				
					<div class="element">
						<div class="logo">
							<img src="{$ThemeDir}/images/igf-logo.png"/>
							<div class='website'>
								<span>Official IGF Website</span>
								<a>www.intgovforum.org</a>
							</div>
						</div>
					
				</div>
			</div>
		</div>
	</div>
</div>
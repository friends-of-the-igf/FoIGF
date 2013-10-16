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
											<li><a href="$Link" title="Go to$Title.XML">$MenuTitle.XML</a></li>
									<% end_if %>
								<% end_loop %>
								<% if $SiteConfig.ShowRegional %>
									<% if $SiteConfig.CanViewType == LoggedInUsers %>
										<% if $CurrentMember %>
											<li class="buff"><a class="blue" href="#" title="Go to Regional and National IGFs">Regional and National IGFs</a></li>
										<% end_if %>
									<% else %>
										<li class="buff"><a class="blue" href="#" title="Go to Regional and National IGFs">Regional and National IGFs</a></li>
									<% end_if %>
								<% end_if %>
								<% loop $Menu(1) %>
									<% if $ClassName == AboutPage || $ClassName == ContactPage %>
										<li class="$LinkingMode buff"><a href="$Link" title="Go to $Title.XML">$MenuTitle.XML</a></li>
									<% end_if %>
								<% end_loop %>	
								<% if CurrentMember %>
									<li><a title='Click here to log out' href="security/logout">Log out </a></li>
								<% else %>
									<li><a title='Click here to log in' href="security/login">Login</a></li>
								<% end_if %>

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
							<span><a title="View all meetings" href="$meetingsLink">All Meetings</a></span>
							<ul class="col-footer">
								<% loop getMeetings %>
									<% if Odd %>
								
										<li><a class="left-col" href="$Link" title="Go to $StartDate.Year() - $Location.City">$StartDate.Year() - $Location.City</a></li>
							
									<% else_if Even %>
							
										<li><a class="right-col" href="$Link" title="Go to $StartDate.Year() - $Location.City">$StartDate.Year() - $Location.City</a></li>
								
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
							<img alt="IGF" src="{$ThemeDir}/images/igf-logo.png"/>
							<div class='website'>
								<span>Official IGF Website</span>
								<a href="http://www.intgovforum.org" title="Go to www.intgovforum.org">www.intgovforum.org</a>
							</div>
						</div>
					
				</div>
			</div>
		</div>
	</div>
</div>
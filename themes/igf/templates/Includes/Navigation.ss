<div id="navigation" class="navbar navbar-static-top">
	<div class="navbar-inner">
		<div class="container">
			<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</a>

			<a class="brand" href="$BaseURL"><img alt="Friends of the IGF" src="{$ThemeDir}/images/logo.png"></a>

			<div class="nav-collapse collapse">
				<ul class="nav">
						
					<% loop $Menu(1).Limit(3) %>
						<li class="$LinkingMode"><a href="$Link" title="$Title.XML">$MenuTitle.XML</a></li>
					<% end_loop %>
					<% if $SiteConfig.CanViewType == LoggedInUsers %>
						<% if $CurrentMember %>
							<li class=""><a class="blue" href="#" title="Regional and National IGFs">Regional and National IGFs</a></li>
						<% end_if %>
					<% else %>
						<li class=""><a class="blue" href="#" title="Regional and National IGFs">Regional and National IGFs</a></li>
					<% end_if %>
					
				</ul>
				<div class="social-icons pull-right visible-desktop">
					<% if $SiteConfig.TwitterURL %><a href="$SiteConfig.TwitterURL" ><img alt="Twitter" src="{$ThemeDir}/images/twitter.png"></a><% end_if %>
					<% if $SiteConfig.FacebookURL %><a href="$SiteConfig.FacebookURL" ><img alt="Facebook" src="{$ThemeDir}/images/fb.png"></a><% end_if %>
				</div>
			</div>
		</div>
	</div>
</div>
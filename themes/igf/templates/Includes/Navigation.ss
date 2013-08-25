<div id="navigation" class="navbar navbar-static-top">
	<div class="navbar-inner">
		<div class="container">
			<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</a>

			<a class="brand" href="$BaseURL"><img src="{$ThemeDir}/images/icons/logo.png"></a>

			<div class="nav-collapse collapse">
				<ul class="nav">
						<li class="divider-vertical"></li>
					<% loop $Menu(1).Limit(3) %>
						<li class="$LinkingMode"><a href="$Link" title="$Title.XML">$MenuTitle.XML</a></li>
					<% end_loop %>
						<li class="divider-vertical"></li>
				</ul>
				<div class="social-icons pull-right visible-desktop">
					<a href="$SiteConfig.FacebookURL" class="social"><img src="{$ThemeDir}/images/icons/facebook-light.png"></a>
					<a href="$SiteConfig.TwitterURL" class="social"><img src="{$ThemeDir}/images/icons/twitter-light.png"></a>
				</div>
				<ul class="nav pull-right">
					<% loop $Menu(1).Limit(5, 3) %>
					
					<li class="$LinkingMode"><a href="$Link" title="$Title.XML">$MenuTitle.XML</a></li>
					
					<% end_loop %>
				</ul>
			</div>
		</div>
	</div>
</div>
<div id="navigation" class="navbar navbar-static-top">
	<div class="navbar-inner">
		<div class="container">
			<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</a>

			<a class="brand" href="$BaseURL"><img src="{$ThemeDir}/images/logo.png"></a>

			<div class="nav-collapse collapse">
				<ul class="nav">
						<li class="divider-vertical"></li>
					<% loop $Menu(1).Limit(3) %>
						<li class="$LinkingMode"><a href="$Link" title="$Title.XML">$MenuTitle.XML</a></li>
					<% end_loop %>
						<li class="divider-vertical"></li>
				</ul>
				<div class="social-icons pull-right visible-desktop">
					<% if $SiteConfig.FacebookURL %><a href="$SiteConfig.FacebookURL" class="social"><img src="{$ThemeDir}/images/fb.png"></a><% end_if %>
					<% if $SiteConfig.TwitterURL %><a href="$SiteConfig.TwitterURL" class="social"><img src="{$ThemeDir}/images/twitter.png"></a><% end_if %>
				</div>
			</div>
		</div>
	</div>
</div>
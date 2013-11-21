<div id="navigation" class="navbar navbar-static-top">
	<div class="navbar-inner">
		<div class="container">
			<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</a>

			<a class="brand" title="Go home" href="$BaseURL"><img alt="Friends of the IGF" src="{$ThemeDir}/images/logo.png"></a>

			<div class="nav-collapse collapse">
				<ul class="nav">
						
					<% loop $Menu(1).Limit(3) %>
						<li class="$LinkingMode"><a href="$Link" title="Go to $Title.XML">$MenuTitle.XML</a></li>
					<% end_loop %>
					<% if $SiteConfig.ShowRegional %>
						<% if $SiteConfig.CanViewType == LoggedInUsers %>
							<% if $CurrentMember %>
								<li class=""><a class="blue" href="regional" title="Go to Regional and National IGFs">Regional and National IGFs</a></li>
							<% end_if %>
						<% else %>
							<li class=""><a class="blue" href="regional" title="Go to Regional and National IGFs">Regional and National IGFs</a></li>
						<% end_if %>
					<% end_if %>
				</ul>
				<ul class="nav pull-right">
					<% loop $Menu(1) %>
						<% if $ClassName == AboutPage || $ClassName == ContactPage %>
							<li class="$LinkingMode"><a href="$Link" title="Go to $Title.XML">$MenuTitle.XML</a></li>
						<% end_if %>
					<% end_loop %>
				</ul>
			</div>
		</div>
	</div>
</div>
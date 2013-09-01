<div id="footer">
	<div class="container">
		<div class="row">
			<div class="span2">
				<ul class="col-footer">
					<% loop $Menu(1).Limit(3) %>
					<li><a href="$Link" title="$Title.XML">$MenuTitle.XML</a></li>
					<% if $Children %>
						<% loop $Children %>
							<li><a href="$Link" title="$Title.XML page">- $MenuTitle.XML</a></li>
						<% end_loop %>
					<% end_if %>
					<% end_loop %>
				</ul>
			</div>
			<div class="span2">
				<ul class="col-footer">
					<% loop $Menu(1).Limit(5, 3) %>
					<li><a href="$Link" title="$Title.XML">$MenuTitle.XML</a></li>
					<% end_loop %>

					<li><% if $SiteConfig.FacebookURL %><a href="$SiteConfig.FacebookURL" title="Facebook"><img src="{$ThemeDir}/images/fb.png" class='social'></a><% end_if %><% if $SiteConfig.TwitterURL %><a href="$SiteConfig.TwitterURL" title="Tiwtter"><img src="{$ThemeDir}/images/twitter.png" class='social'></a><% end_if %></li>			
				</ul>
			</div>
			<div class="span2 logo">		
					<img src="{$ThemeDir}/images/igf-logo.png"/>	
			</div>
			<div class="span2">
				<div class='website'>
					<span>Official Website</span>
					<a>www.intgovforum.org</a>
				</div>
			</div>
		</div>
	</div>
</div>
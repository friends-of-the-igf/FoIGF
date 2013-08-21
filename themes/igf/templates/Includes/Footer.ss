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
					<li><a href="" title=""><img src="{$ThemeDir}/images/icons/facebook-dark.png" class='social'></a><a href="" title=""><img src="{$ThemeDir}/images/icons/twitter-dark.png" class='social'></a></li>
					
				</ul>
			</div>
			<div class="span2 logo">		
					<img src="{$ThemeDir}/images/icons/igf-logo-dark.png"/>	
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
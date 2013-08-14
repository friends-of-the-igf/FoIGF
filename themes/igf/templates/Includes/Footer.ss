<div id="footer">
	<div class="container">
		<div class="row">
			<div class="span2">
				<ul class="col-footer">
					<% loop $Menu(1) %>
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
					<li><a href="" title="">About</a></li>
					<li><a href="" title="">Contact</a></li>
					<li><a href="" title=""><img src="http://placehold.it/25x25" class='img-circle social'></a><a href="" title=""><img src="http://placehold.it/25x25" class='img-circle social'></a></li>
					
				</ul>
			</div>
			<div class="span1">		
					<img src="http://placehold.it/60x45"/>	
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
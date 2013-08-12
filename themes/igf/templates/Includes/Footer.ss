<div id="footer">
	<div class="container">
		<div class="row">
			<div class="span4">
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
			<div class="span2 offset1">
				<img src="http://placehold.it/140x60" />
			</div>
		</div>
	</div>
</div>
<div id="subnav" class="container">
	<ul>
		<% with $Level(1) %>
		<li class="$LinkingMode">
			<a href="$Link" class="$LinkingMode" title="Go to the $Title.XML page">$MenuTitle.XML</a>
		</li>
		<% if LinkOrSection = section %>
			<% if $Children %>
				<% loop $Children %>
					<li class="$LinkingMode">
						<a href="$Link" class="$LinkingMode" title="Go to the $Title.XML page">$MenuTitle.XML</a>
					</li>
				<% end_loop %>
			<% end_if %>
		<% end_if %>
		<% end_with %>
	</ul>
</div>
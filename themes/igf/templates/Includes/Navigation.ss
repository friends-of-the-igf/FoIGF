<div class="navbar navbar-static-top">
	<div class="navbar-inner">
		<div class="container">
			<a class="brand" href="$BaseURL">IGF</a>
			<ul class="nav">
				<% loop $Menu(1) %>
				<li class="$LinkingMode"><a href="$Link" title="$Title.XML">$MenuTitle.XML</a></li>
				<% end_loop %>
			</ul>
			<% if CurrentUser %>
			<a href="{$BaseURL}Security/logout" title="Login" class="pull-right btn btn-warning btn-small">Logout</a>
			<% else %>
			<a href="{$BaseURL}Security/login?BackURL={$BaseURL}" title="Login" class="pull-right btn btn-warning btn-small">Login</a>
			<% end_if %>
		</div>
	</div>
</div>
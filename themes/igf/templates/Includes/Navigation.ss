<div id="navigation" class="navbar navbar-static-top">
	<div class="navbar-inner">
		<div class="container">
			<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</a>

			<a class="brand" href="$BaseURL"><img src="http://placehold.it/110x30"></a>

			<div class="nav-collapse collapse">
				<ul class="nav">
						<li class="divider-vertical"></li>
					<% loop $Menu(1) %>
						<li class="$LinkingMode"><a href="$Link" title="$Title.XML">$MenuTitle.XML</a></li>
					<% end_loop %>
						<li class="divider-vertical"></li>
				</ul>
				<div class="social-icons pull-right visible-desktop">
					<a class="social"><img src="http://placehold.it/25x25" class="img-circle"></a>
					<a class="social"><img src="http://placehold.it/25x25" class="img-circle"></a>
				</div>
				<ul class="nav pull-right">
					<li class=""><a href="" title="About">About</a></li>
					<li class=""><a href="" title="Contact">Contact</a></li>
				</ul>
			</div>
			
			<div class="social-icons hidden-desktop">
				<a class="social"><img src="http://placehold.it/25x25" class="img-circle"></a>
				<a class="social"><img src="http://placehold.it/25x25" class="img-circle"></a>
			</div>

		</div>
	</div>
</div>
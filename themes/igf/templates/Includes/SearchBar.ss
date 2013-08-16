<% if ClassName == SessionsHolder %>
<div id="searchbar">
	<div class="container">	
		<div class="row-fluid">	
			<div class="search span8 offset3">
				<img src="http://placehold.it/30x30" class='img-circle mglass'>
				$SearchForm
			</div>
			<div class="logo span1">
				<img src="http://placehold.it/60x45">
			</div>
		</div>
	</div>
</div>

<% else %>

<div id="searchbar">
	<div class="container">
		<div class="row-fluid">
			<div class="search span9">
				<img src="http://placehold.it/30x30" class='img-circle mglass'>
				$SearchForm
			</div>
			<div class="logo span3">
				<img src="http://placehold.it/60x45">
			</div>
		</div>
	</div>
</div>
<% end_if %>





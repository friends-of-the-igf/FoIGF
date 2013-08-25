<% if ClassName == SessionsHolder %>
<div id="searchbar">
	<div class="container">	
		<div class="row-fluid">	
			<div class="search span8 offset3">
				<img class='mglass' src="{$ThemeDir}/images/icons/search.png">
				$CustomSearchForm
			</div>
			<div class="logo span1">
				<img src="{$ThemeDir}/images/icons/igf-logo-light.png">
			</div>
		</div>
	</div>
</div>

<% else %>

<div id="searchbar">
	<div class="container">
		<div class="row-fluid">
			<div class="search span9">
				<img class='mglass' src="{$ThemeDir}/images/icons/search.png">
				$CustomSearchForm
			</div>
			<div class="logo span3">
				<img src="{$ThemeDir}/images/icons/igf-logo-light.png">
			</div>
		</div>
	</div>
</div>
<% end_if %>





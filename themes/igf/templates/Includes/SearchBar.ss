
<div id="searchbar">
	<div class="container">
		<div class="row-fluid">
			<div class="search span9">
				<img alt="Search" class='mglass' src="{$ThemeDir}/images/icons/search.png">
				$SearchForm
			</div>
			<div class="twitter span3">
				<a title="Share this page" href='https://twitter.com/share' class='twitter-share-button' <% if ClassName  == SessionController %> data-url='www.friendsoftheigf.org/$MeetingSession.Link' data-counturl="www.friendsoftheigf.org/$MeetingSession.Link" <% else %> data-url="www.friendsoftheigf.org/" data-counturl="www.friendsoftheigf.org/" <% end_if %> data-size="large">Tweet</a>
			</div>
		</div>
	</div>
</div>






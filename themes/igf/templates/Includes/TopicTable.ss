<table>
	<tr>
		<th><a href='#' data-sort="Topics">Topics <span class="down">▼</span><span class="up">▲</span></a></th>
		<th><a href='#' data-sort="Score">Score <span class="down">▼</span><span class="up">▲</span></a></th>
	</tr>
<% loop Entities %>
	<tr>
		<td class='name'>$Topic</td>
		<td>$Score</td>
	</tr>
<% end_loop %>
</table>
<div class="ajax-loading">
	<div class="loader-inner">
		<h3> Loading, please wait... </h3>
	</div>
</div>
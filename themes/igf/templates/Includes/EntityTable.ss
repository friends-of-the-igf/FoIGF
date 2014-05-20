<table>
	<tr>
		<th><a href='#' data-sort="Name">Name <span class="down">▼</span><span class="up">▲</span></a></th>
		<th><a href='#' data-sort="Type">Type <span class="down">▼</span><span class="up">▲</span></a></th>
		<th><a href='#' data-sort="Count">Count <span class="down">▼</span><span class="up">▲</span></a></th>
		<th><a href='#' data-sort="Relevance">Relevance <span class="down">▼</span><span class="up">▲</span></a></th>
		<th><a href='#' data-sort="Normalized">Normalized <span class="down">▼</span><span class="up">▲</span></a></th>
	</tr>
<% loop Entities %>
	<tr>
		<td class='name'>$Value</td>
		<td>$Type</td>
		<td>$Count</td>
		<td>$Relevance</td>
		<td>$Normalized</td>
	</tr>
<% end_loop %>
</table>
<div class="ajax-loading">
	<div class="loader-inner">
		<h3> Loading, please wait... </h3>
	</div>
</div>
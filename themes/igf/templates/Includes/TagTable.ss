<table>
	<tr>
		<th><a href='#' data-sort="Tag">Tag <span class="down">▼</span><span class="up">▲</span></a></th>
		<th><a href='#' data-sort="Importance">Importance <span class="down">▼</span><span class="up">▲</span></a></th>
	</tr>
<% loop Entities %>
	<tr>
		<td class='name'>$Tag</td>
		<td>$Importance</td>
	</tr>
<% end_loop %>
</table>
<div class="ajax-loading">
	<div class="loader-inner">
		<h3> Loading, please wait... </h3>
	</div>
</div>

<p>Open Calais extracted $SuggestedTags.Count() unique tags (tags already applied to this Session have been excluded):</p>

<table>
	<% loop SuggestedTags %>
		<tr>
			<td> 
				$Title
			</td>
			<td>
				<a href="#" data-tag='$Title' class="add-tag">  + Add </a> <img style="display:none" class="loader" src="mysite/images/ajax-loader.gif">
			</td>
		</tr>
	<% end_loop %>
</table>

<p><i>Added tags will not show on the Tags tab until the page has been refreshed.</i></p>
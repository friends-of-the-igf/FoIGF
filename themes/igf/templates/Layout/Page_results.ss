<div id="Content" class="searchResults">
    <h1>$Title</h1>
      
    <% if $Query %>
        <p class="searchQuery"><strong>You searched for &quot;{$Query}&quot;</strong></p>
    <% end_if %>
          
    <% if $Results %>
    <div class='results'>
        <ul id="meetingResults">
        <% loop $Results %> 
            <% if ClassName == Meeting %>
            <li>
                <strong>Meeting</strong> - <a title="Go to $Title" class="searchResultHeader" href="$URLSegment">$Title</a>
                <p>$Content.LimitWordCountXML</p>
            </li>
            <% else_if ClassName == MeetingSession %>
            <li>
                <strong>Session</strong> - <a title="Go to $Title" class="searchResultHeader" href="$URLSegment">$Title</a>
                <p>$Content.LimitWordCountXML</p>
            
            </li>
            <% else_if ClassName == Location %> 
            <li>
                <strong>Location</strong> - <a title="Find sessions in $Name" class="searchResultHeader" href="$Link">$Name</a>
            </li>
            <% else_if ClassName == Topic %>
            <li>
                <strong>Topic</strong> - <a title="Find sessions in $Name" class="searchResultHeader" href="$Link">$Name</a>
            </li>
            <% else_if ClassName == Type %>
            <li>
                <strong>Type</strong> - <a title="Find sessions in $Name" class="searchResultHeader" href="$Link">$Name</a>
            </li>
            <% else_if ClassName == File %>
            <li>
                <strong>File</strong> - <a title="Go to $Title" class="searchResultHeader" href="$Link">$Title</a>
                <p>$FileContentCache.LimitWordCountXML</p>
            </li>
            <% end_if %>
        <% end_loop %>
        </ul>
    </div>
    <% else %>
    <p>Sorry, your search query did not return any results.</p>
    <% end_if %>

    <% if $Results.MoreThanOnePage %>
    <div class="pagination pagination-centered">
        <ul>
            <% if $Results.NotFirstPage %>
            <li><a href="$Results.PrevLink" title="View the previous page">Prev</a></li>
            <% end_if %>

                <% loop $Results.Pages %>
                
                    <% if $CurrentBool %>
                    <li class="active"><a href="$Link" title="View page number $PageNum">$PageNum</a></li>
                    <% else %>
                    <li><a href="$Link" title="View page number $PageNum">$PageNum</a></li>
                    <% end_if %>
                
                <% end_loop %>

            <% if $Results.NotLastPage %>
            <li><a href="$Results.NextLink" title="View the next page">Next</a></li>
            <% end_if %>
        </ul>
    </div>
    <p class="text-center">Page $Results.CurrentPage of $Results.TotalPages</p>
    <% end_if %>
</div>
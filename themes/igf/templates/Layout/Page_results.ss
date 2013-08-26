<div id="Content" class="searchResults">
    <h1>$Title</h1>
      
    <% if $Query %>
        <p class="searchQuery"><strong>You searched for &quot;{$Query}&quot;</strong></p>
    <% end_if %>
          
    <% if $Results %>
    <div class='results'>
        <h3>Meetings</h3>
        <div id="noMeeting"></div>
        <ul id="meetingResults">
            <% loop $Results %> 
             <% if ClassName == Meeting %>
            <li>
                <a class="searchResultHeader" href="$URLSegment">$Title</a>
                <p>$Content.LimitWordCountXML</p>
            </li>
            <% else_if ClassName == Location %>
            <li>
                <% loop Meetings %>
                <a class="searchResultHeader" href="$URLSegment">$Title</a>
                <p>$Content.LimitWordCountXML</p>
                <% end_loop %>
            </li>
            <% end_if %>
            <% end_loop %>
        </ul>
    </div>
    <div class='results'>
        <h3> Sessions </h3> 
        <div id="noSessions"></div>
        <ul id="sessionResults">
             <% loop $Results %>
              <% if ClassName == MeetingSession %>
            <li>
                <a class="searchResultHeader" href="$URLSegment">
                    $Title 
                </a> 
                <p>$Content.LimitWordCountXML</p>
            
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
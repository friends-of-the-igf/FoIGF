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
                <a class="searchResultHeader" href="$URLSegment">
                    $Title
                </a>
               
                <p>$Content.LimitWordCountXML</p>
            </li>
            <% end_if %>
            <% end_loop %>
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
   
    </ul>
    <% else %>
    <p>Sorry, your search query did not return any results.</p>
    <% end_if %>
              
    <% if $Results.MoreThanOnePage %>
    <div id="PageNumbers">
        <% if $Results.NotLastPage %>
        <a class="next" href="$Results.NextLink" title="View the next page">Next</a>
        <% end_if %>
        <% if $Results.NotFirstPage %>
        <a class="prev" href="$Results.PrevLink" title="View the previous page">Prev</a>
        <% end_if %>
        <span>
            <% loop $Results.Pages %>
                <% if $CurrentBool %>
                $PageNum
                <% else %>
                <a href="$Link" title="View page number $PageNum">$PageNum</a>
                <% end_if %>
            <% end_loop %>
        </span>
        <p>Page $Results.CurrentPage of $Results.TotalPages</p>
    </div>
    <% end_if %>
</div>
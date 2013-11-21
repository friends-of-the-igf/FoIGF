<% with Meeting %>
<div class="row-fluid header">
  <div class="span12">
    <h3>$Title</h3>
    <h4 class="subtext">$StartDate.Format(j)-$EndDate.Long - in <a title="Search Session by $Location.Name" href="$Location.Link">$Location.Name</a><% if Website %> - <a title="Go to $Website" href="$Website">$Website</a><% end_if %></h4>
  </div>    
</div>
<div class="row-fluid links">
 
  <div class="span8 section right-divide">
    <% if Image %>
      $Image.SetSize(120,95)
    <% else %>
      <img alt="No Image for meeting" src="http://placehold.it/120x95" />
    <% end_if %>
    <h5>Meeting Information</h5>
    <% loop LinkItems %>
      <% if Type == 'URL' %>
        <a title="View $Title" href="$Url">$Title</a><% if not Last %> | <% end_if %>
      <% else_if Type == 'Text' %>
        <a title="View $Title" href="item/$ID">$Title</a><% if not Last %> | <% end_if %>
      <% end_if %>
    <% end_loop %>
  </div>
  <div class="span4 section">
    <div class="row-fluid social">
      <div class="span3">
        <div>
        <span class='st_twitter_vcount' displayText='Tweet'></span>
        </div>
      </div>
      <div class="span3">
       <span class='st_facebook_vcount' displayText='Facebook'></span>
      </div>
      <div class="span3">
        <span class='st_email_vcount' displayText='Email'></span>
      </div>
      <div class="span3">
         <span class='st_googleplus_vcount' displayText='Google +'></span>
      </div>
    </div>
  </div>
</div>
<div class="row-fluid tags-wrap">
  <div class="span12 tags">
    <h5>Popular Tags</h5>
      <% loop $popularTags %><a title="Search Session by $Tag" href="$Link" style="font-size: {$Size};" >$Tag</a> <% end_loop %>
  </div>
</div>
<div class="sessions">
  <div class="row-fluid heading">
    <div class="span8">
      <h3>$MeetingSessions.Count Session<% if $MeetingSessions.Count != 1 %>s<% end_if %></h3>
    </div>
    <div class="span4 link">
      <a title="Filter Sessions" href="$FilterLink" class="btn btn-primary">Filter Sessions</a>
    </div>
  </div>
  <% if MeetingSessions %>
    <% loop MeetingDays %>
        <% if Count != 0 %>
          <div class="row-fluid ses">
            <div class="span12">
              <h4><a title="Toggle Day" class='switch'>$Day: </a>$Date <span class="arrow"><a class="switch">&#9660</a></span></h4>
            </div>
            <div class="row-fluid list">
              <% loop Topics %>
              <% if Count != 0 %>
                <div class="row-fluid topics">
                  <div class="span12">
                    <h5><a title="Toggle topic" class='topic-switch'>$Title ($Count)</a> <span class="topic-arrow"><a class="topic-switch">&#9660</a></span></h5>
                  </div>
                  <div class="row-fluid topic-list">
                    <% loop Sessions %>
                      <div class="span3">
                        <% loop Columns %>
                          <% include Session %>
                        <% end_loop %>
                      </div>
                    <% end_loop %>
                  </div>
                </div>
                <% end_if %>
              <% end_loop %>
            </div>
          </div>
        <% end_if %>
      <% end_loop %>
  <% end_if %>
</div>
<% end_with %>
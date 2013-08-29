<% with Meeting %>
<div class="row-fluid header">
  <div class="span2">
    <% if Image %>
    $Image.SetSize(150,150)
    <% else %>
    <img src="http://placehold.it/150x150" />
    <% end_if %>
  </div>
  <div class="span10">
    <h3>$Title</h3>
    <h4 class="subtext">$StartDate.Format(d)-$EndDate.Long - in <a href="$Location.Link">$Location.Name</a></h4>
  </div>    
</div>
<div class="row-fluid tags-wrap">
  <div class="span12 tags">
    <h5>Topics covered</h5>
      <% loop $allTags %><a href="$Link" style="font-size: {$Size};" >$Tag</a> <% end_loop %>
  </div>
</div>
<div class="row-fluid links">
  <div class="span3 section right-divide">
    <h5>Meeting Website</h5>
      <a href="$Website">$Website</a>
  </div>
  <div class="span6 section right-divide">
    <h5>Meeting Information</h5>
    <% loop LinkItems %>
      <a href="$Url"><% if Title %>$Title<% else %>$Url<% end_if %></a><% if not Last %> | <% end_if %>
    <% end_loop %>
  </div>
  <div class="span3 section">
    <div class="row-fluid social">
      <div class="span4">
        <div>
        <span class='st_twitter_vcount' displayText='Tweet'></span>
        </div>
      </div>
      <div class="span4">
       <span class='st_facebook_vcount' displayText='Facebook'></span>
      </div>
      <div class="span4">
       <span class='st_email_vcount' displayText='Email'></span>
      </div>
    </div>
  </div>
</div>
<div class="sessions">
  <div class="row-fluid heading">
    <div class="span8">
      <h3>$MeetingSessions.Count Session<% if $MeetingSessions.Count != 1 %>s<% end_if %></h3>
    </div>
    <div class="span4 link">
      <a href="$FilterLink" class="btn btn-primary">Filter Sessions</a>
    </div>
  </div>
  <div class="row-fluid ses">
    <div class="span12">
    <% if MeetingSessions %>
      <div class="row-fluid ">
        <% loop getMeetingSessions %>
          <div class="span3">
            <% loop Columns %>
              <% include Session %>
            <% end_loop %>
          </div>
        <% end_loop %>
      </div>
    <% end_if %>
    </div>
  </div>
</div>
<% end_with %>
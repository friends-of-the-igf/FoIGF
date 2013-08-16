<% with Meeting %>
<div class="row-fluid header">
  <div class="span2">
    <img src="http://placehold.it/150x150" />
  </div>
  <div class="span10">
    <h3>$Title</h3>
    <h4 class="subtext">$StartDate.Nice - $EndDate.Nice in <a>$Location.Name</a></h4>
  </div>    
</div>
<div class="row-fluid links">
  <div class="span3">
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
  <div class="span3">
    <h5>Meeting Information</h5>
    <% loop Links %>
      <a href="$URL"><% if Title %>$Title<% else %>$URL</a>
    <% end_loop %>
  </div>
  <div class="span3">
    <h5>Topic's Covered</h5>
     <% loop Topics %><a>$Name</a><br/><% end_loop %>
  </div>
  <div class="span3">
    <h5>Sessions tagged</h5>
   <% loop allTags %><a >$Tag - $Weight<% if not Last %>,<% end_if %></a> <% end_loop %>
  </div>
</div>

<div class="row-fluid">
  <div class="span8">
    <h3>$MeetingSessions.Count Session<% if $MeetingSessions.Count != 1 %>s<% end_if %></h3>
  </div>
  <div class="span4">
    <a href='$sessionLink' class="btn btn-primary">Filter Sessions</a>
  </div>
</div>
<div class="row-fluid">
  <div class="span12">
  <% if MeetingSessions %>
    <div class="row-fluid sessions">
      <% loop MeetingSessions %>
      <div class="span3">
        <% include Session %>
      </div>
      <% end_loop %>
    </div>
  <% end_if %>
  </div>
</div>
<% end_with %>
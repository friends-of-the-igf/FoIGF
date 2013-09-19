<% with Meeting %>
<div class="row-fluid header">
  <div class="span12">
    <h3>$Title</h3>
    <h4 class="subtext">$StartDate.Format(j)-$EndDate.Long - in <a href="$Location.Link">$Location.Name</a><% if Website %> - <a href="$Website">$Website</a><% end_if %></h4>
  </div>    
</div>
<div class="row-fluid links">
 
  <div class="span8 section right-divide">
    <% if Image %>
      $Image.SetSize(120,95)
    <% else %>
      <img src="http://placehold.it/120x95" />
    <% end_if %>
    <h5>Meeting Information</h5>
    <% loop LinkItems %>
      <% if Type == 'URL' %>
        <a href="$Url">$Title</a><% if not Last %> | <% end_if %>
      <% else_if Type == 'Text' %>
        <a href="item/$ID">$Title</a><% if not Last %> | <% end_if %>
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
      <% loop $popularTags %><a href="$Link" style="font-size: {$Size};" >$Tag</a> <% end_loop %>
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
  <% if MeetingSessions %>
    <% with meetingDays %>
      <% with Day0 %>
        <% if Count != 0 %>
          <div class="row-fluid ses">
            <div class="span12">
              <div class="row-fluid ">
                <div class="span12">
                  <h4><a id="dayZero" class='switch'>Day 0: </a>$Date <span class="arrow"><a id="dayZero" class="switch">&#9650</a></span></h4>
                </div>
              </div>
              <div id="listZero" class="row-fluid list">
                <% loop List %>
                  <div class="span3">
                    <% loop Columns %>
                      <% include Session %>
                    <% end_loop %>
                  </div>
                <% end_loop %>
              </div>
            </div>
          </div>
        <% end_if %>
      <% end_with %>
      <% with Day1 %>
        <% if Count != 0 %>
          <div class="row-fluid ses">
            <div class="span12">
              <div class="row-fluid ">
                <div class="span12">
                  <h4><a id="dayOne" class='switch'>Day 1: </a>$Date <span class="arrow"><a id="dayOne" class="switch">&#9650</a></span></h4>
                </div>
              </div>
              <div id="listOne" class="row-fluid list">
                <% loop List %>
                  <div class="span3">
                    <% loop Columns %>
                      <% include Session %>
                    <% end_loop %>
                  </div>
                <% end_loop %>
              </div>
            </div>
          </div>
        <% end_if %>
      <% end_with %>
      <% with Day2 %>
        <% if Count != 0 %>
          <div class="row-fluid ses">
            <div class="span12">
              <div class="row-fluid list">
                <div class="span12">
                  <h4><a id="dayTwo" class='switch'>Day 2: </a>$Date <span class="arrow"><a id="dayTwo" class="switch">&#9650</a></span></h4>
                </div>
              </div>
              <div id="listTwo" class="row-fluid list">
                <% loop List %>
                  <div class="span3">
                    <% loop Columns %>
                      <% include Session %>
                    <% end_loop %>
                  </div>
                <% end_loop %>
              </div>
            </div>
          </div>
        <% end_if %>
      <% end_with %>
      <% with Day3 %>
        <% if Count != 0 %>
          <div class="row-fluid ses">
            <div class="span12">
              <div class="row-fluid ">
                <div class="span12">
                  <h4><a id="dayThree" class='switch'>Day 3: </a>$Date <span class="arrow"><a id="dayThree" class="switch">&#9650</a></span></h4>
                </div>
              </div>
              <div id="listThree" class="row-fluid">
                <% loop List %>
                  <div class="span3">
                    <% loop Columns %>
                      <% include Session %>
                    <% end_loop %>
                  </div>
                <% end_loop %>
              </div>
            </div>
          </div>
        <% end_if %>
      <% end_with %>
      <% with Day4 %>
        <% if Count != 0 %>
          <div class="row-fluid ses">
            <div class="span12">
              <div class="row-fluid list"> 
                <div class="span12">
                  <h4><a id="dayFour" class='switch'>Day 4: </a>$Date <span class="arrow"><a id="dayFour" class="switch">&#9650</a></span></h4>
                </div>
              </div>
              <div id="listFour" class="row-fluid ">
                <% loop List %>
                  <div class="span3">
                    <% loop Columns %>
                      <% include Session %>
                    <% end_loop %>
                  </div>
                <% end_loop %>
              </div>
            </div>
          </div>
        <% end_if %>
      <% end_with %>
    <% end_with %>
  <% end_if %>
</div>
<% end_with %>
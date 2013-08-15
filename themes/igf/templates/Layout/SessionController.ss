<div id="ses-wrap">
	<% with MeetingSession %>
	<div class="row">
		<div class="span12">
			<h3>$Title</h3>
			<div>
				$Date - A <a>$Type</a> on "Topic here" in <a>$Location</a>
			</div>
		</div>	
	</div>
	<div class="row content">
		<div class="span8">	
			<div class="video">
				$getVideo
			</div>
			<div>
				<a class="btn"><b>Read full session transcript</b></a> <a class="btn"><b>View Original proposal</b></a>
			</div>
			<div>
				<h4> Agenda</h4>
				<p>"But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will give you a complete account of the system, and expound the actual teachings of the great explorer of the truth, the master-builder of human happiness. No one rejects, dislikes, or avoids pleasure itself, because it is pleasure, but because those who do not know how to pursue pleasure rationally encounter consequences that are extremely painful. Nor again is there anyone who loves or pursues or desires to obtain pain of itself, because it is pain, but because occasionally circumstances occur in which toil and pain can procure him some great pleasure. To take a trivial example, which of us ever undertakes laborious physical exercise, except to obtain some advantage from it? But who has any right to find fault with a man who chooses to enjoy a pleasure that has no annoying consequences, or one who avoids a pain that produces no resultant pleasure?"</p>
				<h5> Topics</h5>
				<p>"Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. </p>
				<h5> Building Exxective</h5>
				<p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?"
				</p>
				<h5> TImportance of yada yada</h5>
				<p>aicsdvcbskjdvbkjdfvbjkdf</p>
				<h5> Success Stories</h5>
				<p>aicsdvcbskjdvbkjdfvbjkdf</p>
				<h5> Moderated round table</h5>
				<p>aicsdvcbskjdvbkjdfvbjkdf</p>
			</div>

		</div>
		
		<div class="span4">
			<div> Social Icons </div>
			<div>
				<p>
				Tagged: 
				<% loop TagsCollection %>
					<a href="$Link">$Tag</a><% if not Last %>,<% end_if %>
				<% end_loop %>
				</p>
			</div>
			<div>
				Speakers
			</div>
			<div>
				Related Sessions
			</div>
		</div>
	
		</div>
	</div>
	<% end_with %>
</div>

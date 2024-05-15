<script type="text/template" id="answer-template">
	<div class="row answer">
		<div class="answer-controls col-sm-1 text-center d-flex flex-column">
			<i class="fa-solid fa-angle-up arrow" id="upwote-answer" data-clicked="false"
			   data-answer-id="<%=answerid%>"></i>

			<p class="upwotes-count" id="answer-upwotes"><%=upwotes%></p>

			<i class="fa-solid fa-angle-down arrow" id="downwote-answer" data-clicked="false"
			   data-answer-id="<%=answerid%>"></i>
		</div>
		<div class="answer-details col-sm-11">
			<p class="description"> <%= answer %> </p>
			<% if (answerimage !== '') { %>
			<img src="<%=answerimage%>" alt="Answer Image" class="answer-image">
			<% } %>
			<hr>
			<p class="date"><strong>Date: </strong><%= answeraddeddate %></p>
		</div>
	</div>
</script>

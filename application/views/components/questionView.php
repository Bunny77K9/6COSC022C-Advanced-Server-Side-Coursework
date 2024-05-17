<script type="text/template" id="question-view-template">
	<div class="question">
		<h4 class="title">
			<a href="#questions/question/<%=questionid%>"><%=title%></a>
		</h4>
		<p class="description"><%=description.slice(0, 250)%>...</p>
		<div class="info">
			<div class="tags">
				<% tags.forEach(function(tag) { %>
				<p class="tag"><%=tag%></p>
				<% }); %>
			</div>
			<p class="date"><%=date%></p>
		</div>
	</div>
</script>

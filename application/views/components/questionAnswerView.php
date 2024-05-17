<script type="text/template" id="question-answer-template">
	<div class="container row">
		<div class="col-sm-2 d-flex justify-content-center"
			 style="border-right: 1px solid #c9c9c9;">
			<div class="d-flex flex-column sidebar">
				<ul class="nav nav-pills flex-column mb-auto side-nav">
					<li class="nav-item">
						<a href="#questions" class="nav-link link-active">
							<i class="fa-solid fa-house"></i><span class="side-title">Questions</span>
						</a>
					</li>
					<li>
						<a href="#questions/bookmarks" class="nav-link link-dark">
							<i class="fa-solid fa-bookmark"></i><span class="side-title">Bookmarks</span>
						</a>
					</li>
					<li>
						<a href="#questions/categories" class="nav-link link-dark">
							<i class="fa-solid fa-layer-group"></i><span class="side-title">Categories</span>
						</a>
					</li>
					<li>
						<a href="#questions/tags" class="nav-link link-dark">
							<i class="fa-solid fa-tags"></i><span class="side-title">Tags</span>
						</a>
					</li>
				</ul>
				<a class="btn logout-btn" href="#logout" id="logout">
					<i class="fa fa-sign-out me-2"></i>Log out
				</a>
			</div>
		</div>

		<div class="col-sm-10 px-5 mt-2">
			<div class="container-fluid question-container">
				<div class="row">
					<div class="col-sm-1 question-functions text-center d-flex flex-column">
						<i class="fa-solid fa-angle-up arrow" id="upwote-question" data-clicked="false"></i>

						<p class="upwotes-count" id="question-upwotes"><%=upwotes%></p>

						<i class="fa-solid fa-angle-down arrow" id="downwote-question" data-clicked="false"></i>

						<% if (is_bookmark) { %>
						<i class="fa-solid fa-bookmark bookmark" id="remove-bookmark"></i>
						<% } else {%>
						<i class="fa-regular fa-bookmark bookmark" id="add-bookmark"></i>
						<% } %>
					</div>

					<div class="col-sm-11 question-display">
						<h4 class="title"><%=title%></h4>
						<h6 class="section-title">Description</h6>
						<hr>
						<p class="description"><%= description %></p>
						<h6 class="section-title">Expectation</h6>
						<hr>
						<p class="expectation"><%= expectation %></p>
						<% if (images !== '') { %>
						<h6 class="section-title">Supporting Images</h6>
						<hr>
						<img src="<%=images%>" alt="Question Image" class="question-image">
						<% } %>
						<hr>
						<div class="info">
							<div class="tags">
								<% tags.forEach(function(tag) { %>
								<p class="tag"><%=tag%></p>
								<% }); %>
							</div>
							<p class="date"><%=date%></p>
						</div>
					</div>
				</div>
			</div>

			<div class="col-sm-11 answers-list" id="answer" style="display: none">
				<h4 class="title">Answers</h4>
			</div>

			<div class="col-sm-11 new-answer-form">
				<div class="answer-input">
					<p class="title">Your Answer</p>
					<p class="description">Be specific and provide an answer to the question. Answer should have a
						minimum of 20 characters.</p>
					<textarea class="form-control" id="inputQuestionDetails" name="inputQuestionDetails"
							  rows="3" required></textarea>
				</div>

				<div class="answer-input">
					<p class="title">Upload Image</p>
					<p class="description">Any images related to the answer. (optional)</p>
					<input class="form-control" type="file" id="answerImageUpload" name="answerImageUpload"
						   accept="image/png, image/gif, image/jpeg">
				</div>

				<div class="code-of-conduct-read form-check">
					<input type="checkbox" class="form-check-input" id="codeOfConductCheck">
					<label class="form-check-label" for="codeOfConductCheck">By submitting your question, you agree
						to
						the <a href="#questions">code of conduct</a></label>
				</div>

				<button type="submit" id="submit-answer" class="btn btn-primary">Submit Answer
				</button>
			</div>
		</div>
	</div>
</script>

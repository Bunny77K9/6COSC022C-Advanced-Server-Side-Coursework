<script type="text/template" id="new-question-template">
	<div class="container row">
		<div class="col-sm-2 d-flex justify-content-center"
			 style="border-right: 1px solid #c9c9c9;">
			<div class="d-flex flex-column sidebar">
				<ul class="nav nav-pills flex-column mb-auto side-nav">
					<li class="nav-item">
						<a href="#" class="nav-link link-active">
							<i class="fa-solid fa-house"></i><span class="side-title">Questions</span>
						</a>
					</li>
					<li>
						<a href="#home/bookmark/<%=user_id%>" class="nav-link link-dark">
							<i class="fa-solid fa-bookmark"></i><span class="side-title">Bookmarks</span>
						</a>
					</li>
					<li>
						<a href="#home/category" class="nav-link link-dark">
							<i class="fa-solid fa-layer-group"></i><span class="side-title">Categories</span>
						</a>
					</li>
					<li>
						<a href="#home/tags" class="nav-link link-dark">
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
			<div class="new-question-form">
				<div class="page-heading">
					<div class="heading ">
						<h1>Ask a question</h1>
					</div>
					<hr>
				</div>

				<div class="question-form-input">
					<p class="title">Title</p>
					<p class="description">Be specific and imaging you're asking a question to another person</p>
					<input type="text" class="form-control" id="inputQuestionTitle" name="inputQuestionTitle"
						   required
						   autofocus>
				</div>

				<div class="question-form-input">
					<p class="title">Details about your question</p>
					<p class="description">Introduce the problem and expand on what you put in the title. Minimum 20
						characters</p>
					<textarea class="form-control" id="inputQuestionDetails" name="inputQuestionDetails"
							  rows="3" required></textarea>
				</div>

				<div class="question-form-input">
					<p class="title">What do you expect?</p>
					<p class="description">Describe what you tried, what you expected to happen, and what actually
						resulted. Minimum 20 Characters</p>
					<textarea class="form-control" id="inputQuestionExpectation"
							  name="inputQuestionExpectation"
							  rows="3" required></textarea>
				</div>

				<div class="question-form-input">
					<p class="title">Upload Image</p>
					<p class="description">Upload an image related to your question (optional)</p>
					<input type="file" class="form-control" id="imageUpload" name="imageUpload"
						   accept="image/png, image/gif, image/jpeg">
				</div>

				<div class="question-form-input">
					<p class="title">Tags</p>
					<p class="description">Add up to 5 tags to describe what your question is about. Start typing to
						see suggestion </p>
					<input type="text" class="form-control" placeholder="E.g. codeigniter, php, backbone"
						   required id="inputQuestionTags" name="inputQuestionTags">
				</div>

				<div class="question-form-input">
					<p class="title">Category</p>
					<p class="description">Select question category relates to your category</p>
					<select class="form-control" required id="questionCategory">
						<option value="" selected disabled>Please select</option>
						<option value="software">Software</option>
						<option value="hardware">Hardware</option>
						<option value="programming">Programming</option>
						<option value="networking">Networking</option>
						<option value="security">Security</option>
						<option value="database">Database</option>
						<option value="web-development">Web Development</option>
						<option value="mobile-development">Mobile Development</option>
						<option value="cloud-computing">Cloud Computing</option>
						<option value="artificial-intelligence">Artificial Intelligence</option>
					</select>
				</div>

				<div class="code-of-conduct-read form-check">
					<input type="checkbox" class="form-check-input" id="codeOfConductCheck">
					<label class="form-check-label" for="codeOfConductCheck">By submitting your question, you agree
						to
						the <a href="#">code of conduct</a></label>
				</div>

				<button type="submit" id="submit-question" class="btn btn-primary">Submit
					Question
				</button>
			</div>
		</div>
	</div>
</script>

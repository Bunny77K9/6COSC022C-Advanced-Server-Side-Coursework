<script type="text/template" id="category-template">

	<div id="nav-bar-container"></div>
	<div class="container">
		<div class="row" style="margin-top: 70px">
			<div class="col-sm-2 d-flex justify-content-center" style="border-right: 1px solid #c9c9c9;">
				<div class="d-flex flex-column sidebar">
					<ul class="nav nav-pills flex-column mb-auto side-nav">
						<li class="nav-item">
							<a href="#" class="nav-link link-dark">
								<i class="fa-solid fa-house"></i><span class="side-title">Questions</span>
							</a>
						</li>
						<li>
							<a href="#home/category" class="nav-link" aria-current="page" style="color: #007bff; border-color: #007bff">
								<i class="fa-solid fa-layer-group"></i><span class="side-title">Category</span>
							</a>
						</li>
						<li>
							<a href="#home/bookmark/<%=user_id%>" class="nav-link link-dark">
								<i class="fa-solid fa-bookmark"></i><span class="side-title">Bookmarks</span>
							</a>
						</li>
					</ul>
				</div>
			</div>

			<div class="col-sm-10 px-4">
				<div class="page-heading" id="question">
					<div class="heading">
						<h1>Category</h1>
						<button type="button" class="btn btn-outline-primary" id="ask-question-button">Ask Question
						</button>
					</div>
					<hr>
					<div id="category-buttons" class="d-flex gap-2 mt-3"></div>
				</div>
			</div>
		</div>
	</div>
</script>

<script type="text/template" id="bookmark-template">

	<div id="navbar-container">

	</div>

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
							<a href="#home/category" class="nav-link link-dark">
								<i class="fa-solid fa-layer-group"></i><span class="side-title">Category</span>
							</a>
						</li>
						<li>
							<a href="#home/bookmark/<%=user_id%>" class="nav-link link-active">
								<i class="fa-solid fa-bookmark"></i><span class="side-title">Bookmarks</span>
							</a>
					</ul>
					<a class="btn logout-btn" href="#logout" id="logout">
						<i class="fa fa-sign-out me-2"></i>Log out
					</a>
				</div>
			</div>
			<div class="col-sm-10 px-4">
				<div class="page-heading" id="question">
					<div class="heading">
						<h1 class="title">Bookmarks</h1>
						<button type="button" class="btn btn-outline-primary ask-question-btn" id="ask-question">Ask Question
						</button>
					</div>
					<hr>
				</div>
			</div>
		</div>
	</div>
</script>

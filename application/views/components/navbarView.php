<script type="text/template" id="navbar-template">
	<nav class="navbar fixed-top navbar-expand-lg bg-body-tertiary">
		<div class="container-fluid">
			<a class="navbar-brand" href="#">DEV FORUM</a>

			<button class="navbar-toggler" type="button" data-bs-toggle="collapse"
					data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
					aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>

			<div class="collapse navbar-collapse" id="navbarSupportedContent">

				<form class="d-flex justify-content-center flex-grow-1 me-auto mb-2 mb-lg-0 search-form"
					  role="search">
					<input class="form-control me-2" id="search-question-input" type="search"
						   placeholder="&#128269; Search Question" aria-label="Search">
					<button class="btn btn-outline-secondary search-btn" id="search-question" type="submit">Search
					</button>
				</form>

				<ul class="navbar-nav">
					<li class="nav-item">
						<div class="nav-link">
							<a class="profile-btn" href="#home/user/<%=user_id%>">
								<img src="<%=userimage%>" alt="profile image" class="profile-img me-1">
								<%=firstname%>
							</a>
						</div>
					</li>
				</ul>
			</div>
		</div>
	</nav>
</script>

<script type="text/template" id="nav-bar-template">
	<div class="header" style="position:fixed;top:0;left:0;width:100%; z-index: 1">
		<nav class="navbar navbar-expand-lg bg-body-tertiary">
			<div class="container-fluid">
				<a class="navbar-brand" href="#">Dev Forum</a>

				<button class="navbar-toggler" type="button" data-bs-toggle="collapse"
						data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
						aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>

				<div class="collapse navbar-collapse" id="navbarSupportedContent">

					<form class="d-flex justify-content-center flex-grow-1 me-auto mb-2 mb-lg-0 search-form"
						  role="search">
						<input class="form-control me-2" id="srearch-question-input" type="search"
							   placeholder="&#128269; Search Question" aria-label="Search">
						<button class="btn btn-outline-secondary" id="search-question" type="submit">Search
						</button>
					</form>

					<ul class="navbar-nav">
						<li class="nav-item">
							<div class="nav-link">
								<a class="btn" href="#home/user/<%=user_id%>" >
									<i class="fa-solid fa-user"></i> <%=firstname%>
								</a>
							</div>
						</li>
						<li class="nav-item">
							<div class="nav-link">
								<a class="btn btn-outline-danger" href="#logout" id="logout">
									<i class="fa fa-sign-out"></i> Log out
								</a>
							</div>
						</li>
					</ul>
				</div>
			</div>
		</nav>
	</div>
</script>

<script type="text/template" id="profile-template">
	<div class="container row">
		<div class="col-sm-2 d-flex justify-content-center"
			 style="border-right: 1px solid #c9c9c9;">
			<div class="d-flex flex-column sidebar">
				<ul class="nav nav-pills flex-column mb-auto side-nav">
					<li class="nav-item">
						<a href="#questions" class="nav-link link-dark">
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
			<div class="profile-card">
				<div class="row">
					<div class="col-sm-3">
						<div class="profile-picture">
							<% if (profileimg != "") { %>
							<img src="<%=profileimg%>" alt="User Image">
							<% } else { %>
							<img src="../../assets/images/profileimg/user.png" alt="User Image">
							<% } %>
						</div>
					</div>
					<div class="col-sm-9">
						<div class="profile-details">

							<!-- User Details Section -->
							<div class="user-details mb-4">
								<h1><%=firstname%> <%=lastname%></h1>
								<p>@<%=username%></p>
								<p><strong>Title: </strong><%=title%></p>
								<p><strong>Email: </strong><%=email%></p>
							</div>

							<!-- Admin Controls Section -->
							<div class="admin-controls mb-2">
								<button type="button" class="btn btn-outline-primary" data-bs-toggle="modal"
										data-bs-target="#editUserModal"><i
										class="fa-solid fa-pen-to-square"></i> Edit
								</button>
								<button type="button" class="btn btn-outline-primary" id="change-profile-picture"><i
										class="fa-solid fa-user"></i> Change Profile Pic
								</button>
								<button type="button" class="btn btn-outline-danger" data-bs-toggle="modal"
										data-bs-target="#passwordChangeModal"><i
										class="fa-solid fa-lock"></i> Change Password
								</button>
								<input type="file" id="change-profile-picture-input" style="display: none;"
									   accept="image/*">
							</div>

							<!-- Progress Bar Section -->
							<div class="pro-bar mb-3">
								<p><strong>Asked Questions:</strong> <%= questioncount %></p>

								<div class="progress" role="progressbar" aria-valuemin="0" aria-valuemax="100"
									 style="height: 20px;">
									<% let askCnt = questioncount % 10; %>
									<% for (let i = 0; i < askCnt; i++) { %>
									<div class="progress-bar bg-warning"
										 style="width: 10%;"></div>
									<% } %>
								</div>

								<% let questionLevelCount = Math.floor(questioncount / 10); %>
								<div class="level-container d-flex justify-content-between">
									<p class="level" style="text-align: left">Level: <%= questionLevelCount
										%></p>
									<p class="level" style="text-align: right">Level: <%= questionLevelCount
										+ 1 %> </p>
								</div>

								<p><strong>Answered Questions:</strong> <%= answercount %></p>

								<div class="progress" role="progressbar" aria-valuemin="0" aria-valuemax="100"
									 style="height: 20px;">
									<% let answerCnt = answercount % 10; %>
									<% for (let i = 0; i < answerCnt; i++) { %>
									<div class="progress-bar bg-warning"
										 style="width: 10%;"></div>
									<% } %>
								</div>

								<% let answerLevelCount = Math.floor(answercount / 10); %>
								<div class="level-container d-flex justify-content-between">
									<p class="level" style="text-align: left">Level: <%= answerLevelCount
										%></p>
									<p class="level" style="text-align: right">Level: <%= answerLevelCount
										+ 1 %> </p>
								</div>
							</div>

							<!-- Achievements Section -->
							<div class="achievements-section">
								<h5>Achievements</h5>
								<hr>
								<div class="achievement-badges d-flex flex-wrap">
									<% if (questioncount >= 1) { %>
									<div class="achievement-badge">
										<img src="../../assets/images/achievements/goodfirstquestion.png"
											 alt="Good First Question" class="img-fluid">
										<p>Good First Question</p>
									</div>
									<% } %>
									<% if (answercount >= 1) { %>
									<div class="achievement-badge">
										<img src="../../assets/images/achievements/goodfirstanswer.png"
											 alt="Good First Answer" class="img-fluid">
										<p>Good First Answer</p>
									</div>
									<% } %>
									<% if (questionLevelCount >= 1) { %>
									<div class="achievement-badge">
										<img src="../../assets/images/achievements/qlevel1.png"
											 alt="Question Master" class="img-fluid">
										<p>Question Master</p>
									</div>
									<% } %>
									<% if (questionLevelCount >= 2) { %>
									<div class="achievement-badge">
										<img src="../../assets/images/achievements/qlevel2.png" alt="Question Sage"
											 class="img-fluid">
										<p>Question Sage</p>
									</div>
									<% } %>
									<% if (questionLevelCount >= 3) { %>
									<div class="achievement-badge">
										<img src="../../assets/images/achievements/qlevel3.png" alt="Question Guru"
											 class="img-fluid">
										<p>Question Guru</p>
									</div>
									<% } %>
									<% if (answerLevelCount >= 1) { %>
									<div class="achievement-badge">
										<img src="../../assets/images/achievements/alevel1.png" alt="Answer Master"
											 class="img-fluid">
										<p>Answer Masters</p>
									</div>
									<% } %>
									<% if (answerLevelCount >= 2) { %>
									<div class="achievement-badge">
										<img src="../../assets/images/achievements/alevel2.png" alt="Answer Sage"
											 class="img-fluid">
										<p>Answer Sage</p>
									</div>
									<% } %>
									<% if (answerLevelCount >= 3) { %>
									<div class="achievement-badge">
										<img src="../../assets/images/achievements/alevel3.png" alt="Answer Guru"
											 class="img-fluid">
										<p>Answer Guru</p>
									</div>
									<% } %>
									<% if (answerLevelCount >= 10 && questionLevelCount >= 10) { %>
									<div class="achievement-badge">
										<img src="../../assets/images/achievements/aqlevel1.png" alt="Master of All"
											 class="img-fluid">
										<p>Master of All</p>
									</div>
									<% } %>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="modal fade" id="passwordChangeModal" tabindex="-1"
					 aria-labelledby="passwordChangeModalLabel"
					 aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title fs-5" id="passwordChangeModalLabel">Change Password</h5>
								<button type="button" class="btn-close" data-bs-dismiss="modal"
										aria-label="Close"></button>
							</div>
							<div class="modal-body">
								<form id="changePasswordForm">
									<div class="mb-3">
										<label for="oldPassword">Old Password</label>
										<input type="password" class="form-control" id="oldPassword" required>
									</div>
									<div class="mb-3">
										<label for="newPassword">New Password</label>
										<input type="password" class="form-control" id="newPassword" required>
									</div>
									<div class="mb-3">
										<label for="confirmPassword">Confirm Password</label>
										<input type="password" class="form-control" id="confirmPassword" required>
									</div>
								</form>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close
								</button>
								<button type="button" class="btn btn-primary" id="submit-change-password-button">Save
									Password
								</button>
							</div>
						</div>
					</div>
				</div>

				<div class="modal fade" id="editUserModal" tabindex="-1"
					 aria-labelledby="editUserModalLabel"
					 aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title fs-5" id="editUserModalLabel">Edit User Details</h5>
								<button type="button" class="btn-close" data-bs-dismiss="modal"
										aria-label="Close"></button>
							</div>
							<div class="modal-body">
								<form id="changeUserDetailsForm">
									<div class="mb-3">
										<label for="firstname">Firstname</label>
										<input type="text" class="form-control" id="firstname"
											   value="<%=firstname%>"
											   required>
									</div>
									<div class="mb-3">
										<label for="lastname">Lastname</label>
										<input type="text" class="form-control" id="lastname" value="<%=lastname%>"
											   required>
									</div>
									<div class="mb-3">
										<label for="title">Title</label>
										<input type="text" class="form-control" id="title" value="<%=title%>"
											   required>
									</div>
									<div class="mb-3">
										<label for="username">Username</label>
										<input type="text" class="form-control" id="username" value="<%=username%>"
											   required>
									</div>
									<div class="mb-3">
										<label for="email">Email address</label>
										<input type="email" class="form-control" id="email" value="<%=email%>"
											   required>
									</div>
								</form>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close
								</button>
								<button type="button" class="btn btn-primary" id="edit-profile">Save Changes
								</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
</script>

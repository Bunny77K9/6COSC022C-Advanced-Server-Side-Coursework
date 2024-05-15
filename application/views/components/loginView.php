<script type="text/template" id="login_template">
	<div class="login-div">
		<div class="row no-gutters">
			<div class="col-sm-6 mx-auto">
				<h1 class="card-title text-center">Dev Forum</h1>
				<div class="card card-body my-5">
					<h2 class="card-title text-center">Log in</h2>
					<form class="form-main">

						<p class="error text-center" id="errLog"></p>

						<div class="mb-3">
							<input type="text" class="form-control" placeholder="Email or username"
								   required id="inputUsername" name="inputEmail">
						</div>

						<div class="mb-4">
							<input type="password" id="inputPassword" class="form-control"
								   placeholder="Password"
								   required name="password">
						</div>

						<div class="forget-password mb-4 text-center">
							<a href="#" data-bs-toggle="modal" data-bs-target="#forgetPasswordModal">
								Forget Password</a>
						</div>

						<button class="btn btn-primary mb-3 login-button" id="login_button" type="submit">Log in
						</button>

						<p class="auth-change text-center" style="margin-top: 20px">Don't have an account? <a
								href="#signup">Sign up</a></p>
					</form>

					<!-- Forget Password Modal -->
					<div class="modal fade" id="forgetPasswordModal" tabindex="-1" aria-labelledby="passwordModalLabel"
						 aria-hidden="true">
						<!--<div class="modal-dialog modal-dialog-centered">-->
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title fs-5" id="passwordModalLabel">Forget Password</h5>
									<button type="button" class="btn-close" data-bs-dismiss="modal"
											aria-label="Close"></button>
								</div>
								<div class="modal-body">
									<form id="resetPasswordForm">
										<div class="mb-3">
											<label for="username" class="form-label">Email or username</label>
											<input type="text" class="form-control" id="username" required>
										</div>
										<div class="mb-3">
											<label for="newPassword" class="form-label">New Password</label>
											<input type="password" class="form-control" id="newPassword" required>
										</div>
										<div class="mb-3">
											<label for="confirmPassword" class="form-label">Confirm Password</label>
											<input type="password" class="form-control" id="confirmPassword" required>
										</div>
									</form>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close
									</button>
									<button type="button" class="btn btn-primary" id="forgetPasswordChange">Save
										changes
									</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
</script>

<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<script type="text/template" id="signup-template">
	<div class="row no-gutters">
		<div class="col-sm-8 mx-auto loin-signup">
			<h1 class="title text-center">DEV FORUM</h1>
			<div class="card card-body my-5">
				<h2 class="card-title text-center">Sign up</h2>
				<form class="form-main">

					<p class="error text-center" id="signup-error"></p>

					<div class="row form-input-signup mb-4">
						<div class="col-sm-6">
							<input type="text" class="form-control" placeholder="First name"
								   required id="signupFirstname" aria-label="Firstname">
						</div>
						<div class="col-sm-6">
							<input type="text" class="form-control" placeholder="Last name"
								   required id="signupLastname" aria-label="Lastname">
						</div>
					</div>

					<div class="row form-input-signup mb-4">
						<div class="col-sm-12">
							<input type="email" class="form-control" placeholder="Email address"
								   required id="signupEmail" aria-label="Email">
						</div>
					</div>

					<div class="row form-input-signup mb-4">
						<div class="col-sm-12">
							<input type="text" class="form-control" placeholder="Title"
								   required id="signupOccupation" aria-label="Title">
						</div>
					</div>

					<div class="row form-input-signup mb-4">
						<div class="col-sm-6">
							<div class="input-group">
								<span class="input-group-text" id="basic-addon1">@</span>
								<input type="text" class="form-control" placeholder="Username"
									   aria-label="Username" aria-describedby="basic-addon1" required
									   id="signupUsername">
							</div>
						</div>
						<div class="col-sm-6">
							<div class="input-group">
								<span class="input-group-text" id="basic-addon2"><i
										class="fa-solid fa-key"></i></span>
								<input type="password" id="signupPassword" class="form-control"
									   placeholder="Password"
									   aria-label="Username" aria-describedby="basic-addon2"
									   required name="password">
							</div>
						</div>
					</div>

					<button class="btn btn-primary login-button" id="signup-button" type="submit">Sign up</button>
					<p class="auth-change mt-5 text-center">Already have an account? <a href="">Log in</a></p>
				</form>
			</div>
		</div>
	</div>
</script>

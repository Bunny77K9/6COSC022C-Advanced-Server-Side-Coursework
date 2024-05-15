<script type="text/template" id="signup_template">
	<div class="row no-gutters">
		<div class="col-sm-8 mx-auto">
			<h1 class="text-center">Dev Forum</h1>
			<div class="card card-body my-5">
				<h2 class="card-title text-center">Sign up</h2>
				<form class="form-main">

					<p class="error text-center" id="errSign"></p>

					<div class="container">
						<div class="row form-input-signup">
							<div class="col-sm-6">
								<input type="text" class="form-control" placeholder="First name"
									   required id="regFirstname">
							</div>
							<div class="col-sm-6">
								<input type="text" class="form-control" placeholder="Last name"
									   required id="regLastname">
							</div>
						</div>
					</div>

					<div class="container">
						<div class="row form-input-signup">
							<div class="col-sm-12">
								<input type="email" class="form-control" placeholder="Email address"
									   required id="regEmail">
							</div>
						</div>
					</div>

					<div class="container">
						<div class="row form-input-signup">
							<div class="col-sm-12">
								<input type="text" class="form-control" placeholder="Title"
									   required id="regOccupation">
							</div>
						</div>
					</div>

					<div class="container">
						<div class="row form-input-signup">
							<div class="col-sm-6">
								<div class="input-group">
									<span class="input-group-text" id="basic-addon1">@</span>
									<input type="text" class="form-control" placeholder="Username"
										   aria-label="Username" aria-describedby="basic-addon1" required
										   id="regUsername">
								</div>
							</div>
							<div class="col-sm-6">
								<div class="input-group">
								<span class="input-group-text" id="basic-addon2"><i
										class="fa-solid fa-key"></i></span>
									<input type="password" id="regPassword" class="form-control"
										   placeholder="Password"
										   aria-label="Username" aria-describedby="basic-addon2"
										   required name="password">
								</div>
							</div>
						</div>
					</div>

					<button class="btn btn-primary login-button" id="signup_button" type="submit">Sign up</button>
					<p class="auth-change mt-5 text-center">Already have an account? <a href="">Log in</a></p>
				</form>
			</div>
		</div>
	</div>
</script>

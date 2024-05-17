var app = app || {};

app.views.loginView = Backbone.View.extend({
	el: ".container",

	render: function () {
		template = _.template($('#login-template').html());
		this.$el.html(template(this.model.attributes));
	},

	events: {
		"click #login-button": "login",
		"click #reset-password-button": "resetPassword"
	},

	login: function (e) {
		e.preventDefault();
		e.stopPropagation();

		var validateForm = validateLoginForm();
		if (!validateForm) {
			new Noty({
				theme: 'bootstrap-v4', layout: 'bottomCenter',
				type: 'error',
				text: 'Please enter the login cridentials!',
				timeout: 2000
			}).show();
			$("#login-error").html("Please enter the login cridentials!!");
		} else {
			this.model.set(validateForm);
			var url = this.model.url + "login";
			this.model.save(this.model.attributes, {
				"url": url,
				success: function (model, response) {
					new Noty({
						theme: 'bootstrap-v4', layout: 'bottomRight',
						type: 'success',
						text: 'Login successful',
						timeout: 2000
					}).show();
					$("#logout").show();
					localStorage.setItem('user', JSON.stringify(model));
					app.appRouter.navigate("questions", {trigger: true});
				},
				error: function (model, xhr) {
					if (xhr.statsu = 401) {
						$("#login-error").html("Username or Password Incorrect");
						new Noty({
							theme: 'bootstrap-v4', layout: 'bottomRight',
							type: 'error',
							text: 'Username or Password is Incorrect',
							timeout: 2000
						}).show();
					} else if (xhr.status === 500) {
						$("#login-error").html("User not found");
						new Noty({
							theme: 'bootstrap-v4', layout: 'bottomRight',
							type: 'error',
							text: 'Internal server error!',
							timeout: 2000
						}).show();
					} else {
						new Noty({
							theme: 'bootstrap-v4', layout: 'bottomRight',
							type: 'error',
							text: 'Unknown error!',
							timeout: 2000
						}).show();
					}
				}
			});
		}
	},

	resetPassword: function (e) {
		e.preventDefault()
		e.stopPropagation()

		var validateResetPassword = validateResetPasswordForm()

		if (!validateResetPassword.username || !validateResetPassword.newpassword || !validateResetPassword.confirmpassword) {
			new Noty({
				theme: 'bootstrap-v4', layout: 'bottomRight',
				type: 'error',
				text: 'Form validation error: ' + validateResetPassword,
				timeout: 2000
			}).show()
		} else {
			var userPass = {
				'username': $("input#username").val(),
				'newpassword': $("input#newPassword").val(),
				'confirmpassword': $("input#confirmPassword").val()
			}

			var url = this.model.url + "reset_password"

			$.ajax({
				url: url,
				type: 'POST',
				data: userPass,
				success: (response) => {
					if (response.status === true) {
						new Noty({
							theme: 'bootstrap-v4', layout: 'bottomRight',
							type: 'success',
							text: 'Password reset successful!',
							timeout: 2000
						}).show()

						$('#username').val('')
						$('#newPassword').val('')
						$('#confirmPassword').val('')

						$('#resetPasswordModal').modal('hide')
					}
				},
				error: function (response) {
					if (response.status === false) {
						new Noty({
							theme: 'bootstrap-v4', layout: 'bottomRight',
							type: 'error',
							text: 'Please check the details and try again!',
							timeout: 2000
						}).show()
					}
				}
			})
		}
	}
});

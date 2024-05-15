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
			new Noty({ theme: 'bootstrap-v4', layout: 'bottomCenter',
				type: 'error',
				text: 'Please enter the login cridentials!',
				timeout: 2000
			}).show();
			$("#login-error").html("Please enter the login cridentials!!");
		}else {
			this.model.set(validateForm);
			var url = this.model.url + "signin";
			this.model.save(this.model.attributes, {
				"url": url,
				success: function (model, response) {
					new Noty({ theme: 'bootstrap-v4', layout: 'bottomRight',
						type: 'success',
						text: 'Login successful',
						timeout: 2000
					}).show();
					$("#logout").show();
					localStorage.setItem('user', JSON.stringify(model));
					app.appRouter.navigate("home", {trigger: true});
				},
				error:function (model,xhr) {
					if(xhr.statsu=400){
						$("#login-error").html("Username or Password Incorrect");
						new Noty({ theme: 'bootstrap-v4', layout: 'bottomRight',
							type: 'error',
							text: 'Username or Password is Incorrect',
							timeout: 2000
						}).show();
					}
				}
			});
		}
    },

	resetPassword: function (){

		$username = $("input#username").val();
		$newPassword = $("input#newPassword").val();
		$confirmPassword = $("input#confirmPassword").val();

		if($newPassword != $confirmPassword){
			new Noty({ theme: 'bootstrap-v4', layout: 'bottomRight',
				type: 'error',
				text: 'New password and confirm password do not match',
				timeout: 2000
			}).show();
		}else{
			var userPass = {
				'username': $username,
				'newpassword': $newPassword,
				'confirmpassword': $confirmPassword
			};

			var url = this.model.url + "reset_password";

			$.ajax({
				url: url,
				type: 'POST',
				data: userPass,
				success: (response) =>{
					if(response.status === true){
						new Noty({ theme: 'bootstrap-v4', layout: 'bottomRight',
							type: 'success',
							text: 'Password reset successful!',
							timeout: 2000
						}).show();
						$('#resetPasswordModal').modal('hide');
					}else if(response.status === false){
						new Noty({ theme: 'bootstrap-v4', layout: 'bottomRight',
							type: 'error',
							text: 'Username or email incorrect',
							timeout: 2000
						}).show();
					}
				},
				error: function(response){
					new Noty({ theme: 'bootstrap-v4', layout: 'bottomRight',
						type: 'error',
						text: 'Password reset failed!',
						timeout: 2000
					}).show();
				}
			})
		}
		$("input#username").val("");
		$("input#newPassword").val(""),
			$("input#confirmPassword").val("");
	}
});

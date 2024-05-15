var app = app || {};

app.views.loginView = Backbone.View.extend({
    el: ".container",

    render: function () {
        template = _.template($('#login_template').html());
        this.$el.html(template(this.model.attributes));
		console.log("login template");
        // $("#logout").hide();
    },
    events: {
        "click #login_button": "do_login",
		"click #forgetPasswordChange": "forgetPasswordChange"
    },

    do_login: function (e) {
		e.preventDefault();
		e.stopPropagation();

		var validateForm = validateLoginForm();
		if (!validateForm) {
			new Noty({ theme: 'bootstrap-v4', layout: 'bottomCenter',
				type: 'error',
				text: 'Please Enter the Cridential',
				timeout: 2000
			}).show();
			$("#errLog").html("Please fill the form");
		}else {
			this.model.set(validateForm);
			var url = this.model.url + "signin";
			console.log("url: ", url);
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
					console.log("Login Done");
					app.appRouter.navigate("home", {trigger: true});
				},
				error:function (model,xhr) {
					if(xhr.statsu=400){
						$("#errLog").html("Username or Password Incorrect");
						new Noty({ theme: 'bootstrap-v4', layout: 'bottomRight',
							type: 'error',
							text: 'Username or Password Incorrect',
							timeout: 2000
						}).show();
					}
				}
			});
			console.log("detils has been filled");
		}
        console.log("click login");
    },

	forgetPasswordChange: function (){
		console.log("forgetPasswordChange");
		// userJson = JSON.parse(localStorage.getItem("user"));
		// var user_id = userJson['user_id'];

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

			var url = this.model.url + "forget_password";

			$.ajax({
				url: url,
				type: 'POST',
				data: userPass,
				success: (response) =>{
					console.log("response", response);
					if(response.status === true){
						new Noty({ theme: 'bootstrap-v4', layout: 'bottomRight',
							type: 'success',
							text: 'Password changed successfully',
							timeout: 2000
						}).show();
						$('#forgetPasswordModel').modal('hide');
					}else if(response.status === false){
						new Noty({ theme: 'bootstrap-v4', layout: 'bottomRight',
							type: 'error',
							text: 'Username or email incorrect',
							timeout: 2000
						}).show();
					}
				},
				error: function(response){
					console.error("Error:", response);
					new Noty({ theme: 'bootstrap-v4', layout: 'bottomRight',
						type: 'error',
						text: 'Failed to update password. Please try again.',
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

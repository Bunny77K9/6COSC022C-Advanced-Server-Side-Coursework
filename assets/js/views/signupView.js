var app = app || {};


app.views.signupView = Backbone.View.extend({
	el: ".container",

	render: function () {
		template = _.template($('#signup_template').html());
		this.$el.html(template(this.model.attributes));
		console.log("signup template");
		// $("#logout").hide();
	},
	events: {
		"click #signup_button": "do_register",
	},

	do_register: function (e) {
		e.preventDefault();
		e.stopPropagation();

		var validateForm = validateRegisterForm();
		if (!validateForm) {
			$("#errSign").html("Please fill the form");
		} else {
			console.log("validateForm: ");
			this.model.set(validateForm);
			var url = this.model.url + "signup";
			this.model.save(this.model.attributes, {
				"url": url,
				success: function (model, response) {
					new Noty({ theme: 'bootstrap-v4', layout: 'bottomRight',
						type: 'success',
						text: 'Registration successful',
						timeout: 2000
					}).show();
					console.log("Registration Done");
					app.appRouter.navigate("", {trigger: true, replace: true}); // Navigate to root route
				},

				error: function (model, xhr) {
					if (xhr.status === 409) {
						$("#errSign").html(xhr.responseJSON.data);
						new Noty({ theme: 'bootstrap-v4', layout: 'bottomRight',
							type: 'error',
							text: 'Username or Email already exists',
							timeout: 2000
						}).show();
					} else {
						$("#errSign").html();
					}
				}
			});

			console.log("details are filled");
			console.log("validateForm: ", validateForm);
		}

		$('#regFirstname').val('');
		$('#regLastname').val('');
		$('#regUsername').val('');
		$('#regPassword').val('');
		$('#regOccupation').val('');
		$('#regEmail').val('');
	}
});

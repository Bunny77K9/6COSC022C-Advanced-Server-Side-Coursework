var app = app || {};

app.views.signupView = Backbone.View.extend({
	el: ".container",

	render: function () {
		template = _.template($('#signup_template').html());
		this.$el.html(template(this.model.attributes));
	},

	events: {
		"click #signup-button": "register",
	},

	register: function (e) {
		e.preventDefault();
		e.stopPropagation();

		var validateForm = validateRegisterForm();

		if (!validateForm.username || !validateForm.password || !validateForm.email || !validateForm.firstname ||
			!validateForm.lastname || !validateForm.occupation) {
			$("#signup-error").html("Please fill all fields!");
			new Noty({
				theme: 'bootstrap-v4', layout: 'bottomRight',
				type: 'error',
				text: "Form validation error: " + validateForm,
				timeout: 2000
			}).show();
		} else {
			this.model.set(validateForm);
			var url = this.model.url + "signup";
			this.model.save(this.model.attributes, {
				"url": url,
				success: function (model, response) {
					new Noty({
						theme: 'bootstrap-v4', layout: 'bottomRight',
						type: 'success',
						text: 'Registration successful!',
						timeout: 2000
					}).show();
					app.appRouter.navigate("", {trigger: true, replace: true});
				},
				error: function (model, xhr) {
					if (xhr.status === 409) {
						$("#signup-error").html(xhr.responseJSON.data);
						new Noty({
							theme: 'bootstrap-v4', layout: 'bottomRight',
							type: 'error',
							text: 'Username or Email already exists!',
							timeout: 2000
						}).show();
					} else {
						$("#signup-error").html();
					}
				}
			});
		}
		$('#signupFirstname').val('');
		$('#signupLastname').val('');
		$('#signupUsername').val('');
		$('#signupPassword').val('');
		$('#signupOccupation').val('');
		$('#signupEmail').val('');
	}
});

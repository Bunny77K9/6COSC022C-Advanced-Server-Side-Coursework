var app = app || {};

app.views.newQuestionView = Backbone.View.extend({
	el: '.container',

	render: function () {
		template = _.template($('#new-question-template').html());
		this.$el.html(template(this.model.attributes));

		app.navbarView = new app.views.navbarView({model: app.user});
		app.navbarView.render();
	},

	events: {
		'click #submit-question': 'submitQuestion'
	},

	submitQuestion: function (e) {
		e.preventDefault();
		e.stopPropagation();

		var validateQuestionForm = validateQuestionAddForm();

		if (!validateQuestionForm.title) {
			new Noty({
				theme: 'bootstrap-v4', layout: 'bottomRight',
				type: 'error',
				text: "Form validation error: " + validateQuestionForm,
				timeout: 2000
			}).show();
		} else {
			var formData = new FormData();
			var imageFile = $('#imageUpload')[0].files[0];
			formData.append('image', imageFile);

			$.ajax({
				url: this.model.urlAskQuestion + 'new_question_image',
				type: 'POST',
				data: formData,
				processData: false,
				contentType: false,
				success: (response) => {
					validateQuestionForm.images = response.imagePath;
					this.model.set(validateQuestionForm);
					var url = this.model.urlAskQuestion + "add_new_question";
					this.model.save(this.model.attributes, {
						"url": url,
						success: (model, response) => {
							new Noty({
								theme: 'bootstrap-v4', layout: 'bottomRight',
								type: 'success',
								text: 'Question created successfully!',
								timeout: 2000
							}).show();

							$userJson = JSON.parse(localStorage.getItem("user"));
							$userJson['questioncount'] = parseInt($userJson['questioncount']) + 1;
							localStorage.setItem("user", JSON.stringify($userJson));

							$('#inputQuestionTitle').val('');
							$('#inputQuestionDetails').val('');
							$('#inputQuestionExpectation').val('');
							$('#inputQuestionTags').val('');
							$('#questionCategory').val('');
							$('#imageUpload').val('');

							app.appRouter.navigate("questions", {trigger: true});
						},
						error: (model, response) => {
							new Noty({
								theme: 'bootstrap-v4', layout: 'bottomRight',
								type: 'error',
								text: 'Error adding question',
								timeout: 2000
							}).show();
						}
					});
				},
				error: (xhr, status, error) => {
					new Noty({
						theme: 'bootstrap-v4', layout: 'bottomRight',
						type: 'error',
						text: 'Error uploading image!',
						timeout: 2000
					}).show();
				}
			});
		}
	}
})

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
		'click #submit-question': 'submitQuestion',
		"click #search-question": "questionSearch"
	},

	submitQuestion: function (e) {
		e.preventDefault();
		e.stopPropagation();

		var validateQuestionForm = validateQuestionAddForm();

		if (!validateQuestionForm.title) {
			new Noty({ theme: 'bootstrap-v4', layout: 'bottomRight',
				type: 'error',
				text: "Form validation error: " + validateQuestionForm,
				timeout: 2000
			}).show();
		} else {
			var formData = new FormData();
			var imageFile = $('#imageUpload')[0].files[0];
			formData.append('image', imageFile);

			$.ajax({
				url: this.model.url + 'new_question_image',
				type: 'POST',
				data: formData,
				processData: false,
				contentType: false,
				success: (response) => {
					validateQuestionForm.images = response.imagePath;
					this.model.set(validateQuestionForm);
					var url = this.model.urlAskQuestion + "addquestion";
					this.model.save(this.model.attributes, {
						"url": url,
						success: (model, response) => {
							new Noty({ theme: 'bootstrap-v4', layout: 'bottomRight',
								type: 'success',
								text: 'Question created successfully!',
								timeout: 2000
							}).show();

							$userJson = JSON.parse(localStorage.getItem("user"));
							$userJson['askquestioncnt'] = parseInt($userJson['askquestioncnt']) + 1;
							localStorage.setItem("user", JSON.stringify($userJson));

							$('#inputQuestionTitle').val('');
							$('#inputQuestionDetails').val('');
							$('#inputQuestionExpectation').val('');
							$('#inputQuestionTags').val('');
							$('#questionCategory').val('');
							$('#imageUpload').val('');

							app.appRouter.navigate("home", {trigger: true});
						},
						error: (model, response) => {
							new Noty({ theme: 'bootstrap-v4', layout: 'bottomRight',
								type: 'error',
								text: 'Error adding question',
								timeout: 2000
							}).show();
						}
					});
				},
				error: (xhr, status, error) => {
					new Noty({ theme: 'bootstrap-v4', layout: 'bottomRight',
						type: 'error',
						text: 'Error uploading image!',
						timeout: 2000
					}).show();
				}
			});
		}
	},

	questionSearch:
		function (e) {
			e.preventDefault();
			e.stopPropagation();

			var validateAnswer = validateSearchForm();
			var searchValue = $("#srearch-question-input").val();

			if (searchValue != "") {
				app.user = new app.models.User(userJson);
				app.homeView = new app.views.homeView({collection: new app.collections.QuestionCollection()});

				var url = app.homeView.collection.url + "search_questions/" + searchValue;
				app.homeView.collection.fetch({
					reset: true,
					"url": url,
					success: function (collection, response) {
						app.homeView.render();
					},
					error: function (model, xhr, options) {
						if (xhr.status == 204) {
							app.homeView.render();
						}
					}
				});
			} else {
				app.user = new app.models.User(userJson);
				app.homeView = new app.views.homeView({collection: new app.collections.QuestionCollection()});

				var url = app.homeView.collection.url + "display_all_questions";

				app.homeView.collection.fetch({
					reset: true,
					"url": url,
					success: function (collection, response) {
						app.homeView.render();
					},
					error: function (model, xhr, options) {
						if (xhr.status == 404) {
							app.homeView.render();
						}
					}
				});
			}
		}
})

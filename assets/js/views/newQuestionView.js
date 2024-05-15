var app = app || {};

app.views.newQuestionView = Backbone.View.extend({
	el: '.container',

	render: function () {
		console.log('rendering add question view');
		template = _.template($('#add_question_template').html());
		console.log('model attributes:', this.model.attributes);
		this.$el.html(template(this.model.attributes));

		app.navbarView = new app.views.navbarView({model: app.user});
		app.navbarView.render();

	},

	events: {
		'click #submit_question': 'submitquestion',
		"click #homesearch": "home_search"
	},
	submitquestion: function (e) {
		e.preventDefault();
		e.stopPropagation();

		// console.log('submitting question');
		// userJson = JSON.parse(localStorage.getItem('user'));
		// app.user = new app.models.User(userJson);

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
				url: this.model.url + 'ask_question_image',
				type: 'POST',
				data: formData,
				processData: false,
				contentType: false,
				success: (response) => {
					console.log('Image uploaded successfully:', response);
					validateQuestionForm.images = response.imagePath; // Assuming the server returns the image path
					this.model.set(validateQuestionForm);
					var url = this.model.urlAskQuestion + "addquestion";
					console.log("url", url);
					this.model.save(this.model.attributes, {
						"url": url,
						success: (model, response) => {
							console.log('success', model, response);
							new Noty({ theme: 'bootstrap-v4', layout: 'bottomRight',
								type: 'success',
								text: 'Question added successfully',
								timeout: 2000
							}).show();

							$userJson = JSON.parse(localStorage.getItem("user"));
							console.log('userJson: ', $userJson);
							$userJson['askquestioncnt'] = parseInt($userJson['askquestioncnt']) + 1;
							localStorage.setItem("user", JSON.stringify($userJson));

							console.log('question added successfull!');

							$('#inputQuestionTitle').val('');
							$('#inputQuestionDetails').val('');
							$('#inputQuestionExpectation').val('');
							$('#inputQuestionTags').val('');
							$('#questionCategory').val('');
							$('#imageUpload').val('');

							app.appRouter.navigate("home", {trigger: true});
						},
						error: (model, response) => {
							console.log('error', model, response);
							new Noty({ theme: 'bootstrap-v4', layout: 'bottomRight',
								type: 'error',
								text: 'Error adding question',
								timeout: 2000
							}).show();
						}
					});
				},
				error: (xhr, status, error) => {
					console.error('Error uploading image:', error);
					new Noty({ theme: 'bootstrap-v4', layout: 'bottomRight',
						type: 'error',
						text: 'Error uploading image',
						timeout: 2000
					}).show();
				}
			});
		}

		console.log('emptying fields');

		// $('#inputQuestionTitle').val('');
		// $('#inputQuestionDetails').val('');
		// $('#inputQuestionExpectation').val('');
		// $('#inputQuestionTags').val('');
		// $('#questionCategory').val('');
		// $('#imageUpload').val('');
	},

	home_search:
		function (e) {
			e.preventDefault();
			e.stopPropagation();

			var validateAnswer = validateSearchForm();

			// var search = {
			// 	'search': $("input#searchHome").val()
			// };
			var searchWord = $("#searchHome").val();

			if (searchWord != "") {
				console.log('searching')

				app.user = new app.models.User(userJson);
				console.log("user: " + app.user);
				// app.homeView = new app.views.homeView();
				app.homeView = new app.views.homeView({collection: new app.collections.QuestionCollection()});

				var url = app.homeView.collection.url + "displaySearchQuestions/" + searchWord;
				console.log("url: " + url);
				app.homeView.collection.fetch({
					reset: true,
					"url": url,
					success: function (collection, response) {
						console.log("response: " + response);
						app.homeView.render();
					},
					error: function (model, xhr, options) {
						if (xhr.status == 404) {
							// console.log("error 404");
							app.homeView.render();
						}
						// console.log("error");
					}
				});
			} else {

				app.user = new app.models.User(userJson);
				console.log("user: " + app.user);
				// app.homeView = new app.views.homeView();
				app.homeView = new app.views.homeView({collection: new app.collections.QuestionCollection()});

				var url = app.homeView.collection.url + "displayAllQuestions";
				// console.log("url: "+ url);
				app.homeView.collection.fetch({
					reset: true,
					"url": url,
					success: function (collection, response) {
						console.log("response: " + response);
						app.homeView.render();
					},
					error: function (model, xhr, options) {
						if (xhr.status == 404) {
							// console.log("error 404");
							app.homeView.render();
						}
						// console.log("error");
					}
				});
			}

			// app.appRouter.navigate("home/search/"+search, {trigger: true});
		}

})

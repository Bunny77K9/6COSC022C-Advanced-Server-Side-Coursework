var app = app || {};

app.views.questionAnswerView = Backbone.View.extend({
	el: '.container',

	render: function () {
		template = _.template($('#question-answer-template').html());
		this.$el.html(template(this.model.attributes));

		app.navbarView = new app.views.navbarView({model: app.user});
		app.navbarView.render();

		this.collection.each(function (answer) {
			var answerView = new app.views.answerView({model: answer});
			answerView.render();
		});
	},

	events: {
		'click #upwote-question': 'upvoteQuestion',
		'click #downwote-question': 'downvoteQuestion',
		'click #upwote-answer': 'upvoteAnswer',
		'click #downwote-answer': 'downvoteAnswer',
		'click #add-bookmark': 'addBookmark',
		'click #remove-bookmark': 'removeBookmark',
		'click #submit-answer': 'submitAnswer',
	},

	addBookmark: function () {

		var currentUrl = window.location.href;
		var lastPart = currentUrl.substring(currentUrl.lastIndexOf('/') + 1);
		var $questionid = parseInt(lastPart.match(/\d+$/)[0]);

		$userJson = JSON.parse(localStorage.getItem("user"));
		$userid = $userJson['user_id'];

		var $bookmarkIcon = $('#add-bookmark');

		var bookmark = {
			questionid: $questionid,
			userid: $userid
		};

		var url = this.model.url + 'add_bookmark';
		count = 0;

		$.ajax({
			"url": url,
			type: 'POST',
			data: bookmark,
			success: (response) => {
				bookmark["questionid"] = "";
				bookmark["userid"] = "";
				$bookmarkIcon.removeClass('fa-regular').addClass('fa-solid');
				$bookmarkIcon.attr('id', 'remove-bookmark');
			},
			error: (xhr, status, error) => {
				console.error('Error adding bookmark:', error);
			}
		});
	},


	removeBookmark: function () {

		var currentUrl = window.location.href;
		var lastPart = currentUrl.substring(currentUrl.lastIndexOf('/') + 1);
		var $questionid = parseInt(lastPart.match(/\d+$/)[0]);

		$userJson = JSON.parse(localStorage.getItem("user"));
		$userid = $userJson['user_id'];

		var $bookmarkIcon = $('#remove-bookmark');

		var bookmark = {
			questionid: $questionid,
			userid: $userid
		};

		var url = this.model.url + 'remove_bookmark';
		if ($questionid != "" && $questionid != null) {
			app.user.fetch({
				"url": url,
				type: 'POST',
				data: bookmark,
				success: (response) => {
					bookmark["questionid"] = "";
					bookmark["userid"] = "";
					$bookmarkIcon.removeClass('fa-solid').addClass('fa-regular');
					$bookmarkIcon.attr('id', 'add-bookmark');
				},
				error: (xhr, status, error) => {
					console.error('Error removing bookmark:', error);
				}
			});
		}
	},

	upvoteQuestion: function () {

		if ($(this).data('clicked')) {
			return;
		}

		userJson = JSON.parse(localStorage.getItem("user"));
		$questionid = this.model.attributes.questionid;
		var url = this.model.url + 'upvote/' + $questionid;

		if ($questionid != "" && $questionid != null) {
			app.user.fetch({
				"url": url,
				type: 'GET',
				success: (response) => {
					var currentUpwotes = parseInt($('#question-upwotes').text());
					$('#question-upwotes').text(currentUpwotes + 1);
					this.$el.find('#upwote-question').data('clicked', true).css('pointer-events', 'none');
				},
				error: (xhr, status, error) => {
					new Noty({
						theme: 'bootstrap-v4', layout: 'bottomRight',
						type: 'error',
						text: 'Error upvoting',
						timeout: 2000
					}).show();
				}
			});

		} else {
			new Noty({
				theme: 'bootstrap-v4', layout: 'bottomRight',
				type: 'error',
				text: 'Error in upvoting',
				timeout: 2000
			}).show();
		}
	},

	downvoteQuestion: function () {

		if ($(this).data('clicked')) {
			return;
		}

		userJson = JSON.parse(localStorage.getItem("user"));
		$questionid = this.model.attributes.questionid;

		var url = this.model.url + 'downvote/' + $questionid;

		if ($questionid != "" && $questionid != null) {
			app.user.fetch({
				"url": url,
				type: 'GET',
				success: (response) => {
					var currentUpwotes = parseInt($('#question-upwotes').text());
					$('#question-upwotes').text(currentUpwotes - 1);
					this.$el.find('#downwote-question').data('clicked', true).css('pointer-events', 'none');
				},
				error: (xhr, status, error) => {
					new Noty({
						theme: 'bootstrap-v4', layout: 'bottomRight',
						type: 'error',
						text: 'Error downvote',
						timeout: 2000
					}).show();
				}
			});
		} else {
			new Noty({
				theme: 'bootstrap-v4', layout: 'bottomRight',
				type: 'error',
				text: 'Error in downvot',
				timeout: 2000
			}).show();
		}
	},

	upvoteAnswer: function (event) {

		var $clickedButton = $(event.currentTarget);
		var $answerid = $clickedButton.data('answer-id');

		if ($(this).data('clicked')) {
			return;
		}

		userJson = JSON.parse(localStorage.getItem("user"));
		var url = this.model.urlAns + 'upvote/' + $answerid;

		if ($answerid != "" && $answerid != null) {
			app.user.fetch({
				"url": url,
				type: 'GET',
				success: (response) => {
					var currentUpvotesElement = $clickedButton.siblings('.upwotes-count');
					var currentUpwotes = parseInt(currentUpvotesElement.text());
					currentUpvotesElement.text(currentUpwotes + 1);
					$clickedButton.data('clicked', true).css('pointer-events', 'none');
				},
				error: (xhr, status, error) => {
					new Noty({
						theme: 'bootstrap-v4', layout: 'bottomRight',
						type: 'error',
						text: 'Error upvoting',
						timeout: 2000
					}).show();
				}
			});

		} else {
			new Noty({
				theme: 'bootstrap-v4', layout: 'bottomRight',
				type: 'error',
				text: 'Error in upvoting',
				timeout: 2000
			}).show();
		}
	},

	downvoteAnswer: function (event) {
		var $clickedButton = $(event.currentTarget);
		var $answerid = $clickedButton.data('answer-id');

		if ($(this).data('clicked')) {
			return;
		}

		userJson = JSON.parse(localStorage.getItem("user"));
		var url = this.model.urlAns + 'downvote/' + $answerid;

		if ($answerid != "" && $answerid != null) {
			app.user.fetch({
				"url": url,
				type: 'GET',
				success: (response) => {
					var currentUpvotesElement = $clickedButton.siblings('.upwotes-count');
					var currentUpwotes = parseInt(currentUpvotesElement.text());
					currentUpvotesElement.text(currentUpwotes - 1);
					$clickedButton.data('clicked', true).css('pointer-events', 'none');
				},
				error: (xhr, status, error) => {
					new Noty({
						theme: 'bootstrap-v4', layout: 'bottomRight',
						type: 'error',
						text: 'Error downvote',
						timeout: 2000
					}).show();
				}
			});

		} else {
			new Noty({
				theme: 'bootstrap-v4', layout: 'bottomRight',
				type: 'error',
				text: 'Error in downvot',
				timeout: 2000
			}).show();
		}
	},


	submitAnswer: function (e) {
		e.preventDefault();
		e.stopPropagation();

		var validateAnswer = validateAnswerForm();

		if (validateAnswer.answer) {
			var formData = new FormData();
			var imageFIle = $('#answerImageUpload')[0].files[0];
			formData.append('image', imageFIle);
			$.ajax({
				url: this.model.urlAns + 'ans_image',
				type: 'POST',
				data: formData,
				processData: false,
				contentType: false,
				success: (response) => {
					validateAnswer.answerimage = response.imagePath;
					this.model.set(validateAnswer);
					$questionid = this.model.attributes.questionid;
					var url = this.model.urlAns + "add_answer";
					this.model.save(this.model.attributes, {
						"url": url,
						success: (model, response) => {
							new Noty({
								theme: 'bootstrap-v4', layout: 'bottomRight',
								type: 'success',
								text: 'Answer submitted successfully',
								timeout: 2000
							}).show();

							$userJson = JSON.parse(localStorage.getItem("user"));
							$userJson['answerquestioncnt'] = parseInt($userJson['answerquestioncnt']) + 1;

							localStorage.setItem("user", JSON.stringify($userJson));

							this.collection.add(model);
							var newAnswerView = new app.views.answerView({model: model});
							newAnswerView.render();
						},
						error: (model, response) => {
							new Noty({
								theme: 'bootstrap-v4', layout: 'bottomRight',
								type: 'error',
								text: 'Error in submitting answer',
								timeout: 2000
							}).show();
						}
					})
				},
				error: (xhr, status, error) => {
					new Noty({
						theme: 'bootstrap-v4', layout: 'bottomRight',
						type: 'error',
						text: 'Error uploading image',
						timeout: 2000
					}).show();
				}
			});

			$('#inputQuestionDetails').val('');
			$('#answerImageUpload').val('');
			$('#questionrate').val('');
		} else {
			setTimeout(function () {
				new Noty({
					theme: 'bootstrap-v4', layout: 'bottomRight',
					type: 'error',
					text: 'Form validation error: ' + validateAnswer,
					timeout: 2000
				}).show();
			}, 1500);
		}
	}
})

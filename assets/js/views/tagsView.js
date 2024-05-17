var app = app || {};

app.views.tagsView = Backbone.View.extend({
	el: ".container",

	render: function () {
		template = _.template($("#tags-template").html());
		this.$el.html(template(app.user.attributes));

		app.navbarView = new app.views.navbarView({model: app.user});
		app.navbarView.render();

		this.collection.each(function (question) {
			var questionView = new app.views.QuestionView({model: question});
			questionView.render();
		})

		this.fetchTags();
	},

	events: {
		"click #ask-question": "newQuestion",
		"click #search-tag": "getQuestionsTags",
	},

	fetchTags: function () {
		var self = this;
		app.tagsView = new app.views.tagsView({collection: new app.collections.QuestionCollection()});

		var url = app.tagsView.collection.url + "display_all_tags";

		$.ajax({
			url: url,
			type: 'GET',
			success: function (response) {
				self.generateTagsButtons(response);
			},
			error: function (xhr, status, error) {
				console.error('Error fetching tags:', error);
			}
		});
	},

	generateTagsButtons: function (tags) {
		var $container = this.$('#tag-buttons');

		$container.empty();

		for (var i = 0; i < tags.length; i++) {
			var $button = $('<button>', {
				class: 'btn btn-outline-primary m-1',
				id: 'search-tag',
				'data-tag': tags[i].tags,
				text: tags[i].tags
			});
			$container.append($button);
		}
	},

	newQuestion: function (e) {
		e.preventDefault();
		e.stopPropagation();

		app.appRouter.navigate("home/newquestion", {trigger: true});
	},

	getQuestionsTags: function (e) {
		e.preventDefault();
		e.stopPropagation();

		var tag = $(e.currentTarget).data("tag");

		this.$('.tag-btn').removeClass('btn-active');
		$(e.currentTarget).addClass('btn-active');

		if (tag !== "") {
			app.user = new app.models.User(userJson);
			app.tagsView = new app.views.tagsView({collection: new app.collections.QuestionCollection()});

			var url = app.tagsView.collection.url + "display_tag_questions/" + tag;
			app.tagsView.collection.fetch({
				reset: true,
				"url": url,
				success: function (collection, response) {
					if (!response) {
						new Noty({
							theme: 'bootstrap-v4', layout: 'bottomRight',
							type: 'error',
							text: "No questions found!",
							timeout: 2000
						}).show();
					}
					app.tagsView.render();
				},
				error: function (model, xhr, options) {
					if (xhr.status == 204) {
						app.tagsView.render();
					}
				}
			});
		} else {
			app.user = new app.models.User(userJson);
			app.tagsView = new app.views.tagsView({collection: new app.collections.QuestionCollection()});
			var url = app.tagsView.collection.url + "display_all_questions";

			app.tagsView.collection.fetch({
				reset: true,
				"url": url,
				success: function (collection, response) {
					app.tagsView.render();
				},
				error: function (model, xhr, options) {
					if (xhr.status == 404) {
						app.tagsView.render();
					}
				}
			});
		}
	}
})

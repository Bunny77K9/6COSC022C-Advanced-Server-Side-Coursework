var app = app || {};

app.views.categoryView = Backbone.View.extend({
	el: ".container",

	render: function () {
		template = _.template($("#category-template").html());
		this.$el.html(template(app.user.attributes));

		app.navbarView = new app.views.navbarView({model: app.user});
		app.navbarView.render();

		this.collection.each(function (question) {
			var questionView = new app.views.QuestionView({model: question});
			questionView.render();
		})

		this.fetchCategories();
	},

	events: {
		"click #ask-question": "newQuestion",
		"click #search-category": "getQuestionsCategory",
	},

	fetchCategories: function () {
		var self = this;
		app.categoryView = new app.views.categoryView({collection: new app.collections.QuestionCollection()});
		var url = app.categoryView.collection.url + "display_all_categories";

		$.ajax({
			url: url,
			type: 'GET',
			success: function (response) {
				self.generateCategoryButtons(response);
			},
			error: function (xhr, status, error) {
				console.error('Error fetching categories:', error);
			}
		});
	},

	generateCategoryButtons: function (categories) {
		var $container = this.$('#category-buttons');

		$container.empty();

		for (var i = 0; i < categories.length; i++) {
			var $button = $('<button>', {
				class: 'btn btn-outline-primary category-btn',
				id: 'search-category',
				'data-category': categories[i].category,
				text: categories[i].category
			});
			$container.append($button);
		}
	},

	newQuestion: function (e) {
		e.preventDefault();
		e.stopPropagation();

		app.appRouter.navigate("home/newquestion", {trigger: true});
	},

	getQuestionsCategory: function (e) {
		e.preventDefault();
		e.stopPropagation();

		var category = $(e.currentTarget).data("category");

		this.$('.category-btn').removeClass('btn-active');
		$(e.currentTarget).addClass('btn-active');

		if (category !== "") {
			app.user = new app.models.User(userJson);
			app.categoryView = new app.views.categoryView({collection: new app.collections.QuestionCollection()});

			var url = app.categoryView.collection.url + "displayCategoryQuestions/" + category;
			app.categoryView.collection.fetch({
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
					app.categoryView.render();
				},
				error: function (model, xhr, options) {
					if (xhr.status == 204) {
						app.categoryView.render();
					}
				}
			});
		} else {
			app.user = new app.models.User(userJson);
			app.categoryView = new app.views.categoryView({collection: new app.collections.QuestionCollection()});
			var url = app.categoryView.collection.url + "display_all_questions";

			app.categoryView.collection.fetch({
				reset: true,
				"url": url,
				success: function (collection, response) {
					app.categoryView.render();
				},
				error: function (model, xhr, options) {
					if (xhr.status == 404) {
						app.categoryView.render();
					}
				}
			});
		}
	}
})

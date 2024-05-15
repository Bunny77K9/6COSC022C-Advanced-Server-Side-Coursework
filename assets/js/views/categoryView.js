var app = app || {};

app.views.categoryView = Backbone.View.extend({
	el: ".container",

	render: function () {
		console.log('rendering category view');
		template = _.template($("#category_template").html());
		this.$el.html(template(app.user.attributes));

		// Render the navbar
		app.navbarView = new app.views.navbarView({model: app.user});
		app.navbarView.render();

		this.collection.each(function (question) {
			var questionView = new app.views.QuestionView({model: question});
			questionView.render();
		})

		this.fetchCategories();
	},

	events: {
		"click #ask_question_btn": "ask_question",
		"click #search-category": "search_category",
	},

	fetchCategories: function() {
		var self = this;

		app.categoryView = new app.views.categoryView({collection: new app.collections.QuestionCollection()});

		var url = app.categoryView.collection.url + "displayAllCategories";

		$.ajax({
			url: url,
			type: 'GET',
			success: function(response) {
				console.log('Fetched categories:', response);
				console.log('response: ' + response[0].category);
				self.generateCategoryButtons(response);
			},
			error: function(xhr, status, error) {
				console.error('Error fetching categories:', error);
			}
		});
	},

	generateCategoryButtons: function(categories) {
		var $container = this.$('#category-buttons');

		$container.empty();

		// Generate buttons for each category
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

	ask_question: function (e) {
		e.preventDefault();
		e.stopPropagation();

		console.log('ask question');
		app.appRouter.navigate("home/askquestion", {trigger: true});
	},

	search_category: function (e) {
		e.preventDefault();
		e.stopPropagation();

		var category = $(e.currentTarget).data("category");

		// print existing classes
		console.log('search category:', $(e.currentTarget).attr('class'));

		this.$('.category-btn').removeClass('btn-active');

		console.log('search category:', $(e.currentTarget).attr('class'));

		// add active class
		$(e.currentTarget).addClass('btn-active');

		console.log('search category:', $(e.currentTarget).attr('class'));

		if (category !== "") {
			console.log('searching')

			app.user = new app.models.User(userJson);
			console.log("user: " + app.user);

			app.categoryView = new app.views.categoryView({collection: new app.collections.QuestionCollection()});

			var url = app.categoryView.collection.url + "displayCategoryQuestions/" + category;
			console.log("url: " + url);
			app.categoryView.collection.fetch({
				reset: true,
				"url": url,
				success: function (collection, response) {
					console.log("response: " + response);
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
			console.log("user: " + app.user);

			app.categoryView = new app.views.categoryView({collection: new app.collections.QuestionCollection()});

			var url = app.categoryView.collection.url + "displayAllQuestions";

			app.categoryView.collection.fetch({
				reset: true,
				"url": url,
				success: function (collection, response) {
					console.log("response: " + response);
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

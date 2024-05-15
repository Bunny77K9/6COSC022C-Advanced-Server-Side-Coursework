var app = app || {};

app.views.homeView = Backbone.View.extend({
	el: ".container",

	render: function () {
		template = _.template($('#home-template').html());
		this.$el.html(template(app.user.attributes));

		app.navbarView = new app.views.navbarView({model: app.user});
		app.navbarView.render();

		this.collection.each(function (question){
			var questionView = new app.views.QuestionView({model: question});
			questionView.render();
		})
	},
	events: {
		"click #ask-question-button": "newQuestion",
		"click #search-question": "questionSearch",
	},

	newQuestion: function (e) {
		e.preventDefault();
		e.stopPropagation();
		app.appRouter.navigate("home/newquestion", {trigger: true});
	},

	questionSearch: function (e) {
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
					if (!response) {
						new Noty({
							theme: 'bootstrap-v4', layout: 'bottomRight',
							type: 'error',
							text: "No questions found!",
							timeout: 2000
						}).show();
					}
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
});

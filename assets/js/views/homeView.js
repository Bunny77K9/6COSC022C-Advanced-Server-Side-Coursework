var app = app || {};

app.views.homeView = Backbone.View.extend({
	el: ".container",

	render: function () {
		console.log('rendering home view');
		template = _.template($('#home_template').html());
		this.$el.html(template(app.user.attributes));

		// Render the navbar
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

		console.log('ask question');
		app.appRouter.navigate("home/askquestion", {trigger: true});
	},

	questionSearch: function (e) {
		e.preventDefault();
		e.stopPropagation();

		var validateAnswer = validateSearchForm();

		// var search = {
		// 	'search': $("input#srearch-question-input").val()
		// };
		var searchValue = $("#srearch-question-input").val();

		if (searchValue != "") {
			console.log('searching')

			app.user = new app.models.User(userJson);
			console.log("user: " + app.user);
			// app.homeView = new app.views.homeView();
			app.homeView = new app.views.homeView({collection: new app.collections.QuestionCollection()});

			var url = app.homeView.collection.url + "search_questions/" + searchValue;
			console.log("url: " + url);
			app.homeView.collection.fetch({
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
			console.log("user: " + app.user);
			// app.homeView = new app.views.homeView();
			app.homeView = new app.views.homeView({collection: new app.collections.QuestionCollection()});

			var url = app.homeView.collection.url + "display_all_questions";
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
						app.homeView.render();
					}
				}
			});
		}
	}
});

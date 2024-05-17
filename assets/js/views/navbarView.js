var app = app || {};

app.views.navbarView = Backbone.View.extend({
	el: '#navbar-container',

	render: function(){
		template = _.template($('#navbar-template').html());
		this.$el.html(template(this.model.attributes));
	},

	events: {
		"click #search-question": "questionSearch",
	},

	questionSearch: function (e) {
		e.preventDefault();
		e.stopPropagation();

		var validateSearch = validateSearchForm();

		if (validateSearch) {
			app.user = new app.models.User(userJson);
			app.questionsView = new app.views.questionsView({collection: new app.collections.QuestionCollection()});

			var url = app.questionsView.collection.url + "display_search_questions/" + validateSearch.search

			app.questionsView.collection.fetch({
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
					app.questionsView.render();
				},
				error: function (model, xhr, options) {
					if (xhr.status == 204) {
						app.questionsView.render();
					}
				}
			});
		} else {
			app.user = new app.models.User(userJson);
			app.questionsView = new app.views.questionsView({collection: new app.collections.QuestionCollection()});

			var url = app.questionsView.collection.url + "display_all_questions";

			app.questionsView.collection.fetch({
				reset: true,
				"url": url,
				success: function (collection, response) {
					app.questionsView.render();
				},
				error: function (model, xhr, options) {
					if (xhr.status == 404) {
						app.questionsView.render();
					}
				}
			});
		}
	}
});

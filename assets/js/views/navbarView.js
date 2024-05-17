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
			app.homeView = new app.views.homeView({collection: new app.collections.QuestionCollection()});

			var url = app.homeView.collection.url + "display_search_questions/" + validateSearch.search

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

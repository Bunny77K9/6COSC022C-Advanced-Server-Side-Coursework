var app = app || {};

app.views.bookmarksView = Backbone.View.extend({
	el: ".container",

	render: function () {
		template = _.template($("#bookmark-template").html());
		this.$el.html(template(app.user.attributes));

		app.navbarView = new app.views.navbarView({model: app.user});
		app.navbarView.render();

		this.collection.each(function (question) {
			var questionView = new app.views.QuestionView({model: question});
			questionView.render();
		})
	},

	events: {
		"click #ask-question": "newQuestion",
	},

	newQuestion: function (e) {
		e.preventDefault();
		e.stopPropagation();
		app.appRouter.navigate("questions/newquestion", {trigger: true});
	},
})

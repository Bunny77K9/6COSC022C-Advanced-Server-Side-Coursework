var app = app || {};

app.views.bookmarksView = Backbone.View.extend({
	el: ".container",

	render: function(){
		console.log("rendering bookmark view");
		template = _.template($("#bookmark_View").html());
		this.$el.html(template(app.user.attributes));

		app.navbarView = new app.views.navbarView({model: app.user});
		app.navbarView.render();

		this.collection.each(function(question){
			var questionView = new app.views.QuestionView({model: question});
			questionView.render();
		})
	},

	events: {
		"click #ask_question_btn": "ask_question",
		"click #homesearch": "home_search",
	},

	ask_question: function (e) {
		e.preventDefault();
		e.stopPropagation();

		console.log('ask question');
		app.appRouter.navigate("home/askquestion", {trigger: true});
	},
})

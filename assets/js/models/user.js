var app = app || {};
app.models.User = Backbone.Model.extend({
	urlRoot: '/DevForum/index.php/api/User/',
	defaults: {
		firstname: "",
		lastname: "",
		email: "",
		username: "",
		password: "",
		user_id: null,
		title: "",
		premium: false,
		userimage: "",
		answercount: null,
		questioncount: null,
	},
	url: '/DevForum/index.php/api/User/',
	urlAskQuestion: '/DevForum/index.php/api/Question/',
	urlAnswerQuestion: '/DevForum/index.php/api/Answer/'

});

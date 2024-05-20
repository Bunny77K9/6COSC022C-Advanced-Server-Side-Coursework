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
		profileimg: "",
		answercount: null,
		questioncount: null,
	},
	url: '/DevForum/index.php/api/User/',
	urlAskQuestion: '/DevForum/index.php/api/Question/'

});

var app = app || {};
app.models.User = Backbone.Model.extend({
	urlRoot: '/index.php/api/User/',
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
	url: '/index.php/api/User/',
	urlAskQuestion: '/index.php/api/Question/'

});

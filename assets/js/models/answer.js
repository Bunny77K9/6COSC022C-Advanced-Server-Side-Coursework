var app = app || {};

app.models.Answers = Backbone.Model.extend({
	urlRoot: '/DevForum/index.php/api/Answer/',
	defaults:{
		answerid: null,
		questionid: null,
		user_id: null,
		answer: null,
		answerimage: null,
		answerrate: null,
		rate: null,
		questionrate: null,
		viewstatus: null,
		answereddate: null
	},
	url: '/DevForum/index.php/api/Answer/',
});

app.collections.AnswerCollection = Backbone.Collection.extend({
	model: app.models.Answers,
	url: '/DevForum/index.php/api/Answer/',
});

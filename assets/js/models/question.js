var app = app || {};

app.models.Questions = Backbone.Model.extend({
	urlRoot: '/index.php/api/Question/',
	defaults: {
		user_id: null,
		title: null,
		description: null,
		expectation: null,
		images: null,
		category: null,
		tags: null,
		upwotes:null,
		answerrate:null,
		is_bookmark:null,
		views:null,
		date:null,
		answereddate:null,
	},
	url: '/index.php/api/Question/',
	urlAns: '/index.php/api/Answer/',
});

app.collections.QuestionCollection = Backbone.Collection.extend({
	model: app.models.Questions,
	url: '/index.php/api/Question/',
});

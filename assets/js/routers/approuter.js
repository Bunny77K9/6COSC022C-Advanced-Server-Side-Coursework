var app = app || {};

app.routers.AppRouter = Backbone.Router.extend({

	routes: {
		"": "login",
		"signup": "signup",
		"home": "home",
		"home/category": "category",
		"home/tags": "tags",
		"home/newquestion": "newquestion",
		"home/answerquestion/:questionid": "answerquestion",
		"home/bookmark/:userid": "bookmark",
		"home/user/:userid": "user",
		"logout": "logout"
	},

	login: function () {
		userJson = JSON.parse(localStorage.getItem("user"));
		if(userJson == null){
			if(!app.loginView) {
				app.user = new app.models.User();
				app.loginView = new app.views.loginView({ model: app.user });
				app.loginView.render();
			}
		}else {
			this.home();
		}
	},

	signup: function () {
		userJson = JSON.parse(localStorage.getItem("user"));
		if(userJson == null){
			if(!app.signupView) {
				app.user = new app.models.User();
				app.signupView = new app.views.signupView({ model: app.user });
				app.signupView.render();
			}
		}else {
			this.home();
		}
	},

	user: function (userid){
		userJson = JSON.parse(localStorage.getItem("user"));

		if(userJson != null) {
			app.user = new app.models.User(userJson);
			app.profileView = new app.views.profileView({model: new app.models.User()});
			app.profileView.render();
		}
	},

	bookmark: function(userid){
		userJson = JSON.parse(localStorage.getItem("user"));
		$userid = userJson.user_id;
		if(userJson != null){
			app.user = new app.models.User(userJson);
			var url = app.user.urlAskQuestion + "display_all_bookmarked_questions/"+$userid;
			app.bookmarksView = new app.views.bookmarksView({collection: new app.collections.QuestionCollection()});

			app.bookmarksView.collection.fetch({
				reset: true,
				"url": url,
				success: function(collection, response){
					app.bookmarksView.render();
					if (!response){
						new Noty({ theme: 'bootstrap-v4', layout: 'bottomRight',
							type: 'error',
							text: "No bookmarks found!",
							timeout: 2000
						}).show();
					}
				},
				error: function(model, xhr, options){
					if(xhr.status == 204){
						app.bookmarksView.render();
					}
				}
			});
		}else {
			app.appRouter.navigate("", {trigger: true});
		}
	},

	category: function(){
		userJson = JSON.parse(localStorage.getItem("user"));

		if (userJson != null){
			app.user = new app.models.User(userJson);
			app.categoryView = new app.views.categoryView({collection: new app.collections.QuestionCollection()});
			var url = app.categoryView.collection.url + "display_all_questions";

			app.categoryView.collection.fetch({
				reset: true,
				"url": url,
				success: function(collection, response){
					app.categoryView.render();
				},
				error: function(model, xhr, options){
					if(xhr.status == 404){
						app.categoryView.render();
					}
				}
			});
		} else {
			app.appRouter.navigate("", {trigger: true});
		}
	},

	tags: function(){
		userJson = JSON.parse(localStorage.getItem("user"));

		if (userJson != null){
			app.user = new app.models.User(userJson);
			app.tagsView = new app.views.tagsView({collection: new app.collections.QuestionCollection()});

			var url = app.tagsView.collection.url + "display_all_questions";

			app.tagsView.collection.fetch({
				reset: true,
				"url": url,
				success: function(collection, response){
					app.tagsView.render();
				},
				error: function(model, xhr, options){
					if(xhr.status == 404){
						app.tagsView.render();
					}
				}
			});
		} else {
			app.appRouter.navigate("", {trigger: true});
		}
	},

	home: function(){
		userJson = JSON.parse(localStorage.getItem("user"));

		if (userJson != null){
			app.user = new app.models.User(userJson);
			app.homeView = new app.views.homeView({collection: new app.collections.QuestionCollection()});
			var url = app.homeView.collection.url + "display_all_questions";

			app.homeView.collection.fetch({
				reset: true,
				"url": url,
				success: function(collection, response){
					app.homeView.render();
				},
				error: function(model, xhr, options){
					if(xhr.status == 404){
						app.homeView.render();
					}
				}
			});
		} else {
			app.appRouter.navigate("", {trigger: true});
		}
	},

	newquestion: function (){
		userJson = JSON.parse(localStorage.getItem("user"));
		if(userJson != null){
			app.user = new app.models.User(userJson);
			app.newQuestionView = new app.views.newQuestionView({model: app.user});
			app.newQuestionView.render();
		}else {
			app.appRouter.navigate("", {trigger: true});
		}
	},

	answerquestion:function (questionid){
		userJson = JSON.parse(localStorage.getItem("user"));
		$user_id = userJson.user_id;
		if(userJson != null){
			app.user = new app.models.User(userJson);
			var url = app.user.urlAskQuestion + "display_all_questions/" + questionid;

			app.user.fetch({
				"url": url,
				success: function(model, responseQ){
					responseQ['username'] = app.user.get("username");
					var questionModel = new app.models.Questions(responseQ);
					questionModel.set("user_id", responseQ['userid']);
					var urlBookmark = app.user.urlAskQuestion + "display_question_bookmark";

					$.ajax({
						url: urlBookmark,
						type: "POST",
						data: {
							"questionid": questionid,
							"userid": $user_id
						},
						success: function(responseB){
							if(responseB.is_bookmark){
								questionModel.set("is_bookmark", true);
								app.ansQuestionView = new app.views.questionAnswerView({
									model: questionModel,
									collection: new app.collections.AnswerCollection(),
									bookmark: true
								});
							}else {
								questionModel.set("is_bookmark", false);
								app.ansQuestionView = new app.views.questionAnswerView({
									model: questionModel,
									collection: new app.collections.AnswerCollection(),
									bookmark: false
								});
							}

							var answerUrl = app.ansQuestionView.collection.url + "display_question_answers/" + questionid;

							app.ansQuestionView.collection.fetch({
								reset: true,
								"url": answerUrl,
								success: function (collection, response) {
									app.ansQuestionView.render();
								},
								error: function (model, xhr, options) {
									if (xhr.status == 404) {
										console.log("error 404");
									}
								}
							});
						},
						error: function(model, xhr, options){
							if(xhr.status == 404){
								console.log("error 404");
							}
						}
					});
				},
				error: function(model, xhr, options){
					if(xhr.status == 404){
						console.log("error 404");
					}
				}
			})

		}else {
			app.appRouter.navigate("", {trigger: true});
		}
	},

	logout: function(){
		localStorage.clear();
		var url = app.user.url + "logout";

		$.ajax({
			url: url,
			type: "POST",
			success: function (response) {
				window.location.href = "";
			},

			error: function(model, xhr, options){
				if(xhr.status == 404){
					console.log("error 404");
				}
				console.log("error");
			}
		});
	}
});

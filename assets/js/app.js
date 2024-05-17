var app = app || {};
app.views = {};
app.routers = {};
app.models = {};
app.collections = {};

function validateLoginForm() {
	var user = {
		'username': $("input#inputUsername").val(),
		'password': $("input#inputPassword").val()
	};
	if (!user.username || !user.password) {
		return false;
	}
	return user;
}

function validateRegisterForm() {
	var user = {
		'firstname': $("input#signupFirstname").val(),
		'lastname': $("input#signupLastname").val(),
		'username': $("input#signupUsername").val(),
		'password': $("input#signupPassword").val(),
		'email': $("input#signupEmail").val(),
		'occupation': $("input#signupOccupation").val(),
	};

	var registerError1 = 'First name field is empty';
	var registerError2 = 'Last name field is empty';
	var registerError3 = 'Username field is empty';
	var registerError4 = 'Password field is empty';
	var registerError5 = 'Email field is empty';
	var registerError6 = 'Title field is empty';

	if (!user.firstname) {
		return registerError1;
	} else if (!user.lastname) {
		return registerError2;
	} else if (!user.username) {
		return registerError3;
	} else if (!user.password) {
		return registerError4;
	} else if (!user.email) {
		return registerError5;
	} else if (!user.occupation) {
		return registerError6;
	} else {
		return user;
	}
}

function validateUpdateUserProfileForm() {
	var userImg = {
		'userimage': $("input#change-profile-picture-input")[0].files[0]
	};
	return userImg;
}

function validateChangePasswordForm() {
	var userPass = {
		'oldpassword': $("input#oldPassword").val(),
		'newpassword': $("input#newPassword").val(),
		'confirmpassword': $("input#confirmPassword").val()
	};

	var passError1 = 'Old password field is empty';
	var passError2 = 'New password field is empty';
	var passError3 = 'Confirm password field is empty';
	var passError4 = 'New password and confirm password do not match';

	if (!userPass.oldpassword) {
		return passError1;
	} else if (!userPass.newpassword) {
		return passError2;
	} else if (!userPass.confirmpassword) {
		return passError3;
	} else if (userPass.newpassword !== userPass.confirmpassword) {
		return passError4;
	} else {
		return userPass;
	}
}

function validateAnswerForm() {
	var answer = {
		'answer': $("textarea#inputQuestionDetails").val().replace(/\n/g, '<br>'),
		'answerimage': $("input#answerImageUpload")[0].files[0],
		'answeraddeddate': new Date().toISOString().slice(0, 19).replace('T', ' ')
	};

	var answerError1 = 'Answer field is empty';
	var answerError2 = 'Answer should be at least 20 characters';

	if (!answer.answer) {
		return answerError1;
	} else if (answer.answer.length < 20) {
		return answerError2;
	} else {
		return answer;
	}
}

function validateSearchForm() {
	var search = {
		'search': $("input#search-question-input").val()
	};
	if (!search.search) {
		return false;
	}
	return search;
}

function validateEditUserDetailsAddForm() {

	var editUser = {
		'firstname': $("input#firstname").val(),
		'lastname': $("input#lastname").val(),
		'occupation': $("input#title").val(),
		'username': $("input#username").val(),
		'email': $("input#email").val(),
	};
	
	var editUserError1 = 'First name field is empty';
	var editUserError2 = 'Last name field is empty';
	var editUserError3 = 'Title field is empty';
	var editUserError4 = 'Username field is empty';
	var editUserError5 = 'Email field is empty';

	if (!editUser.firstname) {
		return editUserError1;
	} else if (!editUser.lastname) {
		return editUserError2;
	} else if (!editUser.occupation) {
		return editUserError3;
	} else if (!editUser.username) {
		return editUserError4;
	} else if (!editUser.email) {
		return editUserError5;
	} else {
		return editUser;
	}
}

function validateQuestionAddForm() {
	var question = {
		'title': $("input#inputQuestionTitle").val(),
		'description': $("textarea#inputQuestionDetails").val().replace(/\n/g, '<br>'),
		'expectation': $("textarea#inputQuestionExpectation").val().replace(/\n/g, '<br>'),
		'images': $("input#imageUpload")[0].files[0],
		'category': $("select#questionCategory").val(),
		'tags': $("input#inputQuestionTags").val(),
		'date': new Date().toISOString().slice(0, 19).replace('T', ' ')
	};

	var inputError1 = 'Title field is empty';
	var inputError2 = 'Question field is empty';
	var inputError3 = 'Question should be at least 50 characters';
	var inputError4 = 'Expectation field is empty';
	var inputError5 = 'Expectation should be at least 20 characters';
	var inputError6 = 'At least one tag required';
	var inputError7 = 'Maximum of 5 tags allowed';
	var inputError8 = 'Category is not selected';
	var inputError9 = 'Title should be at least 20 characters';
	var inputError10 = 'Title should not exceed 100 characters';

	console.log("title:", question.title, "description:", question.description, "expectation:", question.expectation, "images:", question.images, "category:", question.category, "tags:", question.tags, "date:", question.date);

	var tagsArray = question.tags.split(',').filter(tag => tag.trim() !== '');

	if (!question.title) {
		return inputError1;
	} else if (question.title.length > 50) {
		return inputError9;
	} else if (question.title.length > 100) {
		return inputError10;
	} else if (!question.description) {
		return inputError2;
	} else if (question.description.length < 20) {
		return inputError3;
	} else if (!question.expectation) {
		return inputError4;
	} else if (question.expectation.length < 20) {
		return inputError5;
	} else if (tagsArray.length === 0) {
		return inputError6;
	} else if (tagsArray.length > 5) {
		return inputError7;
	} else if (!question.category) {
		return inputError8;
	} else {
		return question;
	}
}

$(document).ready(function () {
	app.appRouter = new app.routers.AppRouter();
	$(function () {
		Backbone.history.start();
	});
});

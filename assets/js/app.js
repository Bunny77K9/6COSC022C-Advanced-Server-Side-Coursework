var app = app || {};
app.views = {};
app.routers = {};
app.models = {};
app.collections = {};

//Validation for login. If not there it will return false
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
		'firstname': $("input#regFirstname").val(),
		'lastname': $("input#regLastname").val(),
		'username': $("input#regUsername").val(),
		'password': $("input#regPassword").val(),
		'email': $("input#regEmail").val(),
		'occupation': $("input#regOccupation").val(),
	};
	if (!user.firstname || !user.lastname || !user.username || !user.password || !user.occupation || !user.email) {
		return false;
	}
	return user;
}

function validateUpdateUserProfileForm() {
	var userImg = {
		'userimage': $("input#upload_image_input")[0].files[0]
	};

	return userImg;
}

function validateChangePasswordForm(){
	var userPass = {
		'oldpassword': $("input#oldPassword").val(),
		'newpassword': $("input#newPassword").val(),
		'confirmpassword': $("input#confirmPassword").val()
	};

	if(userPass.newpassword !== userPass.confirmpassword){
		return false;
	}

	if (!userPass.oldpassword || !userPass.newpassword || !userPass.confirmpassword) {
		return false;
	}

	return userPass;

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
	}
	else if (answer.answer.length < 20) {
		return answerError2;
	}
	else{
		return answer;
	}
}

function validateSearchForm(){
	var search = {
		'search': $("input#searchHome").val()
	};

	if (!search.search) {
		return false;
	}

	return search;

}

function validateEditUserDetailsAddForm(){
	// Remove disabled attribute temporarily

	var editUser = {
		'firstname': $("input#firstname").val(),
		'lastname': $("input#lastname").val(),
		'occupation': $("input#title").val(),
		'username': $("input#username").val(),
		'email': $("input#email").val(),
	};

	// Restore disabled attribute

	var editUserError1 = 'First name field is empty';
	var editUserError2 = 'Last name field is empty';
	var editUserError3 = 'Title field is empty';
	var editUserError4 = 'Username field is empty';
	var editUserError5 = 'Email field is empty';

	if (!editUser.firstname) {
		return editUserError1;
	}
	else if (!editUser.lastname) {
		return editUserError2;
	}
	else if (!editUser.occupation) {
		return editUserError3;
	}
	else if (!editUser.username) {
		return editUserError4;
	}
	else if (!editUser.email) {
		return editUserError5;
	}
	else{
		return editUser;
	}
}

function validateQuestionAddForm() {
	var question = {
		'title': $("input#inputQuestionTitle").val(),
		'description': $("textarea#inputQuestionDetails").val().replace(/\n/g, '<br>'),
		'expectation': $("textarea#inputQuestionExpectation").val().replace(/\n/g, '<br>'),
		'images': $("input#imageUpload")[0].files[0], // Store the image file directly
		'category': $("select#questionCategory").val(),
		'tags': $("input#inputQuestionTags").val(),
		'date': new Date().toISOString().slice(0, 19).replace('T', ' ')
	};

	// Define input errors
	let inputError1 = 'Title field is empty';
	let inputError2 = 'Question field is empty';
	let inputError3 = 'Question should be at least 20 characters';
	let inputError4 = 'Expectation field is empty';
	let inputError5 = 'Expectation should be at least 20 characters';
	let inputError6 = 'At least one tag required';
	let inputError7 = 'Maximum of 5 tags allowed';
	let inputError8 = 'Category is not selected';

	console.log("title:", question.title, "description:", question.description, "expectation:", question.expectation, "images:", question.images, "category:", question.category, "tags:", question.tags, "date:", question.date);

	// Check if 'question' or 'expectationQ' has at least 20 characters

	var tagsArray = question.tags.split(',').filter(tag => tag.trim() !== ''); // Remove empty tags

	if (!question.title) {
		return inputError1;
	}
	else if (!question.description) {
		return inputError2;
	}
	else if (question.description.length < 20) {
		return inputError3;
	}
	else if (!question.expectation) {
		return inputError4;
	}
	else if (question.expectation.length < 20) {
		return inputError5;
	}
	else if (tagsArray.length === 0) {
		return inputError6;
	}
	else if (tagsArray.length > 5) {
		return inputError7;
	}
	else if (!question.category) {
		return inputError8;
	}
	else{
		return question;
	}
}

$(document).ready(function() {
	app.appRouter = new app.routers.AppRouter();
	$(function() {
		Backbone.history.start();
	});
});

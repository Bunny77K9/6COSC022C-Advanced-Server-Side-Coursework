var app = app || {};

app.views.profileView = Backbone.View.extend({
	el: ".container",

	render: function () {
		template = _.template($('#profile-template').html());
		this.$el.html(template(app.user.attributes));

		app.navbarView = new app.views.navbarView({model: app.user});
		app.navbarView.render();
	},

	events: {
		'click #edit-profile': 'editProfileDetails',
		'click #change-profile-picture': 'changeProfilePicture',
		'click #submit-change-password-button': 'changePassword',
		'change #change-profile-picture-input': 'uploadImage',
	},

	changeProfilePicture: function () {
		$('#change-profile-picture-input').click();
	},

	uploadImage: function () {
		userJson = JSON.parse(localStorage.getItem("user"));
		var user_id = userJson['user_id'];

		var validateUpdateUserProfile = validateUpdateUserProfileForm();
		validateUpdateUserProfile['user_id'] = user_id;

		if (validateUpdateUserProfile) {
			var formData = new FormData();
			var imageFile = $('#change-profile-picture-input')[0].files[0];
			formData.append('image', imageFile);
			formData.append('user_id', user_id);

			var url = this.model.url + "edit_user_image";

			$.ajax({
				url: url,
				type: 'POST',
				data: formData,
				contentType: false,
				processData: false,
				success: (response) => {
					validateUpdateUserProfile.userimage = response.imagePath;
					this.model.set(validateUpdateUserProfile);
					$updateImage = this.model.attributes.userimage;

					var url = this.model.url + "upload_image";

					this.model.save(this.model.attributes, {
						"url": url,
						success: (model, response) => {
							userJson['userimage'] = $updateImage;
							localStorage.setItem("user", JSON.stringify(userJson));
							new Noty({
								theme: 'bootstrap-v4', layout: 'bottomRight',
								type: 'success',
								text: 'Profile picture updated successfully!',
								timeout: 2000
							}).show();
						},
						error: (model, response) => {
							new Noty({
								theme: 'bootstrap-v4', layout: 'bottomRight',
								type: 'error',
								text: 'Profile picture update failed!',
								timeout: 2000
							}).show();
						}
					});
				},
				error: function (response) {
					new Noty({
						theme: 'bootstrap-v4', layout: 'bottomRight',
						type: 'error',
						text: 'Profile picture update failed!',
						timeout: 2000
					}).show();
				}
			});
		}
	},

	editProfileDetails: function () {
		var userJson = JSON.parse(localStorage.getItem("user"));

		var validateEditUserDetailsForm = validateEditUserDetailsAddForm();
		validateEditUserDetailsForm['user_id'] = userJson['user_id'];

		if (validateEditUserDetailsForm.firstname) {

			this.model.set(validateEditUserDetailsForm);
			var url = this.model.url + "edit_user_details";

			this.model.save(this.model.attributes, {
				"url": url,
				success: (model, response) => {
					localStorage.setItem("user", JSON.stringify(model));
					app.appRouter.navigate('home/user/' + userJson['user_id'], {trigger: true});

					new Noty({
						theme: 'bootstrap-v4', layout: 'bottomRight',
						type: 'success',
						text: 'Profile details updated successfully!',
						timeout: 2000
					}).show();

					$('#editUserModal').modal('hide');
					window.location.reload();
				},
				error: (model, response) => {
					new Noty({
						theme: 'bootstrap-v4', layout: 'bottomRight',
						type: 'error',
						text: 'Profile details update failed!',
						timeout: 2000
					}).show();
				}
			});
		} else {
			new Noty({
				theme: 'bootstrap-v4', layout: 'bottomRight',
				type: 'error',
				text: 'Form validation error:' + validateEditUserDetailsForm,
				timeout: 2000
			}).show();
		}
	},

	changePassword: function () {
		userJson = JSON.parse(localStorage.getItem("user"));
		var user_id = userJson['user_id'];

		var validateChangePassword = validateChangePasswordForm();

		if (!validateChangePassword.oldpassword || !validateChangePassword.newpassword || !validateChangePassword.confirmpassword) {
			new Noty({
				theme: 'bootstrap-v4', layout: 'bottomRight',
				type: 'error',
				text: 'Form validation error:' + validateChangePassword,
				timeout: 2000
			}).show();
		} else {
			var userPass = {
				'user_id': user_id,
				'oldpassword': $("input#oldPassword").val(),
				'newpassword': $("input#newPassword").val(),
				'confirmpassword': $("input#confirmPassword").val()
			};

			var url = this.model.url + "change_password";

			$.ajax({
				url: url,
				type: 'POST',
				data: userPass,
				success: (response) => {
					if (response.status === true) {
						new Noty({
							theme: 'bootstrap-v4', layout: 'bottomRight',
							type: 'success',
							text: 'Password changed successfully!',
							timeout: 2000
						}).show();
						$('#passwordChangeModal').modal('hide');

						$("input#oldPassword").val("");
						$("input#newPassword").val("");
						$("input#confirmPassword").val("");

					} else if (response.status === false) {
						new Noty({
							theme: 'bootstrap-v4', layout: 'bottomRight',
							type: 'error',
							text: 'Please check the details and try again!',
							timeout: 2000
						}).show();
					}
				},
				error: function (response) {
					new Noty({
						theme: 'bootstrap-v4', layout: 'bottomRight',
						type: 'error',
						text: 'Password change failed!',
						timeout: 2000
					}).show();
				}
			})
		}
	},

})

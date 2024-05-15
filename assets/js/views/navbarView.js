var app = app || {};

app.views.navbarView = Backbone.View.extend({
	el: '#nav-bar-container',

	render: function(){
		template = _.template($('#nav-bar-template').html());
		this.$el.html(template(this.model.attributes));
		console.log("nav bar attributes: ",this.model.attributes);
		console.log('rendering nav bar');
	}
});

var app = app || {};

app.views.navbarView = Backbone.View.extend({
	el: '#navbar-container',

	render: function(){
		template = _.template($('#navbar-template').html());
		this.$el.html(template(this.model.attributes));
	}
});

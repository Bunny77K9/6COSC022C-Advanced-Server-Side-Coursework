var app = app || {};

app.views.QuestionView = Backbone.View.extend({
	el: '#question',
	render:function (){
		template = _.template($('#question-view-template').html());
		this.$el.append(template(this.model.attributes));
	}
});

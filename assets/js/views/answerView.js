var app = app || {}

app.views.answerView = Backbone.View.extend({
	el: '#answer',

	render: function(){
		template = _.template($('#answer-template').html())
		this.$el.css('display', 'block');
		this.$el.append(template(this.model.attributes));
	}
})

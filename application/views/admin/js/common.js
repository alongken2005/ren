$(function(){
	$('.table2 tr').hover(function() {
		$(this).addClass('tableline');
	}, function() {
		$(this).removeClass('tableline');
	})
})
$('#main').infinitescroll({
	navSelector  : ".pagebrowser",
	nextSelector : ".pagebrowser a.next",
	itemSelector : "#main article.item"
});
$('#metamenu .imprint').click(function() {
	$('#imprint').toggle('fast');
	return false;
});

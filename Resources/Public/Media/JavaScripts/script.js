$('#main').infinitescroll({
	navSelector: ".pagebrowser",
	nextSelector: ".pagebrowser a.next",
	itemSelector: "#main article.item",
	loadingImg: "/_Resources/Static/Packages/Planetflow3/Media/Images/ajax-loader.gif"

});
$('#metamenu .imprint').click(function() {
	$('#imprint').toggle('fast');
	return false;
});

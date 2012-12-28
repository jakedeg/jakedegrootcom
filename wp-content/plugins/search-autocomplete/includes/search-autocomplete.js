(function($) {
	$(function() {
		$( '#s' ).autocomplete({
			source: 'tags.php',
			minLength: 2
		});
	});
})(jQuery);
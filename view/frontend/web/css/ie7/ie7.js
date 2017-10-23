/* To avoid CSS expressions while still supporting IE 7 and IE 6, use this script */
/* The script tag referencing this file must be placed before the ending body tag. */

/* Use conditional comments in order to target IE 7 and older:
	<!--[if lt IE 8]><!-->
	<script src="ie7/ie7.js"></script>
	<!--<![endif]-->
*/

(function() {
	function addIcon(el, entity) {
		var html = el.innerHTML;
		el.innerHTML = '<span style="font-family: \'customer-question\'">' + entity + '</span>' + html;
	}
	var icons = {
		'icon-customer-question-triangle-down': '&#xe903;',
		'icon-customer-question-hammer2': '&#xe9a8;',
		'icon-customer-question-reply': '&#xe900;',
		'icon-customer-question-thumbs-down': '&#xe901;',
		'icon-customer-question-thumbs-up': '&#xe902;',
		'0': 0
		},
		els = document.getElementsByTagName('*'),
		i, c, el;
	for (i = 0; ; i += 1) {
		el = els[i];
		if(!el) {
			break;
		}
		c = el.className;
		c = c.match(/icon-customer-question-[^\s'"]+/);
		if (c && icons[c[0]]) {
			addIcon(el, icons[c[0]]);
		}
	}
}());

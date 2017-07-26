/*Version 2.0 */
var siteURL = diycIEParams.siteURL;
jQuery(document).ready(function() {
	jQuery('td[class="product-name"]').each(function(index, element) {
		var imgHTML = jQuery(this).html();
		var strings = imgHTML.split('<dl',2);
		var stringA = strings[0].trim();
		var stringB = '<a href="'+siteURL+'/product/'+stringA.replace(/\s/g,'-')+'" >'+strings[0]+'</a><dl'+strings[1];
		jQuery(this).html(stringB);
	});
});
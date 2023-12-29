/* To run the Tabs */
jQuery(document).ready(function(jQuery){
	
	jQuery('#tabs-nav li:first-child').addClass('active');
	jQuery('.tab-content').hide();
	jQuery('.tab-content:first').show();

	jQuery('#tabs-nav li').click(function(){
		jQuery('#tabs-nav li').removeClass('active');
		jQuery(this).addClass('active');
		jQuery('.tab-content').hide();

		var activeTab = jQuery(this).find('a').attr('href');
		jQuery(activeTab).fadeIn();
		return false;
	});

(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

	$( document ).ready(function() {

		// var selectedVal = $("#schema_markup_homepage_onoff option:selected").val();

		// if (selectedVal){

			$("#schema_markup_agree_tested"). prop("checked", false);
			$('.schema_markup_insertion_submit_container #submit').attr("disabled", true);

			$("#schema_markup_agree_tested").change(function() {

				$("#valid_json").addClass('hidden');
				$("#invalid_json").addClass('hidden');

				if(this.checked) {

					let valid_json = true;

					try {
						let jsonld = $.trim($("#schema_markup_smi_jason_ld").val());
						var c = $.parseJSON(jsonld);
					}
					catch (err) {
						valid_json = false;
						$("#invalid_json").removeClass('hidden');
					}

					if (valid_json){
						$("#valid_json").removeClass('hidden');
						$('.schema_markup_insertion_submit_container #submit').removeAttr("disabled", true);
					}else{
						$("#schema_markup_agree_tested"). prop("checked", false);
					}

				}else{
					$("#valid_json").addClass('hidden');
					$("#invalid_json").addClass('hidden');
					$('.schema_markup_insertion_submit_container #submit').attr("disabled", true);
				}

			});


		// }

	});

})( jQuery );

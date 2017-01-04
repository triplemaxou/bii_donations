jQuery(function ($) {
	var tohide = "#menu-posts-portfolio, #menu-posts-staff, #menu-posts-testimonials, #wp-admin-bar-new-portfolio, #wp-admin-bar-new-staff, #wp-admin-bar-new-testimonials";
	if (bii_userrole != "admin") {
		tohide += ", #formatdiv, #post_seriesdiv, #um-admin-access-settings, #wpex-metabox, #commentstatusdiv, #mymetabox_revslider_0, #wpex-gallery-metabox"
			+ ", #toplevel_page_vc-welcome, #menu-tools, #toplevel_page_wpex-panel, #toplevel_page_about-ultimate, #menu-settings, #toplevel_page_gf_edit_forms"
			+ ", #toplevel_page_uaf_settings_page, #menu-comments, #wp-admin-bar-comments, #latest-comments, #toplevel_page_woocommerce ";
		tohide += ", #menu-posts-pafb-survey, #menu-posts-logooo, #menu-posts-product";
//		if (bii_userrole == "user") {		
//			
//		}
//		if (bii_userrole == "editeur") {		
//			
//		}
//		if (bii_userrole == "drh") {	
//			tohide += ", #menu-posts-pafb-survey, #menu-posts-logooo";
//			
//		}
//		if (bii_userrole == "gestionnaire") {		
//			tohide += ", #menu-posts-pafb-survey, #menu-posts-logooo";
//		}
//		
		
	}
	$(tohide).hide(0);


	if ($(".ajoutcustom").length) {

		$(".bulk-uncheck").on("click", function (e) {
			e.preventDefault();
			var role = $(this).attr("id");
			role = role.replace("-bulk-uncheck", "");
			$("." + role + "-cbx").prop("checked", false);
		});
		$(".bulk-add").on("click", function (e) {
			e.preventDefault();
			var val = $(this).siblings(".bulk-values").val();
			if (val) {
				var arr = val.split(" ");
				var role = $(this).attr("id");
				role = role.replace("-bulk-add", "");
				$.each(arr, function (index, value) {
					var idinput = role + '-' + value;
					$("#" + idinput).prop("checked", true);
					console.log(idinput);
				});
			}
		});
		$(".bulk-remove").on("click", function (e) {
			e.preventDefault();
			var val = $(this).siblings(".bulk-values").val();
			if (val) {
				var arr = val.split(" ");
				var role = $(this).attr("id");
				role = role.replace("-bulk-remove", "");
				$.each(arr, function (index, value) {
					var idinput = role + '-' + value;
					$("#" + idinput).prop("checked", false);
					console.log(idinput);
				});
			}
		});
	}


});
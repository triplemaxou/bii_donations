jQuery(function ($) {
	$(".synchro-photo").click(function () {
		var $fa = $(this).find(".fa-refresh");
		$(this).addClass("btn-info").removeClass("btn-default");
		$fa.addClass("fa-spin");
		jQuery.ajax({
			url: ajaxurl,
			data: {
				'action': 'bii_synchronize_photos'
			},
			dataType: 'html',
			success: function (reponse) {
				$fa.removeClass("fa-spin");
				$(this).addClass("btn-default").removeClass("btn-info");
			}
		});
	});

	$("#chooseinstance").on("change", function () {
		var val = $(this).val();
		jQuery.ajax({
			url: ajaxurl,
			data: {
				'action': 'bii_change_instance',
				'newinstance': val
			},
			dataType: 'html',
			success: function (reponse) {
//				alert("ok");
				location.reload();
			}
		});
	});
    
    
    $("#test-mail").click(function() {
       jQuery.ajax({
           url: ajaxurl,
           data: {
               'action': 'bii_test_mail'
           },
           success: function(reponse) {
               console.log(reponse);
           }
       });
    });
});
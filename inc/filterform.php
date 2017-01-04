<?php
$action = "";
if (isset($nom_classe)) {
	$action = "action='" . $_SERVER['REQUEST_URI'] . "'";
}
?>

<div id="likeaform" class=" col-lg-12 col-md-12 col-sm-12 col-xs-12">
	<p>Filtrer sur</p>
	<div id='lines'>
		<div id='firstline'>
			<div id='line-0' class='line'>
				<input type="hidden" id="countline" value='0' />
				<div class=" col-lg-4 col-md-4 col-sm-4  col-xs-12">
					<select name="" id="champ-0" class="form-control champ">
						<option class="text" value="nom">Nom</option>
						<option class="nb" value="socid">Id (sur l'erp)</option>
						<option class="nb" value="id">Id (sur AWR)</option>
						<option class="nb" value="motscles">Nombre de mots clés</option>
						<option class="nb" value="motscles_google">Nombre de mots clés google</option>
						<option class="nb" value="motscles_bing">Nombre de mots clés bing</option>
						<option class="nb" value="motscles_yahoo">Nombre de mots clés yahoo</option>
						<option class="nb" value="(motscles_yahoo * motscles_bing * motscles_google)">Multiple des 3</option>
						<option class="nb" value="date_mail">Date d'envoi de mail</option>
						<option class="nb" value="visites">Visites</option>
						<option class="text" value="info_mail">Info sur les mails</option>
						<option class="text" value="mail_contact">Mail du contact</option>
						<option class="text" value="url">URL</option>
					</select>
				</div>
				<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
					<select name="" id="operator-0" class="form-control operator">
						<option class='string' id="LIKE" value="LIKE">contient</option>
						<option class='string' id="BEGINWITH" value="BEGINWITH">commence par</option>
						<option class='string' id="ENDWITH" value="ENDWITH">finit par</option>
						
						<option class='all' id="EQ" value="EQ">=</option>
						<option class='all' id="NOT" value="NOT">≠</option>
						<option class='math' id="LT" value="LT">&lt;</option>
						<option class='math' id="GT" value="GT">&gt;</option>

						
					</select>
				</div>
				<div class=" col-lg-4 col-md-4 col-sm-4 col-xs-12">
					<input type="text" id="valuefilter-0" class=" form-control valuefilter" />
				</div>
				<div class=" col-lg-2 col-md-2 col-sm-2 col-xs-12">
					<button class="btn btn-primary add" id="addform"><span class='fa fa-plus'></span></button>
					<button class="btn btn-success" id="valform">OK</button>
				</div>
			</div>
		</div>
	</div>

</div>
<form method="get" <?php echo $action; ?> id="formfilter" class=" col-lg-12 col-md-12 col-sm-12 col-xs-12">

	<?php if (isset($nom_classe)) { ?>
		<input type="hidden" name="class" value='<?php echo $nom_classe; ?>'>
	<?php } ?>
	<?php if (isset($contrat)) { ?>
		<!--<input type="hidden" name="contrat" value='<?php echo $contrat; ?>'>-->
	<?php } ?>
	<input type="hidden" name="filter" id="filter">
</form>
<script>
	jQuery(function () {
		jQuery(".string").show();
		jQuery(".math").hide();


		jQuery("#likeaform").on("change", ".champ", function () {
			var id = jQuery(this).attr("id");
			id = id.substring(6);

			jQuery("#line-" + id + " .champ option").each(function () {
				if (jQuery(this).is(":selected")) {
					if (jQuery(this).hasClass("nb")) {
						jQuery("#line-" + id + " .operator .string").hide();
						jQuery("#line-" + id + " .operator .math").show();

					}
					if (jQuery(this).hasClass("text")) {
						jQuery("#line-" + id + " .operator .string").show();
						jQuery("#line-" + id + " .operator .math").hide();
					}
				}
			});
		});

		jQuery("#likeaform").on("keyup", ".valuefilter", function () {
			filtervalue();
		});
		jQuery("#likeaform").on("change", ".valuefilter", function () {
			filtervalue();
		});


		jQuery("#valform").click(function () {
			filtervalue();
			jQuery("#formfilter").submit();
		});

		function filtervalue() {
			var countline = jQuery("#countline").val();
			var val = "";
			for (var i = 0; i <= countline; i++) {
				if (i > 0) {
					val += "£";
				}
				var champ = jQuery("#champ-" + i).val();
				var operator = jQuery("#operator-" + i).val();
				var valuefilter = jQuery("#valuefilter-" + i).val();
				val += champ + "$" + operator + "$" + valuefilter;

			}


			jQuery("#filter").val(val);
		}


		jQuery("#likeaform").on("click", ".add", function () {
			var countline = jQuery("#countline").val();
			countline++;

			var input = jQuery("#firstline").html();
			input = input.replace('line-0', 'line-' + countline);
			input = input.replace('champ-0', 'champ-' + countline);
			input = input.replace('operator-0', 'operator-' + countline);
			input = input.replace('valuefilter-0', 'valuefilter-' + countline);
			input = input.replace('addform', 'removeform-' + countline);
			input = input.replace('fa-plus', 'fa-minus');
			input = input.replace('primary', 'warning');
			input = input.replace('add', 'rem');
			input = input.replace('<button class="btn btn-success" id="valform">OK</button>', '');

			jQuery(input).insertAfter("#firstline");
			jQuery("#countline").val(countline);
			jQuery("#line-" + countline + " .operator .string").show();
			jQuery("#line-" + countline + " .operator .math").hide();
		});

		jQuery("#likeaform").on("click", ".rem", function () {
			var id = jQuery(this).attr("id");
			id = id.substring(11);

			jQuery("#line-" + id).remove();

		});
	});
</script>


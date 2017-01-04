<?php
$mois_fr = ["", "janvier", "février", "mars", "avril", "mai", "juin", "juillet", "août", "septembre", "octobre", "novembre", "décembre"];
$moiscourant = date("n");
$ancourant = date("Y");
$timestamp_debut = firstDayOfMonth("timestamp", $moiscourant, $ancourant);

$moisprec = $moiscourant - 1;
$anprec = $ancourant;
if ($moisprec == 0) {
	$moisprec = 12;
	--$anprec;
}
$timestamp_prec = firstDayOfMonth("timestamp", $moisprec, $anprec);
?>
<div class="bii_dashboard">
	<div class="message"><?php
		if (isset($_SESSION["bii_message"])) {
			echo $_SESSION["bii_message"];
		}
		?></div>
	<div class="titre ">
		<h1 class="faa-parent animated-hover"><span class="fa fa-shopping-cart faa-passing"></span> Plugin BiiDonations version <?= bii_donation_version; ?></h1>

	</div>
	<div class="col-xxs-12 col-md-8">
		<h2 class="faa-parent animated-hover"><i class="fa fa-info faa-shake"></i> Informations</h2>
		<div class="col-xxs-12 col-md-4">
			<?php $where = "etat='paye'"; ?>
			<h3 class=""><i class="fa fa-bank"></i> Total</h3>
			<ul>
				<li>Nombre de donations : <span class='badge'><?= donation::nb($where) ?></span></li>
				<li>Montant des donations : <span class='badge'><?= donation::sumDonation($where) ?> €</span></li>
				<li>Nombre de cotisations  : <span class='badge'><?= cotisation::nb($where) ?></span></li>
			</ul>
		</div>
		<div class="col-xxs-12 col-md-4">
			<h3 class=""><i class="fa fa-calendar"></i> <?= ucfirst($mois_fr[$moiscourant]) . " $ancourant"; ?></h3>
			<?php $where = "etat='paye' and date_insert > $timestamp_debut"; ?>
			<li>Nombre de donations : <span class='badge'><?= donation::nb($where) ?></span></li>
			<li>Montant des donations : <span class='badge'><?= donation::sumDonation($where) ?> €</span></li>
			<li>Nombre de cotisations : <span class='badge'><?= cotisation::nb($where) ?></span></li>
		</div>
		<div class="col-xxs-12 col-md-4">
			<h3 class=""><i class="fa fa-calendar"></i> <?= ucfirst($mois_fr[$moisprec]) . " $anprec"; ?></h3>
			<?php $where = "etat='paye' and date_insert > $timestamp_prec and date_insert < $timestamp_debut"; ?>
			<li>Nombre de donations : <span class='badge'><?= donation::nb($where) ?></span></li>
			<li>Montant des donations : <span class='badge'><?= donation::sumDonation($where) ?> €</span></li>
			<li>Nombre de cotisations : <span class='badge'><?= cotisation::nb($where) ?></span></li>

		</div>
		<div class="col-xxs-12">
			<h3 class="faa-parent animated-hover"><i class="fa fa-cog faa-wrench"></i> Liste des shortcodes</h3>
			<p>
				<span>[bii_displaywhenrequest cle="valeur"] contenu [/bii_displaywhenrequest]</span> : Affiche contenu lorsque cle est égal à valeur (si valeur est égal à "all", alors contenu est affiché si cle existe)
			</p>
			<p>
				<span>[bii_notdisplaywhenrequest cle="valeur"] contenu [/bii_notdisplaywhenrequest]</span> : Affiche contenu <strong>sauf</strong> lorsque cle est égal à valeur (si valeur est égal à "all", alors contenu n'est pas affiché si cle existe)
			</p>
		</div>
	</div>


	<div class="col-xxs-12 col-md-4">

		<div class="col-xxs-12">
			<h2 class="faa-parent animated-hover"><i class="fa fa-cogs faa-ring"></i> Zone de test</h2>
			<?php
			echo bii_cvnbst(1213658.65);
//			pre(unserialize('a:46:{s:8:"mc_gross";s:6:"100.00";s:22:"protection_eligibility";s:10:"Ineligible";s:14:"address_status";s:11:"unconfirmed";s:8:"payer_id";s:13:"CM8JVGDR9LV8G";s:3:"tax";s:4:"0.00";s:14:"address_street";s:33:"Av. de la Pelouse, 87648672 Mayet";s:12:"payment_date";s:25:"05:25:48 Apr 04, 2016 PDT";s:14:"payment_status";s:7:"Pending";s:7:"charset";s:12:"windows-1252";s:11:"address_zip";s:5:"75002";s:10:"first_name";s:4:"test";s:17:"option_selection1";s:19:"undefined undefined";s:17:"option_selection2";s:1:",";s:17:"option_selection3";s:11:"Non défini";s:20:"address_country_code";s:2:"FR";s:12:"address_name";s:10:"test buyer";s:14:"notify_version";s:3:"3.8";s:6:"custom";s:30:"migla20160404122400_1766026692";s:12:"payer_status";s:8:"verified";s:15:"address_country";s:6:"France";s:12:"address_city";s:5:"Paris";s:8:"quantity";s:1:"0";s:11:"verify_sign";s:56:"AzFmRN2alSXsVcRQ-bkJDjsW0iFTA2yzIKD2V.tu3HpTEIVspW10gjTF";s:11:"payer_email";s:32:"siegedaf-buyer@liguehavraise.com";s:12:"option_name1";s:14:"DisclosureName";s:12:"option_name2";s:28:"DisclosureEmployerOccupation";s:12:"option_name3";s:8:"Campaign";s:6:"txn_id";s:17:"10Y77951AV035212X";s:12:"payment_type";s:7:"instant";s:9:"last_name";s:5:"buyer";s:13:"address_state";s:6:"Alsace";s:14:"receiver_email";s:26:"siegedaf@liguehavraise.com";s:17:"shipping_discount";s:4:"0.00";s:16:"insurance_amount";s:4:"0.00";s:14:"pending_reason";s:10:"unilateral";s:8:"txn_type";s:10:"web_accept";s:9:"item_name";s:8:"donation";s:8:"discount";s:4:"0.00";s:11:"mc_currency";s:3:"EUR";s:11:"item_number";s:0:"";s:17:"residence_country";s:2:"FR";s:8:"test_ipn";s:1:"1";s:15:"shipping_method";s:7:"Default";s:19:"transaction_subject";s:30:"migla20160404122400_1766026692";s:13:"payment_gross";s:0:"";s:12:"ipn_track_id";s:13:"87cd1a8aa6392";}'));
//			pre(do_shortcode("[bii_displaywhenrequest truc='all']ok[/bii_displaywhenrequest]"),"green");
//			pre(do_shortcode("[bii_notdisplaywhenrequest truc='all']ok[/bii_notdisplaywhenrequest]"),"red");
			?>
		</div>
	</div>

</div>


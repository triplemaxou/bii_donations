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
			
            
            //$miglaid = 'migla20170315083532_1775662923';
            //$don = donation::from_session($miglaid);
            //bii_generate_fiscal();
			?>
		</div>
	</div>

</div>


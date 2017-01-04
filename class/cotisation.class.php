<?php

class cotisation extends donation {

	public function makechrono() {
		$date = $this->date_insert_tmstp();
		$annee = date("Y", $date);
		$mois = date("m", $date);
		$id = $this->id;
		return "eC-$annee-$mois-$id";
	}

	public static function nom_classe_admin() {
		return "cotisation";
	}
	
	public static function getListeProprietes() {
		$array = [
			"id" => "id",
			"chrono" => "N°chrono",
			"date_insert" => "date",
			"nom" => "nom",
			"prenom" => "prénom",
			"adresse" => "adresse",
			"code_postal" => "code postal",
			"ville" => "ville",
			"montant" => "montant",
			"numero_transaction_paypal" => "N° de Transaction Paypal",
			"etat" => "État",
			"lien_recu" => "Reçu Fiscal",
		];
		return $array;
	}
	
	public static function mappingArrayPaypal($post) {
		$array = [
			"prenom" => $post["prenom"],
			"nom" => $post["nom"],
			"email" => $post["os0"],
			"montant" => $post["amount"],
			"adresse" => $post["adresse"],
			"ville" => $post["ville"],
			"code_postal" => $post["code_postal"],
			"migla" => $post["migla_session_id"],
			
			"etat" => "en attente",
		];
		return $array;
	}
	
	public function montant(){
		return 54;
	}
	
	
	
	protected function en_tete() {
		ob_start();
		?>
		<font face="Calibri" size="14pt">
		<cell width="3.6cm" left="15cm" top="1cm"  align="right">N° Ordre du reçu</cell>
		<cell width="3.6cm" left="15cm" top="1.4cm"  align="right">Cotisation N°<?= $this->chrono(); ?></cell>
		</font>
		<img src="http://liguehavraise.fr/wp-content/uploads/2016/04/ligue-havraise-pour-pdf-300x173.jpg" top="2cm" left="8cm" height="3cm"/>
		<font face="Calibri" size="14pt">
		<cell width="7.5cm" left="1.6cm" top="5.7cm"  align="center">Reçu aux oeuvres</cell>
		</font>
		<font face="CalibriBold" size="14pt">
		<cell width="7.5cm" left="1.6cm" top="6.3cm"  align="center">COTISATION <?= $this->year(); ?></cell>
		</font>
		<font face="Calibri" size="9pt">
		<cell width="7.5cm" left="1.6cm" top="6.8cm"  align="center">(Article 200 et 238bis du Code Général des impôts)</cell>
		</font>
		<?php if ($this->nom && $this->adresse && $this->ville && $this->code_postal) { ?>
			<font face="Calibri" size="10pt">
			<cell width="5cm" left="14.1cm" top="5.5cm"  align="left">M ou Mme <?= $this->nom; ?></cell>
			</font>
			<font face="CalibriItalic" size="10pt">
			<cell width="5cm" left="14.1cm" top="6.1cm"  align="left"><?= $this->adresse; ?></cell>

			<cell width="5cm" left="14.1cm" top="7.1cm"  align="left"><?= $this->code_postal; ?> <?= strtoupper($this->ville); ?></cell>
			</font>
		<?php } ?>
		<?php
		$contents = ob_get_contents();
		ob_end_clean();
		return $contents;
	}
}

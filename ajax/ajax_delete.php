<?php
//ini_set('display_errors', '1');
require_once(plugin_dir_path(__FILE__)."../config.php");

if (isset($_REQUEST["nom_classe"])) {
	$nom_classe = $_REQUEST["nom_classe"];
	if (isset($_REQUEST["id_delete"])) {
		$id_delete = $_REQUEST["id_delete"];
		$nom_classe::deleteStatic($id_delete);

//include("inc/pagination.php");
		$array = $nom_classe::all_id();
		$count = $nom_classe::nb();
		$nb_attr = count($nom_classe::getListeProprietes());
		
		if ($count > 0) {
			$i = 1;
			foreach ($array as $id) {
				$item = new $nom_classe($id);
				?>
				<tr <?php echo (($i % 2) ? 'class="alternate"' : ''); ?> id="card-<?php echo $id; ?>"><?php $item->ligneValeurs(); ?></tr>
				<?php
				$i++;
			}
		} else {
			?>
			<tr class="no-items">
				<td colspan="<?php echo $nb_attr + 2; ?>" class="colspanchange"><?php echo $nom_classe::messageRienAAfficher(); ?></td>
			</tr>
		<?php
		}
	} else {
		?><p class="warning">id seems to be uninitialized</p><?php
	}
} else {
	?><p class="warning">class_name seems to be uninitialized</p><?php
}
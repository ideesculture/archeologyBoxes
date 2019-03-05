<div style="background:white;display:block;"><?php
    $id = $this->getVar("id");
    $plugin_path = $this->getVar("plugin_path");
    $plugin_url = $this->getVar("plugin_url");
    $caisses = $this->getVar("caisses");
    $addBox_url = $this->getVar("addBox_url");
    $references = $this->getVar("references");
?>
	<h3 style="margin-top: 0;">Ajouter à une nouvelle caisse</h3>
	<form method="post" action="<?php print $addBox_url; ?>" id="addBox">
		<div style="background-color:#F7F7F7;padding:20px 20px 5px 20px;"><select name="reference" class="" id="reference" style="width: 240px; ">
				<?php
				foreach($references as $ref=>$name):
					print "<option value=\"{$ref}\">&nbsp;&nbsp;&nbsp; {$name}</option>";
				endforeach;
				?>
			</select><span style="display: inline-block;width:40px;"></span> Numéro de caisse : <input name="idno" maxlength="255" class="" style="width: 180px; height: 16px;"></input>

		</div>


		<div class="control-box rounded">
			<div class="control-box-left-content">
				<a href="#"
				   onclick="jQuery('#addBox').submit();"
				   class="form-button 1506021943"><span class="form-button">
                        <i class="caIcon fa fa-check-circle-o" style="font-size: 30px;"></i>
                        <span class="form-button">Créer la nouvelle caisse et ajouter l'objet</span></a>
			</div>
		</div>
	</form>
	<?php
    if(!$caisses) $caisses=[];
?>
    <div style="margin-top:70px;clear:both;">
        <h3>Ajouter à une caisse déjà existante</h3>
<?php
    foreach($caisses as $vt_caisse) {
        $caisse_id = $vt_caisse->get("ca_objects.object_id");
        print "<div style='width:126px;text-align: center;float:left;'>";
        print "<a href='".__CA_URL_ROOT__."/index.php/archeologyBoxes/Box/Attach/id/".$id."/box/".$caisse_id."'><img src=\"".$plugin_url."/assets/img/caisse.png\" data-id=\"".$caisse_id."\"style=\"background-color: grey;\"/></a>";
        print "<p style='margin:7px 0;padding:0'><b>".$vt_caisse->get("ca_objects.idno")."</b></p>";
        print "<p style='font-size: 0.9em;margin:0;padding:0;text-align: center;'>".$vt_caisse->get("ca_objects.dimensions_empl.caisse_long")."x".$vt_caisse->get("ca_objects.dimensions_empl.caisse_largeur")."x".$vt_caisse->get("ca_objects.dimensions_empl.caisse_hauteur")."</p>";
        print "<p><a target='_blank' href='".__CA_URL_ROOT__."/index.php/editor/objects/ObjectEditor/Edit/object_id/".$caisse_id."'><img style='height:12px;width:auto;' src='https://d30y9cdsu7xlg0.cloudfront.net/png/3878-200.png' /></a></p>";
        print "</div>";
    }
?>
    </div>
</div>
<style>
	#caCreateChildPanel {
		width: 850px;
		position:absolute;
		left:50%;
	}
	div.quickAddDialogHeader {
		position: relative;
	}
</style>

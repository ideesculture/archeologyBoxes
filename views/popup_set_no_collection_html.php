<div style="background:white;display:block;"><?php
    $id = $this->getVar("id");
    $plugin_path = $this->getVar("plugin_path");
    $plugin_url = $this->getVar("plugin_url");
    $caisses = $this->getVar("caisses");
    $addBox_url = $this->getVar("addBox_url");
    $references = $this->getVar("references");
?>
	<p><b><?php print caNavIcon(__CA_NAV_ICON_INFO__); ?></b> Il est impossible de placer les objets de l'ensemble dans une caisse, celui-ci n'est lié à aucune opération.</p>
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

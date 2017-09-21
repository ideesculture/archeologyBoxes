<?php
    $id = $this->getVar("id");
    $plugin_path = $this->getVar("plugin_path");
    $plugin_url = $this->getVar("plugin_url");
    $caisses = $this->getVar("caisses");
    $addBox_url = $this->getVar("addBox_url");

    if(!$caisses) $caisses=[];
?><div class="quickAddDialogHeader"><div class="quickAddTypeList">Mise en caisse
        <script type="text/javascript">jQuery(document).ready(function() { var f; jQuery('#CollectionQuickAddFormTypeID').on('change', f=function() { var c = jQuery('#CollectionQuickAddFormTypeID').find('option:selected').data('color'); jQuery('#CollectionQuickAddFormTypeID').css('background-color', c ? c : '#fff'); return false;}); f(); });</script></div>
        <div class="quickAddControls">
            <a class="form-button" id="CollectionQuickAddForm" onclick="jQuery('#caPanel').data('panel').hidePanel();"><span class="form-button"><i class="caIcon fa fa-minus-circle cancelIcon fa-2x"></i>Annuler</span></a>
        </div>
    <br style="clear: both;">
    </div>
    <div style="display:inline-block;width:46%;height:120%;min-height:120%;overflow-y:scroll;border-right:1px solid lightgrey;padding:40px 2% 20px 2%;">
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
    <div style="display:inline-block;position:fixed;height:120%;min-height:120%;overflow-y:scroll;padding:40px 2% 20px 2%;">
        <h3>Ajouter à une nouvelle caisse</h3>
        <form method="post" action="<?php print $addBox_url; ?>" id="addBox">
        <div class="bundleLabel" style="width:400px;"><span class="formLabelText" id="ca_attribute_ObjectEditorForm_fragments_mobilier">Numéro de caisse</span>  <span class="bundleContentPreview" id="P838ObjectEditorForm_attribute_614_BundleContentPreview" style="display: none;">&nbsp;</span><span class="iconButton"><a href="#" onclick="caBundleVisibilityManager.toggle(&quot;P838ObjectEditorForm_attribute_614&quot;);  return false;"></a></span>
            <script type="text/javascript">jQuery(document).ready(function() { caBundleVisibilityManager.registerBundle('P838ObjectEditorForm_attribute_614', ''); }); </script><div id="P838ObjectEditorForm_attribute_614" style="">
                <div class="bundleContainer">
                    <div class="caItemList">
                        <div id="P838ObjectEditorForm_attribute_614Item_19214" class="labelInfo repeatingItem" style="background-color: rgb(255, 255, 255);">
                            <span class="formLabelError"></span>
                            <table class="attributeListItem">
                                <tbody><tr>
                                    <td class="attributeListItem"><div class="formLabel"><input name="idno" maxlength="255" class="" style="width: 180px; height: 16px;"></input>
                                        </div>
                                        <script type="text/javascript">
                                            jQuery(document).ready(function() {
                                                jQuery('._attribute_value_fragments_mobilier').attr('title', 'Comptage du nombre de restes').tooltip({ tooltipClass: 'tooltipFormat', show: 150, hide: 150});
                                            });
                                        </script>
                                    </td>					</tr>
                                </tbody></table>
                            <input type="hidden" name="P838ObjectEditorForm_attribute_614_locale_id_19214" id="P838ObjectEditorForm_attribute_614_locale_id_19214" value="2" class="labelLocale">		</div></div>
                </div>
            </div>
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
    </div>

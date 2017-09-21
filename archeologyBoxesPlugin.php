<?php


class archeologyBoxesPlugin extends BaseApplicationPlugin
{
    # -------------------------------------------------------
    private $opo_config;
    private $ops_plugin_path;

    # -------------------------------------------------------
    public function __construct($ps_plugin_path)
    {
        $this->ops_plugin_path = $ps_plugin_path;
        $this->description = _t('archeologyBoxes plugin');
        parent::__construct();
        $ps_plugin_path = __CA_BASE_DIR__ . "/app/plugins/archeologyBoxes";

        if (file_exists($ps_plugin_path . '/conf/local/archeologyBoxes.conf')) {
            $this->opo_config = Configuration::load($ps_plugin_path . '/conf/local/archeologyBoxes.conf');
        } else {
            $this->opo_config = Configuration::load($ps_plugin_path . '/conf/archeologyBoxes.conf');
        }
    }
    # -------------------------------------------------------
    /**
     * Override checkStatus() to return true - this plugin always initializes ok
     */
    public function checkStatus()
    {
        return array(
            'description' => $this->getDescription(),
            'errors' => array(),
            'warnings' => array(),
            'available' => ((bool)$this->opo_config->get('enabled'))
        );
    }

    # -------------------------------------------------------
    /**
     * Insert into ObjectEditor info (side bar)
     */
    public function hookAppendToEditorInspector(array $va_params = array()) {
        $t_item = $va_params["t_item"];

        $vs_table_name = $t_item->tableName();
        $vn_item_id = $t_item->getPrimaryKey();
        $vn_code = $t_item->getTypeCode();

        
        if (($vs_table_name == "ca_objects")&& (in_array($vn_code,$this->opo_config->get("object_types")))) {
            $vs_archeologyBoxes_url = caNavUrl($this->getRequest(), "archeologyBoxes", "box", "Popup", array("id"=>$vn_item_id));

            $vs_buf = "<div style=\"text-align:center;width:100%;margin-top:10px;\">"
                . "<span onclick=\"javascript:modalArcheologyBoxes.showPanel('" . $vs_archeologyBoxes_url . "');\" class='put-in-box-button'>"
                . $this->opo_config->get('button_text')
                . "</span></div>
<script>
    var modalArcheologyBoxes={};
    jQuery(document).on(\"ready\", function() {
        modalArcheologyBoxes = caUI.initPanel({
            panelID: \"caPanel\",						/* DOM ID of the <div> enclosing the panel */
            panelContentID: \"caPanelContent\",		/* DOM ID of the content area <div> in the panel */
            exposeBackgroundColor: \"#000000\",				
            exposeBackgroundOpacity: 0.7,					
            panelTransitionSpeed: 400,						
            closeButtonSelector: \".close\",
            center: true,
                centerHorizontal: true,
                centerVertical : true,
            onOpenCallback: function() {
                jQuery(\"#topNavContainer\").hide(250);
            },
            onCloseCallback: function() {
                jQuery(\"#topNavContainer\").show(250);
            }  
        });
    });
    
    //modalArcheologyBoxes.showPanel('http://www.inrap.local/gestion/index.php/lookup/StorageLocation/GetHierarchyLevel?id=0%3A0&bundle=&init=1&root_item_id=&start=0&max=500'); //'http://www.inrap.local/".$vs_archeologyBoxes_url."');
    console.log('http://www.inrap.local".$vs_archeologyBoxes_url."');
      
        
</script>

";

            $va_params["caEditorInspectorAppend"] = $vs_buf;
        }

        return $va_params;
    }
    # -------------------------------------------------------
    /**
     * Insert menu
     */
    public function hookRenderMenuBar($pa_menu_bar)
    {
        // No menu insertion for now

        return $pa_menu_bar;
    }

    public function hookRenderWidgets($pa_widgets_config)
    {
        // No widget for now
        return $pa_widgets_config;
    }
    # -------------------------------------------------------
    /**
     * Get plugin user actions
     */

    static public function getRoleActionList() {
        // No required role
        return array();
    }

    # -------------------------------------------------------
    /**
     * Add plugin user actions
     */
    public function hookGetRoleActionList($pa_role_list) {
        //No role action now
        return $pa_role_list;
    }
}

?>
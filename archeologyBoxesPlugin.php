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
		$this->description = _t('Archeology boxes');
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
	public function hookAppendToEditorInspector(array $va_params = array())
	{
        MetaTagManager::addLink('stylesheet', __CA_URL_ROOT__."/app/plugins/archeologyBoxes/assets/css/archeologyBoxes.css",'text/css');

		$t_item = $va_params["t_item"];

		// basic zero-level error detection
		if (!isset($t_item)) return false;

		// fetching content of already filled vs_buf_append to surcharge if present (cumulative plugins)
		if (isset($va_params["vs_buf_append"])) {
			$vs_buf = $va_params["vs_buf_append"];
		} else {
			$vs_buf = "";
		}

		$vs_table_name = $t_item->tableName();
		$vn_item_id = $t_item->getPrimaryKey();
		$vn_code = $t_item->getTypeCode();

		if (($vs_table_name == "ca_objects")&& (in_array($vn_code,$this->opo_config->get("types")))) {
			$vs_archeologyBoxes_url = caNavUrl($this->getRequest(), "archeologyBoxes", "Boxes", "Index", array("id"=>$vn_item_id));

            $vs_buf = "<div style=\"text-align:center;width:100%;margin-top:10px;\">"
                . "<a href=\"" . $vs_archeologyBoxes_url . "\" class='put-in-box-button'>"
                . "Mise en caisse"
                . "</a></div>";

		}

		$va_params["caEditorInspectorAppend"] = $vs_buf;
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
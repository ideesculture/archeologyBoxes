<?php
	require_once(__CA_MODELS_DIR__."/ca_list_items.php");
	require_once(__CA_MODELS_DIR__."/ca_sets.php");

    class BoxController  extends ActionController {
        # -------------------------------------------------------
        protected $opo_config; // plugin configuration file
        protected $ps_plugin_path;
        protected $ps_plugin_url;
        # -------------------------------------------------------
        #
        # -------------------------------------------------------
        public function __construct(&$po_request, &$po_response, $pa_view_paths = null)
        {
            parent::__construct($po_request, $po_response, $pa_view_paths);

            $this->ps_plugin_path = __CA_BASE_DIR__ . "/app/plugins/archeologyBoxes";
            $this->ps_plugin_url  = __CA_URL_ROOT__ . "/app/plugins/archeologyBoxes";
            if (file_exists($this->ps_plugin_path . '/conf/local/archeologyBoxes.conf')) {
                $this->opo_config = Configuration::load($this->ps_plugin_path . '/conf/local/archeologyBoxes.conf');
            } else {
                $this->opo_config = Configuration::load($this->ps_plugin_path . '/conf/archeologyBoxes.conf');
            }
        }

        public function popup() {
            $this->view->setVar('plugin_path', $this->ps_plugin_path);
            $this->view->setVar('plugin_url', $this->ps_plugin_url);

            $vn_id = $this->request->getParameter("id",pInteger);
            $this->view->setVar('id', $vn_id);



			$vn_set = $this->request->getParameter("set",pInteger);
            if($vn_set) {
            	// It's a set
				$vt_set = new ca_sets($vn_set);
				$collection_id = $vt_set->get("ca_sets.set_operation");
				if(!$collection_id) {
					return $this->render('popup_set_no_collection_html.php');
				}
				$this->view->setVar('message', 'Créer la nouvelle caisse et ajouter les objets de l\'ensemble à la caisse');
				$url = caNavUrl($this->getRequest(), "*", "*", "CreateBox/set/".$vn_set);

			} else {
            	// It's an object
				$vt_object = new ca_objects($vn_id);
				$collection_id = $vt_object->get("ca_collections.collection_id");
				if(!$collection_id) {
					return $this->render('popup_no_collection_html.php');
				}
				$this->view->setVar('message', 'Créer la nouvelle caisse et ajouter l\'objet');
				$url = caNavUrl($this->getRequest(), "*", "*", "CreateBox/id/".$vn_id);
			}
			$this->view->setVar('addBox_url',$url); //$url = caNavUrl($this->getRequest(), "*", "*", "*");



			$vt_collection = new ca_collections($collection_id);
            $caisses = $vt_collection->get("ca_objects.object_id", ["returnAsArray"=>true]);

            $vt_referentiel = new ca_lists();
            $vt_referentiel->load(array('idno'=>"inrap_referentiel)"));
			$references = [];
			foreach($vt_referentiel->getItemsForList("inrap_referentiel") as $num=>$reference) {
				$reference = reset($reference);
				$references[$num] = $reference["name_singular"];
			}
			
			$vt_object_types = new ca_lists();
			$vt_object_types->load(array('idno'=>"object_types)"));
			$types_caisses = [];
			foreach($vt_object_types->getItemsForList("object_types") as $key=>$type_caisse) {
				$type_caisse = reset($type_caisse);
				if(!in_array($type_caisse["idno"],$this->opo_config->get("box_types"))) continue;
				$types_caisses[$key] = $type_caisse["name_singular"];
			}
			
			if(is_array($caisses) && count($caisses)>0) {
			    foreach($caisses as $key=>$caisse) {
			        $vt_caisse = new ca_objects($caisse);
			        $type = $vt_caisse->get("ca_objects.type_id", ["convertCodesToDisplayText"=>true]);
			        // Exit if not physical box
			        if(!in_array($type,$this->opo_config->get("box_types"))) continue;
			        $view_caisses[] = $vt_caisse;
			    }
			} else {
				$view_caisses = [];
			}

			$this->view->setVar('caisses', $view_caisses);
			$this->view->setVar('references', $references);
			$this->view->setVar('types_caisses', $types_caisses);
		
		
		    return $this->render('popup_html.php');
		}

        public function Attach() {
            $this->view->setVar('plugin_path', $this->ps_plugin_path);
            $this->view->setVar('plugin_url', $this->ps_plugin_url);
            $vn_id = $this->request->getParameter("id",pInteger);
            $box_id = $this->request->getParameter("box",pInteger);
            $vt_object = new ca_objects($vn_id);
            $vt_box = new ca_objects($box_id);
            $return = $vt_object->addRelationship('ca_objects', $box_id, "part_of", null, null, null, null, array('allowDuplicates' => false));

			$this->redirect(caNavUrl($this->getRequest(), "editor", "objects", "ObjectEditor/Edit", ["object_id"=>$vn_id])); //"/gestion/index.php/editor/objects/ObjectEditor/Edit/object_id/3065");
        }

        public function CreateBox() {
			error_reporting(E_ERROR);
            $this->view->setVar('plugin_path', $this->ps_plugin_path);
            $this->view->setVar('plugin_url', $this->ps_plugin_url);
            $vn_id = $this->request->getParameter("id",pInteger);
            if(!$vn_id) {
				$vn_set= $this->request->getParameter("set",pInteger);
			}
            $box_idno = $this->request->getParameter("idno",pString);

            $reference_id = $this->request->getParameter("reference",pString);
            
            $type_caisse_id = $this->request->getParameter("type_caisse",pInteger);

            $vt_reference = new ca_list_items($reference_id);

            $caisse_long = $vt_reference->get("ca_list_items.dimensions_empl.caisse_long");
			$vn_caisse_long = $caisse_long*1;
			if(strpos($caisse_long, "cm")) $vn_caisse_long *= 10;

			$caisse_largeur = $vt_reference->get("ca_list_items.dimensions_empl.caisse_largeur");
			$vn_caisse_largeur = $caisse_largeur*1;
			if(strpos($caisse_largeur, "cm")) $vn_caisse_largeur *= 10;

			$caisse_hauteur = $vt_reference->get("ca_list_items.dimensions_empl.caisse_hauteur");
			$vn_caisse_hauteur = $caisse_hauteur*1;
			if(strpos($caisse_hauteur, "cm")) $vn_caisse_hauteur *= 10;

			$caisse_surface = $vt_reference->get("ca_list_items.dimensions_empl.caisse_surface");
			$vn_caisse_surface = $caisse_surface*1;
			if(strpos($caisse_surface, "cm")) $vn_caisse_surface *= 10;

			$caisse_litres = $vt_reference->get("ca_list_items.inrap_volume_caisse_l");
			$vn_caisse_litres = $caisse_litres*1;


			if($vn_id) {
				$vt_object = new ca_objects($vn_id);
				$collection_id = $vt_object->get("ca_collections.collection_id");
				$vn_ids = [$vn_id];
			} else {
				$vt_set = new ca_sets($vn_set);
				$collection_id = $vt_set->get('ca_sets.set_operation');
				$vn_ids = array_keys($vt_set->getRowIDRanksForSet($vn_set));
			}

            $vt_box = new ca_objects();
            $vt_box->setMode(ACCESS_WRITE);
            $vt_box->set('type_id', $type_caisse_id);
            $vt_box->set('idno', $box_idno);
            $box_id = $vt_box->insert();

            $vt_box->addLabel(array(
                'name' => strtoupper($box_idno)
            ), 2, null, true);
            $vt_box->update();
            $dimensions = [
				"dimensions_depth"=>$vn_caisse_long." mm",
				"dimensions_width"=>$vn_caisse_largeur." mm",
				"dimensions_height"=>$vn_caisse_hauteur." mm"
			];
            $vt_box->addAttribute($dimensions,"dimensions");
			$vt_box->update();
			$vt_box->addAttribute($reference_id, "referentiel");
			$vt_box->update();

			foreach($vn_ids as $id) {
				$vt_object = new ca_objects($id);
				$vt_object->addRelationship('ca_objects', $box_id, "part_of", null, null, null, null, array('allowDuplicates' => false));
			}
            if($collection_id) {
				$vt_box->addRelationship('ca_collections', $collection_id, "part_of", null, null, null, null, array('allowDuplicates' => false));
			}

			if($vn_id) {
				$this->redirect(caNavUrl($this->getRequest(), "editor", "objects", "ObjectEditor/Edit", ["object_id"=>$box_id])); 
			} else {
				$this->redirect(caNavUrl($this->getRequest(), "manage", "sets", "SetEditor/Edit", ["set_id" => $vn_set]));
			}
        }

    }

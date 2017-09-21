<?php

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

            $url = caNavUrl($this->getRequest(), "*", "*", "CreateBox/id/".$vn_id);
            $this->view->setVar('addBox_url',$url); //$url = caNavUrl($this->getRequest(), "*", "*", "*");

            $vt_object = new ca_objects($vn_id);
            $collection_id = $vt_object->get("ca_collections.collection_id");
            $vt_collection = new ca_collections($collection_id);
            $caisses = $vt_collection->get("ca_objects.object_id", ["returnAsArray"=>true]);
            if(is_array($caisses) && count($caisses)>0) {
                foreach($caisses as $key=>$caisse) {
                    $vt_caisse = new ca_objects($caisse);
                    $type = $vt_caisse->get("ca_objects.type_id", ["convertCodesToDisplayText"=>true]);

                    // Exit if not physical box
                    if(!in_array($type,$this->opo_config->get("caisse_type"))) continue;
                    $view_caisses[] = $vt_caisse;
                }
            } else {
                $caisses = [];
            }
            $this->view->setVar('caisses', $view_caisses);
            return $this->render('popup_html.php');
        }

        public function Attach() {
            $this->view->setVar('plugin_path', $this->ps_plugin_path);
            $this->view->setVar('plugin_url', $this->ps_plugin_url);
            $vn_id = $this->request->getParameter("id",pInteger);
            $box_id = $this->request->getParameter("box",pInteger);
            $vt_object = new ca_objects($vn_id);
            $vt_box = new ca_objects($box_id);
            $vt_object->addRelationship('ca_objects', $box_id, "isPartOf", null, null, null, null, array('allowDuplicates' => false));

            $this->redirect(caNavUrl($this->getRequest(), "editor", "objects", "ObjectEditor/Edit", ["object_id"=>3065])); //"/gestion/index.php/editor/objects/ObjectEditor/Edit/object_id/3065");
        }

        public function CreateBox() {
            $this->view->setVar('plugin_path', $this->ps_plugin_path);
            $this->view->setVar('plugin_url', $this->ps_plugin_url);
            $vn_id = $this->request->getParameter("id",pInteger);
            $box_idno = $this->request->getParameter("idno",pString);

            $vt_object = new ca_objects($vn_id);
            $collection_id = $vt_object->get("ca_collections.collection_id");

            $vt_box = new ca_objects();
            $vt_box->setMode(ACCESS_WRITE);
            $vt_box->set('type_id', "Caisse");
            $vt_box->set('idno', $box_idno);
            $box_id = $vt_box->insert();
            $vt_box->addLabel(array(
                'name' => $box_idno
            ), 2, null, true);
            $vt_box->update();
            
            $vt_object->addRelationship('ca_objects', $box_id, "isPartOf", null, null, null, null, array('allowDuplicates' => false));

            $vt_box->addRelationship('ca_collections', $collection_id, "part_of", null, null, null, null, array('allowDuplicates' => false));

            $this->redirect(caNavUrl($this->getRequest(), "editor", "objects", "ObjectEditor/Edit", ["object_id"=>$box_id])); //"/gestion/index.php/editor/objects/ObjectEditor/Edit/object_id/3065");
        }

    }
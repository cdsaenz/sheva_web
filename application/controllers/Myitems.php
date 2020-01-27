<?php if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Myitems extends CDS_Controller
{

    /**
     * MAIN CONTROLLER FOR ITM_MST
     * SOLO PARA DISTRIBUIDOR
     */

    public function __construct()
    {
        // call the parent custom constructor as it does setup stuff
        parent::__construct();

        // verify logged in and admin
        $this->app->AllowSupplier();

        // set the model var to keep the functions generic
        $this->load->model("items_model");
    }



    /**
     *  Default - show all report
     *  (just run the dosearch method)
     */

    public function index()
    {
        // run search without parameters, equals to all
        $this->_dosearch('all');
    }


    /**
     *  THIS IS THE MAIN MASS SEARCH ACTION
     *  Get a search URL like
     *  /search/$sid/$page and execute dosearch
     */

    public function search()
    {
        // If there is a post (from form) capture and process
        $filter   = $this->input->post(null, true);
        // added where to return (back from edit)
        $this->session->set_userdata('referred_from', current_url());

        // execute
        if ($filter) {
            // Process FORM, WHERE/LIKE/ORDER BY and save to sid
            $w = [];

            // keyword
            if (isset($filter["keyword"]) && ($filter["keyword"] != "")) {
                $v = $filter["keyword"];
                // utf8_general_ci is case insensitive. PARENTHESIS WILL FAIL WITH EXTRA_WHERE!
                $w = $this->items_model->add_search_param($w, "WHERE", "itm_cod = '$v' OR itm_name LIKE '%$v%'", "");
                // new store the formvar (ex: keyword)
                $w = $this->items_model->add_search_param($w, "FORMVAR", "keyword", "", $v);
            }

            // item type
            if (isset($filter["type"]) && ($filter["type"] != "")) {
                $v = $filter["type"];
                // utf8_general_ci is case insensitive. PARENTHESIS WILL FAIL WITH EXTRA_WHERE!
                $w = $this->items_model->add_search_param($w, "WHERE", "itm_type","=",$v);
                // new store the formvar (ex: keyword)
                $w = $this->items_model->add_search_param($w, "FORMVAR", "type", "", $v);
            }

            // save to sid
            $sid    = $this->items_model->save_search($w, "itm_mst");
            $page   = 1;

            // goto search with id, this will avoid losing filter on back button
            redirect("myitems/search/$sid");
        } else {
            // LOOK FOR A SEARCH ID get parameters from url
            // it should have fixed where included unless 0/all
            $sid      = $this->uri->segment(3, 0);
            $page     = max((int)$this->uri->segment(4), 1);

            // ALL RECORDS
            if ($sid == 0) {
                // make more readable
                $sid = 'all';
            }

            // launch the query method
            $this->_dosearch($sid, $page);
        }


    }


    /**
     * run the search
     */

    public function _dosearch($sid = 0, $page = 1)
    {
        // keep "all" as needed
        $isid     = (int)$sid;
        // pick up company id
        $cid      = $this->session->user_company_id;

        // distribuidor
        $extra_where = ["itm_mst.company_id" => $cid];
        // supplier's item types
        $types       = $this->app->get_item_types(true,$cid);

        // get records upon sid (0=all)
        $result          = $this->items_model->get_records($page, "myitems/search/$sid/", $isid, $extra_where);

        // add pagination, dataset and total
        $data["links"]   = $result["links"];
        $data["dataset"] = $result["query"]->result_array();
        $data["count"]   = $result["count"];
        $data["types"]   = $types;
        // added form vars persistance
        $data["vars"]    = $result["vars"];

        // title
        $data["title"]   = $sid == 0 ? _("Mis Items") : _("Mis Items (Filtrado)") ;

        // get view
        $this->load_view('myitems/list', $data);
    }

    /**
     * Applications
     */


    /**
     *  Single record view method
     *  Get id by url
     */
    public function view()
    {
        // get the query
        $id              = $this->uri->segment(3);
        $row             = $this->items_model->get_record($id);

        // verify we actually got something.
        if (!$row) {
            $this->error(_('Error: ID no existe.'));
        } else {
            // check company
            $cid  = $row['company_id'];
            if ($cid <> $this->session->user_company_id) {
                $this->error("Error, acceso a item restringido a distribuidor/admin.");
            }

            // set id info & title
            $data['id']      = $id;
            $data['row']     = $row;
            $data['media']   = $this->items_model->get_media('items', $id, 'PICTURE');
            $data['hidden']  = array('edit_mode' => 'view',
                                     'key_id'    => $id   );
            $data['title']   = $row['itm_cod'] ;

            // get view
            $this->load_view('myitems/view', $data);
        }

    }



    /**
      *  Single record edit method
      *  Get id by url
      */

    public function edit()
    {
        // get the query
        $id      = $this->uri->segment(3,0);
        // verify we actually got something.
        if (!$id) {
            $this->error(_('Error: ID invalido'));
        }

        $row     = $this->items_model->get_record($id);
        // verify we actually got something.
        if (!$row) {
            $this->error(_('Error: ID no existe.'));
        }

        // check company
        $cid  = $row['company_id'];
        if ($cid <> $this->session->userdata("user_company_id")) {
            $this->error("Error, acceso a item restringido a distribuidor/admin.");
        }

        // supplier's item types
        $types       = $this->app->get_item_types(true,$cid);

        // set id info & title
        $data['row']       = $row;
        // rest of data
        $data['types']     = $types;
        $data['hidden']    = array('edit_mode' => 'edit',
                                   'company_id'=> $row['company_id'],
                                   'itm_cod'   => $row['itm_cod'],
                                   'key_id'    => $id   );

        $data['title']     = $row['itm_cod']. _(" - Editar Item");

        // load the view
        $this->load_view('myitems/edit', $data);
    }

    /**
     *  Single record post edit or insert
     *  Get data by post
     */

    public function doedit()
    {
        // get the query from post
        $parms = $this->input->post(null, true);

        // called without a post
        if (!$parms) {
            $this->error(_("Llamada incompatible."));
        }

        // launch the poster (it should validate before posting)
        $mode  = $parms['edit_mode'];
        if ($mode == 'edit') {
            $id = $this->input->post('key_id');
            $this->items_model->update_record($parms, $id);
            // go back
            redirect("myitems/view/$id", "refresh");
        } elseif ($mode == 'add') {
            $id = $this->items_model->insert_record($parms);
            // go back
            redirect("myitems/view/$id", "refresh");
        }
    }


    /**
     *  Single record insert/add method
     */
    public function add()
    {
        // get an empty array of record
        $row              = $this->items_model->get_record(0,true);

        // set defaults
        $cid              = $this->session->user_company_id;
        $row['company_id']= $cid;

        // supplier's item types
        $types       = $this->app->get_item_types(true,$cid);

        // fill data
        $data['row']       = $row;
        // set restricted for dropdown
        $data['restricted']= " disabled readonly";
        // more info
        $data['types']     = $types;
        $data['hidden']    = array( 'company_id'  => $cid,
                                   'edit_mode'   => 'add');
        $data['title']     = _("Nuevo Item Distribuidor");


        $this->load_view('myitems/edit', $data);
    }


    /**
     *  Mass price change
     *
     */
    public function priceup()
    {
        // company of this user
        $cid = $this->session->user_company_id;

        // verify supplier exists
        $this->load->model("suppliers_model");
        $supplier = $this->suppliers_model->get_record($cid);
        if (!$supplier) {
            $this->error("Error, distribuidor desconocido.");
        }

        // parameters
        $data            = array ( "cid"         => $cid,
                                   "supp_name"   => $supplier["supp_name"],
                                   "title"       => "Cambio de Precios Masivo",
                                   "types"       => $this->app->get_item_types(TRUE, $cid));
        // pass it on
        $data ["hidden"] = [ "itm_supp_id" => $cid ];

        // get view
        $this->load_view('myitems/priceup', $data);
    }

    /**
     *  Run price mass change
     *
     */

    public function dopriceup()
    {
        // get the query from post
        $parms = $this->input->post(null, true);

        // called without a post
        if (!$parms) {
            $this->error(_("Llamada incompatible."));
        }

        // process the change
        $tot_chg = $this->items_model->price_change($parms);

        // inform result
        $this->feedback("Se procesaron $tot_chg items", "items", $url_title = 'Ir a Items', $title = 'Informacion');
    }



    /**
     *  CSV Importer
     *
     */
    public function import()
    {
        // supplier only his company
        $cid = $this->session->user_company_id;

        // pass on the post
        $hidden = array("company_id" => $cid);

        // data
        $data   = array("title"       => "Importar CSV a Items",
                        "company_id"  => $cid,
                        "hidden"      => $hidden);

        // get the view
        $this->load_view('myitems/import', $data);
    }

    /**
     *  Run csv importer
     *  upload data first.
     */

    public function doimport()
    {
        // get company id from post
        $company_id  = $this->input->post("company_id");
        if (!$company_id) {
            $this->error(_("Llamada incompatible."));
        }

        // field with the upload filename, link data etc
        $field_name  = "file_media_src";
        // file uploaded
        $file_temp   = $_FILES[$field_name]['tmp_name'];
        $file_name   = $_FILES[$field_name]['name'];

        // do the upload
        if (file_exists($file_temp) && is_uploaded_file($file_temp)) {
            // build a name for the file
            $upload_name = $file_name;
            // perform the upload to the subfolder. extra parameter is extension allowed
            $upload_data = $this->items_model->doUpload($upload_name, $field_name, "batch","csv|txt");
            // update the record
            if ($upload_data) {
                // full path to csv
                $csv_file = rtrim(config_item('attach_path'), "/") . "/batch/" . $upload_name;

                // process the csv INSIDE THE VIEW!
                $data = array( "title"      => "Importacion de CSV a items, Distribuidor $company_id",
                               "company_id" => $company_id,
                               "csv_file"   => $csv_file );

                // Load the process view.
                $this->load_view('myitems/import_process', $data);
            }
        } else {
            $this->error(_("Error procesando archivo o archivo no ingresado."));
        }

    }

    /**
     *  Output to pdf
     */
    public function topdf()
    {
      // filter out
      $cid    = $this->session->user_company_id;
      if (!$cid) {
          $this->error(_("Distribuidor no definido."));
      }

      // scratch problem is: where limits, order by.
      $sql    = "SELECT itm_name Item, itm_cod Codigo
                  FROM itm_mst WHERE itm_mst.company_id = $cid ";

      // get the query
      $query = $this->db->query($sql);
      // QUICK OPTION: transform to table etc and output to the pdf screen
      $this->items_model->to_pdf($query, 'Mis Items', false, 'myitems.pdf');
    }

}

/* End of file items.php */
/* Location: ./application/controllers/items.php */

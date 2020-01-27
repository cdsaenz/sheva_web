<?php if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Itypes extends CDS_Controller
{

    /**
     * MAIN CONTROLLER FOR ITM_TYPES
     *
     */

    public function __construct()
    {
        // call the parent custom constructor as it does setup stuff
        parent::__construct();

        // CLOSED TO ADMIN verify logged in
        $this->app->AllowOnlySupplier();

        // set the model var to keep the functions generic
        $this->load->model("itypes_model");
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
                $w = $this->itypes_model->add_search_param($w, "WHERE", "type_name LIKE '%$v%'", "");
                // new store the formvar (ex: keyword)
                $w = $this->itypes_model->add_search_param($w, "FORMVAR", "keyword", "", $v);
            }

            // save to sid
            $sid    = $this->itypes_model->save_search($w, "itm_types");
            $page   = 1;

            // goto search with id, this will avoid losing filter on back button
            redirect("itypes/search/$sid");
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
        $isid        = (int)$sid;
        // pick up company id
        $cid         = $this->session->user_company_id;

        // admin ALL (could be filtered before)
        $extra_where = ["type_supp_id" => $cid];

        // get records upon sid (0=all)
        $result      = $this->itypes_model->get_records($page, "itypes/search/$sid/", $isid, $extra_where);

        // add pagination, dataset and total
        $data["links"]   = $result["links"];
        $data["dataset"] = $result["query"]->result_array();
        $data["count"]   = $result["count"];
        // added form vars persistance
        $data["vars"]    = $result["vars"];

        // title
        $data["title"]   = $sid == 0 ? _("Tipos de Item") : _("Tipos de Items (Filtrado)") ;

        // get view
        $this->load_view('itypes/list', $data);
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
        $row             = $this->itypes_model->get_record($id);

        // verify we actually got something.
        if (!$row) {
            $this->error(_('Error: ID no existe.'));
        } else {
            // set id info & title
            $data['id']      = $id;
            $data['row']     = $row;
            // pictures
            $data['hidden']  = array('edit_mode' => 'view',
                                     'key_id'    => $id   );
            $data['title']   = $row['type_name'] ;

            // get view
            $this->load_view('itypes/view', $data);
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

        $row     = $this->itypes_model->get_record($id);
        // verify we actually got something.
        if (!$row) {
            $this->error(_('Error: ID no existe.'));
        }

        // set id info & title
        $data['row']       = $row;
        // rest of data
        $data['hidden']    = array('edit_mode'   => 'edit',
                                   'type_supp_id'=> $row['type_supp_id'],
                                   'key_id'      => $id   );

        $data['title']     = $row['type_name']. _(" - Editar Tipo");

        // load the view
        $this->load_view('itypes/edit', $data);
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
            $this->itypes_model->update_record($parms, $id);
            // go back
            redirect("itypes/view/$id", "refresh");
        } elseif ($mode == 'add') {
            $id = $this->itypes_model->insert_record($parms);
            // go back
            redirect("itypes/view/$id", "refresh");
        }
    }


    /**
     *  Single record insert/add method
     */
    public function add()
    {
        // get an empty array of record
        $row               = $this->itypes_model->get_record(0,true);

        // set defaults
        $cid                = $this->session->user_company_id;
        $row['type_supp_id']= $cid;

        // fill data
        $data['row']        = $row;
        // more info
        $data['hidden']     = array( 'type_supp_id'  => $cid,
                                     'edit_mode'     => 'add');
        $data['title']      = _("Nuevo Tipo de Item");


        $this->load_view('itypes/edit', $data);
    }


    /**
     *  Output to pdf
     */
    public function topdf()
    {
        $this->error(_("Funcion no implementada."));
    }

}

/* End of file items.php */
/* Location: ./application/controllers/items.php */

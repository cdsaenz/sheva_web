<?php if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Myorders extends CDS_Controller
{

    /**
     * MAIN CONTROLLER FOR ORDER_DET
     * SOLO PARA DISTRIBUIDOR
     */

    public function __construct()
    {
        // call the parent custom constructor as it does setup stuff
        parent::__construct();

        // verify logged in and admin
        $this->app->AllowSupplier();

        // set the model var to keep the functions generic
        $this->load->model("orders_model");
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
                $w = $this->orders_model->add_search_param($w, "WHERE", "itm_cod = '$v' OR itm_name LIKE '%$v%'", "");
                // new store the formvar (ex: keyword)
                $w = $this->orders_model->add_search_param($w, "FORMVAR", "keyword", "", $v);
            }

            // customer
            if (isset($filter["cust_id"]) && ($filter["cust_id"] != "")) {
                $v = $filter["cust_id"];
                // utf8_general_ci is case insensitive. PARENTHESIS WILL FAIL WITH EXTRA_WHERE!
                $w = $this->orders_model->add_search_param($w, "WHERE", "cust_id","=",$v);
                // new store the formvar (ex: keyword)
                $w = $this->orders_model->add_search_param($w, "FORMVAR", "cust_id", "", $v);
            }

            // save to sid
            $sid    = $this->orders_model->save_search($w, "itm_mst");
            $page   = 1;

            // goto search with id, this will avoid losing filter on back button
            redirect("myorders/search/$sid");
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

        // distribuidor FIJO
        $extra_where = ["order_det.supp_id" => $cid];

        // get records upon sid (0=all)
        $result          = $this->orders_model->get_records($page, "myorders/search/$sid/", $isid, $extra_where);

        // add pagination, dataset and total
        $data["links"]    = $result["links"];
        $data["dataset"]  = $result["query"]->result_array();
        $data["customers"]= $this->app->get_supplier_customers($cid,TRUE);
        $data["count"]    = $result["count"];
        // added form vars persistance
        $data["vars"]     = $result["vars"];

        // title
        $data["title"]   = $sid == 0 ? _("Mis Ordenes") : _("Mis Ordenes (Filtrado)") ;

        // get view
        $this->load_view('myorders/list', $data);
    }

    /**
     *  Single record view method
     *  Get id by url
     */
    public function view()
    {
        // get the query
        $id              = $this->uri->segment(3);
        $row             = $this->orders_model->get_record($id);

        // verify we actually got something.
        if (!$row) {
            $this->error(_('Error: ID no existe.'));
        } else {
            // check company
            $cid  = $row['supp_id'];
            if ($cid <> $this->session->user_company_id) {
                $this->error("Error, acceso a orden restringido a distribuidor.");
            }

            // set id info & title
            $data['id']      = $id;
            $data['row']     = $row;
            $data['media']   = $this->orders_model->get_media('items', $id, 'PICTURE');
            $data['hidden']  = array('edit_mode' => 'view',
                                     'key_id'    => $id   );
            $data['title']   = $row['order_nbr'] ;

            // get view
            $this->load_view('myorders/view', $data);
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

        $row     = $this->orders_model->get_record($id);
        // verify we actually got something.
        if (!$row) {
            $this->error(_('Error: ID no existe.'));
        }

        // check company
        $cid  = $row['supp_id'];
        if ($cid <> $this->session->user_company_id) {
            $this->error("Error, acceso a orden restringido a distribuidor.");
        }

        // set id info & title
        $data['row']       = $row;
        // rest of data
        $data['hidden']    = array('edit_mode' => 'edit',
                                   'supp_id'   => $row['supp_id'],
                                   'itm_cod'   => $row['itm_cod'],
                                   'key_id'    => $id   );

        $data['title']     = $row['order_nbr']. _(" - Editar Orden");

        // load the view
        $this->load_view('myorders/edit', $data);
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
            $this->orders_model->update_record($parms, $id);
            // go back
            redirect("myorders/view/$id", "refresh");
        } elseif ($mode == 'add') {
            $id = $this->orders_model->insert_record($parms);
            // go back
            redirect("myorders/view/$id", "refresh");
        }
    }


    /**
     *  Output to pdf
     */
    public function topdf()
    {
        $this->error(_("Funcion no implementada"));
    }

}

/* End of file Myorders.php */
/* Location: ./application/controllers/Myorders.php */

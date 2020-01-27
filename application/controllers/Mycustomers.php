<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mycustomers extends CDS_Controller {

	/**
	 * MAIN CONTROLLER FOR CUST_MST (Clientes) MGMT
   * PARA DISTRIBUIDORES
	 */

    public function __construct() {
        // call the parent custom constructor as it does setup stuff
        parent::__construct();

        // set the model var to keep the functions generic
        $this->load->model("mycustomers_model");

        // verify logged in and admin or supliers
        $this->app->AllowSupplier();
     }


 	 /**
     *  Default - show outstanding
     */

    public function index()
	  {
       // limited search
       if ($this->app->is_admin())
          // admin can't run this
          redirect("customers");
      else
          // load the limited customers list
	        $this->_dosearch("all");
    }

    /**
       *  THIS IS THE MAIN MASS SEARCH ACTION
       *  Get a search URL like
       *  /search/$sid/$page and execute dosearch
       */

    public function search()
    {
        // If there is a post (from form) capture and process
        $filter   = $this->input->post(NULL,TRUE);
        // added where to return (back from edit)
        $this->session->set_userdata('referred_from', current_url());

        // execute
        if ($filter) {
            // Process FORM, WHERE/LIKE/ORDER BY and save to sid
            $w = [];
            // keyword
            if (isset($filter["keyword"]) && ($filter["keyword"] != "")) {
                $v = strtoupper($filter["keyword"]);
                $w = $this->mycustomers_model->add_search_param($w,"WHERE","UPPER(cust_name) LIKE '%$v%'", "");
                // new store the formvar (ex: keyword)
                $w = $this->mycustomers_model->add_search_param($w,"FORMVAR","keyword","", $v);
            }

            // save to sid
            $sid    = $this->mycustomers_model->save_search($w,$this->mycustomers_model->db_table);
            $page   = 1;
        }
        else {
            // LOOK FOR A SEARCH ID get parameters from url
            $sid      = $this->uri->segment(3,0);
            $page     = max((int)$this->uri->segment(4),1);
        }

        // ALL RECORDS
        if ($sid == 0) {
            // make more readable
            $sid = 'all';
        }

        // launch the query method
        $this->_dosearch($sid,$page);
    }

    /**
     * Solo los clientes asociados al distribuidor
     */
    function _dosearch($sid = 0, $page = 1)
    {
	    	// keep "all" as needed
        $isid       = (int)$sid;

        // fixed where: ALWAYS your company. UNLESS admin
        $extra_where = [];

        // supplier id
        $cid         = $this->session->user_company_id;

    		// get only the supplier's linked customers
    		$result      = $this->mycustomers_model->get_records($page,"mycustomers/search/$sid/",$isid, $extra_where, $cid);

		     // add pagination, dataset and total
        $data["links"]   = $result["links"];
		    $data["dataset"] = $result["query"]->result_array();
        $data["count"]   = $result["count"];
        // added form vars persistance
        $data["vars"]    = $result["vars"];

        // title
        $data["title"]   = $sid == 0 ? _("Mis Clientes") : _("Mis Clientes (Filtrado)") ;

        // get the view
        $this->load_view('mycustomers/list',$data);

	}

    /**
     *  Single record view method
     *  Get id by url
     */
    public function view()
    {
        // get the query, return 0 on no value
        $id              = $this->uri->segment(3,0);
        $row             = $this->mycustomers_model->get_record($id);

        // verify we actually got something.
        if (!$row) {
            $this->error(_('Error: registro no existe o no asociado a distribuidor.'));
        }
        else {
            // from login
            $cid      = $this->session->user_company_id;
            // from record
            $cust_id  = $row["cust_id"];
            $supp_id  = $row["supp_id"];
            if ($supp_id <> $cid) {
                $this->error(_('Error: Cliente no asociado a Distribuidor actual.'));
            }

            // set id info & title
            $data['id']      = $id;
            $data['row']     = $row;
            $data['hidden']  = array('edit_mode' => 'view',
                                     'key_id'    => $id   );
            $data['title']   = $row['cust_name'] ;

            // get the view
            $this->load_view('mycustomers/view', $data);
        }
    }

    /**
     *  Output to pdf
     */
    public function topdf() {

        // from login
        $cid      = $this->session->user_company_id;

        // scratch problem is: where limits, order by.
        $sql = "SELECT cust_name Nombre
                  FROM cust_supp
                  LEFT JOIN cust_mst ON cust_mst.id = cust_supp.cust_id
                  LEFT JOIN supp_mst ON supp_mst.id = cust_supp.supp_id
                  LEFT JOIN users ON users.id = cust_supp.posted_by
                  WHERE cust_supp.supp_id = $cid";

        // get the query
        $query = $this->db->query($sql);
        // QUICK OPTION: transform to table etc and output to the pdf screen
        $this->app->to_pdf($query, 'Mis Clientes', false, 'misclientes.pdf');
    }



}

/* End of file Customers.php */
/* Location: ./application/controllers/Customers.php */

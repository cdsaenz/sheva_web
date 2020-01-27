<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Codes extends CDS_Controller {

	/**
	 * MAIN CONTROLLER FOR CODE_MST (Tablas generales) MGMT
	 */

    public function __construct() {
        // call the parent custom constructor as it does setup stuff
        parent::__construct();

        // admin only
        $this->app->_AllowOnlyAdmin();

        // set the model var to keep the functions generic
        $this->load->model("codes_model");
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
        $filter   = $this->input->post(NULL,TRUE);
  		// added where to return (back from edit)
        $this->session->set_userdata('referred_from', current_url());

        // execute
        if ($filter) {
            // Process FORM, WHERE/LIKE/ORDER BY and save to sid
			$w = [];
			if (isset($filter["keyword"]) && ($filter["keyword"] != ""))
				$w = $this->codes_model->add_search_param($w,"WHERE","UPPER(code_value)","LIKE", strtoupper($filter["keyword"]));
			if (isset($filter["tipo"]) && ($filter["tipo"] != ""))
				$w = $this->codes_model->add_search_param($w,"WHERE","UPPER(code_field)","=", strtoupper($filter["tipo"]));

        	// save to sid
			$sid    = $this->codes_model->save_search($w,"code_mst");
            $page   = 1;
        }
        else {
            // LOOK FOR A SEARCH ID get parameters from url
            // it should have fixed where included unless 0/all
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
     * run the search
	 */

	function _dosearch($sid = 0, $page = 1)

	{
		// keep "all" as needed
        $isid     = (int)$sid;

        // fixed where: ALWAYS your company. UNLESS admin
        $extra_where = [];

		// get records upon sid (0=all)
		$result   = $this->codes_model->get_records($page,"codes/search/$sid/",$isid, $extra_where);

		// add pagination, dataset and total
        $data["links"]   = $result["links"];
		$data["dataset"] = $result["query"]->result_array();
        $data["count"]   = $result["count"];

        // title
        $data["title"]   = $sid == 0 ? _("Tablas Generales") : _("Tablas Generales (Filtrado)") ;

        // inject data into the main content view and load over layout
        $this->load_view('codes/list',$data);

	}


    /**
     *  Single record view method
     *  Get id by url
     */
    public function view()
    {

        // restrict to admin
        $this->_AllowOnlyAdmin();

        // get the query
        $id              = $this->uri->segment(3);
        $data['row']     = $this->codes_model->get_record($id);


        // verify we actually got something.
        if (!$data['row']) {
            $this->error(_('Error: ID no existe en la base de datos.'));
        }

        // TODO: Security limitar view

        else {
            // set id info & title
            $data['id']      = $id;
            $data['hidden']  = array('edit_mode' => 'view',
                                     'key_id'    => $id   );
            $data['title']   = _("Detalle de Codigo de Tabla");

            // get the content FROM this view (process with vars) into a var
            $this->load_view('codes/view', $data);
        }

    }



   /**
     *  Single record edit method
     *  Get id by url
     */

    public function edit()

    {
        // limitar solo a admin
        $this->_AllowOnlyAdmin();

        // get the query
        $id              = $this->uri->segment(3);
        $data['row']     = $this->codes_model->get_record($id);

        // verify we actually got something.
        if (!$data['row']) {
            $this->error(_('Error: ID no existe en la base de datos.'));
        }

        // set id info & title
        $data['hidden']  = array('edit_mode' => 'edit',
                                 'code_field'=> $data['row']['code_field'],
                                 'code_value'=> $data['row']['code_value'],
                                 'key_id'    => $id   );
        $data['title']   = _("Modificar Codigo");

        // get the content FROM this view (process with vars) into a var
        $this->load_view('codes/edit', $data);


    }

      /**
     *  Single record post edit or insert
     *  Get data by post
     */

     public function doedit()

     {
        // get the query from post
        $parms = $this->input->post(NULL,TRUE);

        if (!$parms)
            $this->error(_("Datos invalidos."));

         // launch the poster (it should validate before posting)
		$mode  = $parms['edit_mode'];
        if ($mode == 'edit') {
            $id = $this->input->post('key_id');
            $this->codes_model->update_record($parms,$id);
            // go back
            redirect("codes/view/$id","refresh");
        } else if ($mode == 'add') {
			$id = $this->codes_model->insert_record($parms);
            // go back
            redirect("codes/view/$id","refresh");
		}


    }

  	/**
     *  Single record insert/add method
     */
    public function add()

    {
        // limitar solo a admin
        $this->_AllowOnlyAdmin();

        // set info
        $data['hidden']  = array('edit_mode' => 'add');
        $data['title']   = _("Nuevo Codigo de Tabla");

        // get the content FROM this view (process with vars) into a var
        $data['row']       = $this->codes_model->get_record(0);

        // get the view
        $this->load_view('codes/edit', $data);
    }


  	/**
     *  Multiple record insert/add method
     */
    public function multiple()

    {
        // limitar solo a admin
        $this->_AllowOnlyAdmin();

        // set info
        $data['hidden']  = array('edit_mode' => 'add');
        $data['title']   = _("Ingreso multiple de Codigos");

        // get the content FROM this view (process with vars) into a var
        $data['row']       = $this->codes_model->get_record(0);

        // get the view
        $this->load_view('codes/multiple', $data);
    }

      /**
     *  Multiple record post edit or insert
     *  Get data by post
     */

     public function domultiple()

     {
        // get the query from post
        $parms = $this->input->post(NULL,TRUE);

        if (!$parms)
            $this->error(_("Datos invalidos."));

        // launch the poster (it should validate before posting)
		$this->codes_model->multiple_insert($parms);
        redirect("codes","refresh");

    }


}

/* End of file codes.php */
/* Location: ./application/controllers/codes.php */

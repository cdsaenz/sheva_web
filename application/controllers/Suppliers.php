<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Suppliers extends CDS_Controller {

	/**
	 * MAIN CONTROLLER FOR SUPP_MST (Proveedores/Distribuidores) MGMT
	 */

    public function __construct() {
        // call the parent custom constructor as it does setup stuff
        parent::__construct();

        // set the model var to keep the functions generic
        $this->load->model("suppliers_model");

        // verify logged in and admin
        $this->app->AllowOnlyAdmin();
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
            // keyword
      			if (isset($filter["keyword"]) && ($filter["keyword"] != "")) {
      			    $v = strtoupper($filter["keyword"]);
      				  $w = $this->suppliers_model->add_search_param($w,"WHERE","UPPER(supp_name) LIKE '%$v%' OR supp_cod = '$v'", "");
                // new store the formvar (ex: keyword)
                $w = $this->suppliers_model->add_search_param($w,"FORMVAR","keyword","", $v);
            }

            // save to sid
      			$sid    = $this->suppliers_model->save_search($w,"supp_mst");
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
    		$result          = $this->suppliers_model->get_records($page,"suppliers/search/$sid/",$isid, $extra_where);

		     // add pagination, dataset and total
        $data["links"]   = $result["links"];
		    $data["dataset"] = $result["query"]->result_array();
        $data["count"]   = $result["count"];
        // added form vars persistance
        $data["vars"]    = $result["vars"];

        // title
        $data["title"]   = $sid == 0 ? _("Distribuidores") : _("Distribuidores (Filtrado)") ;

        // Get the view
        $this->load_view('suppliers/list',$data);

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
        $id              = $this->uri->segment(3,0);
        $row             = $this->suppliers_model->get_record($id);

        // verify we actually got something.
        if (!$row) {
            $this->error(_('Error: ID no existe.'));
        }
        else {
            // load media model
            $this->load->model("media_model");

            // set id info & title
            $data['id']      = $id;
            $data['row']     = $row;
            // get logo only
            $data['logo']    = $this->media_model->get_main_media('suppliers',$id,'LOGO');
            $data['hidden']  = array('edit_mode' => 'view',
                                     'key_id'    => $id   );
            $data['title']   = $row['supp_name'] ;

            // get the view
            $this->load_view('suppliers/view', $data);
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
        $row     = $this->suppliers_model->get_record($id);

        // verify we actually got something.
        if (!$row) {
            $this->error(_('Error: ID no existe.'));
        }

        // load media model
        $this->load->model("media_model");

        // set id info & title
        $data['row']     = $row;
        // get logo only
        $data['logo']    = $this->media_model->get_main_media('suppliers',$id,'LOGO');
        $data['hidden']  = array('edit_mode' => 'edit',
                                 'supp_cod'  => $data['row']['supp_cod'],
                                 'key_id'    => $id   );
        $data['title']   = $row['supp_name']. _(" - Editar Distribuidor");

        // get the view
        $this->load_view('suppliers/edit', $data);
    }

      /**
     *  Single record post edit or insert
     *  Get data by post
     */

     public function doedit()
     {
        // get the query from post
        $parms = $this->input->post(NULL,TRUE);

        // called without a post
        if (!$parms)
            $this->error(_("Llamada incompatible."));

        // cargar modelo medios
        $this->load->model("media_model");

         // launch the poster (it should validate before posting)
		    $mode  = $parms['edit_mode'];
        if ($mode == 'edit') {
            $id     = $this->input->post('key_id');
            // actualizar registro
            $this->suppliers_model->update_record($parms,$id);
            // subir el logo al media master
            $upload = $this->media_model->upload_media('LOGO','suppliers',$id,'ONLY');

            // go back
            redirect("suppliers/view/$id","refresh");
        } else if ($mode == 'add') {
			      $id      = $this->suppliers_model->insert_record($parms);

            // subir el logo al media master
            $upload  = $this->media_model->upload_media('LOGO','suppliers',$id,'ONLY');

            // go back
            redirect("suppliers/view/$id","refresh");
		    }

    }


  	/**
     *  Single record insert/add method
     */
    public function add()

    {
        // set info. empty specs
        $data['hidden']  = array('edit_mode'   => 'add');
        $data['title']   = _("Nuevo Distribuidor");

        // get the content FROM this view (process with vars) into a var
        $data['row']       = $this->suppliers_model->get_record(0);
        // get the view
        $this->load_view('suppliers/edit', $data);
    }


    /**
     *  Output to pdf
     */
    public function topdf() {

        // scratch problem is: where limits, order by.
        $sql = "SELECT supp_name Nombre, supp_cod Codigo
                    FROM supp_mst";

        // get the query
        $query = $this->db->query($sql);
        // QUICK OPTION: transform to table etc and output to the pdf screen
        $this->suppliers_model->to_pdf($query, 'Distribuidores', false, 'distrib.pdf');
    }


}

/* End of file Suppliers.php */
/* Location: ./application/controllers/Suppliers.php */

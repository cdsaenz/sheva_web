<?php if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Registrations extends CDS_Controller
{

    /**
     * MAIN CONTROLLER FOR REG_MST (Registrations) MGMT
     */

    public function __construct()
    {
        // call the parent custom constructor as it does setup stuff
        parent::__construct();

        // set the model var to keep the functions generic
        $this->load->model("registrations_model");

        // verify logged in ONLY SUPPLIERS
        $this->app->AllowSupplier();
    }


    /**
    *  Default - show all report
    *  (just run the dosearch method)
    */

    public function index()
    {
        // solo pendientes
        $this->outstanding();
    }


    /**
     *  Outstanding requests:
     *   ADMIN: NUEVO
     *   DISTRIBUIDORES: de su empresa, NUEVO
     *   si implementamos "viewed" podria tomarse
     */
    public function outstanding()
    {
        // check if admin
        $isadmin  = $this->app->is_admin();
        $w        = [];
        if ($isadmin) {
            $w = $this->registrations_model->add_search_param($w, "WHERE_IN", "reg_status", "IN", "NUEVO");
            // save to sid
            $sid    = $this->registrations_model->save_search($w, "reg_mst");
            $page   = 1;
            // launch the query method
            $this->_dosearch($sid, $page, "Registraciones (Pendientes)");
        } else {
            // your company id
            $cid = $this->session->user_company_id;
            // filter out
            $w   = $this->registrations_model->add_search_param($w, "WHERE_IN", "reg_status", "IN", "NUEVO");
            $w   = $this->registrations_model->add_search_param($w, "WHERE", "reg_mst.supp_id", "=", $cid);
            // save to sid
            $sid    = $this->registrations_model->save_search($w, "reg_mst");
            $page   = 1;
            // launch the query method
            $this->_dosearch($sid, $page, "Mis Registraciones (Filtrado)");
        }
    }

    /**
     * run the search
     */

    public function _dosearch($sid = 0, $page = 1)
    {
        // keep "all" as needed
        $isid       = (int)$sid;

        // fixed where: ALWAYS your company. UNLESS admin
        $extra_where = [];

        // get records upon sid (0=all)
        $result      = $this->registrations_model->get_records($page, "registrations/search/$sid/", $isid, $extra_where);

        // add pagination, dataset and total
        $data["links"]   = $result["links"];
        $data["dataset"] = $result["query"]->result_array();
        $data["count"]   = $result["count"];
        // added form vars persistance
        $data["vars"]    = $result["vars"];

        // title
        $data["title"]   = $sid == 0 ? _("Registraciones") : _("Registraciones (Filtrado)") ;

        // get the view
        $this->load_view('registrations/list', $data);
    }


    /**
     *  Single record view method
     *  Get id by url
     */
    public function view()
    {
        // get the query, return 0 on no value
        $id              = $this->uri->segment(3, 0);
        $row             = $this->registrations_model->get_record($id);

        // check security
        $cid = $this->session->user_company_id;
        if (!$this->app->is_admin()) {
            if ($row['supp_id'] <> $cid) {
                $this->error("La registración no le pertenece a su Empresa.");
            }
        }

        // verify we actually got something.
        if (!$row) {
            $this->error(_('Error: registro no existe.'));
        } else {
            // css for the status
            if ($row['reg_status'] == 'APROBADO')
                $data['status_class'] = 'badge badge-success p-1';
            else
                $data['status_class'] = 'badge badge-warning p-1';

            // set id info & title
            $data['id']      = $id;
            $data['row']     = $row;
            $data['hidden']  = array('edit_mode' => 'view',
                                     'key_id'    => $id   );
            $data['title']   = $row['cust_name'] ;

            // get the view
            $this->load_view('registrations/view', $data);
        }
    }



    /**
      *  Single record edit method
      *  EVAL (approve/reject)
      *  Get id by url
      */

    public function eval()
    {
        // get the query
        $id      = $this->uri->segment(3, 0);
        $row     = $this->registrations_model->get_record($id);

        // verify we actually got something.
        if (!$row) {
            $this->error(_('Error: registro no existe.'));
        }

        // check security
        $cid = $this->session->user_company_id;
        if (!$this->app->is_admin()) {
            if ($row['supp_id'] <> $cid) {
                $this->error("La registración no le pertenece a su Empresa.");
            }
        }

        // verificar status
        if ($row['reg_status'] <> 'NUEVO') {
            $this->error(_("Registracion procesada no se puede modificar."));
        }

        // extra data, defaults
        $row['price_list_id'] = 1;

        // set id info & title
        $data['row']     = $row;
        $data['hidden']  = array('edit_mode' => 'edit',
                                 'supp_id'   => $this->session->user_company_id,
                                 'key_id'    => $id   );
        $data['title']   = $row['cust_name']. _(" - Procesar Registracion");

        // get the view
        $this->load_view('registrations/edit', $data);
    }

    /**
     *  Single record post edit or insert
     *  Get data by post
     */

    public function doeval()
    {
        // get the query from post
        $parms = $this->input->post(null, true);

        // called without a post
        if (!$parms) {
            $this->error(_("Llamada incompatible."));
        }

        // launch the poster (it should validate before posting)
        $id = $this->input->post('key_id');

        // this will redirect accordingly
        $this->registrations_model->process_registration($parms, $id);
    }

}

/* End of file Registrations.php */
/* Location: ./application/controllers/Registrations.php */

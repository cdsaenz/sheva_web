<?php if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Users extends CDS_Controller
{

    /**
     * MAIN CONTROLLER FOR USER SECURITY, LOGIN/OUT ETC
     */

    public $model;
    public $model_name = 'users_model';        // base model name

    public function __construct()
    {
        // call the parent custom constructor as it does setup stuff
        parent::__construct();

        // DON'T BLOCK ACCESS HERE, AS LOGOUT/LOGIN ARE PUBLIC

        // set the model var to keep the functions generic
        $this->load->model($this->model_name);
        $this->model = $this->{$this->model_name};
    }

    /**
     *  Default - show all report
     *  (just run the dosearch method)
     */

    public function index()
    {
        // verify logged in and rights adequate
        $this->app->AllowOnlyAdmin();

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
        // verify logged in and rights adequate
        $this->app->AllowOnlyAdmin();

        // If there is a post (from form) capture and process
        $filter   = $this->input->post(null, true);
        // added where to return (back from edit)
        $this->session->set_userdata('referred_from', current_url());

        // execute
        if ($filter) {
            // Process FORM, WHERE/LIKE/ORDER BY and save to sid
            $w = [];
            if (isset($filter["is_active"]) && ($filter["is_active"] != "")) {
                $v = $filter["is_active"];
                $w = $this->users_model->add_search_param($w, "WHERE", "is_active", "=", $v);
                // new store the formvar (ex: keyword)
                $w = $this->users_model->add_search_param($w, "FORMVAR", "is_active", "", $v);
            }
            if (isset($filter["keyword"]) && ($filter["keyword"] != "")) {
                $v = $filter["keyword"];
                $w = $this->users_model->add_search_param($w, "WHERE", "last_name LIKE '$v' OR nick LIKE '$v' OR email LIKE '$v'","");
                // new store the formvar (ex: keyword)
                $w = $this->users_model->add_search_param($w, "FORMVAR", "keyword", "", $v);
            }
            // admin can optionally filter out companies
            if ($this->users_model->can_user('is_admin')) {
                if (isset($filter["company_id"])) {
                    $v = $filter["company_id"];
                    $w = $this->users_model->add_search_param($w, "WHERE", "itm_mst.company_id", "=", $v);
                    // new store the formvar (ex: keyword)
                    $w = $this->users_model->add_search_param($w, "FORMVAR", "company_id", "", $v);
                }
            }

            // save to sid
            $sid    = $this->users_model->save_search($w, "users");
            $page   = 1;

            // enforce the full url to keep the filter active on navigating back
            redirect("users/search/$sid");
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

        // fixed where
        if ($this->app->is_admin())
            // admin ALL (could be filtered before)
            $extra_where = [];
        else if ($this->app->is_supp())
            // distribuidor
            $extra_where = ["user_company_id" => $this->session->userdata("user_company_id")];

        // get records upon sid (0=all)
        $result   = $this->users_model->get_records($page, "users/search/$sid/", $isid, $extra_where);

        // add pagination, dataset and total. and previous formvars
        $data["links"]   = $result["links"];
        $data["dataset"] = $result["query"]->result_array();
        $data["count"]   = $result["count"];
        $data["vars"]    = $result["vars"];

        // title
        $data["title"]   = $sid == 0 ? _("Usuarios") : _("Usuarios (Filtrado)") ;

        // load the view
        $this->load_view('users/list', $data);
    }


    /**
     *  Single record view method
     *  Get id by url
     */
    public function view()
    {

        // verify logged in and rights adequate
        $this->app->AllowOnlyAdmin();

        // get the query
        $id     = $this->uri->segment(3);
        $row    = $this->users_model->get_record($id);

        // verify we actually got something.
        if (!$row) {
            $this->error(_('Error: ID no existe en la base de datos.'));
        }
        else {
            $data['row']     = $row;
            // set id info & title
            $data['id']      = $id;
            $data['hidden']  = array('edit_mode' => 'view',
                                     'key_id'    => $id   );
            $data['status']  =  $row['is_active'] == 'Y' ? "Activo" : "Inactivo";
            $data['title']   = _("Ficha de Usuario");

            // get the content FROM this view (process with vars) into a var
            $this->load_view('users/view', $data);
        }
    }


    /**
     *   PROFILE MAINTENANCE FUNCTIONS
     *   PUBLIC
     */


    /**
     * my Profile EDIT form
     */
    public function profile()
    {
        // inject data into the main content view and load over layout
        $id = $this->session->userdata("user_id");
        if (!$id) {
            $this->error(_("Perfil no disponible"));
        }

        // look for the data
        $row = $this->model->get_record($id);

        // save to data
        $data['row']    = $row;
        $data['hidden'] = array('rowid' => $id);

        // load the view
        $this->load_view('users/profile_edit', $data);
    }

    /**
     * my Profile view
     */
    public function myprofile()
    {
        // inject data into the main content view and load over layout
        $id = $this->session->userdata("user_id");
        if (!$id) {
            $this->error(_("Perfil no disponible"));
        }

        $row  =  $this->model->get_record($id);
        // verify we actually got something.
        if (!$row) {
            $this->error(_('Error: ID no existe en la base de datos.'));
        }
        else {
            // look for the data
            $data['row']             = $row;
            $data['status']          = $row['is_active'] == 'Y' ? "Activo" : "Inactivo";

            // load view
            $this->load_view('users/profile_view', $data);
        }


    }

    /**
     * Profile post
     */
    public function doprofile()
    {
        // process
        $parms = $this->input->post(null, true);
        if (!$parms) {
            $this->error(_("Accion no disponible"));
        }

        // validate and save.
        $this->model->validate_profile($parms);
        $this->model->save_profile($parms);

        redirect("users/myprofile");
    }


    /**
     *   LOGIN/OUT FUNCTIONS
     *
     */

    /**
     * Login form
     */
    public function login()
    {
        $this->load_view('users/login');
    }

    /**
     * Login post
     */
    public function dologin()
    {
        // see if it's really a valid post
        $parms = $this->input->post(null, true);
        if (!$parms) {
            $this->error(_("Accion no disponible"));
        }

        // process the login info
        $nickm  = $this->input->post("nick_mail");
        $pwd    = $this->input->post("user_pwd");

        // validate entered info
        $row    = $this->users_model->validate_user($nickm, $pwd);
        if ($row) {
            // app specific. solo admins or distribuidores tienen acceso
            if ($row->user_company_type == 'CUST') {
                $this->error(_("Usuario restringido."));
            }

            // save to session vars and go back.
            $this->users_model->save_user_info($row->id);

            // either to last position or home
            redirect();
        } else {
            $this->error(_("Usuario o contrase&ntilde;a son incorrectos. Reintente."));
        }
    }


    /**
     * Logout. just destroy the session and all the variables
     */
    public function logout()
    {
        // process logout
        $this->session->sess_destroy();

        // go home
        redirect();
    }

    /**
      *  Cambio de contraseña
      *  Get id by url
      */

    public function pwd()
    {
        // get the user id, if logged in
        $id = $this->session->userdata("user_id");
        if (!$id) {
            $this->error(_("Perfil no disponible"));
        }

        // pick up the user data
        $row     = $this->users_model->get_record($id);

        // verify we actually got something.
        if (!$row) {
            $this->error(_('Error: ID no existe en la base de datos.'));
        }
        else {
            // set id info & title
            $data['row']     = $row;
            $data['hidden']  = array('edit_mode' => 'edit',
                                     'key_id'    => $id   );

            $data['title']   = _("Cambio contrase&ntilde;a");

            // load view into layout
            $this->load_view('users/password', $data);
        }


    }

    /**
      *  Single record post edit or insert
      *  Get data by post
      */

    public function dopwd()
    {
        // get the query from post
        $parms = $this->input->post(null, true);

        if (!$parms) {
            $this->error(_("Datos invalidos."));
        }

        // launch the poster (it should validate before posting)
        $id = $this->input->post('key_id');
        $this->users_model->update_password($parms, $id);

        // go home
        redirect("", "refresh");
    }


    /**
      *  Single record edit method
      *  Get id by url
      */

    public function edit()
    {

        // verify logged in and rights adequate
        $this->app->AllowOnlyAdmin();

        // get the query
        $id              = $this->uri->segment(3);
        $data['row']     = $this->users_model->get_record($id);

        // verify we actually got something.
        if (!$data['row']) {
            $this->error(_('Error: ID no existe en la base de datos.'));
        }

        // set id info & title
        $data['cid']     = $data['row']['user_company_id'];
        $data['hidden']  = array('edit_mode' => 'edit',
                                 'user_company_id' => $data['cid'],
                                 'key_id'    => $id   );
        $data['title']   = _("Modificar Usuario");

        // get the view
        $this->load_view('users/edit', $data);
    }

    /**
     *  Single record post edit or insert
     *  Get data by post
     */

    public function doedit()
    {
        // get the query from post
        $parms = $this->input->post(null, true);

        if (!$parms) {
            $this->error(_("Datos invalidos."));
        }

        // launch the poster (it should validate before posting)
        $mode  = $parms['edit_mode'];
        if ($mode == 'edit') {
            $id = $this->input->post('key_id');
            $this->users_model->update_record($parms, $id);
            // go back
            redirect("users/view/$id", "refresh");
        } elseif ($mode == 'add') {
            $id = $this->users_model->insert_record($parms);
            // go back
            redirect("users/view/$id", "refresh");
        }
    }

    /**
     *  Single record insert/add method
     */
    public function add()
    {
        // verify logged in and rights adequate
        $this->app->AllowOnlyAdmin();

        // set info
        $data['cid']     = 0;
        $data['hidden']  = array('edit_mode' => 'add');
        $data['title']   = _("Crear usuario");

        // get the content FROM this view (process with vars) into a var
        $data['row']       = $this->users_model->get_record(0);

        // get the view
        $this->load_view('users/edit', $data);
    }
}

/* End of file user.php */
/* Location: ./application/controllers/user.php */

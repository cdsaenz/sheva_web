<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Start_model extends CDS_Model {

	/**
	 * MODEL FOR INDEX FRONT PAGE
	 */

    public function __construct() {
        // call the parent custom constructor as it does setup stuff
        parent::__construct();
    }

    public function get_domains() {
        // query the admin database (must be current)
        // to obtain a list of installed domains (company/database)

        $domains = array("DEMO"  => "Empresa Demo 1",
                         "LAGO"  => "Casa Lago");

        return $domains;
    }

}

/* End of file start.php */
/* Location: ./application/models/start_model.php */
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Start extends CDS_Controller {

	/**
	 * MAIN CONTROLLER FOR INDEX FRONT PAGE
	 */

    public function __construct() {
        // call the parent custom constructor as it does setup stuff
        parent::__construct();
    }

	/**
	 * Application main controller
     * Front page and utilities
	 */
	public function index()
	{
        // inject data into the main content view and load over layout
        $data['content']   = $this->load->view('start/main',[], true);
        $this->load->view('layout', $data);
	}

  public function test() {
      echo default_img("items-4-6.webp","");
  }

}

/* End of file start.php */
/* Location: ./application/controllers/start.php */

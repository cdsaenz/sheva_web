<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 *  Base Controller for my applications
 *  VERSION 5.1 1/1/20
 *  simplified for internet apps
 */


class CDS_Controller extends CI_Controller {

    // controller & method name
    public $ctl_name;
    public $act_name;
    // CI handle
    public $CI = NULL;

    /**
     *  Constructor, do initialization for ALL controllers
     *  ex: Gettext Localization setup, login check
     */
    public function __construct($secured = TRUE) {
        // COMMON TASKS
        parent::__construct();

        // set timezone! new 2018 july. also in config file codeigniter
        date_default_timezone_set("America/Argentina/Buenos_Aires");

        // default locale. atencion con este IF por carencia hosting
        if (function_exists("bindtextdomain")) {
            $this->set_locale();
        }

        // controller & aciton name (previous style is deprecated)
        $this->ctl_name = $this->router->class;
        $this->act_name = $this->router->method;

        // accessable in views
        $this->CI = & get_instance();

        // a workaround for some issue appearing on 10/27/19
        ini_set('max_execution_time', 300);

        // always load the app model (functions specific to app)
        $this->load->model("app_model",'app');
    }


    /**
    *  Standard view in layout
    */
    public function load_view ( $view, $data = [], $layout = 'layout') {

      // if it exists, load bottom code (javascript etc) with data parameter
      $view_bottom = "{$view}_bottom";
      if (file_exists(APPPATH."views/{$view_bottom}.php")) {
          $data['content_bottom'] = $this->load->view($view_bottom,$data,true);
      }

      // get the content FROM this view (process with vars) into a var
      $data['content']        = $this->load->view($view,$data,true);

      //charge the view content the main template
      $this->load->view('layout', $data);
    }


    /**
    *  Standard error message function
    */

    public function error ($error_msg, $cangoback = true, $error_title = 'Error') {

        // define back option
        $back = "";
        if ($cangoback)
            $back = "<button class='btn btn-primary btn-sm' onclick='window.history.go(-1); return false;'>"._("Volver")."</button>";

        // prepare parameters;
        $data['title']      = $error_title;
        $data['error_msg']  = $error_msg;
        $data['back']       = $back;

        // get the content FROM this view (process with vars) into a var
        $data['content'] = $this->load->view('errors/error', $data, true);
        //charge the view content the main template
        $this->load->view('layout', $data);

        // Force the CI engine to render the content generated until now
        $this->output->_display();

        // end application
        die;
    }


    /**
    *  Generic feedback message function
    */
    public function feedback ($info_msg, $url = null , $url_title = 'Volver' , $title = 'Informacion') {

        // info could be an array of strings
        if (is_array($info_msg)) {
            $info_msg = implode("<br />",$info_msg);
        }

        // make up a layout format
        $url  = base_url($url);
        $btn  = "<a href='$url' class='btn btn-primary btn-sm'>$url_title</a>";
        $msg  = "<div class='container-fluid mt-3'>
                   <div class='row'>
                     <div class='col-sm-12 col-md-8 mx-auto'>
                       <div class='mt-2 alert alert-info' role='alert'>
                           <h4 class='alert-heading'>$title</h4>
                           <p>$info_msg</p>
                            $btn
                       </div>
                     </div>
                   </div>
                 </div>";

        //charge the view content the main template
        $this->load->view('layout', ['content' => $msg]);

        // Force the CI engine to render the content generated until now
        $this->output->_display();

        // end application
        die;
    }


   /**
    *  Generic confirm message function
    */

    public function confirm ($confirm_msg,$yes_url,$title='Confirm') {
        // make up a layout format

        $yes_url    = base_url($yes_url);
        $no_button  = "<button class='btn btn-primary btn-sm' onclick='window.history.go(-1); return false;'>"._("Back")."</button>";
        $yes_button = "<a href='$yes_url' class='btn btn-primary btn-sm'>"._("Confirm")."</a>";

        $message    = "<div class='mt-2 alert alert-warning' role='alert'>
                          <h4 class='alert-heading'>$title</h4>
                          <p>$confirm_msg</p>
                          $no_button $yes_button
                      </div>";

        //charge the view content the main template
        $this->load->view('layout', ['content' => $message]);

        // Force the CI engine to render the content generated until now
        $this->output->_display();

        // end process
        die;
    }



    /**
     *  Internationalization. Test setlocale to return a positive
     *  IMPORTANT: IN LINUX DO THIS:
     *  RUN
     *  # locale -a
     *  if no es language, run:
     *  # sudo locale es
     *  in rpi: sudo dpkg-reconfigure locales
     */

    public function set_locale ($locale = "es_AR") {

        // localization, default AR. if session var exists, overwrite.
        $lang = $this->session->userdata('locale');

        if ($lang)
            $locale = $lang;

        $locale = "es_AR";

        // save (back) to cookie
        $lang_path = /*FCPATH.*/APPPATH.'language/locale';
        $this->session->set_userdata('locale',$locale);
        putenv("LC_ALL=$locale");
        putenv("LANG=$locale");

        // try to set the locale:
        $result = setlocale(LC_ALL, $locale);
        if (!$result)
            $this->error(_("Error setting Locale: $locale. Please contact admin."));

        // bind it all now
        bindtextdomain("messages", $lang_path);
        bind_textdomain_codeset('messages', 'ISO-8859-1');
        textdomain("messages");

    }

    /*
    *  Switch locale
    *  eg usage: myapp/locale/es_AR
    */
    public function locale()
    {
        $lang = $this->uri->segment(3,"es_AR");
        if ($lang == 'en' || $lang == 'es_AR') {
            $this->session->set_userdata('locale',$lang);

            // same page with new language!
            if (isset($_SERVER['HTTP_REFERER']))
                redirect($_SERVER['HTTP_REFERER'],'refresh');
            else
                redirect("/", 'refresh');
        }
    }

    /**
     *  GENERIC ID CHECK/LOGIN METHOD
     *  If no session var set, redirect.
     *  Only in routes that require security
     *  DON'T CALL AUTOMATICALLY IN JS API CALLS!
     */
    public function check_login() {

        // redirect if no var set
        $logged_in = $this->session->userdata('logged_in');
        if (!$logged_in) {
            // save back url
            $this_url = current_url();
            $this->session->set_userdata("back_url",$this_url);

            // go to the login screen
            redirect("users/login");
        }
    }



   }


/* End of file cds_controller.php */

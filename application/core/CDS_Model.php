<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * CDS CUSTOM BASE model
 * simplified for internet apps
 * @author		csaenz
 * CAMBIO RADICAL BUSQUEDAS 19/1/20 seguridad con uid
 */

class CDS_Model extends CI_Model {

    public $CI;


    // config items now go in app/config/cds_config.php
    public function __construct() {
        // COMMON TASKS tbd
        parent::__construct();

        // CI REFERENCE
        $this->CI =& get_instance();
    }

    /**
     * ERROR & Info (Feedback) Shorthand method
     */
    public function error($msg) {
        return $this->CI->error($msg);
    }
    public function feedback ($info_msg, $url = '' , $url_title = 'Volver' , $title = 'Informacion') {
        return $this->CI->feedback($info_msg, $url, $url_title, $title);
    }


    /**
     * Standard combo values getter
     * Fill up an array with code_mst options by field
     * OPTION 1: No int unique code. Value is code.
     */

    function get_select_values($field, $addBlank = FALSE) {

        $field   = strtoupper($field);

        // FX071108 - Added sort by number (order) and then value
        // compatible with current value 0 in number (will sort by value)
        $sql     = "SELECT code_value FROM code_mst WHERE code_field = '$field' ORDER BY code_number,code_value";
        $qry     = $this->db->query($sql);
        $avals   = $qry->result_array();

        // convert to associative array (key = value)
        $arr = array();
        foreach ($avals as $row) {
            foreach ($row as $fn => $fv){
                $arr[$fv] = $fv;
            }
        }

        // add "ALL" option (for filters, for example)
        // NOTE: EMPTY CAPTION as it may work as "ALL" or .. just empty
        if ($addBlank) $arr = array("" => "") + $arr;
        return $arr;
    }


        /**
         * Standard combo values getter
         * Fill up an array with code_mst options by field
         * OPTION 2: value/label
         */


        function get_select_labels($field, $addBlank = FALSE, $PlaceHolder = "") {

            $field   = strtoupper($field);
            $sql     = "SELECT code_value, code_label FROM code_mst
                          WHERE code_field = '$field' ORDER BY code_number,code_value";

            $qry     = $this->db->query($sql);
            $avals   = $qry->result_array();

            // convert to associative array (key = value)
            $arr = array();
            foreach ($avals as $row) {
                $fv       = $row["code_value"];
                $fl       = $row["code_label"];
                $arr[$fv] = $fl;
            }

            // add "ALL" option (for filters, for example)
            // NOTE: EMPTY CAPTION as it may work as "ALL" or .. just empty
            if ($addBlank) $arr = array("" => $PlaceHolder) + $arr;
            return $arr;
        }


    /**
     * Returns a "user-friendly" label for a code id
     * OR STRING VALUE!
     */
    function get_code_label ($field,$id) {

        $field   = strtolower($field);
        if (is_numeric($id))
          $sql     = "SELECT code_label FROM code_mst WHERE code_field = '$field' AND id = $id";
        else
          $sql     = "SELECT code_label FROM code_mst WHERE code_field = '$field' AND code_value = '$id'";

        $qry     = $this->db->query($sql);
        $result  = $qry->row()->code_label;
        return $result;
    }

   /**
     * Returns the mnemonic code value for a code id
     */
    function get_code_value ($field,$id) {

        $field   = strtolower($field);
        $sql     = "SELECT code_value FROM code_mst WHERE code_field = '$field' AND id = $id";
        $qry     = $this->db->query($sql);
        $result  = $qry->row()->code_value;
        return $result;
    }

    /**
     *  Find if value exists in code_mst for a certain type
     *  value is code!
     */

    public function code_exists($code_field,$code_value) {

        $sql   = "SELECT id FROM code_mst WHERE code_field = '$code_field' AND code_value = '$code_value' ";
        $qry   = $this->db->query($sql);

        // found = true
        return ( $qry->num_rows() > 0 );
    }


    /**
     * YES/NO combo values. Y=Si, N=No.
     */

    function get_yn_values($addBlank = FALSE, $PlaceHolder = "") {

        $arr =  array ("Y" => _("Si"), "N" => _("No") );
        // add "ALL" option (for filters, for example)
        // NOTE: EMPTY CAPTION as it may work as "ALL" or .. just empty
        if ($addBlank)
            $arr = array("" => "") + $arr;
        if ($PlaceHolder)
            $arr = array('_CHOOSE_' => $PlaceHolder) + $arr;
        return $arr;
    }


    /**
     * Can function security by function
     */
    public function can_user($token){

        // check the specific token
        $cando   = $this->session->userdata($token);
        // True if Y or S
        return ($cando == 'Y' or $cando == 'S');
    }


    /**
     * BUILD PAGINATOR
     * for db navigation
     */

	 function get_paginator($base_url,$count,$segment = 3)
	 {
        // load the helper
        $this->load->library('pagination');

    		// pagination style BOOTSTRAP STYLING USED.
    		$config['attributes']       = array('class' => 'page-link');
    		$config['full_tag_open']    = "<ul class='pagination mb-1 mt-1'>";
    		$config['full_tag_close']   ="</ul>";
    		$config['num_tag_open']     = '<li class="page-item">';
    		$config['num_tag_close']    = '</li>';
    		$config['cur_tag_open']     = "<li class='page-item active'><a class='page-link' href='#'>";
    		$config['cur_tag_close']    = "<span class='sr-only'></span></a></li>";
    		$config['next_tag_open']    = "<li>";
    		$config['next_tagl_close']  = "</li>";
    		$config['prev_tag_open']    = "<li>";
    		$config['prev_tagl_close']  = "</li>";
    		$config['first_tag_open']   = "<li>";
    		$config['first_tagl_close'] = "</li>";
    		$config['last_tag_open']    = "<li>";
    		$config['last_tagl_close']  = "</li>";
        $config['first_link']       = "<i class='fas fa-fast-backward'></i>"; //_("First");
        $config['last_link']        = "<i class='fas fa-fast-forward'></i>"; //_("Last");
        $config['next_link']        = "<i class='fas fa-arrow-right'></i>"; //_("Next");
        $config['prev_link']        = "<i class='fas fa-arrow-left'></i>"; //_("Prev");

    		// build pagination preferences
    		$config['use_page_numbers'] = TRUE;
    		$config['uri_segment']		= $segment;
        $config['base_url']       = $base_url;
        $config['total_rows']     = $count;
        $config['per_page']       = config_item("per_page");
        $this->pagination->initialize($config);

    		// return links code
    		return $this->pagination->create_links();
	 }





	/*----------------------------------------------------------------*/
    // ADD a parameter to a search array
    // Return the augmented array
    // type = WHERE, ORDER BY, FORMVAR
    /*----------------------------------------------------------------*/
    public function add_search_param($inparms,$type,$name,$op='',$value='') {

		$inparms[] = array("type"  => strtoupper($type),
					   	         "name"  => $name,
						           "op"	   => strtoupper($op),
						           "value" => $value);
		return $inparms;
	}


	/*----------------------------------------------------------------*/
    // SAVE FORM SEARCH PARAMETERS TO database (SEO/MVC friendly)
    // Receive a search array. Save it to db
    // delete purge schedule:
    // CREATE EVENT my_event
    // ON SCHEDULE
    //  EVERY 1 DAY
    //    STARTS '2014-04-30 00:20:00' ON COMPLETION PRESERVE ENABLE
    // DO delete from sid_mst; delete from sid_det;
    /*----------------------------------------------------------------*/
    public function save_search($inparms,$table,$name='',$rel_type = 'AND') {

    // save even if duplicated parameters.
		// add master. ONLY if parameters is not empty.
		$sid = 0;
		if ($inparms) {

        // pick up $uid
        $uid = $this->session->user_id;

        // prepare query
    		$sql = "INSERT INTO sid_mst (main_table,search_name,rel_type,search_uid)
                  VALUES ('$table','$name','$rel_type',$uid) ";
    		$this->db->query($sql) or $this->error(_("Error guardando busqueda.") );
    		$sid = $this->db->insert_id();

    		// add children. no empty filters check (calling routine must do that)
    		foreach ($inparms as $parm) {
    				// fetch each param
    				$type = $parm["type"];
    				$name = $parm["name"];
    				$op   = ""; $value = "";
    				// verify not empty (ORDER BY)
    				if (isset($parm["op"]))
    					 $op   = $parm["op"];
    				if (isset($parm["value"]))
    					$value= $parm["value"];

    				//param_type  - WHERE or ORDER BY or FORMVAR
    				//param_name  - Field name: example, "employee_name"
    				//param_op    - Operator: example: =, >, LIKE
    				//param_value  - Field value expected: example "JONES%"
            $data = array(
               'search_id'  => $sid,
               'param_name' => $name ,
               'param_op'   => $op ,
               'param_type' => $type ,
               'param_value'=> $value
            );

            $this->db->insert('sid_det', $data);

    		}
		}
        return $sid;
    }

    /*----------------------------------------------------------------*/
    // GET FORM SEARCH PARAMETERS FROM database (SEO/MVC friendly)

    // Capture search parameters from database and put in format
	// compatible with CI's ActiveRecord.
	//id  search_name		main_table	rel_type
	//5   closed_payments	payments_mst	AND (default) / OR

	//id  det_id	param_type	param_name	param_op   	param_value
	//5   10		WHERE		pay_status	= (default)	'CLOSED'
	//5   11 		WHERE		pay_date	>		'20/01/2005'
	//6   12		ORDER BY	upper(cust_id)
    /*----------------------------------------------------------------*/
    public function get_search($sid, $table = '', $check_rights = true) {

		// stored stuff for ActiveRecord
    $orderby  = "";   //ORDER BY CLAUSE (string)
    $awhere   = [];	  //WHERE array
    $where_in = [];   //WHERE IN ( WHERE state IN ('AZ','NY') )
    $alike    = [];   //WHERE array (with like: treated different by ActiveRecord)
    $formvars = [];   // NEW store form entered values.

		// get the search, master and detail
      $sql      = "SELECT  sid_mst.id,
                           sid_mst.search_uid,
  	                       sid_mst.search_name, sid_mst.main_table, sid_mst.rel_type,
						               sid_det.param_type,sid_det.param_name, sid_det.param_op, sid_det.param_value
                   FROM    sid_mst LEFT OUTER JOIN
                           sid_det ON sid_mst.id = sid_det.search_id
                   WHERE  (sid_mst.id = $sid AND sid_mst.main_table = '$table')";

      $query    = $this->db->query($sql);

      // process
      foreach ($query->result() as $row)
      {
        // new 19-1-20 check user who made the original search
        if ($check_rights) {
            if ($row->search_uid <> $this->session->user_id) {
                $this->error(_("Error busqueda no corresponde. Reintentar"));
            }
        }

  	    // add this one to the lot of params.
		    if ($row->param_type == "ORDER BY")
			     // "employee_age DESC"
			     $orderby = $row->param_name;
      	else if ($row->param_type == "FORMVAR")
           // [STATE] = "ny,ms";
      		 $formvars[$row->param_name] = $row->param_value ;
      	else if ($row->param_type == "WHERE_IN")
           // [STATE] = "ny,ms";
      		 $where_in[$row->param_name] = $row->param_value ;
		    else if ($row->param_type == "WHERE") {
			     // a complex index like this allows to have "age >" and "age <"
			     // LIKE are added to a different array
			     if ($row->param_op == "LIKE")
				      $alike[$row->param_name] = $row->param_value;
			     else {
              $awhere[$row->param_name . " " . $row->param_op] = $row->param_value;
           }
          }
		}

    return array("orderby" =>$orderby, "where" =>$awhere, "like" => $alike, "where_in" => $where_in,
                     "formvars"=>$formvars);
    }


    /**
     *  Get media for an certain object type (ex: items)
     *  media_type = PICTURE,VIDEO,OTHER,DOCUMENT
     *  src_type = FILE,LINK
     *  3/11/19 ADDED rel_type as part of the url, so its ex: uploads/items/name_of_the_file.
     */
    public function get_media($rel_type=false,$rel_id=false,$media_type=false,$src_type=false) {
        // build sql. conditional wheres.
        $this->db->select("media_mst.*")->from("media_mst");
        if ($media_type)
            $this->db->where("media_type",$media_type);
        if ($src_type)
            $this->db->where("src_type",$src_type);
        if ($rel_type)
            $this->db->where("rel_type",$rel_type);
        if ($rel_id)
            $this->db->where("rel_id",$rel_id);

        // execute
        $query   = $this->db->get();
        if ($query->num_rows() > 0) {
            // prepare links
            $pictures = $query->result_array();
            foreach ($pictures as &$picture) {
                if ($picture["src_type"] == "FILE")
                    // ANY SERVER URL: removed base_url, as the root might be out of the app structure
                    $picture["url"] = rtrim(config_item('attach_url'),'/') . "/$rel_type/" . $picture['media_src'];
                else
                    $picture["url"] = $picture["media_src"];

                // other embellishments, 20-1-20
                $picture["media_type_name"] = $this->get_code_label("media_type",$picture["media_type"]);
                $picture['is_main_name']    = ($picture['is_main'] == 'Y') ? "Principal" : "&nbsp;";
            }
            return $pictures;
        }
        else
            // or empty_result_array($query);
            return false;
    }



    /**
     *  Central upload function
     *  update 19/1/20 -> $allowed_types :  "Can be either an array or a pipe-separated string"
     */

    public function doUpload($file_name,$field_name = "media_src",$folder = "",$allowed_types = false)
    {
        // CI upload library
        $this->load->library('upload');

        // override the allowed types for certain things. array o gif|jpg|png. "" => all
        if (!$allowed_types) {
            // tomar default de config
            $allowed_types = config_item('attach_allowed_ext');
        }

        // setup attachments config and load
        $config = array(
         'allowed_types' => $allowed_types,
          // if a subfolder is provided it should be "folder/"
         'upload_path'   => rtrim(config_item('attach_path'),"/") . "/" . $folder,
         'max_size'      => config_item('attach_max_size'),
         'file_name'     => $file_name,  // could be a new filename
         'overwrite'     => true
       );
        $this->upload->initialize($config);

        // do the upload
        if(!$this->upload->do_upload($field_name))
            $this->error( $this->upload->display_errors('<p>', '</p>') );
        else
            $upload_data = $this->upload->data();

        // for further processing
        return $upload_data;
    }


     /**
      * EXPORT QUERY RESULT TO CSV
      * Generic
      */

     function to_csv($query, $filename = 'CSV_Report.csv', $delimiter = ";" )
     {
         $this->load->dbutil();
         $this->load->helper('file');
         $this->load->helper('download');

         // default
         $newline   = "\r\n";

         // get stuff
         $data = $this->dbutil->csv_from_result($query, $delimiter, $newline);
         force_download($filename, $data);
     }

     /**
      *  Send query to PDF
      *  Requires TCPDF in libraries and the translated library
      *  WRITEHTML:
      * @param $html (string) text to display
      * @param $ln (boolean) if true add a new line after text (default = true)
      * @param $fill (boolean) Indicates if the background must be painted (true) or transparent (false).
      * @param $reseth (boolean) if true reset the last cell height (default false).
      * @param $cell (boolean) if true add the current left (or right for RTL) padding to each Write (default false).
      * @param $align (string) Allows to center or align the text. Possible values are:<ul><li>L : left align</li><li>C : center</li><li>R : right align</li><li>'' : empty string : left for LTR or right for RTL</li></ul>
    */
      function to_pdf($query, $title = 'Report', $tofile = false, $filename = 'pdf_report.pdf') {

           // load tcpdf library via intermediate
           $this->load->library("Pdf");
           $fname = config_item('attach_path') . $filename;

           // build the html table
           $template = array(
                   'table_open'            => '<table class="table table-bordered">',

                   'thead_open'            => '<thead class="thead-dark">',
                   'thead_close'           => '</thead>',

                   'heading_row_start'     => '<tr>',
                   'heading_row_end'       => '</tr>',
                   'heading_cell_start'    => '<th>',
                   'heading_cell_end'      => '</th>',

                   'tbody_open'            => '<tbody>',
                   'tbody_close'           => '</tbody>',

                   'row_start'             => '<tr>',
                   'row_end'               => '</tr>',
                   'cell_start'            => '<td>',
                   'cell_end'              => '</td>',

                   'row_alt_start'         => '<tr>',
                   'row_alt_end'           => '</tr>',
                   'cell_alt_start'        => '<td>',
                   'cell_alt_end'          => '</td>',

                   'table_close'           => '</table>'
           );

           $this->table->set_template($template);
           $html = $this->table->generate($query);

           // create the pdf file structure P=Portrati, L=landscape
           $pdf = new Pdf('L', 'mm', 'A4', true, 'UTF-8', false);

           $pdf->SetCreator(PDF_CREATOR);
           $pdf->SetAuthor('csdev.com.ar');
           $pdf->SetTitle($title);
           $pdf->SetSubject($title);
           $pdf->SetKeywords($title);

           // set header and footer fonts
           $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
           $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

           // set default header data
           $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, $title , PDF_HEADER_STRING);


           // set default monospaced font
           $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

           // set margins
           $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
           $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
           $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

           // set auto page breaks
           $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
           $pdf->SetDisplayMode('real', 'default');

           // new page
           $pdf->AddPage();

           // html write
           $pdf->writeHTML($html);

           // and output
           if ($tofile)
               // write to file
               $pdf->Output($fname, 'F');
           else
               // write to standard output
               $pdf->Output($filename, 'I');

     }


}  /* END OF CLASS*/

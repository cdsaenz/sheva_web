<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Code Master (tablas) model
 * @author		csaenz
 * 22-08-2018
 */

class Codes_model extends CDS_Model {

    public function __construct() {
        // COMMON TASKS
        parent::__construct();

        // define fields that can be updated in a form.
        $this->db_table  =  "code_mst";
        $this->db_fields =  $this->db->list_fields($this->db_table);
    }

   /**
     * Add multiple codes for a type
     *
     */

   public function multiple_insert($data) {

        // validation not empty
        if ($data["code_field"] == "")
            $this->error(_("Debe informar tipo de tabla"));
        if ($data["code_list"] == "")
            $this->error(_("Debe ingresar codigos validos a insertar."));

        // turn to vars
        $field = $data["code_field"] ;
        $clist = $data["code_list"] ;

        // convertir a array;
        $codes = explode("\n", str_replace("\r", "", $clist));
        $errors= [];

        // validate first each code
        foreach ($codes as $value) {
            $sql  = "SELECT id from code_mst WHERE (code_field = '$field' AND code_value = '$value')";
            $qry  = $this->db->query($sql);
            if ($qry->num_rows() > 0)
                $errors[] = "Codigo '$value' ya existe para tipo '$field'";
        }

        // show errors if any
        if ($errors)
            $this->error(implode("<br>",$errors) );

        // determine if NO CODE EXISTS FOR THE TYPE
        $sql  = "SELECT count(*) as tot_rec from code_mst WHERE (code_field = '$field')";
        $qry  = $this->db->query($sql);
        $totr = $qry->row()->tot_rec;

        // now if all is fine, update
        $i    = 0;
        foreach ($codes as $value) {
            // order only if no other records in the type!
            $number = 0;
            if ($totr == 0)
                // assign the order you loaded in the box
                $number = ++$i;

            // prepare array
            $data = array("code_field" => $field,
                          "code_value" => $value,
                          "code_number"=> $number  );

            // shoot
            $this->insert_record($data);
        }

   }


   /**
     * Add a new record
     * with an array of $field => $value
     */

    public function insert_record ($data) {

		// clean data and keep only valid fields
        // CDS CHANGED: $arr  = array_filter(elements($this->db_fields, $data));
        $arr = valid_fields($this->db_fields,$data);

        // validation not empty
        if ($arr["code_field"] == "")
            $this->error(_("Debe informar tipo de tabla"));
        if ($arr["code_value"] == "")
            $this->error(_("Debe informar codigo de tabla"));

        // order must be numeric
        $arr["code_number"] = intval($arr["code_number"]);

        // validation of not repeated field/value
        $code  = $arr["code_field"];
        $value = $arr["code_value"];

        $sql  = "SELECT id from code_mst WHERE (code_field = '$code' AND code_value = '$value')";
        $qry  = $this->db->query($sql);
        if ($qry->num_rows() > 0)
            $this->error(_("Tipo/codigo ya existe en la base de datos."));

        // current uid
        $uid  = $this->session->userdata("user_id");
        $tim  = date ("YmdHi");

        // add/modify extra data in array as necessary
        $arr ['posted_on'] = $tim;
        $arr ['posted_by'] = $uid;

        // insert data
        $this->db->insert($this->db_table, $arr);
        $id = $this->db->insert_id();

        // return the $id
        return $id;

    }

    /**
     * Update a record
     * with an array of $field => $value
     */

    public function update_record ($data, $id ) {

        // filter only what we want from the form $_post, the $db_fields
        $arr = valid_fields($this->db_fields,$data);

        // validation not empty
        if ($arr["code_field"] == "")
            $this->error(_("Debe informar tipo de tabla"));
        if ($arr["code_value"] == "")
            $this->error(_("Debe informar codigo de tabla"));

        // order must be numeric
        $arr["code_number"] = intval($arr["code_number"]);

        // validation of not repeated field/value in OTHER records.
        // THIS SHOULDN'T HAPPEN UNLESS I LET THEM EDIT THE KEY
        $code  = $arr["code_field"];
        $value = $arr["code_value"];

        $sql  = "SELECT id from code_mst WHERE (code_field = '$code' AND code_value = '$value' AND id <> $id)";
        $qry  = $this->db->query($sql);
        if ($qry->num_rows() > 0)
            $this->error(_("Tipo/codigo ya existe en la base de datos."));

		// current uid & cid
        $uid  = $this->session->userdata("user_id");
        // add/modify extra data in array as necessary
        $arr ['modified_on'] = date ("YmdHi");
        $arr ['modified_by'] = $uid;

        // apply the key limit and update
        $this->db->update($this->db_table, $arr, "id = $id");
    }

    /**
     * Get Records
     */
    public function get_records($page,$url,$sid,$extra_where = [])

    {
        // determine offset
		$limit			 = config_item("per_page");
		$offset          = ($page-1) * $limit;

		// start query building: where,like
		$this->db->start_cache();
		$this->db->from($this->db_table);
		// apply $sid if any. any fixed stuff should be saved in the sid
		if ($sid <> 0){
			$parms = $this->get_search($sid,"code_mst");
			// add to Activerecord
			$this->db->where($parms["where"]);
			$this->db->like($parms["like"]);
		}
        else if ($extra_where) {
            // add a fixed where, example for your own records only.
            $this->db->where($extra_where);
        }

		// cache to avoid loading where twice.
		$this->db->stop_cache();

		// total records BEFORE limit and AFTER where
        $count = $this->db->count_all_results();

     	// pagination links. segment is 4 because of sid (which is 3)
		$links = $this->get_paginator(base_url($url),$count,4);
        // build query
		$this->db->select("code_mst.*,users.nick added_by")
				 ->from("code_mst")
   		         ->join('users', 'users.id = code_mst.posted_by', 'left');

        // both zeroes don't add limit (all records)
        if ($limit <> 0 || $offset <> 0)
            $query = $this->db->limit($limit,$offset);

        // order by. code_number is visual order
        $this->db->order_by("code_mst.code_field, code_mst.code_number, code_mst.code_value");

        // execute the query and return a two dimensional array with query object
        $query = $this->db->get();

        // debug
        // echo $this->db->last_query();

        return array( "query" => $query, "count" => $count, "links" => $links);
    }


    /**
     * GET record data based on id (nbr)
     */
    public function get_record($id) {

        // search
        $sql   = "SELECT code_mst.*, users.nick added_by
                  FROM code_mst
                  LEFT JOIN users ON users.id = code_mst.posted_by
                  WHERE code_mst.id = $id";

        $query = $this->db->query($sql);

        // return a single row or false if not found.
        if ($query->num_rows() > 0)
            return $query->row_array();
        else
            return false;

    }

    /**
     *  Yield table codes (distinct fields) dynamically
     *  value is code!
     */

    public function get_field_types($addBlank = FALSE, $caption = "") {

        $sql   = "SELECT code_value FROM code_mst WHERE code_field = 'code_field'";
        $qry   = $this->db->query($sql);
        $avals = $qry->result_array();

        // convert to associative array (key = value)
        $arr = array();
        foreach ($avals as $row) {
            foreach ($row as $fn => $fv){
                $arr[$fv] = $fv;
            }
        }

        // NOTE: EMPTY CAPTION as it may work as "ALL" or .. just empty
        if ($addBlank) $arr = array("" => $caption) + $arr;
        return $arr;
    }


}

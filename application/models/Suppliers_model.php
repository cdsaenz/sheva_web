<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Suppliers model
 * @author		csaenz
 * 22-08-2018
 */

class Suppliers_model extends CDS_Model {

    public function __construct() {
        // COMMON TASKS
        parent::__construct();

        // define fields that can be updated in a form.
        $this->db_table  =  "supp_mst";
        $this->db_fields =  $this->db->list_fields($this->db_table);
    }



   /**
     * Add a new record
     * with an array of $field => $value
     */

    public function insert_record ($data) {

       // clean data and keep only valid fields
        $arr = valid_fields($this->db_fields,$data);

        // validation: required fields
        if ($arr["supp_cod"] == "")
            $this->error(_("Ingrese un codigo para el proveedor"));
        if ($arr["supp_name"] == "")
            $this->error(_("Debe informar nombre de distribuidor/proveedor."));

        // validation of not repeated code
        $code = $arr["supp_cod"];
        $sql  = "SELECT id from supp_mst WHERE (supp_cod = '$code')";
        $qry  = $this->db->query($sql);
        if ($qry->num_rows() > 0)
            $this->error(_("Codigo de proveedor ($code) ya existe."));

        // current uid/time
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

        // validation: required fields
        if ($arr["supp_cod"] == "")
            $this->error(_("Ingrese un codigo para el proveedor"));
        if ($arr["supp_name"] == "")
            $this->error(_("Debe informar nombre de distribuidor/proveedor."));

        // validation of not repeated code
        $code = $arr["supp_cod"];
        $sql  = "SELECT id from supp_mst WHERE (supp_cod = '$code' AND id <> $id)";
        $qry  = $this->db->query($sql);
        if ($qry->num_rows() > 0)
            $this->error(_("Codigo de proveedor ($code) ya existe para otro registro."));

        // current uid/time
        $uid  = $this->session->userdata("user_id");
        $tim  = date ("YmdHi");

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

        // NO PROTECTION.
        $this->db->protect_identifiers = false;

    		// start query building: where,like
    		$this->db->start_cache();
    		$this->db->from($this->db_table,false);
    		// apply $sid if any. any fixed stuff should be saved in the sid
    		if ($sid <> 0){
    			$parms = $this->get_search($sid,"supp_mst");
                $vars  = $parms["formvars"];
    			// add to Activerecord
    			$this->db->where($parms["where"],false);
    			$this->db->like($parms["like"],false);
    		}
        else {
            $vars = [];
            if ($extra_where) {
                // add a fixed where, example for your own records only.
                $this->db->where($extra_where);
            }
        }

    		// cache to avoid loading where twice.
    		$this->db->stop_cache();

		    // total records BEFORE limit and AFTER where
        $count = $this->db->count_all_results();

     	  // pagination links. segment is 4 because of sid (which is 3)
		    $links = $this->get_paginator(base_url($url),$count,4);
        // build query. FALSE in select eliminates backticks for better parsing
        $this->db->select("supp_mst.*,users.nick added_by",false)
   		           ->join('users', 'users.id = supp_mst.posted_by', 'left',false);

        // both zeroes don't add limit (all records)
        if ($limit <> 0 || $offset <> 0)
            $query = $this->db->limit($limit,$offset,false);

        // order by. code_number is visual order
        $this->db->order_by("supp_mst.supp_name",'ASC',false);

        // execute the query and return a two dimensional array with query object
        $query = $this->db->get();

        // debug
        // echo $this->db->last_query();

        return array( "query" => $query, "count" => $count, "links" => $links, "vars" => $vars);
    }


    /**
     * GET record data based on id (nbr)
     */
    public function get_record($id) {

        // search
        $sql   = "SELECT supp_mst.*, users.nick added_by
                  FROM supp_mst
                  LEFT JOIN users ON users.id = supp_mst.posted_by
                  WHERE supp_mst.id = $id";


        $query = $this->db->query($sql);

        // return a single row or false if not found.
        if ($query->num_rows() > 0)
            return $query->row_array();
        else
            return false;

    }




}

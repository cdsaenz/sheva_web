<?php  if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * Customers model
 * @author		csaenz
 * 22-08-2018
 */

class Customers_model extends CDS_Model
{
    public $db_table;
    public $db_fields;

    public function __construct()
    {
        // COMMON TASKS
        parent::__construct();

        // define fields that can be updated in a form.
        $this->db_table  =  "cust_mst";
        $this->db_fields =  $this->db->list_fields($this->db_table);
    }



    /**
      * Add a new record
      * with an array of $field => $value
      */

    public function insert_record($data)
    {

       // clean data and keep only valid fields
        $arr = valid_fields($this->db_fields, $data);

        // validation: required fields
        if ($arr["cust_name"] == "") {
            $this->error(_("Debe informar nombre de cliente."));
        }

        // current uid/time
        $uid  = $this->session->userdata("user_id");
        $tim  = date("YmdHi");

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
     * Associate an existing customer with an existing supplier
     * in cust_supp
     */
     public function cust_to_supp($cust_id,$supp_id, $price_list_id,$silent_err = false) {

        // validate customer id
        $sql   = "SELECT id FROM cust_mst WHERE id = $cust_id";
        $qry   = $this->db->query($sql);
        if ($qry->num_rows() == 0) {
            $this->error(_("No se puede asociar, cliente $cust_id no existe."));
        }

        // validate supplier id
        $sql   = "SELECT id FROM supp_mst WHERE id = $supp_id";
        $qry   = $this->db->query($sql);
        if ($qry->num_rows() == 0) {
            $this->error(_("No se puede asociar, distribuidor $supp_id no existe."));
        }

        // validate association doesnt exist
        $sql   = "SELECT id FROM cust_supp WHERE cust_id = $cust_id AND supp_id = $supp_id";
        $qry   = $this->db->query($sql);
        if ($qry->num_rows() > 0) {
            if (!$silent_err)
                $this->error(_("Ya existe asociacion cliente/proveedor."));
        }
        else {
            // now associate
            $data ["cust_id"]       = $cust_id;
            $data ["supp_id"]       = $supp_id;
            $data ["price_list_id"] = $price_list_id;

            // add/modify extra data in array as necessary
            $data ['posted_on'] = date("YmdHi");
            $data ['posted_by'] = $this->session->user_id;

            // save association
            $this->db->insert("cust_supp",$data);
            $cust_supp_id = $this->db->insert_id();

            // retornar valor
            return $cust_supp_id;
        }

     }

    /**
     * Update a record
     * with an array of $field => $value
     */

    public function update_record($data, $id)
    {

           // filter only what we want from the form $_post, the $db_fields
        $arr = valid_fields($this->db_fields, $data);

        // validation: required fields
        if ($arr["cust_name"] == "") {
            $this->error(_("Debe informar nombre de cliente."));
        }

        // current uid/time
        $uid  = $this->session->userdata("user_id");
        $tim  = date("YmdHi");

        // add/modify extra data in array as necessary
        $arr ['modified_on'] = date("YmdHi");
        $arr ['modified_by'] = $uid;

        // apply the key limit and update
        $this->db->update($this->db_table, $arr, "id = $id");
    }

    /**
     * Get Records
     */
    public function get_records($page, $url, $sid, $extra_where = [])
    {
        // determine offset
        $limit			 = config_item("per_page");
        $offset      = ($page-1) * $limit;

        // NO PROTECTION.
        $this->db->protect_identifiers = false;

        // start query building: where,like
        $this->db->start_cache();
        $this->db->from($this->db_table, false);
        // apply $sid if any. any fixed stuff should be saved in the sid
        if ($sid <> 0) {
            $parms = $this->get_search($sid, $this->db_table);
            $vars  = $parms["formvars"];
            // add to Activerecord
            $this->db->where($parms["where"], false);
            $this->db->like($parms["like"], false);
        } else {
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
        $links = $this->get_paginator(base_url($url), $count, 4);
        // build query. FALSE in select eliminates backticks for better parsing
        $this->db->select("{$this->db_table}.*,users.nick added_by", false)
                   ->join('users', "users.id = {$this->db_table}.posted_by", 'left', false);

        // both zeroes don't add limit (all records)
        if ($limit <> 0 || $offset <> 0) {
            $query = $this->db->limit($limit, $offset, false);
        }

        // order by. code_number is visual order
        $this->db->order_by("{$this->db_table}.cust_name", 'ASC', false);

        // execute the query and return a two dimensional array with query object
        $query = $this->db->get();

        // debug
        // echo $this->db->last_query();

        return array( "query" => $query, "count" => $count, "links" => $links, "vars" => $vars);
    }


    /**
     * GET record data based on id (nbr)
     */
    public function get_record($id)
    {

        // search
        $sql   = "SELECT {$this->db_table}.*, users.nick added_by
                  FROM {$this->db_table}
                  LEFT JOIN users ON users.id = {$this->db_table}.posted_by
                  WHERE {$this->db_table}.id = $id";


        $query = $this->db->query($sql);

        // return a single row or false if not found.
        if ($query->num_rows() > 0) {
            return $query->row_array();
        } else {
            return false;
        }
    }

}

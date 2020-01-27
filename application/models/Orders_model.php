<?php  if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * Customer Orders model
 * @author		csaenz
 * 20-1-20
 */

class Orders_model extends CDS_Model
{
    public $db_table;
    public $db_fields;

    public function __construct()
    {
        // COMMON TASKS
        parent::__construct();

        // define fields that can be updated in a form.
        $this->db_table  =  "order_det";
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
        if ($arr["cust_id"] == "") {
            $this->error(_("Debe informar cliente."));
        }
        if ($arr["supp_id"] == "") {
            $this->error(_("Debe informar distribuidor."));
        }
        if ($arr["itm_id"] == "") {
            $this->error(_("Debe informar item."));
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
     * Update a record
     * with an array of $field => $value
     */

    public function update_record($data, $id)
    {

           // filter only what we want from the form $_post, the $db_fields
        $arr = valid_fields($this->db_fields, $data);

        // validation: required fields
        if ($arr["cust_id"] == "") {
            $this->error(_("Debe informar cliente."));
        }
        if ($arr["supp_id"] == "") {
            $this->error(_("Debe informar distribuidor."));
        }
        if ($arr["itm_id"] == "") {
            $this->error(_("Debe informar item."));
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

            // Add each where "line/statement" in a group to provide parentheses
            if ($parms["where"]) {
              $this->db->group_start();
              foreach ($parms["where"] as $wk => $wv) {
                  // third paramenter eliminates protection!
                  $this->db->where($wk . $wv, null, false);
              }
              $this->db->group_end();
            }

            // like function
            $this->db->like($parms["like"], null, false);
        } else {
            $vars = [];
        }

        // fix, extrawhere ALWAYS applies!
        if ($extra_where) {
            // add a fixed where, example for your own records only.
            $this->db->group_start();
            $this->db->where($extra_where,null,false);
            $this->db->group_end();
        }

        // cache to avoid loading where twice.
        $this->db->stop_cache();

        // total records BEFORE limit and AFTER where
        $count = $this->db->count_all_results();

        // pagination links. segment is 4 because of sid (which is 3)
        $links = $this->get_paginator(base_url($url), $count, 4);
        // build query. FALSE in select eliminates backticks for better parsing
        $this->db->select("order_det.*,users.nick added_by, supp_name, cust_name", false)
               ->join('supp_mst', 'supp_mst.id = order_det.supp_id', 'left', false)
               ->join('cust_mst', 'cust_mst.id = order_det.cust_id', 'left', false)
               ->join('users', 'users.id = order_det.posted_by', 'left', false);

        // both zeroes don't add limit (all records)
        if ($limit <> 0 || $offset <> 0) {
            $query = $this->db->limit($limit, $offset, false);
        }

        // order by. code_number is visual order
        $this->db->order_by("order_det.id", 'DESC', false);

        // execute the query and return a two dimensional array with query object
        $query = $this->db->get();

        // debug
        // echo $this->db->last_query();

        return array( "query" => $query, "count" => $count, "links" => $links, "vars" => $vars);
    }

    /**
     * GET record data based on id (nbr)
     */
    public function get_record($id)    {

        // search
        $sql   = "SELECT {$this->db_table}.*, users.nick added_by, supp_name, cust_name
                  FROM {$this->db_table}
                  LEFT JOIN cust_mst ON cust_mst.id = {$this->db_table}.cust_id
                  LEFT JOIN supp_mst ON supp_mst.id = {$this->db_table}.supp_id
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

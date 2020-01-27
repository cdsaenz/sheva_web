<?php  if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * Users/Security model (login etc)
 * version 5.0 enhanced revised
 * Added user maintenance
 * @author		csaenz
 *
 */

define("RULES_PWD_MIN_LENGTH", 4);
define("RULES_NICK_MIN_LENGTH", 5);


class Users_model extends CDS_Model
{
    public function __construct()
    {
        // COMMON TASKS
        parent::__construct();

        // define fields that can be updated in a form.
        $this->db_table  =  "users";
        $this->db_fields =  $this->db->list_fields($this->db_table);
    }

    /**
      * Validate a user record
      *
      */
    public function validate_record($data, $is_edit)
    {
        // validation
        if ($data["first_name"] == "") {
            $this->error(_("Debe informar primer nombre"));
        }
        if ($data["last_name"] == "") {
            $this->error(_("Debe informar apellido"));
        }
        if ($data["email"] == "") {
            $this->error(_("Debe informar email"));
        }
        if ($data["nick"] == "") {
            $this->error(_("Debe informar alias/apodo"));
        }

        // validations on edit record or add
        if ($is_edit) {
            // validation of not repeated nick/email in OTHER records.
            $nick = $data["nick"];
            $email= $data["email"];
            $sql  = "SELECT id from {$this->db_table} WHERE (nick = '$nick' OR email = '$email') AND id <> $id";
            $qry  = $this->db->query($sql);

            if ($qry->num_rows() > 0) {
                $this->error(_("Alias o Email existe para otro usuario."));
            }
        }
        else {
            // validation of not repeated nick/email
            $nick = $data["nick"];
            $email= $data["email"];
            $sql  = "SELECT id from {$this->db_table} WHERE (nick = '$nick' OR email = '$email')";
            $qry  = $this->db->query($sql);
            if ($qry->num_rows() > 0) {
                $this->error(_("Alias o Email ya existen en la base de datos."));
            }
        }

    }


    /**
      * Add a new record
      * with an array of $field => $value
      */

    public function insert_record($data)
    {
        // clean data and keep only valid fields
        $arr = valid_fields($this->db_fields, $data);

        // validate the user record
        $this->validate_record($data, false);

        // current uid
        $uid  = $this->session->userdata("user_id");
        $tim  = date("YmdHi");

        // save registration hash for activation
        $hash              = substr(md5($arr['email'] . uniqid(rand(), true)), 1, 50);
        $arr ['reg_code']  = $hash;

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
        // clean data and keep only valid fields
        $arr = valid_fields($this->db_fields, $data);

        // validate the user record
        $this->validate_record($data, true);

        // current uid
        $uid  = $this->session->userdata("user_id");
        // add/modify extra data in array as necessary
        $arr ['modified_on'] = date("YmdHi");
        $arr ['modified_by'] = $uid;

        // apply the key limit and update
        $this->db->update($this->db_table, $arr, "id = $id");
    }


    /**
     * Update a password only
     * with an array of $field => $value
     */

    public function update_password($data, $id)
    {
        // password validation
        if ($data["pwd"] == "") {
            $this->error(_("Debe informar contrase&ntilde;a"));
        }
        if ($data["pwd_check"] == "") {
            $this->error(_("Debe confirmar contrase&ntilde;a"));
        }
        if ($data["pwd_check"] <> $data["pwd"]) {
            $this->error(_("Contrase&ntilde;as no coinciden"));
        }
        if (strlen($data["pwd"]) < RULES_PWD_MIN_LENGTH) {
            $this->error(_("Contrase&ntilde;a es muy corta"));
        }

        // current uid
        $uid  = $this->session->userdata("user_id");

        // Set as active automatically (controversial!)
        // but if the user is doing it.. it's already enabled before to login
        $arr ['is_active']   = 'Y';
        $arr ['pwd']         = md5($data['pwd']);
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

        // build query
        $this->db->select("{$this->db_table}.*")
                 ->from($this->db_table);

        // both zeroes don't add limit (all records)
        if ($limit <> 0 || $offset <> 0) {
            $query = $this->db->limit($limit, $offset);
        }

        // order by
        $this->db->order_by("last_name, first_name asc");

        // execute the query and return a two dimensional array with query object
        $query = $this->db->get();

        return array( "query" => $query, "count" => $count, "links" => $links, "vars" => $vars);
    }


    /**
     * GET USER ID IF PASSWORD MATCHES
     */
    public function validate_user($nick_mail, $pwd)
    {
        // encrypt
        $pwd   = md5($pwd);

        // search
        $sql   = "SELECT {$this->db_table}.* FROM {$this->db_table}
                    WHERE (nick = '$nick_mail' or email = '$nick_mail') AND pwd = '$pwd'";

        $query = $this->db->query($sql);

        // return a single row or false if not found.
        if ($query->num_rows() > 0) {
            // FIXED: Wasn't checking if this was an activ(at)e(d) user
            if ($query->row("is_active") != 'Y') {
                $this->error(_("Usuario no activo, utilizar mail de registracion o contactar Admin."));
            }
            return $query->row();
        } else {
            return false;
        }
    }

    /**
     * GET USER data based on id (nbr)
     */
    public function get_record($id)
    {

        // search
        $sql   = "SELECT {$this->db_table}.*, supp_name, cust_name
                    FROM {$this->db_table}
                  LEFT JOIN supp_mst ON supp_mst.id = {$this->db_table}.user_company_id AND {$this->db_table}.user_company_type = 'SUPP'
                  LEFT JOIN cust_mst ON cust_mst.id = {$this->db_table}.user_company_id AND {$this->db_table}.user_company_type = 'CUST'
                  WHERE {$this->db_table}.id = $id";

        $query = $this->db->query($sql);

        // return a single row or false if not found.
        if ($query->num_rows() > 0) {
            return $query->row_array();
        } else {
            return false;
        }
    }


    /**
     *  Grab user's groups etc and save to session
     */
    public function save_user_info($uid)    {

        // care with this! lower all!
        $row = $this->get_record($uid);

        // found or not
        if ($row) {
            // Password validated already
            // use session! else they go away!!
            $this->session->set_userdata('logged_in', 'TRUE');
            $this->session->set_userdata('user_id', $uid);
            $this->session->set_userdata('user_nick', $row['nick']);
            $this->session->set_userdata('user_first', $row['first_name']);
            $this->session->set_userdata('user_last', $row['last_name']);
            // multi company single db
            $this->session->set_userdata('user_company_id', $row['user_company_id']);

            // company name
            if ($row['user_company_type'] == 'CUST')
                $this->session->set_userdata('user_company_name', $row['cust_name']);
            if ($row['user_company_type'] == 'SUPP')
                $this->session->set_userdata('user_company_name', $row['supp_name']);
            if ($row['user_company_type'] == 'ADMIN')
                $this->session->set_userdata('user_company_name', 'ADMIN');

            // security tokens
            $this->session->set_userdata('is_admin', $row['is_admin']);
            $this->session->set_userdata('is_supp', $row['is_supp']);
            $this->session->set_userdata('is_genera', $row['is_genera']);
        }
    }



    /**
     * validate profile
     */
    public function validate_profile($row)
    {
        // pick up data
        $id    = $row['rowid'];
        $unk   = $row['nick'];
        $mail  = $row['email'];

        // pre-validation
        if (strlen($unk) < RULES_NICK_MIN_LENGTH) {
            $this->error(_("El alias es muy corto."));
        }

        if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
            $this->error(_("Email ingresado es invalido. Reintente."));
        }

        // validate in db. search email and nick not used in other record.
        $sql   = "SELECT {$this->db_table}.* FROM {$this->db_table}
                  WHERE (nick = '$unk' OR email = '$mail') AND id <> $id";

        $query = $this->db->query($sql);

        // return a single row or false if not found.
        if ($query->num_rows() > 0) {
            $this->error(_("El alias o email ya existen en la base de datos. Reintente"));
        }
    }

    /**
     *  save profile
     */

    public function save_profile($data)
    {
        // clean data and keep only valid fields
        $arr = valid_fields($this->db_fields, $data);

        // extra fields
        $id = $data ['rowid'];

        // update record.
        $this->db->update($this->db_table, $arr, "id = $id");
    }
}

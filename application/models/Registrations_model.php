<?php  if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * Customers model
 * @author		csaenz
 * 22-08-2018
 */

class Registrations_model extends CDS_Model
{
    public $db_table;
    public $db_fields;

    public function __construct()
    {
        // COMMON TASKS
        parent::__construct();

        // define fields that can be updated in a form.
        $this->db_table  =  "reg_mst";
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
     * Process a registration
     * with an array of $field => $value
     */


    public function process_registration($data, $id)
    {
        // modelos necesarios
        $this->load->model("customers_model");

        // pasar array DE POST a variables
        extract($data);

        // variable resultados
        $result = [];

        // no fue aprobado
        if ($reg_status == 'NUEVO') {
            // solo actualizar
            $this->update_record($data,$id);

            // informar y permitir volver
            $url = "registrations/view/$id";
            $this->feedback("Actualizado, status '$reg_status'. No se procesara registracion no aprobada.",$url);
        }

        // datos registracion
        $reg       = $this->get_record($id);
        if (!$reg) {
            $this->error(_("Registracion no hallada."));
        }
        // get some data
        $supp_token= $reg["supp_token"];
        // TBD: stored in plain text
        $password  = $reg["usr_pwd"];
        // tipo de registracion
        $reg_type  = $reg["reg_type"];

        /**
         *  REGISTRACION DE NUEVO CLIENTE/USUARIO
         */

        if ($reg_type == "NC") {
            // primeras validaciones
            // si el userid existe. no puede seguir! un email solo puede registrar 1 solo cliente.
            $sql = "SELECT id FROM users WHERE users.email = '$usr_email_addr'";
            $qry = $this->db->query($sql);
            if ($qry->num_rows() > 0) {
                $this->error("Email $usr_email_addr ya existe. No puede continuar.");
            }

            // customer data. EL CLIENTE NO DEBE EXISTIR!! USER 1 A 1 CON CLIENTE
            $sql = "SELECT id FROM cust_mst WHERE cust_mst.tax_id = '$tax_id'";
            $qry = $this->db->query($sql);
            if ($qry->num_rows() == 0) {
                  // data for the customer
                  $cust['cust_name']        = $cust_name;
                  $cust['cust_is_disabled'] = 'N';
                  $cust['cust_type']        = 'General';
                  // default email de la empresa es la del primer usuario
                  $cust['email_addr']       = $usr_email_addr;
                  // CUIT
                  $cust['tax_id']           = $tax_id;
                  $cust['notes']            = "REGISTRACION #$id";

                  // crear cliente
                  $cust_id  = $this->customers_model->insert_record($cust);
                  // asociar cliente, distribuidor, lista de precios
                  $assoc_id = $this->customers_model->cust_to_supp($cust_id,$supp_id,$price_list_id);

                  // LOG
                  $result[] = "Creado cliente " .anchor("mycustomers/view/$cust_id",$cust_name). " como #$cust_id";
                  $result[] = "Creada asociacion con lista $price_list_id como #$assoc_id";
            }
            else {
                 // abortar
                 $this->error("Cliente con CUIT $tax_id ya existe. No puede continuar.");
            }

            // el usuario se crea aca, porque necesito id de cliente!
            $sql = "SELECT id FROM users WHERE users.email = '$usr_email_addr'";
            $qry = $this->db->query($sql);
            if ($qry->num_rows() > 0) {
                // esto no deberia ocurrir pero..
            }
            else {
                // crear usuario owner del cliente
                $user["user_company_id"]   = $cust_id;
                // registraciones siempre son de clientes.. TBD, TODO ANALYZE
                $user["user_company_type"] = 'CUST';
                // activo por ahora. probablemente no debiera..
                $user["is_active"]         = 'Y';
                // nunca funciones de admin o distribuidor
                $user["is_admin"]          = 'N';
                $user["is_supp"]           = 'N';
                // siempre puede generar ordenes
                $user["is_genera"]         = 'Y';
                // ver efectos
                $user["nick"]              = '';
                $user["email"]             = $usr_email_addr;
                // TBD TODO revisar si es la mejor estrategia
                // password deberia verificarse *previamente* (validacion de complejidad)..
                $user["pwd"]               = md5($password);

                // crear. no usar modelos por validaciones..
                $this->db->insert("users",$user);
                $user_id = $this->db->insert_id();

                // LOG
                $result[] = "Creado usuario " . $usr_email_addr . " como #$user_id";
            }

            // actualizar registro
            $reg['reg_status'] = 'APROBADO';
            $this->update_record($reg,$id);

            // informar y permitir volver
            $url = "registrations/view/$id";
            $this->feedback($result,$url,"Volver","Proceso finalizado");
        }
        elseif ($reg_type == 'AD') {
            /**
             *  AGREGAR DISTRO A CLIENTE
             */

             // user data
             $sql = "SELECT id,user_company_id FROM users WHERE users.email = '$usr_email_addr' AND users.user_company_type = 'CUST' ";
             $qry = $this->db->query($sql);
             if ($qry->num_rows() == 0) {
                 $this->error("Usuario con Email $usr_email_addr no existe o invalido. No puede continuar.");
             }
             $user = $qry->row();

             // customer data. EL CLIENTE NO DEBE EXISTIR!! USER 1 A 1 CON CLIENTE
             $sql = "SELECT id FROM supp_mst WHERE supp_mst.supp_token = '$supp_token'";
             $qry = $this->db->query($sql);
             if ($qry->num_rows() == 0) {
                   // error distribuidor nuevo no hallado
                   $this->error("Distribuidor con TOKEN $supp_token NO existe. No puede continuar.");
             }
             $supp = $qry->row();

             // intentar asociacion, puede faltar:
             $assoc_id = $this->customers_model->cust_to_supp($user->user_company_id,$supp->id,$price_list_id);

             // LOG
             $result[] = "Creada asociacion para {$supp->id} con {$user->user_company_id} con lista $price_list_id como #$assoc_id";

             // actualizar registro
             $reg['reg_status'] = 'APROBADO';
             $this->update_record($reg,$id);

             // informar y permitir volver
             $url = "registrations/view/$id";
             $this->feedback($result,$url,"Volver","Proceso finalizado");
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

        // cust_mst, dar de alta cliente si fuera necesario

        // relacionar cliente-distribuidor (cust_supp)

        // crear usuario! (users)


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
        $this->db->order_by("{$this->db_table}.id", 'DESC', false);

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
        $sql   = "SELECT {$this->db_table}.*, users.nick added_by, supp_name
                  FROM {$this->db_table}
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

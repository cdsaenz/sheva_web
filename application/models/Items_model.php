<?php  if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * Items model
 * @author		csaenz
 * 22-08-2018
 */

class Items_model extends CDS_Model
{
    public function __construct()
    {
        // COMMON TASKS
        parent::__construct();

        // define fields that can be updated in a form.
        $this->db_table  =  "itm_mst";
        $this->db_fields =  $this->db->list_fields($this->db_table);
    }

    /**
      * Import a csv file with items
      *
      */
    public function convert_encoding($str)
    {
        return iconv("ISO-8859-15", "UTF-8", $str);
    }

    public function import_csv($file_name,$company_id)
    {
        $fields = array("itm_cod","itm_barcode","itm_type","itm_name",
                        "itm_price1","itm_price2","itm_price3","itm_price4",
                        "itm_package");

        if (!file_exists($file_name)) {
            $this->error(_("Archivo no hallado:") . $file_name);
        }

        // inform
        $basefile = basename($file_name);
        echo "<h3>Procesando archivo $basefile..</h3>";

        // stamps
        $uid  = $this->session->userdata("user_id");
        $tim  = date("YmdHi");

        // abrir y PROCESAR
        $handle    = fopen($file_name, "r");
        $rows      = 0;
        $added     = 0;
        $modified  = 0;
        $got_errors= false;
        while (($row = fgetcsv($handle, 10000, ";")) != false) { //get row vales
            $rows++;
            // skip header
            if ($rows == 1) {
                echo "Salteando encabezado.. <br />";
                continue;
            }

            // process column by column
            $data = [];
            echo "<h4>Procesando fila $rows.. </h4>";
            foreach ($row as $key => $value) {
                $value = $this->convert_encoding($value);

                // levantar campo de lista por posicion
                if (isset($fields[$key])) {
                    $field = $fields[$key];
                } else {
                    $this->error("Error de layout de campos!: $key");
                }

                // take off the $ from the price
                if (in_array($field, ["itm_price1","itm_price2","itm_price3","itm_price4"])) {
                    $value = trim(str_replace("$", "", $value));
                }

                // validar item type
                if ($field == 'itm_type') {
                   $sql     = "SELECT id FROM itm_types
                                WHERE id = $value AND type_is_disabled = 'N' AND type_supp_id = $company_id";

                   $qry = $this->db->query($sql);
                   if ($qry->num_rows() == 0) {
                        echo("ERROR: $value no es un tipo de item valido para el Distribuidor.");
                        $got_errors = true;
                        break;
                   }
                }

                // inform
                echo "$field ==> $value";
                echo "<br />";

                // add to array
                $data[$field] = $value;
            }

            if (!$got_errors) {
              // add fixed supplier id
              $data["company_id"] = $company_id;

              // check duplicity
              $code = $data["itm_cod"];
              $supp = $data["company_id"];

              // buscar por CODIGO *Y* DISTRIBUIDOR
              $sql  = "SELECT id from itm_mst
                       WHERE (itm_cod = '$code') AND (company_id = $supp)";

              $qry  = $this->db->query($sql);
              if ($qry->num_rows() > 0) {
                  $id = $qry->row("id");
                  echo(_("<br /><i>Codigo ($code) ya existe para el distribuidor. Modificando ID# $id </i><br />"));

                  // update now
                  $data ['modified_on'] = $tim;
                  $data ['modified_by'] = $uid;
                  $this->db->update($this->db_table, $data, "id = $id");

                  // modified count
                  echo "<span class='font-weight-bold text-warning'>Modificado: ID# $id</span><br />";
                  $modified++;
              } else {
                  // check duplicity for barcode
                  $code = $data["itm_barcode"];
                  $sql  = "SELECT id from itm_mst
                            WHERE (itm_barcode = '$code') AND (company_id = $supp)";

                  $qry  = $this->db->query($sql);
                  if ($qry->num_rows() > 0) {
                      $id = $qry->row("id");
                      echo "<span class='font-weight-bold text-danger'>ERROR - Barcode ya existe para el distribuidor.. ID# $id<span><br />";
                  } else {
                      // process start uid/time
                      $data ['posted_on'] = $tim;
                      $data ['posted_by'] = $uid;

                      // insert data
                      $this->db->insert($this->db_table, $data);
                      $id = $this->db->insert_id();
                      echo "<span class='font-weight-bold text-success'>Agregado item: ID# $id</span><br />";

                      // added count
                      $added++;
                  }
              }
            }
            else {
               // hay errores
               echo "<span class='font-weight-bold text-danger'>SE PRODUJERON ERRORES. NO SE PUEDE CONTINUAR.</span><br />";
               exit;
            }

            echo "<br />";
        }

        echo "<b>PROCESO TERMINADO</b><br />";
        echo "<b>PROCESADOS : $rows</b><br />";
        echo "<b>AGREGADOS  : $added</b><br />";
        echo "<b>MODIFICADOS: $modified</b><br /><br />";

        echo "<a onClick='history.go(-1)' class='btn btn-primary'>Volver</a>";
    }


    /**
      * Add a new record
      * with an array of $field => $value
      */

    public function insert_record($data)
    {

        // clean data and keep only valid fields
        $arr = valid_fields($this->db_fields, $data);

        // more check
        if ($arr["itm_cod"] == "") {
            $this->error(_("Ingrese un codigo para el item"));
        }
        if ($arr["itm_name"] == "") {
            $this->error(_("Ingrese un nombre para el item"));
        }

        // validation of not repeated code
        $code = $arr["itm_cod"];
        $supp = $arr["company_id"];

        $sql  = "SELECT id from itm_mst WHERE (itm_cod = '$code') AND (company_id = $supp)";
        $qry  = $this->db->query($sql);
        if ($qry->num_rows() > 0) {
            $this->error(_("Codigo de Item ($code) ya existe para distribuidor."));
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

        // more check
        if ($arr["itm_cod"] == "") {
            $this->error(_("Debe ingresar un codigo para el item"));
        }
        if ($arr["itm_name"] == "") {
            $this->error(_("Debe ingresar un nombre para el item"));
        }

        // validation of not repeated code
        $code = $arr["itm_cod"];
        $supp = $arr["company_id"];

        $sql  = "SELECT id from itm_mst WHERE (itm_cod = '$code') AND (company_id = $supp) AND (id <> $id)";
        $qry  = $this->db->query($sql);
        if ($qry->num_rows() > 0) {
            $this->error(_("Codigo de Item ($code) ya existe para otro registro del distribuidor."));
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
     * Update multiple items
     * price change
     */

    public function price_change($data)
    {

        // grab parameters/limits to select
        $itm_supp_id  = $data["itm_supp_id"];
        $itm_type     = $data["itm_type"];

        // what field to update and how
        $lista_id     = $data["lista_id"];
        $pct_chg      = $data["pct_chg"];
        $field_up     = "itm_price$lista_id";

        // take off the $ from the price
        if (!in_array($field_up, ["itm_price1","itm_price2","itm_price3","itm_price4"])) {
            $this->error("Campo de precio $field_up desconocido");
        }

        // build query
        $sql  = "SELECT itm_mst.* from itm_mst
                    WHERE itm_mst.company_id = $itm_supp_id";

        // un item type o todos
        if ($itm_type) {
            $sql .= " AND itm_type = $itm_type";
        }

        // run
        $qry  = $this->db->query($sql);
        if ($qry->num_rows() == 0) {
            $this->error(_("No se hallaron items coincidentes "));
        }

        // current uid/time
        $uid  = $this->session->userdata("user_id");
        $tim  = date("YmdHi");

        // update each record in the lot
        $tot_chgd = 0;
        foreach ($qry->result_array() as $row) {
            // pick up the id
            $id = $row["id"];

            // price update, if pct_chg is negative it will decreases
            $base_price          =  $row[$field_up];
            $amt_chg             =  ($base_price * $pct_chg / 100);
            $new_price           =  $base_price + $amt_chg;

            // this is because the locale dictates , as decimals separator
            $arr [$field_up]     =  str_replace(',', '.', $new_price);

            // add/modify extra data in array as necessary
            $arr ['modified_on'] = date("YmdHi");
            $arr ['modified_by'] = $uid;

            // apply the key limit and update
            $this->db->update($this->db_table, $arr, "id = $id");

            // increment counter
            $tot_chgd++;
        }

        return $tot_chgd;
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
        $this->db->select("itm_mst.*,users.nick added_by, itm_types.type_name, mm.media_src AS main_pic, supp_name", false)
               ->join('itm_types', 'itm_types.id = itm_mst.itm_type', 'left', false)
               ->join('supp_mst', 'supp_mst.id = itm_mst.company_id', 'left', false)
               ->join('media_mst mm', "mm.rel_type = 'items' AND mm.rel_id = itm_mst.id AND mm.is_main = 'Y' AND mm.media_type = 'PICTURE' AND mm.src_type = 'FILE'", 'left', false)
               ->join('users', 'users.id = itm_mst.posted_by', 'left', false);

        // both zeroes don't add limit (all records)
        if ($limit <> 0 || $offset <> 0) {
            $query = $this->db->limit($limit, $offset, false);
        }

        // order by. code_number is visual order
        $this->db->order_by("itm_mst.id", 'DESC', false);

        // execute the query and return a two dimensional array with query object
        $query = $this->db->get();

        // debug
        // echo $this->db->last_query();

        return array( "query" => $query, "count" => $count, "links" => $links, "vars" => $vars);
    }


    /**
     * GET record data based on id (nbr)
     *  option to return empty array on false (for new record)
     */
    public function get_record($id, $empty_array = false)
    {
        // search
        $sql   = "SELECT itm_mst.*, users.nick added_by, type_name, mm.media_src AS main_pic, supp_name
                  FROM itm_mst
                  LEFT JOIN itm_types ON itm_types.id = itm_mst.itm_type
                  LEFT JOIN supp_mst ON supp_mst.id = itm_mst.company_id
                  LEFT JOIN media_mst mm ON mm.rel_type = 'items'
                       AND mm.rel_id = itm_mst.id AND mm.is_main = 'Y' AND mm.media_type = 'PICTURE'
                       AND mm.src_type = 'FILE'
                  LEFT JOIN users ON users.id = itm_mst.posted_by
                  WHERE itm_mst.id = $id";


        $query = $this->db->query($sql);

        // return a single row or false if not found.
        if ($query->num_rows() > 0) {
            // return a single row array
            return $query->row_array();
        } else {
            // return empty array or false.
            return $empty_array ? empty_result_array($query) : false;
        }
    }

}

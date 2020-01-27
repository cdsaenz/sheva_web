<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Application model
 * specific for each app
 * @author		csaenz
 */

class App_Model extends CDS_Model {

    // config items now go in app/config/cds_config.php
    public function __construct() {
        // COMMON TASKS tbd
        parent::__construct();
    }

    /**
     * Get users tipo "is_admin"
     */

    function get_users_admin() {

        // ensure no company is set up for the user
        $sql     = "SELECT id,nick,email FROM usr_mst WHERE is_active='Y' and is_admin='Y'" ;
        $qry     = $this->db->query($sql);
        return $qry->result_array();
    }

    /**
     * Shorthand to allow only admins in certain functions
     */
    function AllowOnlyAdmin()
    {
        $this->CI->check_login();

        if (!$this->is_admin()) {
            $this->error("Ud no es administrador");
        }
    }

    /**
     * Shorthand to allow only suppliers (distribuidor) and above
     */
    function AllowSupplier()
    {
        $this->CI->check_login();

        // if it's admin ok
        if (!$this->is_admin()) {
          if (!$this->is_supp()) {
              $this->error("Ud no es distribuidor ni admin.");
          }
        }
    }

    /**
     * Shorthand to allow only suppliers NOT ADMIN (unless both)
     */
    function AllowOnlySupplier()
    {
        $this->CI->check_login();

        // if it's admin ok
        if (!$this->is_supp()) {
            $this->error("Ud no es distribuidor.");
        }
    }

    function is_supp()
    {
       return $this->can_user('is_supp');
    }

    function is_admin()
    {
       return $this->can_user('is_admin');
    }


    /**
     *  translate controller. fix for myitems/mycustomers for suppliers
     */

    function get_media_controller($rel_type) {

       // by default items, customers etc
       $controller = $rel_type;

       if (!$this->is_admin()) {
          if (in_array($controller,['items','customers'])  ) {
              $controller = "my$controller";
          }
       }
       return $controller;
    }


    /**
     * Get item types, show name, value is id
     */

    function get_item_types($addBlank = FALSE, $supp_id = false) {

        // limitar a un solo distribuidor
        if ($supp_id) {
            $sql     = "SELECT id, type_name FROM itm_types
                        WHERE type_is_disabled = 'N' AND type_supp_id = $supp_id
                        ORDER BY type_name";
        }
        else {
            // o todos (admin)
            $sql     = "SELECT id, type_name FROM itm_types
                          WHERE type_is_disabled = 'N'
                          ORDER BY type_name";

        }

        $qry     = $this->db->query($sql);
        $avals   = $qry->result_array();

        // convert to associative array
        $arr = array();
        foreach ($avals as $row) {
            // id -> description
            $v       = $row['id'];
            $n       = $row['type_name'];
            $arr[$v] = $n;
        }

        // add "ALL" option (for filters, for example)
        // NOTE: EMPTY CAPTION as it may work as "ALL" or .. just empty
        if ($addBlank) $arr = array("" => "") + $arr;
        return $arr;
    }


    /**
     *  Fill up a combo box with items
     *  id => code
     */
    function get_items($addBlank = FALSE, $use_name = false) {

        $sql     = "SELECT id,itm_cod,itm_name FROM itm_mst WHERE itm_is_disabled = 'N' ORDER BY itm_cod";
        $qry     = $this->db->query($sql);
        $rows    = $qry->result_array();

        if ($use_name)
            $show_field = 'itm_name';
        else
            $show_field = 'itm_cod';

        // convert to associative array (key = value)
        $arr = array();
        foreach ($rows as $row) {
            $arr[$row['id']] = $row[$show_field];
        }

        // add "ALL" option (for filters, for example)
        // NOTE: EMPTY CAPTION as it may work as "ALL" or .. just empty
        if ($addBlank) $arr = array("" => "") + $arr;
        return $arr;
    }


    /**
     *  Fill up a combo box with suppliers
     *  id => code
     */
    function get_suppliers($addBlank = FALSE) {

        $sql     = "SELECT id,supp_cod,supp_name
                    FROM supp_mst
                    WHERE supp_is_disabled = 'N'
                    ORDER BY supp_name";

        $qry     = $this->db->query($sql);
        $rows    = $qry->result_array();

        // convert to associative array (key = value)
        $arr = array();
        foreach ($rows as $row) {
            $arr[$row['id']] = $row["supp_name"];
        }

        // add "ALL" option (for filters, for example)
        // NOTE: EMPTY CAPTION as it may work as "ALL" or .. just empty
        if ($addBlank) $arr = array("" => "") + $arr;
        return $arr;
    }

    /**
     *  Fill up a combo box with customers that belong to a supplier
     *  id => code
     */
    function get_supplier_customers($supp_id, $addBlank = FALSE) {

        $sql     = "SELECT cust_id,cust_mst.cust_name
                      FROM cust_supp
                    LEFT JOIN cust_mst ON cust_mst.id = cust_supp.cust_id
                      WHERE cust_mst.cust_is_disabled = 'N'
                        AND cust_supp.supp_id = $supp_id
                      ORDER BY cust_mst.cust_name";

        $qry     = $this->db->query($sql);
        $rows    = $qry->result_array();

        // convert to associative array (key = value)
        $arr = array();
        foreach ($rows as $row) {
            $arr[$row['cust_id']] = $row["cust_name"];
        }

        // add "ALL" option (for filters, for example)
        // NOTE: EMPTY CAPTION as it may work as "ALL" or .. just empty
        if ($addBlank) $arr = array("" => "") + $arr;
        return $arr;
    }


    /**
     *  json suppliers
     *  id => code
     */
    function json_get_suppliers($current_id,$except_id = 0,$term = "") {
        // wanna exclude some company?
        $where   = "";
        $sql     = "SELECT id, supp_name from supp_mst ";

        // the COLLATE clause makes the search case insensitive
        if ($term)
            $where = " WHERE supp_name LIKE '%$term%' COLLATE utf8_general_ci";

        // add the exception if indicated
        if ($where)
            $where = $where . " AND ";
        else {
            $where = " WHERE ";
        }
        $where = $where . " id <> $except_id";

        $sql     = $sql . $where;
        $qry     = $this->db->query($sql);
        $soc     = $qry->result_array();

        // convert to id, name
        $arr = array();
        foreach ($soc as $row) {
            $arr[] = array("value" => $row["id"],
                           "label" => $row["supp_name"]);
        }

        return json_encode($arr);
    }


    /**
     * find if there is a relationship between supplier & customer
     * cust_supp
     */
    public function has_supplier($cust_id, $supp_id)
    {
        // search
        $sql   = "SELECT id
                  FROM cust_supp
                  WHERE cust_id = $cust_id AND supp_id = $supp_id";

        $query = $this->db->query($sql);

        // return a single row or false if not found.
        if ($query->num_rows() > 0) {
            return $query->row_array();
        } else {
            return false;
        }
    }

    /**
     * find if a part belongs to a supplier
     */
    public function has_item($supp_id, $item_id)
    {
        // search
        $sql   = "SELECT id
                  FROM itm_mst
                  WHERE id = $item_id AND company_id = $supp_id AND itm_is_disabled = 'N' ";

        $query = $this->db->query($sql);

        // return a single row or false if not found.
        if ($query->num_rows() > 0) {
            return $query->row_array();
        } else {
            return false;
        }
    }



 }

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 *  External REST api JSON response/request
 *  Version 2.0 17may2019, cds - 29/12/19
 *  single database, enable functions for it.
 */

 // constants, change here
DEFINE('TOKEN','GW&H$H&W)F^Q0q#F^#$)G#)G^a^)#ASF^)#)^G#G&g$L%YUL$WTRAW@D#%A)%^f)#$G^S%#L');

// WAY to get the token
DEFINE('TOKEN_IN_HEADER',true);


/* ATTENTION NOT INHERITING FROM CORE CDS_CONTROLLER, LIGHTER */
class Extapi extends CI_Controller {

 	 /**
	  * API JS
    */
    public function __construct() {

        // call the parent custom constructor as it does setup stuff
        parent::__construct();
    }

    /**
     *  DB Error processor
     */

    function _db_error($parms = []) {

      $err = $this->db->error();
      $msg = "General";

      // capturar error info
      if (!empty($err))
           $msg = '[' . $err['code'] . '] - ' . $err['message'];

      // return to main loop
      $ret = $this->_json_response("DB Error $msg","ERROR",array("errors" =>"ERR_DB","params" => $parms),200);
      return $ret;
    }

    /***
     * save to log for debug
     */
    function _logtofile ($rawin,$headers) {

        ob_flush();
        ob_start();
        // dump to file
        var_dump($rawin,$headers);
        file_put_contents("/tmp/dump.txt", ob_get_flush());
        exit;
    }

    /**
     * Generic json response
     */
    function _json_response($message = null, $result, $data = null, $code = 200)
    {
        // clear the old headers
        header_remove();
        // set the actual code
        http_response_code($code);
        // set the header to make sure cache is forced
        header("Cache-Control: no-transform,public,max-age=300,s-maxage=900");
        // allow access
        header('Access-Control-Allow-Origin: *');
        // treat this as json
        header('Content-Type: application/json');
        $status = array(
            200 => '200 OK',
            400 => '400 Bad Request',
            422 => '422 Unprocessable Entity',
            500 => '500 Internal Server Error'
            );
        // ok, validation error, or failure
        header('Status: '.$status[$code]);
        // return the encoded json
        return json_encode(array(
            'status' => $code < 300, // success or not?
            'result' => $result,     // in words: OK or ERROR
            'json'   => $data,
            'message' => $message
            ),JSON_PRETTY_PRINT);
    }


    /***
     * abstract: get token from post header or data
     */

    function get_token($rawin) {

        if (TOKEN_IN_HEADER)
            $token   =  $this->input->get_request_header('File-Token', TRUE);
        else
            $token   =  $rawin['token'];

        return $token;
    }




    /**
     * Json - Validate user login
     * PARAMETROS: Email, Clave
     */
    public function validate_user() {

      // clean the paramters
      $rawin  = json_decode($this->input->raw_input_stream,true);
      // token
      $token   =  $this->get_token($rawin);

      // errors array
      $errors = [];

      // get the array, rest make up
      $parms  = isset($rawin) ? $rawin : null;

      // now get each parameter. No UID. UNAME must be informed in this case
      // (email)
      $uname  = isset($parms["uname"]) ? $parms["uname"] : null ;
      $pwd    = isset($parms["pwd"]) ? $parms["pwd"] : null ;

      // Validate token etc
      if (!$token || !$uname || !$pwd || !$parms) {
          $ret = $this->_json_response("Error de parametros.","ERROR",array("errors" =>"ERR_INVALID_PARAMS","params" => $rawin),200);
      }
      else if ($token != TOKEN) {
          $ret = $this->_json_response("Error de autorizacion.","ERROR",array("errors" =>"ERR_INVALID_TOKEN","params" => $rawin),200);
      }
      else {
          //-----------------------
          // allright: encrypt
          //-----------------------
          $pwd   = md5($pwd);
          // search user with matching password and active
          $this->db->db_debug = false;
          $sql   = "SELECT id,nick,
                           user_company_id,user_company_type,
                           is_admin,is_genera,is_supp
                      FROM users
                    WHERE (email = '$uname') AND pwd = '$pwd' AND is_active ='Y'";

          $query = @$this->db->query($sql);
          if (!$query) {
              $ret = $this->_db_error();
          }
          else {
              // load media model for images
              $this->load->model("media_model");

              // found! now search for distribuidores asociados
              if ($query->num_rows() > 0) {

                  // add to result
                  $user          = $query->row_array();
                  $suppliers     = [];

                  // get related suppliers
                  if ($user['user_company_type'] == 'CUST') {
                    $sql   = "SELECT supp_id, price_list_id, supp_name
                                FROM cust_supp
                                LEFT JOIN supp_mst ON supp_mst.id = cust_supp.supp_id
                              WHERE (cust_id = {$user['user_company_id']})";

                    // get all data into the array
                    if ($query = $this->db->query($sql))
                        $suppliers = $query->result_array();

                    // get log for each supplier
                    foreach ($suppliers as &$supplier) {
                        $logo = $this->media_model->get_main_media("suppliers",$supplier["supp_id"],"LOGO");
                        $supplier["logo"] = $logo;
                    }
                  }

                  // build result
                  $result = array("user"=> $user, "suppliers" => $suppliers);

                  // return data
                  $ret    = $this->_json_response("Usuario validado correctamente.","OK",$result,200);
              }
              else
                  $ret = $this->_json_response("Usuario o password invalidos o usuario inactivo.","ERROR",array("errors" =>"ERR_INVALID_LOGIN","params" => $rawin),200);
            }
       }

       // output the json code
       echo $ret;
   }

   /**
    * Json - Accept a user request
    *        for registration
    */
   public function register_user() {

     // clean the paramters
     $rawin  = json_decode($this->input->raw_input_stream,true);

     // token
     $token   =  $this->get_token($rawin);

     // errors array
     $errors = [];

     // get the array, rest make up
     $parms  = isset($rawin) ? $rawin : null;

     // now get each parameter.
     $cuit       = isset($parms["cuit"]) ? $parms["cuit"] : null ;
     // company name, only informative
     $company    = isset($parms["company"]) ? $parms["company"] : null ;
     $email      = isset($parms["email"]) ? $parms["email"] : null ;
     // tbd
     $pwd        = isset($parms["password"]) ? $parms["password"] : null ;
     $supp_token = isset($parms["supp_token"]) ? $parms["supp_token"] : null ;

     // Validate token etc
     if (!$token || !$cuit || !$company || !$email  || !$pwd || !$supp_token || !$parms) {
         $ret = $this->_json_response("Error de parametros.","ERROR",array("errors" =>"ERR_INVALID_PARAMS","params" => $rawin),200);
     }
     else if ($token != TOKEN) {
         $ret = $this->_json_response("Error de autorizacion.","ERROR",array("errors" =>"ERR_INVALID_TOKEN","params" => $rawin),200);
     }
     else {
         $result = [];
         // 1- look for distribuidor/supplier via token
         $this->db->db_debug = false;
         $sql   = "SELECT id
                     FROM supp_mst
                   WHERE (supp_token = '$supp_token') AND supp_is_disabled ='N'";

         $query = @$this->db->query($sql);
         if (!$query) {
             $ret = $this->_db_error();
         }
         else {
            if ($query->num_rows() == 0) {
                $ret = $this->_json_response("Distribuidor no hallado.","ERROR",array("errors" =>"ERR_INVALID_SUPP_TOKEN","params" => $rawin),200);
            }
            else {
                // supplier ok via token
                $result ["supp_id"]  = $query->row("id");

                // regla 1 usuario x c/1 cliente
                // guardar datos del user en registraciones pendientes (reg_mst)
                // email, password (if) - VERIFICAR QUE NO EXISTA EMAIL!
                $sql   = "SELECT id FROM users
                          WHERE (users.email = '$email')";

                $query = @$this->db->query($sql);
                if (!$query) {
                    $ret = $this->_db_error();
                }
                else {
                  if ($query->num_rows() > 0) {
                      // email usuario ya existe!!
                      $ret = $this->_json_response("Error email ya registrado.","ERROR",array("errors" =>"ERR_USER_EMAIL_EXISTS","params" => $rawin),200);
                  }
                  else {
                      // buscar cliente.. si no existe, crear registro de pendientes (reg_mst)
                      $sql   = "SELECT id FROM cust_mst
                                WHERE (cust_mst.tax_id = '$cuit')";

                      $query = @$this->db->query($sql);
                      if (!$query) {
                          $ret = $this->_db_error();
                      }
                      else {
                          if ($query->num_rows() > 0) {
                              // cliente ya existe!!
                              $ret = $this->_json_response("Error cliente ya existe con CUIT provisto.","ERROR",array("errors" =>"ERR_CUSTOMER_EXISTS","params" => $rawin),200);
                          }
                          else {
                              // nuevo cliente (y user): crear registro de registracion reg_mst
                              $reg["reg_status"]       = 'NUEVO';
                              $reg["reg_type"]         = 'NC';                    // nuevo cliente
                              $reg["supp_id"]          = $result ["supp_id"];
                              $reg["supp_token"]       = $supp_token;
                              $reg["cust_name"]        = $company;
                              $reg["usr_email_addr"]   = $email;
                              $reg["usr_pwd"]          = $pwd;
                              $reg["tax_id"]           = $cuit;

                              // time
                              $tim = date ("YmdHi");
                              // add/modify extra data in array as necessary
                              $reg ['posted_on']       = $tim;
                              $reg ['posted_by']       = 0;

                              // insert data.
                              $this->db->db_debug = false;
                              if (!@$this->db->insert("reg_mst", $reg)) {
                                  $ret = $this->_db_error($parms);
                              }
                              else {
                                  // SUCESS!! ok, pick up the reg_mst id
                                  $id   = $this->db->insert_id();

                                  // return data
                                  $result = array("id" => $id);
                                  $ret    = $this->_json_response("Se inicio el proceso de Registracion correctamente.","OK",$result,200);
                              }
                          }
                      }
                  }
                }

            }
         }
      }

      // output the json code
      echo $ret;
  }


     /**
      * Json - Accept a user request
      *        to add a supplier
      */
     public function connect_supplier() {

       // clean the paramters
       $rawin  = json_decode($this->input->raw_input_stream,true);

       // token
       $token   =  $this->get_token($rawin);

       // errors array
       $errors = [];

       // get the array, rest make up
       $parms  = isset($rawin) ? $rawin : null;

       // now get each parameter.
       $uid        = isset($parms["uid"]) ? $parms["uid"] : null ;
       $supp_token = isset($parms["supp_token"]) ? $parms["supp_token"] : null ;

       // Validate token etc
       if (!$token || !$uid || !$supp_token || !$parms) {
           $ret = $this->_json_response("Error de parametros.","ERROR",array("errors" =>"ERR_INVALID_PARAMS","params" => $rawin),200);
       }
       else if ($token != TOKEN) {
           $ret = $this->_json_response("Error de autorizacion.","ERROR",array("errors" =>"ERR_INVALID_TOKEN","params" => $rawin),200);
       }
       else {
           $result = [];
           // 1- look for distribuidor/supplier via token
           $this->db->db_debug = false;
           $sql   = "SELECT id
                       FROM supp_mst
                     WHERE (supp_token = '$supp_token') AND supp_is_disabled ='N'";

           $query = @$this->db->query($sql);
           if (!$query) {
               $ret = $this->_db_error();
           }
           else {
              if ($query->num_rows() == 0) {
                  $ret = $this->_json_response("Nuevo Distribuidor no hallado.","ERROR",array("errors" =>"ERR_INVALID_SUPP_TOKEN","params" => $rawin),200);
              }
              else {
                  // get supplier info
                  $supp    = $query->row();
                  $supp_id = $supp->id;

                  // buscar usuario, y debe ser tipo CLIENTE (CUST)
                  $sql   = "SELECT id,email,user_company_id,user_company_type FROM users
                            WHERE (users.id = '$uid' AND user_company_type = 'CUST' AND user_company_id <> 0)";

                  $query = @$this->db->query($sql);
                  if (!$query) {
                      $ret = $this->_db_error();
                  }
                  else {
                    if ($query->num_rows() == 0) {
                        // email usuario NO existe!!
                        $ret = $this->_json_response("Error usuario no existe.","ERROR",array("errors" =>"ERR_INVALID_UID","params" => $rawin),200);
                    }
                    else {
                        // id de cliente del usuario
                        $user    = $query->row();
                        $cust_id = $user->user_company_id;

                        // buscar cliente.. debe existir
                        $sql     = "SELECT id, cust_name FROM cust_mst
                                  WHERE (cust_mst.id = '$cust_id')";

                        $query   = @$this->db->query($sql);
                        if (!$query) {
                            $ret = $this->_db_error();
                        }
                        else {
                            if ($query->num_rows() == 0) {
                                // cliente ya existe!!
                                $ret = $this->_json_response("Error cliente #$cust_id no existe.","ERROR",array("errors" =>"ERR_INVALID_CUSTOMER","params" => $rawin),200);
                            }
                            else {
                                // customer data
                                $cust = $query->row();

                                // buscar cliente-supplier.. NO debe existir!!
                                $sql     = "SELECT id FROM cust_supp
                                              WHERE (cust_id = $cust_id AND supp_id = $supp_id)";

                                $query   = @$this->db->query($sql);
                                if (!$query) {
                                    $ret = $this->_db_error();
                                }
                                else {
                                    if ($query->num_rows() > 0) {
                                        // cliente ya existe!!
                                        $ret = $this->_json_response("Error cliente #$cust_id ya asociado a distribuidor #$supp_id.","ERROR",array("errors" =>"ERR_CUSTOMER_LINKED","params" => $rawin),200);
                                    }
                                    else {
                                        // nuevo cliente (y user): crear registro de registracion reg_mst
                                        $reg["reg_status"]       = 'NUEVO';
                                        $reg["reg_type"]         = 'AD';                    // AD agrega distribuidor
                                        $reg["supp_id"]          = $supp->id;
                                        $reg["supp_token"]       = $supp_token;
                                        // informativo solamente
                                        $reg["cust_name"]        = $cust->cust_name;
                                        $reg["usr_email_addr"]   = $user->email;

                                        // time
                                        $tim = date ("YmdHi");
                                        // add/modify extra data in array as necessary
                                        $reg ['posted_on']       = $tim;
                                        $reg ['posted_by']       = 0;

                                        // insert data.
                                        $this->db->db_debug = false;
                                        if (!@$this->db->insert("reg_mst", $reg)) {
                                            $ret = $this->_db_error($parms);
                                        }
                                        else {
                                            // SUCESS!! ok, pick up the reg_mst id
                                            $id   = $this->db->insert_id();

                                            // return data
                                            $result = array("id" => $id);
                                            $ret    = $this->_json_response("Se inicio el proceso de Conexión a Distribuidor correctamente.","OK",$result,200);
                                        }

                                    }
                               }

                            }
                        }
                    }
                  }

              }
           }
        }

        // output the json code
        echo $ret;
    }



   /**
    * Json - get items records
    */
   public function items_get() {

     // clean the paramters
     $rawin  = json_decode($this->input->raw_input_stream,true);
     // token
     $token   =  $this->get_token($rawin);

     // errors array
     $errors = [];

     // get the array, rest make up
     $parms  = isset($rawin) ? $rawin : null;
     // user id making the request. MANDATORY.
     $uid    = isset($parms["uid"]) ? $parms["uid"] : null ;
     // get the company id (cliente)
     $cid    = isset($parms["user_company_id"]) ? $parms["user_company_id"] : null;
     // get the distribuidor id
     $suppid = isset($parms["supp_id"]) ? $parms["supp_id"] : null;
     // get the distribuidor id
     $priceid= isset($parms["price_list_id"]) ? $parms["price_list_id"] : null;
     // cantidad de registros desde el principio, 0 start.
     $offset = isset($parms["offset"]) ? $parms["offset"] : 0;
     // cantidad de registros: 0
     $limit  = isset($parms["limit"]) ? $parms["limit"] : 0;

     // Validate token etc
     if (!$token || !$uid || !$cid || !$suppid || !$priceid || !$parms) {
         $ret = $this->_json_response("Error de parametros.","ERROR",array("errors" =>"ERR_INVALID_PARAMS","params" => $rawin),200);
     }
     else if ($token != TOKEN) {
         $ret = $this->_json_response("Error de autorizacion.","ERROR",array("errors" =>"ERR_INVALID_TOKEN","params" => $rawin),200);
     }
     else {
         //-----------------------
         // get records
         //-----------------------
         $price_field  = intval($priceid);
         if ($price_field > 0 && $price_field < 5) {
             $price_field = "itm_price$price_field";
             $sql  = "SELECT im.id,itm_cod,itm_name,itm_barcode,$price_field as itm_cust_price,
                        um_cod,itm_package,type_name,type_order
                        FROM itm_mst im
                        LEFT JOIN itm_types it ON it.id = im.itm_type
                        WHERE im.company_id = $suppid
                             AND itm_is_disabled = 'N'
                        ORDER BY im.id";

             // apply limit if valid
             if ($limit > 0 && $offset > 0) {
                $sql .= " LIMIT $limit OFFSET $offset ";
             }

             // fetch'em
             $query = $this->db->query($sql);

             // found: correct validation
             if ($query->num_rows() > 0)
                 $ret = $this->_json_response("Registros hallados.","OK",$query->result_array(),200);
             else
                 $ret = $this->_json_response("No se hallaron registros coincidentes","ERROR",array("errors" =>"ERR_NOT_FOUND","params" => $rawin),200);
         }
         else {
             $ret = $this->_json_response("Lista de precios invalida.","ERROR",array("errors" =>"ERR_INVALID_PRICE_LIST","params" => $rawin),200);
         }

     }

     // output the json code
     echo $ret;
  }


    /**
     *  Guarda un registro de ordenes
     */
    public function order_new() {

        // clean the paramters
        $rawin   = json_decode($this->input->raw_input_stream,true);
        // token
        $token   =  $this->get_token($rawin);

        // errors array
        $errors = [];

        // get the array, rest make up
        $parms   = isset($rawin) ? $rawin : null;
        // user id requesting the create
        $uid     = isset($rawin["uid"]) ? $rawin["uid"] : null;
        // campos: proveedor
        $supp_id = isset($rawin["supp_id"]) ? $rawin["supp_id"] : null;
        // ARRAY de items
        $items   = isset($rawin["items"]) ? $rawin["items"] : null;

        // Validate parameters token etc
        if (!$token || !$uid || !$supp_id || !$items ||  !$parms) {
             $ret = $this->_json_response("Error de parametros.","ERROR",array("errors" =>"ERR_INVALID_PARAMS","params" => $rawin),200);
        }
        else if ($token != TOKEN) {
             $ret = $this->_json_response("Error de autorizacion.","ERROR",array("errors" =>"ERR_INVALID_TOKEN","params" => $rawin),200);
        }
        else {
            // load app model (not using core)
            $this->load->model("app_model","app");

            // verificar uid & get customer id
            $this->load->model("users_model");
            $user_id = $uid;
            $user    = $this->users_model->get_record($user_id);
            if (!$user || ($user["user_company_id"] == 0) || ($user["user_company_type"] != 'CUST')) {
                $ret = $this->_json_response("Error usuario no hallado o no es tipo cliente.","ERROR",array("errors" =>"ERR_INVALID_UID","params" => $rawin),200);
            }
            else {
              // now validate customer exists
              $this->load->model("customers_model");
              $cust_id = $user ["user_company_id"];
              $cust    = $this->customers_model->get_record($cust_id);
              if (!$cust) {
                  $ret = $this->_json_response("Error cliente no hallado.","ERROR",array("errors" =>"ERR_INVALID_CUSTOMER","params" => $rawin),200);
              }
              else {
                  // verify cust -> supplier link
                  if (!$this->app->has_supplier($cust_id,$supp_id)) {
                      $ret = $this->_json_response("Error cliente $cust_id no asociado a distribuidor $supp_id.","ERROR",array("errors" =>"ERR_CUSTOMER_UNLINKED","params" => $rawin),200);
                  }
                  else {
                      // primero validar items
                      foreach ($items as $item) {
                          // chequear parte es correcta y pertenece al customer
                          $itm_id    = $item['itm_id'];
                          if (!$this->app->has_item($supp_id,$itm_id)) {
                              $errors[] = "Item #$itm_id no asociado a cliente";
                              break;
                          }
                      }

                      // if any part is wrong, bail out
                      if ($errors)  {
                          $ret = $this->_json_response("Error en proceso de items.","ERROR",array("errors" =>"ERR_ITEMS_PROCESSING","params" => $rawin,"errors" => $errors),200);
                      }
                      else {
                          // time
                          $tim = date("YmdHi");
                          // prepare order_mst
                          $data ['order_date'] = date("Y-m-d");
                          $data ['cust_id']    = $cust_id;
                          $data ['supp_id']    = $supp_id;
                          // add/modify extra data in array as necessary
                          $data ['posted_on']  = $tim;
                          $data ['posted_by']  = $uid;

                          // insert data. GET REQUEST ID. Errors are possible here.
                          $this->db->db_debug  = false;
                          if (!@$this->db->insert("order_mst", $data)) {
                              $ret = $this->_db_error();
                          }
                          else {
                              // ok, pick up the id from THE MASTER
                              $order_id  = $this->db->insert_id();

                              // insert details
                              foreach ($items as $item) {
                                  // parte ya chequeada before
                                  $itm_id    = $item['itm_id'];
                                  $itm_qtty  = $item['itm_qtty'];
                                  // clean up
                                  $data = [];
                                  // prepare array
                                  $data['order_id']  = $order_id;
                                  $data['itm_id']    = $itm_id;
                                  $data['itm_qtty']  = $itm_qtty;
                                  // add/modify extra data in array as necessary
                                  $data ['posted_on']  = $tim;
                                  $data ['posted_by']  = $uid;
                                  // insert each item
                                  $this->db->insert("order_det",$data);
                              }

                              // response with id number
                              $ret = $this->_json_response("Orden grabada con exito","OK",array("id" => $order_id),200);

                          }
                      }
                  }
              }
            }
        }

       echo $ret;
    }


    /**
      *  Devuelve un registro de mensaje
      */
     public function message_get() {

         // clean the paramters
         $rawin  = json_decode($this->input->raw_input_stream,true);
         // token
         $token   =  $this->get_token($rawin);

         // errors array
         $errors = [];

         // parameters
         $id     = isset($rawin["id"]) ? $rawin["id"] : null;
         // user is mandatory
         $uid    = isset($rawin["uid"]) ? $rawin["uid"] : null;

        // Validate parameters
         if (!$token || !$uid || !$id) {
             $ret = $this->_json_response("Error de parametros.","ERROR",array("errors" =>"ERR_INVALID_PARAMS","params" => $rawin),200);
         }
         else if ($token != TOKEN) {
             $ret = $this->_json_response("Error de autorizacion.","ERROR",array("errors" =>"ERR_INVALID_TOKEN","params" => $rawin),200);
         }
         else {
            // SEARCH
             $sql = "SELECT msg_mst.* FROM msg_mst WHERE msg_mst.id = $id";
             $qry = $this->db->query($sql);
             if ($qry->num_rows() == 0) {
                 // response with errors
                 $ret = $this->_json_response("Registro no hallado en empresa $code","ERROR",
                                        array("errors" => "ERR_NOT_FOUND_SINIESTRO","params" => $rawin),200);
             }
             else {
                 // return as json
                 $ret = $this->_json_response("Registro hallado en empresa $code","OK",$qry->row_array(),200);
             }
         }

        echo $ret;
     }


     /**
       *  Marca mensaje como leido etc
       */
      public function message_done() {

          // clean the paramters
          $rawin  = json_decode($this->input->raw_input_stream,true);
          // token
          $token   =  $this->get_token($rawin);

          // errors array
          $errors = [];

          // parameters
          $id     = isset($rawin["id"]) ? $rawin["id"] : null;
          // user is mandatory
          $uid    = isset($rawin["uid"]) ? $rawin["uid"] : null;

          // Validate parameters
          if (!$token || !$uid || !$id) {
              $ret = $this->_json_response("Error de parametros.","ERROR",array("errors" =>"ERR_INVALID_PARAMS","params" => $rawin),200);
          }
          else if ($token != TOKEN) {
              $ret = $this->_json_response("Error de autorizacion.","ERROR",array("errors" =>"ERR_INVALID_TOKEN","params" => $rawin),200);
          }
          else {
              // add/modify extra data in array as necessary
              $data ['modified_on']      = date ("YmdHi");
              $data ['modified_by']      = $uid;
              $data ['is_unread']        = 'N';

              // update data. Errors are possible here.
              $this->db->db_debug = false;
              if (!@$this->db->update("msg_mst", $data, array("id" => $id))) {
                  $ret = $this->_db_error();
              }

              // return as json
              $ret = $this->_json_response("Registro actualizado en empresa $code","OK",[],200);
          }

         echo $ret;
    }



    /**
     * Json - mensajes records
     */
    public function messages_get() {

      // clean the paramters
      $rawin  = json_decode($this->input->raw_input_stream,true);
      // token
      $token   =  $this->get_token($rawin);

      // errors array
      $errors = [];

      // get the array, rest make up
      $parms  = isset($rawin) ? $rawin : null;
      // user id making the request. MANDATORY.
      $uid    = isset($parms["uid"]) ? $parms["uid"] : null ;

      // Validate token etc
      if (!$token || !$uid || !$parms) {
          $ret = $this->_json_response("Error de parametros.","ERROR",array("errors" =>"ERR_INVALID_PARAMS","params" => $rawin),200);
      }
      else if ($token != TOKEN) {
          $ret = $this->_json_response("Error de autorizacion.","ERROR",array("errors" =>"ERR_INVALID_TOKEN","params" => $rawin),200);
      }
      else {
          //-----------------------
          // get records ONLY UNREAD
          //-----------------------
          $sql   = "SELECT msg_mst.* FROM msg_mst
                     WHERE msg_mst.msg_to = $uid AND is_unread = 'Y'
                     ORDER BY msg_mst.id DESC";

          $query = $this->db->query($sql);

          // found: correct validation
          if ($query->num_rows() > 0)
              $ret = $this->_json_response("Registros hallados.","OK",$query->result_array(),200);
          else
              $ret = $this->_json_response("No se hallaron registros para usuario $uid","ERROR",array("errors" =>"ERR_NOT_FOUND","params" => $rawin),200);
       }

       // output the json code
       echo $ret;
   }


   /**
    * Json - mensajes records count
    */
   public function messages_count() {

     // clean the paramters
     $rawin  = json_decode($this->input->raw_input_stream,true);
     // token
     $token   =  $this->get_token($rawin);

     // errors array
     $errors = [];

     // get the array, rest make up
     $parms  = isset($rawin) ? $rawin : null;
     // user id making the request. MANDATORY.
     $uid    = isset($parms["uid"]) ? $parms["uid"] : null ;

     // Validate token etc
     if (!$token || !$uid || !$parms) {
         $ret = $this->_json_response("Error de parametros.","ERROR",array("errors" =>"ERR_INVALID_PARAMS","params" => $rawin),200);
     }
     else if ($token != TOKEN) {
         $ret = $this->_json_response("Error de autorizacion.","ERROR",array("errors" =>"ERR_INVALID_TOKEN","params" => $rawin),200);
     }
     else {
         //-----------------------
         // get records
         //-----------------------
         $sql   = "SELECT count(*) AS tot_msg FROM msg_mst
                    WHERE msg_mst.msg_to = $uid
                          AND is_unread = 'Y'
                    ORDER BY msg_mst.id DESC";

         $query = $this->db->query($sql);

         // found: correct validation
         if ($query->num_rows() > 0)
             $ret = $this->_json_response("Registros hallados.","OK",$query->row_array(),200);
         else
             $ret = $this->_json_response("No se hallaron registros para usuario $uid","ERROR",array("errors" =>"ERR_NOT_FOUND","params" => $rawin),200);

      }

      // output the json code
      echo $ret;
  }



}

/* End of file Extapi.php */

/* Location: ./application/controllers/Extapi.php */

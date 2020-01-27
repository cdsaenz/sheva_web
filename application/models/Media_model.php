<?php  if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * Media Master (Pictures, attachments etc) model
 * @author		csaenz
 * 22-08-2018
 */

class Media_model extends CDS_Model
{
    public function __construct()
    {
        // COMMON TASKS
        parent::__construct();

        // define fields that can be updated in a form.
        $this->db_table  =  "media_mst";
        $this->db_fields =  $this->db->list_fields($this->db_table);
    }


    /**
      * Add a new record
      * with an array of $field => $value
      */

    public function insert_record($data)
    {

        // clean data and keep only valid fields
        // CDS CHANGED: $arr  = array_filter(elements($this->db_fields, $data));
        $arr = valid_fields($this->db_fields, $data);

        // validation not empty
        if ($arr["media_name"] == "") {
            $this->error(_("Debe ingresar un nombre para el medio"));
        }

        // get related record info
        $rel_type    = $arr['rel_type'];
        $rel_id      = $arr['rel_id'];
        $media_type  = $arr['media_type'];

        // exists other record as main for the rel/type?
        $main_id = $this->main_media_exists($rel_type, $rel_id, $media_type);
        if (!$main_id) {
            // no hay otro, FORZAR ESTE como main
            $arr['is_main'] = 'Y';
        }

        // current uid
        $uid  = $this->session->userdata("user_id");
        $tim  = date("YmdHi");
        // add/modify extra data in array as necessary
        $arr ['posted_on'] = $tim;
        $arr ['posted_by'] = $uid;

        // insert data
        $this->db->insert($this->db_table, $arr);
        $id = $this->db->insert_id();

        // si este es main, marcar los otros como NOT MAIN
        if ($arr['is_main'] == 'Y') {
            // cambiado por si hay otro media type! ej puede haber un PICTURE is_main = Y  pero tambien un PDF is_main = Y
            $filter = "id <> $id AND rel_id = $rel_id AND rel_type = '$rel_type' AND media_type = '$media_type'  ";
            // execute
            $this->db->update($this->db_table, array("is_main" => "N"), $filter);
        }

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

        // validation not empty
        if ($arr["media_name"] == "") {
            $this->error(_("Debe ingresar un nombre para el medio."));
        }

        // get related record info
        $rel_type    = $arr['rel_type'];
        $rel_id      = $arr['rel_id'];
        $media_type  = $arr['media_type'];

        // exists other record as main for the rel/type?
        $main_id = $this->main_media_exists($rel_type, $rel_id, $media_type);
        if (!$main_id) {
            // no hay otro, FORZAR ESTE como main
            $arr['is_main'] = 'Y';
        }

        // current uid & cid
        $uid  = $this->session->userdata("user_id");
        // add/modify extra data in array as necessary
        $arr ['modified_on'] = date("YmdHi");
        $arr ['modified_by'] = $uid;

        // apply the key limit and update
        $this->db->update($this->db_table, $arr, "id = $id");

        // mark all others OF the same type as not main if this is main..
        if ($arr['is_main'] == 'Y') {
            // cambiado por si hay otro media type! ej puede haber un PICTURE is_main = Y  pero tambien un PDF is_main = Y
            $filter = "id <> $id AND rel_id = $rel_id AND rel_type = '$rel_type' AND media_type = '$media_type'  ";
            // execute
            $this->db->update($this->db_table, array("is_main" => "N"), $filter);
        }
    }


    /**
     * Delete a record
     */

    public function delete_record($id)
    {

        // find the record data
        $data = $this->get_record($id);
        if (!$data) {
            $this->error(_("Error, medio no hallado."));
        }

        // vars
        $rel_type    = $data['rel_type'];
        $rel_id      = $data['rel_id'];
        $media_type  = $data['media_type'];
        $media_src   = $data['media_src'];
        $src_type    = $data['src_type'];

        // apply the key limit and update
        $this->db->delete($this->db_table, "id = $id");

        // mark some as main
        if ($data['is_main'] == 'Y') {

            // pasar a is_main el primer registro del tipo que se encuentre.
            $sql = "UPDATE {$this->db_table} SET is_main = 'Y' WHERE
                      id <> $id AND rel_id = $rel_id AND rel_type = '$rel_type' AND media_type = '$media_type'
                      ORDER BY id ASC LIMIT 1";

            $this->db->query($sql);
        }


        // Unlink/delete file if physical
        if ($src_type == 'FILE') {
            // file with upload path
            $filepath = config_item('attach_path') . "/$rel_type/" . $media_src;
            if (is_file($filepath)) {
                unlink($filepath);
            }
        }
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
            $this->db->where($extra_where, null, false);
            $this->db->group_end();
        }

        // cache to avoid loading where twice.
        $this->db->stop_cache();

        // total records BEFORE limit and AFTER where
        $count = $this->db->count_all_results();

        // pagination links. segment is 4 because of sid (which is 3)
        $links = $this->get_paginator(base_url($url), $count, 4);
        // build query
        $this->db->select("media_mst.*,users.nick added_by")
                 ->from("media_mst")
                 ->join('users', 'users.id = media_mst.posted_by', 'left');

        // both zeroes don't add limit (all records)
        if ($limit <> 0 || $offset <> 0) {
            $query = $this->db->limit($limit, $offset);
        }

        // order by. code_number is visual order
        $this->db->order_by("media_mst.id");

        // execute the query and return a two dimensional array with query object
        $query = $this->db->get();

        // debug
        // echo $this->db->last_query();
        return array( "query" => $query, "count" => $count, "links" => $links, "vars" => $vars);
    }


    /**
     * GET record data based on id (nbr)
     */
    public function get_record($id, $return_empty_array = false)
    {

        // search. JOIN ONLY FOR suppliers. Customer own nothing.
        $sql   = "SELECT media_mst.*, users.nick added_by, supp_mst.supp_name media_company_name
                  FROM media_mst
                  LEFT JOIN supp_mst ON media_mst.media_company_id = supp_mst.id
                  LEFT JOIN users ON users.id = media_mst.posted_by
                  WHERE media_mst.id = $id";

        $query = $this->db->query($sql);

        // return a single row or empty array if not found.
        if ($query->num_rows() > 0) {
            return $query->row_array();
        } else {
            if ($return_empty_array) {
                return empty_result_array($query);
            } else {
                return false;
            }
        }
    }

    /**
     *  Get related record
     */

    public function get_rel_record($type, $id)
    {
        if ($type == 'items') {
            // owner is the owner of the item
            $sql   = "SELECT itm_name rel_name, 'SUPP' media_company_type, company_id media_company_id
                      FROM itm_mst WHERE id = $id";
        }
        else if ($type == 'suppliers') {
            // owner is the supplier itself
            $sql   = "SELECT supp_name rel_name, 'SUPP' media_company_type, id media_company_id
                       FROM supp_mst WHERE id = $id";
        }
        else {
            $this->error(_("Tipo de registro relacionado no existe: $type"));
        }

        // if not empty return record
        $qry   = $this->db->query($sql);
        if ($qry->num_rows() > 0) {
            return $qry->row_array();
        } else {
            return false;
        }
    }

    /**
     * main_media_exists
     * Busca medio del related tipo/id y tipo medio que sea main y retorna id o false
     */

    public function main_media_exists($rel_type, $rel_id, $media_type)
    {
        $sql = "SELECT id FROM media_mst
               WHERE rel_id = $rel_id
                AND rel_type = '$rel_type'
                AND media_type = '$media_type'
                AND is_main = 'Y'";

        $query = $this->db->query($sql);
        if ($query->num_rows() == 0) {
            return false;
        } else {
            return $query->row("id");
        }
    }

    /**
     * get main media of a type
     * return full url
     */

    public function get_main_media($rel_type, $rel_id, $media_type)
    {
        // default NOT FOUND image
        $url  = img_url("notfound.png");

        // look for media info
        $sql = "SELECT id, media_src FROM media_mst
               WHERE rel_id = $rel_id
                AND rel_type = '$rel_type'
                AND media_type = '$media_type'
                AND is_main = 'Y'";

        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
          // pick the data
          $row    = $query->row_array();

          // subfolder
          $folder = "/$rel_type/";

          // server root
          $root = config_item("server_root");

          // file source
          $file = $row['media_src'];

          // empty or not
          if ($file) {
              // file with upload path
              $fullpath = config_item('attach_path') . $folder . $file;
              // build url from config
              $fullurl  = $root . config_item('attach_url') . $folder;

              // exists? else send default picture
              // DON'T PERFORM base_url as it may be out of the app folder
              if (is_file($fullpath))
                  $url  = "$fullurl$file";
          }
        }

        return $url;
    }

    /**
     * generic FILE uploader. parameters:
     *  rel_type (ex customers)
     *  rel_id (id del objeto relacionado)
     *  $id of the media_mst record
     */
    public function upload_file($rel_type, $rel_id,$id, $field_name  = "file_media_src") {

          // field with the upload filename, link data etc
          $file_temp   = $_FILES[$field_name]['tmp_name'];
          $file_name   = $_FILES[$field_name]['name'];

          // original file name <> media_src
          $src_name    = $file_name;
          // verify temp file exists and it's uploaded
          if (file_exists($file_temp) && is_uploaded_file($file_temp)) {
              // build a unique and searchable filename. example: items-8-35.jpg
              $ext         = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
              $upload_name = "$rel_type-$rel_id-$id.$ext";
              // perform the ACTUAL upload to the (sub)folder
              $upload_data = $this->media_model->doUpload($upload_name, $field_name, "$rel_type");
              // update the new file name
              if ($upload_data) {
                  $media_src = $upload_data['file_name'];

                  // src_name  : ORIGINAL FILE NAME
                  // media_src : NEW REAL NAME
                  return (array("src_name" => $src_name, "media_src" => $media_src, "upload_data" => $upload_data ));
              }
              else
                  // upload failed
                  return false;
          }
          else {
              // file not found or not uploaded
              return false;
          }

    }

    /**
     * generic MEDIA create & uploader
     * action: ONLY: only one main media exists of the type, delete others
     *         MAIN: INSERT new as main (force)
     *         AUTO: INSERT new as main if none, or else secondary
     */

    public function upload_media($media_type,$rel_type,$rel_id,$action = 'AUTO',
                                 $field_name  = "file_media_src") {

          // verify related record exists
          $rel = $this->get_rel_record($rel_type,$rel_id);
          if (!$rel) {
              $this->error("Error, no se encontro registro relacionado para subir archivo.");
          }

          // see if another main media of this type exists.
          $main_id   = $this->media_model->main_media_exists($rel_type, $rel_id, $media_type);
          // see how to treat upload
          if (strcasecmp($action,"MAIN") == 0) {
              $is_main   = 'Y';
          }
          else if (strcasecmp($action,"AUTO") == 0) {
              $is_main   = $main_id ? 'N' : 'Y';
          }
          else if (strcasecmp($action,"ONLY") == 0) {
              // leave this one only
              $is_main = 'Y';
              // delete all media for the related record & media type
              $this->delete_all_media($rel_type,$rel_id,$media_type);
          }

          // first, need the id of media_mst, no file names
          $media["media_type"]  = $media_type;
          $media["rel_type"]    = $rel_type;
          $media["rel_id"]      = $rel_id;
          $media["media_name"]  = $rel["rel_name"];
          $media["src_type"]    = "FILE";
          $media["is_main"]     = $is_main;

          // do the insert
          $id = $this->insert_record($media);

          // upload file to its type folder, previous renaming
          $upload = $this->upload_file($rel_type,$rel_id,$id,$field_name);
          if ($upload) {
              // update the file names
              $media["media_src"]   = $upload["media_src"];
              $media["src_name"]    = $upload["src_name"];

              // do the update
              $this->update_record($media,$id);

              // return the id of the media_mst record
              return $id;
          }
          else {
              // borrar registro sin file names
              $this->db->delete($this->db_table,array("id" => $id));
              // don't show error, let origin handle
              return false;
          }

    }


    /**
     * llama a funcion borrado para cada registro coincidente
     */
    public function delete_all_media($rel_type,$rel_id,$media_type) {

      // delete all of this type/related record
      $query   = $this->db->get_where($this->db_table,array("rel_type"   => $rel_type,
                                                            "rel_id"     => $rel_id,
                                                            "media_type" => $media_type ));
      // recorrer, borrar registro y en disco
      if ($query->num_rows() > 0) {
          foreach ($query->result() as $row) {
              $this->delete_record($row->id);
          }
      }
    }
}

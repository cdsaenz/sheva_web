<?php if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Media extends CDS_Controller
{

    /**
     * MAIN CONTROLLER FOR MEDIA_MST (Tabla de medios, pictures attachments etc) MGMT
     */

    public function __construct()
    {
        // call the parent custom constructor as it does setup stuff
        parent::__construct();

        // set the model var to keep the functions generic
        $this->load->model("media_model");

        // verify logged in and.. only admin!
        $this->app->AllowOnlyAdmin();
    }


    /**
       *  Default - show all report
       *  (just run the dosearch method)
       */

    public function index()
    {
        // run search without parameters, equals to all
        $this->_dosearch('all', 1, _("Archivos"));
    }


    /**
     *  THIS IS THE MAIN MASS SEARCH ACTION
     *  Get a search URL like
     *  /search/$sid/$page and execute dosearch
     */

    public function search()
    {
        // If there is a post (from form) capture and process
        $filter   = $this->input->post(null, true);
        // added where to return (back from edit)
        $this->session->set_userdata('referred_from', current_url());

        // execute
        if ($filter) {
            // Process FORM, WHERE/LIKE/ORDER BY and save to sid
            $w = [];
            if (isset($filter["keyword"]) && ($filter["keyword"] != "")) {
                $v = $filter["keyword"];
                $w = $this->media_model->add_search_param($w, "WHERE", "media_name", "LIKE", $v);
                // new store the formvar (ex: keyword)
                $w = $this->media_model->add_search_param($w, "FORMVAR", "keyword", "", $v);
            }

            // save to sid
            $sid    = $this->media_model->save_search($w, "media_mst");
            $page   = 1;
        } else {
            // LOOK FOR A SEARCH ID get parameters from url
            // it should have fixed where included unless 0/all
            $sid      = $this->uri->segment(3, 0);
            $page     = max((int)$this->uri->segment(4), 1);
        }

        // ALL RECORDS
        if ($sid == 0) {
            // make more readable
            $sid = 'all';
        }

        // launch the query method
        $this->_dosearch($sid, $page, _("Archivos"));
    }


    /**
     * run the search
     */

    public function _dosearch($sid = 0, $page = 1, $title = "List")
    {
        // keep "all" as needed
        $isid     = (int)$sid;
        // pick up company id
        $cid      = $this->session->user_company_id;

        // fixed where
        if ($this->app->is_admin()) {
            // admin ALL (could be filtered before)
            $extra_where = [];
        }
        else if ($this->app->is_supp()) {
            // distribuidor
            $extra_where = array("media_mst.media_company_id" => $cid,
                                 "media_mst.media_company_type" => "'SUPP'");
        }

        // get records upon sid (0=all)
        $result   = $this->media_model->get_records($page, "media/search/$sid/", $isid, $extra_where);

        // add pagination, dataset and total
        $data["links"]   = $result["links"];
        $data["dataset"] = $result["query"]->result_array();
        $data["count"]   = $result["count"];
        // added form vars persistance
        $data["vars"]    = $result["vars"];

        // title
        $data["title"]   = $sid == 0 ? $title : $title . _(" (Filtrado)") ;

        // get view
        $this->load_view('media/list', $data);
    }


    /**
     *  Single record view method
     *  Get id by url
     */
    public function view()
    {
        // get the query
        $id        = $this->uri->segment(3);
        $row       = $this->media_model->get_record($id);

        // verify we actually got something.
        if (!$row) {
            $this->error(_('Error: ID does not exist.'));
        } else {
            // get type and id for related record
            $rel_type      = $row['rel_type'];
            $rel_id        = $row['rel_id'];
            $rel           = $this->media_model->get_rel_record($rel_type, $rel_id);
            if (!$rel) {
                $rel = "Origen No Hallado";
            }

            // check company
            $cid  = $row['media_company_id'];
            if (!$this->app->is_admin()) {
                if ($cid <> $this->session->user_company_id) {
                    $this->error("Error, acceso a medio restringido a distribuidor/admin.");
                }
            }

            // related is with my if not admin.
            $folder  = $this->app->get_media_controller($rel_type);
            $related = anchor("$folder/view/$rel_id", $rel['rel_name']);

            // picture link if any
            if ($row["src_type"] == "FILE") {
                // get proper url
                $url = default_img($row['media_src'], $rel_type);
            } else {
                $url = file_url($row['media_src'], $rel_type);
            }

            // set id info & title
            $data['id']      = $id;
            $data['row']     = $row;
            $data['url']     = $url;
            // set related
            $data['related'] = $related;
            // hidden & title
            $data['hidden']  = array('edit_mode' => 'view',
                                     'key_id'    => $id   );
            $data['title']   = _($row['media_src']);

            // get view
            $this->load_view('media/view', $data);
        }

    }



    /**
      *  Single record edit method
      *  Get id by url
      */

    public function edit()
    {
        // get the query
        $id            = $this->uri->segment(3);
        $row           = $this->media_model->get_record($id);

        // verify we actually got something.
        if (!$row) {
            $this->error(_('Error: ID no existe.'));
        }

        // get type and id for related record
        $rel_type      = $row['rel_type'];
        $rel_id        = $row['rel_id'];
        $rel           = $this->media_model->get_rel_record($rel_type, $rel_id);
        if (!$rel) {
            $rel = "Origen No Hallado";
        }

        // check company
        $cid  = $row['media_company_id'];
        if (!$this->app->is_admin()) {
            if ($cid <> $this->session->user_company_id) {
                $this->error("Error, acceso a medio restringido a distribuidor/admin.");
            }
        }

        // set info
        $data['related'] = anchor("$rel_type/view/$rel_id", $rel['rel_name']);
        $data['row']     = $row;

        // picture link if any
        if ($row["src_type"] == "FILE") {
            // full url
            $type= $row["rel_type"];
            $url = rtrim(config_item('attach_url'), '/') . "/$type/" . $row['media_src'];
        } else {
            $url = $row["media_src"];
        }

        // set id info & title
        $data['url']     = $url;
        $data['id']      = $id;
        $data['hidden']  = array('edit_mode' => 'edit',
                                 'rel_type'  => $rel_type,
                                 'rel_id'    => $rel_id,
                                 'key_id'    => $id   );
        $data['title']   = _("Editar Datos Archivo");

        // get view
        $this->load_view('media/edit', $data);

    }

    /**
     *  Single record post edit or insert
     *  Get data by post
     */

    public function doedit()
    {
        // DETERMINE origin record type and get post
        $rel_type = $this->input->post('rel_type');
        $rel_id   = $this->input->post('rel_id');
        $parms    = $this->input->post(null, true);

        // depends on edit more
        $mode  = $parms['edit_mode'];
        if ($mode == 'edit') {
            // JUST update the record.
            $id    = $parms['key_id'];
            $this->media_model->update_record($parms, $id);
        } else {
            // NEW: create a base record to get the id
            $id = $this->media_model->insert_record($parms);

            // field with the upload filename, link data etc
            $field_name  = "file_media_src";
            $link_name   = $parms['link_media_src'];
            $file_temp   = $_FILES[$field_name]['tmp_name'];
            $file_name   = $_FILES[$field_name]['name'];

            // do the upload IF IT'S AN UPLOAD "FILE" TYPE
            if ($parms ['src_type'] == 'FILE') {
                $parms ['src_name']    = $file_name;    // original file name <> media_src
                if (file_exists($file_temp) && is_uploaded_file($file_temp)) {
                    // build a unique and searchable filename. example: items-8-35.jpg
                    $ext         = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
                    $upload_name = "$rel_type-$rel_id-$id.$ext";
                    // perform the upload to the subfolder
                    $upload_data = $this->media_model->doUpload($upload_name, $field_name, "$rel_type");
                    // update the record
                    if ($upload_data) {
                        $parms["media_src"] = $upload_data['file_name'];
                    }
                }
            } elseif ($parms ['src_type'] == 'LINK') {
                $parms ['src_name']    = $link_name;    // entered link, same as media_src
                $parms ['media_src']   = $link_name;    // entered link, same as media_src
            }

            // update the record.
            $this->media_model->update_record($parms, $id);
        }

        // try to return to individual request/report
        redirect("media/view/$id", "refresh");
    }


    /**
      *  Single record delete method
      *  Get id by url
      */

    public function delete()
    {
        // get the query
        $id            = $this->uri->segment(3);
        $row           = $this->media_model->get_record($id);

        // verify we actually got something.
        if (!$row) {
            $this->error(_('Error: ID no existe.'));
        }

        // get type and id for related record
        $rel_type      = $row['rel_type'];
        $rel_id        = $row['rel_id'];
        $rel           = $this->media_model->get_rel_record($rel_type, $rel_id);
        if (!$rel) {
            $rel = "Origen No Hallado";
        }

        // check company
        $cid  = $row['media_company_id'];
        if (!$this->app->is_admin()) {
            if ($cid <> $this->session->user_company_id) {
                $this->error("Error, acceso a medio restringido a distribuidor/admin.");
            }
        }

        // set info
        $data['related'] = anchor("$rel_type/view/$rel_id", $rel['rel_name']);
        $data['row']     = $row;

        // picture link if any
        if ($row["src_type"] == "FILE") {
            // full url
            $type= $row["rel_type"];
            $url = rtrim(config_item('attach_url'), '/') . "/$type/" . $row['media_src'];
        } else {
            $url = $row["media_src"];
        }

        // set id info & title
        $data['url']     = $url;
        $data['id']      = $id;
        $data['hidden']  = array('edit_mode' => 'delete',
                                  'rel_type'  => $rel_type,
                                  'rel_id'    => $rel_id,
                                  'key_id'    => $id   );
        $data['title']   = _("Borrar archivo de medios");

        // get view
        $this->load_view('media/delete', $data);
    }


    /**
     *  Single record delete
     *  Get data by post
     */

    public function dodelete()
    {
        // get the parameters
        $rel_type = $this->input->post('rel_type');
        $rel_id   = $this->input->post('rel_id');
        $parms    = $this->input->post(null, true);

        // depends on edit more
        $mode  = $parms['edit_mode'];
        if ($mode == 'delete') {
            // JUST delete
            $id    = $parms['key_id'];
            $this->media_model->delete_record($id);
        }

        // try to return to individual request/report
        redirect("media", "refresh");
    }

    /**
     *  Single record insert/add method
     *  ADD a media record for any declared type
     *  items,customers
     */
    public function add()
    {
        // get type and id for related record
        $rel_type      = $this->uri->segment(3, 0);
        $rel_id        = $this->uri->segment(4, 0);
        // default media ex: LOGO, default PICTURE.
        $media_type    = $this->uri->segment(5, "PICTURE");

        // check if RELATED exists.
        $rel           = $this->media_model->get_rel_record($rel_type, $rel_id);
        if (!$rel) {
            $this->error(_("Error, registro relacionado no existe"));
        }

        // check media type exists.
        if (!$this->media_model->code_exists("media_type",$media_type)) {
            $this->error(_("Error, tipo de media: $media_type no declarado en Tablas."));
        }

        // set info
        $data['related'] = anchor("$rel_type/view/$rel_id", $rel['rel_name']);
        $data['url']     = false;
        $data['hidden']  = array('edit_mode' => 'add',
                                 'rel_type'  => $rel_type,
                                 'rel_id'    => $rel_id,
                                 'media_company_type' => $rel["media_company_type"],
                                 'media_company_id'   => $rel["media_company_id"]
                                );
        $data['title']   = _("Nuevo Archivo de Medios");

        // get the content FROM this view (process with vars) into a var
        $row             = $this->media_model->get_record(0, true);

        // check if main media exists for the media_type
        $id      = $this->media_model->main_media_exists($rel_type, $rel_id, $media_type);
        $is_main = $id ? 'N' : 'Y';

        // some defaults
        $row['media_name']   = $rel["rel_name"];
        $row['src_type']     = "FILE";
        $row['media_origin'] = "Desconocido";
        $row['media_type']   = $media_type;
        $row['is_main']      = $is_main;

        // ownership


        // pass on to form
        $data['row']         = $row;

        // view it
        $this->load_view('media/edit', $data);
    }


    /**
     *  Get/show attachment securely
     */
    public function getfile()
    {

      // get the id of the media record
        $id   = $this->uri->segment(3);
        $row  = $this->media_model->get_record($id);
        // verify we actually got something.
        if (!$row) {
            $this->error(_('Error: Archivo de medios no hallado.'));
        }
        // find the filename and see if it exists
        $f =  rtrim(config_item('attach_path', '/')) . '/' . $row['media_src'];
        if (!file_exists($f)) {
            $this->error(_("Error: Archivo No Hallado: $f"));
        }

        // check company
        $cid  = $row['media_company_id'];
        if (!$this->app->is_admin()) {
            if ($cid <> $this->session->user_company_id) {
                $this->error("Error, acceso a medio restringido a distribuidor/admin.");
            }
        }

        // new name
        $n   = $row['src_name'];
        $ext = strtolower(pathinfo($f, PATHINFO_EXTENSION));
        $n   = ChangeFileExt($n, ".$ext");

        // set content type per extension
        if (in_array($ext, ['png','jpg'])) {
            header("Content-Type: image/png");
        } elseif (in_array($ext, ['jpeg','jpg'])) {
            header("Content-Type: image/jpg");
        } elseif (in_array($ext, ['pdf'])) {
            header('Content-Type: application/pdf');
        } else {
            header('Content-Type: application/octet-stream');
        }

        // output the file. here we rename it
        header('Content-Disposition: inline; filename='.$n.'');
        header("Content-Transfer-Encoding: Binary");
        header('Content-Length: ' . filesize($f));
        //clean all levels of output buffering
        while (ob_get_level()) {
            ob_end_clean();
        }
        readfile($f);
        exit();
    }
}

/* End of file media.php */
/* Location: ./application/controllers/media.php */

<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/***
 * CDS Custom config settings
 * VERSION 5.0 31/12/19
 */

// APP SPECIFICS


// APP - REVISE FOR EACH APP
$app_short          = "Sheva";
$app_code           = strtolower($app_short);
$app_folder         = "/admin";

// config for application naming/ver
$config['app_code'] =  $app_short;
$config['app_name']	= "$app_short";                            // short app name
$config['app_title']= "$app_short Dashboard";                            // app title or short description
$config['app_desc']	= "Gestion de Distribuidores";                  // longer description or company
$config['app_ver']  = "1.0";

// STYLING
$config['app_color']         = "#152F4F";                                      // topbar/footer color
$config['app_color_panel']   = "goldenrod";                                    // list header back color.

// company info
$config['app_copyright']  = "© 2019 - CSDev";                                 // footer copyright
$config['app_about']      = 'Software de gestion de distribuidoras';


// SECURITY. GOOGLE CAPTCHA
$config['captcha_secret'] = "CHANGEMENOW";

// DATABASE etc. records per page in pagination
$config['per_page']       = 8;

// EMAIL SETUP
$config['mail_from_addr'] = 'admin-noresponder@sheva.com.ar';   // address used to appear as SENDING mails
$config['mail_from_name'] = $config['app_name'] . ' - Admin';
$config['mail_format']    = 'html';

// uploads settings: pdf|jpg|jpeg|webp|png|gif|zip|doc|ppt|txt|docx|xls|xlsx|csv';
// minimizado a solo imagenes standard
$config['attach_allowed_ext'] = 'jpg|png';
// minimizado con Martin para mobile hasta definir tamaños por tipo archivo.
$config['attach_max_size']    = 2048 ; // 2mb = 2048

// set path to one level above (the public facing folder)
$config['attach_path']        = "/home/shevacom/public_html/uploads";
$config['attach_url']         = "/uploads"; // full path!


// FIXED images folder, example a logo, etc
// THEY COULD BE OUTSIDE THIS TREE
$config['assets_images']      = "/admin/assets/img";
$config['assets_js']          = "/admin/assets/js";
$config['assets_css']         = "/admin/assets/css";

// root, no end backslash
$config['server_root']        = "https://" . $_SERVER['SERVER_NAME'];

/* End of file cds_config.php */
/* Location: ./application/config/cds_config.php */

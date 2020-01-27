<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
// ------------------------------------------------------------------------

/**
 * CDS Helpers (csaenz)
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/helpers/array_helper.html
 * 2016 january 25  CDS
 */

   /***
    *  Alternative for non gettext environment
    */
   if (!function_exists("_")) {
       function _($text) {
           return $text;
       }
   }

  /**
   * Log variable to file
   */

  function logtofile($var, $logfile = '/tmp/loggercs.txt' ) {

    $var_str = var_export($var, true);
    file_put_contents($logfile, $var_str);

  }


  /*-------------------------------------*/
  /* DB - Only elements with key = field
  * ie: return only elements in data also
  * in fields
  /*-------------------------------------*/

  function valid_fields ($fields, $data)
  {
      $result = array_filter($data, function($field) use($fields){
                      return in_array($field,$fields);
                },ARRAY_FILTER_USE_KEY );
      return $result;
  }


  /*-------------------------------------*/
  /* NUMBER HANDLING
  /*-------------------------------------*/

  function Getfloat($str,$decimals = 2) {
    if(strstr($str, ",")) {
      $str = str_replace(".", "", $str); // replace dots (thousand seps) with blanks
      $str = str_replace(",", ".", $str); // replace ',' with '.'
    }

    if(preg_match("#([0-9\.]+)#", $str, $match)) { // search for number that may contain '.'
      return number_format(floatval($match[0]),$decimals);
    } else {
      return number_format(floatval($str),$decimals); // take some last chances with floatval
    }
  }


  /*-------------------------------------*/
  /* HTML/CSS HELPERS
  /*-------------------------------------*/
  function thumb_anchor($url, $src, $w = 140, $h = 140 , $alt = NULL)
  {
      if ($alt == NULL)
          $alt = _("Image Not Available");

      $img = array( 'src'   => $src,
                    'alt'   => $alt,
                    'width' => $w,
                    'height'=> $h,
                    'class' => "img-thumbnail"
                  );

      return anchor( $url, img($img) );
  }



  /*------------------------------------*/
  /*  ARRAY FUNCTIONS                    */
  /*------------------------------------*/


  /*------------------------------------*/
  /*  DATE FUNCTIONS                    */
  /*------------------------------------*/

  /**
   *  Validate Date string: Returns true if it matches the format.
   */
  function validateDate($date, $format = 'd-m-Y')
  {
      $d = DateTime::createFromFormat($format, $date);
      return $d && $d->format($format) == $date;
  }

  /**
   *  Convert YYYYMMDD string to a human readable D/M/Y string.
   */
  function AnsiDateToDMY($datestr,$shortdate = false,$withtime = true)
  {
     $result = "";
     if ($datestr) {
        if (!$shortdate) {
           // separates in dmy
           $year  = substr($datestr,0,4);
           $month = substr($datestr,4,2);
           $day   = substr($datestr,6,2);

           $hour  = substr($datestr,8,2);
           $min   = substr($datestr,10,2);

           if ($hour && $withtime)
               $result = "$day/$month/$year $hour:$min";
           else
                $result = "$day/$month/$year";
        }
        else {
           // separates in dmy
           $year  = substr($datestr,0,4);
           $month = substr($datestr,4,2);
           $day   = substr($datestr,6,2);

           if ($year == date("Y"))
                $result = "$day/$month";
           else {
                $result = "$day/$month/$year";
           }

        }
     }

     return $result;
  }

  /**
   * Expects date in format dd/mm/yyyy or dd-mm-yyyy
   * modifies param to yyyymmdd, returns false if error
   */
  function DMYToAnsiDate(&$datestr)
  {
     $result  = true;
        $newdate = $datestr;
        if ($newdate && $newdate != "0000-00-00" ) {
            // strips separators
            $newdate = str_replace("-","",$newdate);
            $newdate = str_replace("/","",$newdate);

            // separates in dmy
            $day    = substr($newdate,0,2);
            $month  = substr($newdate,2,2);
            $year   = substr($newdate,4,4);

            //
            if (!checkdate ( $month , $day , $year )) {
                $result = false;
            }
            else {
                // modify original: insure date format
                // is ok for the database (ansi!)
                $datestr = $year.$month.$day;
            }
        }

        return $datestr;
  }

  /**
   *  Convert a valid date format (e.g yyyymmddhhnnss) to
   *  to a formatted date.
   */

  function todate($date,$format = 'd/m/Y')
  {

    if (!empty($date))
        $date   = date($format,strtotime($date));

    return $date;

  }

  /**
   * translate Y and N to Si/No
   * LOCALIZED
   */
  function yesno ($yn) {
      if ($yn == 'Y')
          return _("Si");  // o Yes si localized
      else
          return _("No");
  }


  /**
   * Get Current Time in an x time zone as human readable. See:
   * http://php.net/manual/en/timezones.php
   */

  function TimeInZone($newTZ) {

      $currTZ = date_default_timezone_get();
      date_default_timezone_set($newTZ);
      $the_time = date('D j, h:i A');
      date_default_timezone_set($currTZ);
      return $the_time;

  }

  /**
   * is the file an image?
   */
  function is_image($file) {

      $images = array('jpg','jpeg','webp','png','gif');
      $ext    = pathinfo($file, PATHINFO_EXTENSION);

      return in_array($ext,$images);
  }

  /**
   * css_url helper - CI style
   *
   * Create a css URL based on your basepath.
   * Segments can be passed in as a string or an array, same as site_url
   * or a URL to a file can be passed in, e.g. to an image file.
   */

  function css_url($uri = '') {
     return config_item("server_root") .  config_item("assets_js").$uri;
  }

  /**
   * js_url helper - CI style
   * attention esto ejecuta codigo CI!!
   */

  function js_url($uri = '') {
     return config_item("server_root") .  config_item("assets_js").$uri;
  }

  /**
   * FIXED IMAGES img_url helper
   *
   * Create a img URL based on your basepath.
   * Segments can be passed in as a string or an array, same as site_url
   * or a URL to a file can be passed in, e.g. to an image file.
   */
  function img_url($uri = '') {
      return rtrim(config_item("server_root") . config_item("assets_images"),"/").'/'.$uri;
  }


  /**
   *  BUILD IMG URL
   *  image uploaded, url with alt if not found
   */
   function default_img($file, $folder = "") {

        // an array like $row with "media_src" => 'foto.jpg' & rel_type => 'items' (folder)
        if (is_array($file)) {
            $folder = $file['rel_type'];
            $file   = $file['media_src'];
        }

        // subfolder
        if ($folder)
            $folder = "/$folder/";
        else
            $folder = "/";

        // root
        $root = config_item("server_root");

        // default NOT FOUND image
        $url  = img_url("notfound.png?v=$file");

        // empty or not
        if ($file) {
            // file with upload path
            $fullpath = config_item('attach_path') . $folder . $file;
            // build url from config. url_folder might differ..
            $fullurl  = $root . config_item('attach_url') . $folder;

            // exists? else send default picture
            // DON'T PERFORM base_url as it may be out of the app folder
            if (file_exists($fullpath))
                $url  = "$fullurl$file";
        }

       return $url;
   }


  // will insure www.ibm.com won't be taken against the local domain
  function external_url ($link ='') {
    $scheme = parse_url($link, PHP_URL_SCHEME);
    if (empty($scheme)) {
          $link = 'http://' . ltrim($link, '/');
    }

    return $link;
  }


  /***************************/
  /* FILE HANDLING ROUTINES  */
  /***************************/

  function get_local($url) {
      $urlParts = parse_url($url);
      return $_SERVER['DOCUMENT_ROOT'] . $urlParts['path'];
  }

  /**
   * Change file extension of a file path
   * pass extension with . (that allows to add suffix)
   */

  function ChangeFileExt($file, $newext) 	{
      $file = pathinfo($file,PATHINFO_FILENAME) . $newext;
      return $file;
  }

  /**
   * Validador de CUIT
   * de internet  / wikipedia
   */

  function isValidCuit( $cuit ){
  	$cuit = preg_replace( '/[^\d]/', '', (string) $cuit );
  	if( strlen( $cuit ) != 11 ){
  		return false;
  	}
  	$acumulado = 0;
  	$digitos = str_split( $cuit );
  	$digito = array_pop( $digitos );

  	for( $i = 0; $i < count( $digitos ); $i++ ){
  		$acumulado += $digitos[ 9 - $i ] * ( 2 + ( $i % 6 ) );
  	}
  	$verif = 11 - ( $acumulado % 11 );
  	$verif = $verif == 11? 0 : $verif;

  	return $digito == $verif;
  }

  /*
   * DB FUNCTIONS
   */

  /*
     Get an array of the query object result with empty values
     but all fields as array members
  */
  function empty_result_array($query)
  {
      $fields = $query->list_fields();
      $arr    = array();
      // create empty array fields for form usage.
      foreach ($fields as $f) {
          $arr[$f] = "";
      }
      return $arr;
  }

  /**
   *  Other utilities
   */

   /**
    *  send single mail from LINUX
    *  $to     = "cdsaenz@hotmail.com, patoadrianperez@gmail.com";
    */
    function send_mail($to, $subject, $body) {

       $from_name = $this->config->item("mail_from_name");
       $from_mail = $this->config->item("mail_from_addr");

       $headers  = 'MIME-Version: 1.0' . "\r\n";
       $headers .= "From: $from_name <$from_mail>" . "\r\n";
       $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

       // send the mail: configuration in ssmtp.conf
       mail($to, $subject, $body, $headers);
   }



     /**
      * DOWNLOAD_FILE
      * Generic Write content to file and download to PC
      */
     function download_file($output, $fname) {

         // todo define file temporary dir
         $fname = "c:\\temp\\" . $fname;

         if ( @ob_get_length() ) {
               @ob_end_clean();
         }

         // load file with the content
         file_put_contents($fname, $output);

         // send to the browser
         header("Pragma: public");
         header("Expires: 0");
         header('Content-Description: File Transfer');
         header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
         header("Content-Type: application/force-download");
         header("Content-Type: application/octet-stream");
         header("Content-Type: application/download");
         header("Content-Disposition: attachment;filename=".basename($fname));
         header("Content-Transfer-Encoding: binary ");
         header('Content-Length: ' . filesize($fname));

         /* send the file to the buffer (download)*/
         @ob_clean(); @flush();
         @readfile($fname);
         exit;
     }

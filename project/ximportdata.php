<?php

    /***
     *  XIMPORTDATA - PHP Import
     *  Version 1.00
     * <!--%DateStamp%-->28/10/2019<!---->
     */

    // log errors
    ini_set("log_errors", 1);
    ini_set("error_log", "/tmp/error-import-data.log");
    // DB CONSTANTS. DB ADMIN NAME
    define("DB_HOST","localhost");
    define("DB_NAME","geradobr_motobatt");
    define("DB_USER","geradobr");
    define("DB_PWD","wzTXP9VAfT");

    // set timezone
    date_default_timezone_set("America/Argentina/Buenos_Aires");

    // full debugging
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    if (!$argv[1]) {
      exit( "Usage: program <csv>\n" );
    }

    // filename input
    $csvfile = $argv[1];

    // fields file
    $strfile =
    // db fields in SAME ORDER as CSV columns
    $fields  = file($strfile, FILE_IGNORE_NEW_LINES);

    var_
    exit;

    // connect to db
    $dbc  = new DBConnector(DB_HOST,DB_NAME,DB_USER,DB_PWD);

    // delete ALL previous records
    $dbc->db->query("DELETE FROM custom_soc_mst");

    // read csv
    if (($handle = fopen($filename, "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {

            // check number of columns
            $num  = count($data);
            if ($num <> count($fields)) {
               echo "Error, campos tienen que ser " . count($fields) . " no $num \r\n";
               exit;
            }

            $sql  = 'INSERT INTO custom_soc_mst ';
            for ($c=0; $c < $num; $c++) {
                // db field
                $field = $fields[$c];
                // trim and sanitize
                $value = iconv("ISO-8859-15", "UTF-8",$data[$c]);
                // excel adds 194+160 para rellenar!
                $value = rtrim($value,chr(32).chr(194).chr(160));
                // sanitizar
                $value = $dbc->sanitize($value);
                // fix null
                if ($value == 'NULL')
                  $value = '';

                echo "$field => $value\r\n";
                // fix the command
                if ($c !== 0)
                    $sql .= ',';
                else
                    $sql .= ' SET ';

                // add to sql
                $sql  .= "$field = '$value'";
            }

            // write the row to db
            echo "Query: $sql \r\n";
            $dbc->db->query($sql);
            echo "Writing row.. \r\n\r\n";
        }
        fclose($handle);
    }

    // done
    echo "Finalizado \r\n";

    /* eom */


    /**
     *  send single mail from LINUX
     *  $to   = "xxx@hotmail.com, xx3@gmail.com";
     */

    function send_mail($to, $subject, $message, $from_name, $from_mail) {

        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= "From: $from_name <$from_mail>" . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

        // send the mail: configuration in ssmtp.conf
        mail($to, $subject, $message, $headers);
    }



    /**
     *  Database connector
     */

    class DBConnector {

        private $server;
        private $user;
        private $pwd;
        private $dbname;
        public $db;

        public function __construct($server, $dbname, $user, $pwd) {
            // it will die on error
            $this->Connect($server,$user,$pwd,$dbname);
        }

        function error($msg) {
            die("Error: $msg");
        }

        /****
         *  Connect to server
         */
        function Connect($server,$user,$pwd,$dbname) {

            // Create connection
            $this->db = new mysqli($server, $user, $pwd, $dbname);

            // Check connection
            if ($this->db->connect_error) {
                $this->error("Fallo conexión: " . $this->db->connect_error);
            }
        }

        /**
         * Sanitize query value
         */

        function sanitize($sqlv) {

            // full treatment
            $sqlv = stripslashes($sqlv);
            // it screws things up
            //$sqlv = htmlentities($sqlv);
            $sqlv = strip_tags($sqlv);
            $sqlv = mysqli_real_escape_string($this->db,$sqlv);
            return $sqlv;
        }


        /**
         * Get Socios
         */

        function get_socios() {

              $sql   = "SELECT * FROM custom_soc_mst";
              $query = $this->db->query($sql);
              if ($query->num_rows == 0)
                 return false;
              else
                 return $query;
        }


    }

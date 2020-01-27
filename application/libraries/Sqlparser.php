<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__) . '/PHPSQLParser/PHPSQLParser.php';

class Sqlparser extends PHPSQLParser
{
    function __construct($sql = false, $calcPositions = false)
    {
        parent::__construct($sql, $calcPositions);
    }
}

/* End of file DbSQLParser.php */
/* Location: ./application/libraries/DbSQLParser.php */
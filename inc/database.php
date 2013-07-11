<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

function db($query,$rows=false,$fetch=false,$log=false)
{
    database::init();
    if($rows || $fetch)
    {
        database::query($query,$log);

        if($fetch && $rows)
            $return = database::fetch(true);
        else if($fetch && !$rows)
            $return = database::fetch(false);
        else
            $return = database::rows();

        database::free_result();
        return $return;
    }

    return database::query($query,$log);
}

function db_stmt($query,$params=array(),$rows=false,$fetch=false,$log=false)
{
    database::init();
    if($rows || $fetch)
    {
        database::stmt_query($query,$params,$log);

        if($fetch && !$rows)
        {
            $return = database::fetch(false);
            $return = $return[0];
        }
        else
            $return = database::rows();

        database::free_result();
        return $return;
    }

    return database::stmt_query($query,$params);
}

/**
 * Liefert die Anzahl der Zeilen im Ergebnis
 *
 * @return integer
 **/
function _rows($mysqli_result)
{ return database::rows($mysqli_result != null && !empty($mysqli_result) ? $mysqli_result : null); }

/**
 * Liefert einen Datensatz als assoziatives Array
 *
 * @return array
 **/
function _fetch($mysqli_result=null)
{ return database::fetch(false,$mysqli_result != null && !empty($mysqli_result) ? $mysqli_result : null); }

/**
 * Funktion um diverse Dinge aus Tabellen auszaehlen zu lassen
 *
 * @return integer
 **/
function cnt($count, $where = "", $what = "id")
{
    $cnt_sql = db("SELECT COUNT(".$what.") AS num FROM ".$count." ".$where.";");
    if(_rows($cnt_sql))
    {
        $cnt = _fetch($cnt_sql);
        return $cnt['num'];
    }

    return 0;
}

/**
 * Funktion um diverse Dinge aus Tabellen zusammenzaehlen zu lassen
 *
 * @return integer
 **/
function sum($db, $where = "", $what)
{
    $cnt_sql = db("SELECT SUM(".$what.") AS num FROM ".$db.$where.";");
    if(_rows($cnt_sql))
    {
        $cnt = _fetch($cnt_sql);
        return $cnt['num'];
    }

    return 0;
}

/**
 * Funktion um CMS Settings aus der Datenbank auszulesen
 * Updated for DZCP-Extended Edition
 *
 * @return mixed/array
 **/
function settings($what)
{
    if(is_array($what))
    {
        $sql='';
        foreach($what as $qy)
        { $sql .= $qy.", "; }
        $sql = substr($sql, 0, -2); $hash = md5($sql);
        if(!RTBuffer::check($hash)) return RTBuffer::get($hash);
        $get = db("SELECT ".$sql." FROM `".dba::get('settings')."`",false,true);
        RTBuffer::set($hash,$get); return $get;
    }
    else
    {
        $hash = md5('sql_settings_'.$what);
        if(!RTBuffer::check($hash)) return RTBuffer::get($hash);
        $get = db("SELECT ".$what." FROM `".dba::get('settings')."`",false,true);
        RTBuffer::set($hash,$get[$what]); return $get[$what];
    }
}

/**
 * Funktion um die CMS Config aus der Datenbank auszulesen
 * Updated for DZCP-Extended Edition
 *
 * @return mixed/array
 **/
function config($what)
{
    if(is_array($what))
    {
        $sql='';
        foreach($what as $qy) { $sql .= $qy.", "; }
        $sql = substr($sql, 0, -2); $hash = md5($sql);
        if(!RTBuffer::check($hash)) return RTBuffer::get($hash);
        $get = db("SELECT ".$sql." FROM `".dba::get('config')."`",false,true);
        RTBuffer::set($hash,$get); return $get;
    }
    else
    {
        $hash = md5('sql_config_'.$what);
        if(!RTBuffer::check($hash)) return RTBuffer::get($hash);
        $get = db("SELECT ".$what." FROM `".dba::get('config')."`",false,true);
        RTBuffer::set($hash,$get[$what]); return $get[$what];
    }
}

/**
 * Informationen �ber die MySQL-Datenbank abrufen
 *
 * @return resource
 **/
function dbinfo()
{
    $info = array(); $sum = 0; $rows = 0; $entrys = 0;
    $qry = db("Show table status");
    while($data = _fetch($qry))
    {
        $allRows = $data["Rows"];
        $dataLength  = $data["Data_length"];
        $indexLength = $data["Index_length"];

        $tableSum = $dataLength + $indexLength;

        $sum += $tableSum;
        $rows += $allRows;
        $entrys ++;
    } //while end

    $info["entrys"] = $entrys;
    $info["rows"] = $rows;
    $info["size"] = @round($sum/1048576,2);

    return $info;
}

// #######################################
// ################ CLASS ################
// #######################################
class database
{
    private static $mysqli = null;
    private static $sql_query = null;
    private static $insert_id = 0;
    private static $runned = false;
    private static $stmt = null;

    /**
     * Datenbank Connect
     */
    public static final function init()
    {
        global $db_array;

        if(!self::$runned)
        {
            DebugConsole::insert_initialize('database::init()', 'DZCP Database-Core');
            self::$runned = true;
        }

        if(is_resource(self::$mysqli) && self::$mysqli != null) //already connected
            return;

        if($_SESSION['installer'] && !$_SESSION['db_install']) //For Installer
            return;

        if(!empty($db_array['host']) && !empty($db_array['user']) && !empty($db_array['pass']) && !empty($db_array['db']))
        {
            if(!self::$mysqli = mysqli_init())
                die('mysqli_init failed');

            if(!mysqli_options(self::$mysqli, MYSQLI_OPT_CONNECT_TIMEOUT, 5))
                die('Setting MYSQLI_OPT_CONNECT_TIMEOUT failed');

            $use_persistconns = (ini_get('mysqli.allow_persistent') == '0' || ini_get('mysqli.max_persistent') == '0' ? false : runtime_sql_persistconns);
            if (!self::$mysqli=mysqli_connect(($use_persistconns ? 'p:' : '').$db_array['host'], $db_array['user'], $db_array['pass'], $db_array['db']))
                self::print_db_error();
        }
        else
        {
            self::$mysqli = null;
            echo '<html><head><meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" /></head><body><b>';
            if(empty($db_array['host']))
                echo "Das MySQL-Hostname fehlt in der Configuration!<p>";

            if(empty($db_array['user']))
                echo "Der MySQL-Username fehlt in der Configuration!<p>";

            if(empty($db_array['pass']))
                echo "Das MySQL-Passwort fehlt in der Configuration!<p>";

            if(empty($db_array['db']))
                echo "Der MySQL-Datenbankname fehlt in der Configuration!<p>";

            die("Bitte �berpr�fe deine mysql.php!</b></body></html>");
        }
    }

    /**
     * Datenbank Query senden
     * @param string $sql_query
     */
    public static final function query($sql_query='',$log=false)
    {
        global $db_array;
        if(!$_SESSION['installer'] || $_SESSION['db_install']) //For Installer
        {
            if($log || debug_all_sql_querys) DebugConsole::wire_log('debug', 9, 'SQL_Query', $sql_query);
            if(!mysqli_real_query(self::$mysqli, $sql_query))
                DebugConsole::sql_error_handler($sql_query);
            else
            {
                self::$insert_id = mysqli_insert_id(self::$mysqli);
                self::$sql_query = mysqli_store_result(self::$mysqli);
                unset($sql_query);
                return self::$sql_query;
            }
        }
    }

    /**
     * Datenbank Query senden als prepare SQL statement
     * @param string $static_query
     * @param array params
     */
    public static final function stmt_query($query='SELECT * FROM test WHERE name=?',$params=array('si', 'hallo', '4'), $log=false)
    {
        if($log || debug_all_sql_querys) DebugConsole::wire_log('debug', 9, 'SQL_Query', $query);
        if(self::$stmt = mysqli_prepare(self::$mysqli, $query))
        {
            /**
             *  i 	corresponding variable has type integer
             *  d 	corresponding variable has type double
             *  s 	corresponding variable has type string
             *  b 	corresponding variable is a blob and will be sent in packets
             */
            call_user_func_array(array(self::$stmt, 'bind_param'), self::refValues($params));
            mysqli_stmt_execute(self::$stmt);

            $meta = mysqli_stmt_result_metadata(self::$stmt);
            if(!$meta || empty($meta)) { mysqli_stmt_close(self::$stmt); self::close(); return; }
            $row = array(); $parameters = array(); $results = array();
            while ( $field = mysqli_fetch_field($meta) )
            {
                $parameters[] = &$row[$field->name];
            }

            mysqli_stmt_store_result(self::$stmt);
            $results['_stmt_rows_'] = mysqli_stmt_num_rows(self::$stmt);
            call_user_func_array(array(self::$stmt, 'bind_result'), self::refValues($parameters));

            while ( mysqli_stmt_fetch(self::$stmt) )
            {
                $x = array();
                foreach( $row as $key => $val )
                {
                    $x[$key] = $val;
                }

                $results[] = $x;
            }

            self::$sql_query = $results;

            /* close statement */
            self::$insert_id = mysqli_insert_id(self::$mysqli);
            mysqli_stmt_close(self::$stmt);
            self::close();

            return self::$sql_query;
        }

        return false;
    }

    private static final function refValues($arr)
    {
        if (strnatcmp(phpversion(),'5.3') >= 0)
        {
            $refs = array();
            foreach($arr as $key => $value)
                $refs[$key] = &$arr[$key];

            return $refs;
        }

        return $arr;
    }

    /**
     * Datenbankverbindung �bergeben
     */
    public static final function get_insert_id()
    { return self::$insert_id; }

    /**
     * Liefert einen Datensatz als assoziatives Array
     *
     * @param boolean $array
     * @param mysqli_result $result
     */
    public static final function fetch($array=false,$result=null)
    {
        if(is_array($result) || (!empty(self::$sql_query) && count(self::$sql_query) >= 1) && is_array(self::$sql_query))
        {
            if(array_key_exists('_stmt_rows_', is_array($result) ? $result : self::$sql_query))
            {
                if(is_array($result)) unset($result['_stmt_rows_']);
                else unset(self::$sql_query['_stmt_rows_']);
            }

            return is_array($result) ? $result[0] : self::$sql_query;
        }
        else if(empty(self::$sql_query) && !count(self::$sql_query) && is_array(self::$sql_query))
            return false;
        else if(!$_SESSION['installer'] || $_SESSION['db_install'] && (!empty(self::$sql_query) || !empty($result))) //For Installer
        {
            if($array)
                return mysqli_fetch_array(empty($result) || $result == null ? self::$sql_query : $result, MYSQLI_BOTH);
             else
                return mysqli_fetch_assoc(empty($result) || $result == null ? self::$sql_query : $result);
        }

        return false;
    }

    /**
     * Liefert die Anzahl der Zeilen im Ergebnis
     *
     * @param mysqli_result $result
     */
    public static final function rows($result=null)
    {
        if(is_array($result) || is_array(self::$sql_query))
            return is_array($result) ? $result['_stmt_rows_'] : self::$sql_query['_stmt_rows_'];

        if(!$_SESSION['installer'] || $_SESSION['db_install']) //For Installer
            return mysqli_num_rows(empty($result) || $result == null ? self::$sql_query : $result);

        return false;
    }

    /**
     * Gibt den Ergebnisspeicher frei
     */
    public static final function free_result()
    {
        if(self::$sql_query != null && !empty(self::$sql_query))
            mysqli_free_result(self::$sql_query);
    }

    /**
     * Schlie�t die Datenbankverbindung
     */
    public static final function close()
    {
        if(is_resource(self::$mysqli) && self::$mysqli != null)
            mysqli_close(self::$mysqli);
    }

    /**
     * Gibt Datenbank Fehler aus und stoppt die Ausf�hrung des CMS
     **/
    private static final function print_db_error($query=false)
    {
        global $prefix;
        echo '<b>MySQL-Query failed:</b><br /><ul>'.
                '<li><b>ErrorNo</b> = '.mysqli_connect_errno().
                '<li><b>Error</b>   = '.mysqli_connect_error().
                ($query ? '<li><b>Query</b>   = '.$query.'</ul>' : '');
        self::close();
        die();
    }

    /**
     * Gibt die MySQL Version zur�ck
     */
    public static final function version()
    { return mysqli_get_server_info(self::$mysqli); }

    /**
     * Erstellt ein Datenbank Backup
     *
     * @return string
     */
    public static final function backup()
    {
        global $db_array;
        $backup_table_data = array();

        //Table Drop
        self::query('SHOW TABLE STATUS');
        while($table = self::fetch())
        { $backup_table_data[$table['Name']]['drop'] = 'DROP TABLE IF EXISTS `'.$table['Name'].'`;'; }
        unset($table);

        //Table Create
        foreach($backup_table_data as $table => $null)
        {
            unset($null); self::query('SHOW CREATE TABLE '.$table.';');
            while($table = self::fetch())
            { $backup_table_data[$table['Table']]['create'] = $table['Create Table'].';'; }
        }
        unset($table);

        //Cleanup
        foreach($backup_table_data as $table => $null)
        {
            unset($null);
            if($table == dba::get('cache')) //Clear Cache
                self::query('TRUNCATE TABLE `'.dba::get('cache').'`');
        }
        unset($table);

        //Insert Create
        foreach($backup_table_data as $table => $null)
        {
            unset($null); $backup = '';
            self::query('SELECT * FROM '.$table.' ;');
            while($dt = self::fetch())
            {
                if(!empty($dt))
                {
                    $backup_data = '';
                    foreach ($dt as $key => $var)
                    { $backup_data .= "`".$key."` = '".convert::ToString(str_replace("'", "`", $var))."',"; }

                    $backup .= "INSERT INTO `".$table."` SET ".substr($backup_data, 0, -1).";\r\n";
                    unset($backup_data);
                }
            }

            $backup_table_data[$table]['insert'] = $backup;
            unset($backup);
        }
        unset($table);

        $sql_backup =  "-- -------------------------------------------------------------------\r\n";
        $sql_backup .= "-- Datenbank Backup von deV!L`z Clanportal v."._version." "._edition."\r\n";
        $sql_backup .= "-- Build: "._release." * "._build."\r\n";
        $sql_backup .= "-- Host: ".$db_array['host']."\r\n";
        $sql_backup .= "-- Erstellt am: ".date("d.m.Y")." um ".date("H:i")."\r\n";
        $sql_backup .= "-- MySQL-Version: ".mysqli_get_server_info(self::$mysqli)."\r\n";
        $sql_backup .= "-- PHP Version: ".phpversion()."\r\n";
        $sql_backup .= "-- -------------------------------------------------------------------\r\n\r\n";
        $sql_backup .= "--\r\n-- Datenbank: `".$db_array['db']."`\r\n--\n\n";
        $sql_backup .= "-- -------------------------------------------------------------------\r\n";
        foreach($backup_table_data as $table => $data)
        {
            $sql_backup .= "\r\n--\r\n-- Tabellenstruktur: `".$table."`\r\n--\r\n\r\n";
            $sql_backup .= $data['drop']."\r\n";
            $sql_backup .= $data['create']."\r\n";

            if(!empty($data['insert']))
            {
                $sql_backup .= "\r\n--\r\n-- Datenstruktur: `".$table."`\r\n--\r\n\r\n";
                $sql_backup .= $data['insert']."\r\n";
            }
        }

        unset($data);
        return $sql_backup;
    }
}

############# DBA #############
class dba
{
    private static $dba = array();

    public static final function init()
    {
        global $db_array;
        foreach ($db_array as $dba_key => $dba_val)
        {
            if($dba_key == 'host' || $dba_key == 'user' || $dba_key == 'pass' || $dba_key == 'db' || $dba_key == 'prefix')
                continue;

            self::$dba[$dba_key] = $db_array['prefix'].$dba_val; // Add prefix
        }
    }

    public static function get($tag = '')
    {
        if(empty($tag) || $tag == false || !array_key_exists($tag, self::$dba))
            return '';
        else
            return self::$dba[$tag];
    }

    public static function set($tag = '', $table = '')
    {
        global $db_array;

        if(array_key_exists($tag, self::$dba))
            return false;

        self::$dba[$tag] = $db_array['prefix'].$table; // Add prefix
        return true;
    }

    //array(array('test' => 'test123'),array('dl' => 'downloads'));
    public static function set_array($array = array())
    {
        global $db_array;

        if(!is_array($array) || !count($array))
            return false;

        $i=0;
        foreach($array as $dba_key => $dba_val)
        {
            if(array_key_exists($dba_key, self::$dba)) continue;
            self::$dba[$dba_key] = $db_array['prefix'].$dba_val; // Add prefix
            $i++;
        }

        return $i >= 1 ? true : false;
    }

    public static function replace($tag = '', $new_table = '')
    {
        global $db_array;

        if(!array_key_exists($tag, self::$dba))
            return false;

        self::$dba[$tag] = $db_array['prefix'].$new_table; // Add prefix
        return true;
    }
}

dba::init(); //Run DBA
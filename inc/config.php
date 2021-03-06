<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

#########################################
//-> DZCP Settings Start
#########################################

date_default_timezone_set("Europe/Berlin"); //Zeitzone

define('is_debug', false); // Schaltet den Debug Modus ein, zeigt alle Fehler und Notices etc.
define('cache_in_debug', true); // Entscheidet ob im Debug, Seiten gecached werden k�nnen
define('show_debug_console', true); //Zeigt die Debug Console
define('save_debug_console', false); //Speichert alle in der Debug Console ausgegebenen Texte in eine Log Datei

define('buffer_gzip_compress', true); // Seite mit Hilfe der GZIP-Komprimierung �bertragen
define('buffer_gzip_compress_level', 2); // Level der Kompression 1 - 9 *Optimal Level 2

define('dzcp_version_checker', true); // Version auf DZCP.de abgleichen und benachrichtigen ob eine neue Version zur Verf�gung steht
define('dzcp_version_checker_refresh', (30*60)); // Wie lange soll gewartet werden um einen Versionsabgleich auszuf�hren

define('xfire_enable', true); // XFire Status anzeigen
define('xfire_preloader', true); // XFire Profil per AJAX laden
define('xfire_skin', 'shadow'); // Skin von XFire: shadow,kampf,scifi,fantasy,wow,default
define('xfire_refresh', (10*60)); // Wann soll das Profilbild aktualisiert werden

define('glossar_enabled', true); // Schaltet die Glossar Funktion bei W�rtern an oder aus
define('AjaxLoad', true); // Men�s per Ajax laden

define('modapi_enabled', true); // DZCP Addons-API erlauben
define('modapi_events_enabled', true); // DZCP Addons-API Events ausf�hren

define('allow_ftp_support', true); // DZCP eigener FTP Support
define('allow_fsockopen', true); // fsockopen support
define('zend_support', false); // Zend Framework Support
define('allow_additional', true); // additional functions,etc erlauben
define('cache_thumbgen', true); // zwischenspeichern der Thumbgen generierten Bildern
define('use_curl', true); // Verwendet die CURL PHP Erweiterung, anstelle von file_get_contents() f�r externe Zugriffe, wenn vorhanden.
define('use_mod_rewrite',true); // Erlaubt dem CMS Mod Rewrite zu benutzen
define('use_trash_mails', false); // Erlaubt Trashmail Server als E-Mail Adresse.
define('trash_mail_url', 'http://www.mogelmail.de/mogelmails.xml'); // Liste der Trashmail Server * XML Format

define('extern_urls_detect', false); //DZCP erkennt selbst�ndig externe URLs und �ffnet sie in einen neuen Tab, Vorrausetzung ist das die Domain im Impressum richtig eingetragen wurde.
define('use_html5_worker', true); //Verwendet f�r AJAX Requests die neuen HTML5 Web Worker *So fern im Zielbrowser unterst�tzt* um Seiten und Inhalte Parallel zu laden.

define('steam_enable', true); // Steam Status anzeigen
define('steam_avatar_cache', true); // Steam Useravatare f�r schnellen Zugriff speichern
define('steam_avatar_refresh', (10*60)); // Wann soll das Avatarbild aktualisiert werden
define('steam_refresh', (5*60)); // Wann soll der Steam Status in der Userliste aktualisiert werden
define('steam_api_refresh', 30); // Wann sollen die Daten der Steam API aktualisiert werden * Online / Offline / In-Game Status
define('steam_infos_cache', true); //Sollen die Profil Daten zwischen gespeichert werden, * Cache Use
define('steam_infos_refresh', (60*60)); // Wann sollen die Profil Daten aktualisiert werden * Steam User-Profil * 1 Mal in der Stunde
define('steam_infos_game_refresh', (60*60)); // Wann sollen die Profil Daten aktualisiert werden * Steam User-Profil * 1 Mal in der Stunde

//Skype
define('skype_enable', true);
define('skype_preloader', true);
define('skype_refresh', (5*60));

//XBox
define('xbox_enable', true);
define('xbox_infos_cache', true); //Sollen die Profil Daten zwischen gespeichert werden?
define('xbox_infos_acheivements_refresh', (60*60));
define('xbox_infos_friends_refresh', (60*60));
define('xbox_infos_games_refresh', (60*60));
define('xbox_infos_profile_refresh', (60*60));

define('psn_enable', true);
define('origin_enable', true);
define('bnet_enable', true);

define('salt', true); // Salt f�r die Passwort Codierung, *Der Salt darf nach der Installation nicht mehr ge�ndert werden.
// Die Datei inc/mysql_salt.php muss gesichert werden *Backup* !

/*
* Wenn Imagick 'nicht' verwendet wird, muss bei gro�en Bildern auf die PHP Einstellung "memory_limit" geachtet werden.
* Sollte diese zu klein sein, werden mache Vorschaubilder nicht generiert.
* Imagick ist bei gro�en Bildern deutlich schneller als die PHP GD Erweiterung.
*/
define('use_imagick', true); // Verwendet die Imagick PHP Erweiterung um Vorschaubilder zu erstellen, wenn vorhanden.

//Diverse Zeiten
define('count_clicks_expires', (24*60*60)); // Wie Lange die IPs f�r den Click-Counter gespeichert bleiben.
define('cookie_expires', (60*60*24*30*12)); // Wie Lange die Cookies des CMS ihre G�ltigkeit behalten.
define('users_online', (15*60)); // Wie Lange ein User unt�tig sein muss, um als Offline zu gelten.
define('counter_reload', (24*3600)); // Ab wann der Besucherz�hler eine aktualisierung durchf�hrt * User basierend *
define('rss_cache_public_news', (15*60)); // Wann soll der Public RSS Feed aktualisiert werden.
define('rss_cache_private_news', (5*60)); // Wann soll der Interne RSS Feed aktualisiert werden.
define('check_msg_email', (5*60)); // Wann soll �berpr�ft werden, ob ein User eine neue Nachricht hat * E-Mail Senden *
define('shoutbox_refresh', (10)); // Wann soll die Shoutbox aktualisiert werden *

/*
 * DZCP - Extended Protect * Bug! *
 * Diese Einstellungen regeln wann eine IP automatisch gesperrt wird, um die Sicherheit des CMS und der User zu gew�hrleisten.
 */
define('max_protect_users', 5); //Die Maximale Anzahl der Usernamen die w�hrend der Zeit des "max_protect_cache_time" verwendet werden d�rfen.
define('max_protect_time_diff', 0.5); //Die Zeit die vergehen muss, zwischen jedem einzelnen Login versuch.
define('max_protect_cache_time', 60); //Die Zeit die der Index im Cache verbleibt.

define('use_dzcp_protect', false); //Ob DZCP - Extended Protect verwendet werden soll. * DEV *
define('use_protect_block_timer', true); //User blockieren die zu oft in k�rzester Zeit versuchen sich anzumelden. "max_protect_time_diff"
define('use_protect_block_user', true); //User blockieren die versuchen Usernamen zu erraten. "max_protect_users" abh�ngig von "max_protect_cache_time"

/* DZCP - Captcha */
define('captcha_case_sensitive', false); //Unterscheidet Gro� und Kleinschreibung beim Captcha
define('captcha_mathematic', false); //Stellt den Usern einfache Rechenaufgaben anstelle eines Captcha Codes

/* DZCP - Sessions
* Entscheidet wie die PHP-Sessions gespeichert werden, es kann, PHP Standard, MySQL Datenbank, Memcache oder APC verendet werden.
* PHP Standard 'php': Funktioniert im Normalfall �berall, hat keine weiteren Vorteile, wird in der Standard Konfiguration von PHP auf die Festplatte des Servers geschrieben.
*
* MySQL Datenbank 'mysql': Speichert die Sessions in der DZCPEE Eigenen Datenbank.
* 	Pro: Hohe Performance und Datensicherheit.
* 	Contra: Zus�tzliche Datenbankbelastung.
*
* Alternative PHP Cache 'apc': Speichert die Sessions im Server Cache.
* 	Pro: Sehr hohe Performance und niedrige Datenbankbelastung.
* 	Contra: Niedrigere Datensicherheit.
*
* Memcache 'memcache': Speichert die Sessions auf einem entfernten Memcache Server.
* 	Pro: Sehr hohe Performance, niedrige Datenbankbelastung und Clustern der Server ist m�glich,
* 	Contra: Niedrigere Datensicherheit, zus�tzlicher Netzwerkzugriff, eine extra Serveranwendung ist n�tig 'Memcache Server'.
*/
define('sessions_backend', 'php'); //php,mysql,memcache,apc
define('sessions_encode_type', 'sha1');
define('sessions_encode', true);
define('sessions_ttl_maxtime', (2*60*60)); //Live-Time 2h
define('sessions_memcache_host', 'localhost');
define('sessions_memcache_port', 11211);

/*
 * Speichert bestimmte SQL Abfragen und Datenlisten f�r etwa 1 Sekunde in der PHP Laufzeit.
 * Reduziert Abfragen an die MySQL Datenbank und Festplattenzugriffe zbs. d�rch Datenlisten etc.
 * Sollte nur auf false gestellt werden wenn es umbedingt n�tig ist.
 */
define('runtime_buffer', true);

/*
 * Speichert bestimmte PHP Daten im Cache zwischen.
* Sollte nur verwendet werden, wenn APC oder Memchache verwendet wird.
*/
define('file_to_cache', true);
define('file_to_cache_refresh', (90*60));

/*
 * Bitte vor der Aktivierung der Persistent Connections lesen:
 * http://php.net/manual/de/features.persistent-connections.php
 * Beschleunigt die mySQL-Abfragen
 */
define('runtime_sql_persistconns', false);

## Copyrightlinks ##
$cp_color = '#d3d3d3'; //Hex Farbcode der Hintergrundfarbe Copyrightlinks am Ende der Homepage

// DSL Geschwindigkeiten f�r errechnen der Download Zeiten * Es kann hier einfach erweitert werden, es wird automatisch auf der Seite eingef�gt *
$dsl_formats = array("DSL 1000"=>1024, "DSL 2000"=>2048, "DSL 6000"=>6144, "DSL2+ 16000"=>16384, "VDSL 25.000"=>25600, "VDSL 50.000"=>51200);

// Zeichen f�r den Passwort Generator:
//                           Alphabet gro�:                Alphabet klein:                Zahlen:        Sonderzeichen:
$passwordComponents = array("ABCDEFGHIJKLMNOPQRSTUVWXYZ" , "abcdefghijklmnopqrstuvwxyz" , "0123456789" , "#$@!");

$AjaxLoad_blacklist = array('welcome'=>false,'server'=>false,'teamspeak'=>false,'infos'=>false); // Ajax Preload gesperrte Men�s *Nur �ndern wenn n�tig!
$picformat = array('jpg', 'jpeg', 'gif', 'png'); // Unterst�tzte Bildformate

## Downloads Filesize Extended START ##
/*
 * Wenn aktiviert, wird das Hostsystem des Servers verwendet *Linux / Windows* um die Datengr��en der Downloads zu berechnen.
 * Das ist n�tig, wenn Ihr Downloads habt die �ber 2GB hinausgehen. Daten die 4GB oder Gr��er sind, ist ein 64-Bit System notwendig.
 * Hinweis: PHP muss einen OS-Shell Zugriff haben und das ausf�hren von Befehlen auf dem Hostsystem zulassen. *exec() or *shell_exec()
 * Achtung: Bitte nur aktivieren wenn es ben�tigt wird.
 */
define('allow_os_shell', false);
## Downloads Filesize Extended END ##

define('server_show_empty_players', false); //Alle Spieler anzeigen, die deren Namen nicht angezeigt werden k�nnen, User werden sonst entfernt.
define('cache_md5_file_check', true); //Pr�ft ob eine PHP Datei ver�ndert wurde und aktualisiert den Cache automatisch.

#########################################
//-> DZCP Settings Ende
#########################################

if(function_exists("date_default_timezone_set") and function_exists("date_default_timezone_get"))
    @date_default_timezone_set(@date_default_timezone_get());

## REQUIRES ##
if(file_exists(basePath."/inc/mysql.php") && file_exists(basePath."/inc/mysql_salt.php"))
{
    require_once(basePath."/inc/mysql.php");
    require_once(basePath."/inc/mysql_salt.php");
}
else
    { $sql_host = ''; $sql_user = ''; $sql_pass = ''; $sql_db = ''; $sql_prefix = ''; $sql_salt = ''; $sql_engine  = '';}

$db_array = array('host' => $sql_host, 'user' => $sql_user, 'pass' => $sql_pass, 'db' => $sql_db, 'prefix' => $sql_prefix, 'engine' => $sql_engine);

//-> MySQL-Datenbankangaben
//          [TAG]                [TABELLE]              [NAME + PREFIX]
$db_array['artikel']          = 'artikel';             # dzcp_artikel
$db_array['addons']           = 'addons';              # dzcp_addons
$db_array['acomments']        = 'acomments';           # dzcp_acomments
$db_array['awards']           = 'awards';              # dzcp_awards
$db_array['away']             = 'away';                # dzcp_away
$db_array['buddys']           = 'userbuddys';          # dzcp_userbuddys
$db_array['ipcheck']          = 'ipcheck';             # dzcp_ipcheck
$db_array['clankasse']        = 'clankasse';           # dzcp_clankasse
$db_array['c_kats']           = 'clankasse_kats';      # dzcp_clankasse_kats
$db_array['c_payed']          = 'clankasse_payed';     # dzcp_clankasse_payed
$db_array['counter']          = 'counter';             # dzcp_counter
$db_array['c_ips']            = 'counter_ips';         # dzcp_counter_ips
$db_array['c_who']            = 'counter_whoison';     # dzcp_counter_whoison
$db_array['cw']               = 'clanwars';            # dzcp_clanwars
$db_array['cw_comments']      = 'cw_comments';         # dzcp_cw_comments
$db_array['cw_player']        = 'clanwar_players';     # dzcp_clanwar_players
$db_array['clicks_ips']       = 'clicks_ips';          # dzcp_clicks_ips
$db_array['cache']            = 'cache';               # dzcp_cache
$db_array['captcha']          = 'captcha';             # dzcp_captcha
$db_array['downloads']        = 'downloads';           # dzcp_downloads
$db_array['dl_kat']           = 'download_kat';        # dzcp_download_kat
$db_array['dl_comments']      = 'dlcomments';          # dzcp_dlcomments
$db_array['events']           = 'events';              # dzcp_events
$db_array['f_access']         = 'f_access';            # dzcp_f_access
$db_array['f_abo']            = 'f_abo';               # dzcp_f_abo
$db_array['f_kats']           = 'forumkats';           # dzcp_forumkats
$db_array['f_posts']          = 'forumposts';          # dzcp_forumposts
$db_array['f_skats']          = 'forumsubkats';        # dzcp_forumsubkats
$db_array['f_threads']        = 'forumthreads';        # dzcp_forumthreads
$db_array['gallery']          = 'gallery';             # dzcp_gallery
$db_array['gb']               = 'gb';                  # dzcp_gb
$db_array['gb_comments']      = 'gb_comments';         # dzcp_gb_comments
$db_array['glossar']          = 'glossar';             # dzcp_glossar
$db_array['geo']              = 'geometry';            # dzcp_geometry
$db_array['ipban']            = 'ipban';               # dzcp_ipban
$db_array['links']            = 'links';               # dzcp_links
$db_array['linkus']           = 'linkus';              # dzcp_linkus
$db_array['msg']              = 'messages';            # dzcp_messages
$db_array['news']             = 'news';                # dzcp_news
$db_array['navi']             = 'navi';                # dzcp_navi
$db_array['navi_kats']        = 'navi_kats';           # dzcp_navi_kats
$db_array['newscomments']     = 'newscomments';        # dzcp_newscomments
$db_array['newskat']          = 'newskat';             # dzcp_newskat
$db_array['partners']         = 'partners';            # dzcp_partners
$db_array['permissions']      = 'permissions';         # dzcp_permissions
$db_array['pos']              = 'positions';           # dzcp_positions
$db_array['profile']          = 'profile';             # dzcp_profile
$db_array['rankings']         = 'rankings';            # dzcp_rankings
$db_array['rss']              = 'rss_config';          # dzcp_rss_config
$db_array['server']           = 'server';              # dzcp_server
$db_array['settings']         = 'settings';            # dzcp_settings
$db_array['slideshow']        = 'slideshow';           # dzcp_slideshow
$db_array['shout']            = 'shoutbox';            # dzcp_shoutbox
$db_array['sites']            = 'sites';               # dzcp_sites
$db_array['squads']           = 'squads';              # dzcp_squads
$db_array['squaduser']        = 'squaduser';           # dzcp_squaduser
$db_array['sponsoren']        = 'sponsoren';           # dzcp_sponsoren
$db_array['startpage']        = 'startpage';           # dzcp_startpage
$db_array['sessions']         = 'sessions';            # dzcp_sessions
$db_array['ts']               = 'teamspeak';           # dzcp_teamspeak
$db_array['users']            = 'users';               # dzcp_users
$db_array['usergallery']      = 'usergallery';         # dzcp_usergallery
$db_array['usergb']           = 'usergb';              # dzcp_usergb
$db_array['userpos']          = 'userposis';           # dzcp_userposis
$db_array['userstats']        = 'userstats';           # dzcp_userstats
$db_array['votes']            = 'votes';               # dzcp_votes
$db_array['vote_results']     = 'vote_results';        # dzcp_vote_results
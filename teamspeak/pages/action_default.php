﻿<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

####################################
## Wird in einer Index ausgeführt ##
####################################
if (!defined('IS_DZCP'))
    exit();

if (_version < '1.0') //Mindest Version pruefen
    $index = _version_for_page_outofdate;
else
{
    if(fsockopen_support())
    {
        $qry = db("SELECT id,default_server FROM ".dba::get('ts')." ORDER BY `default_server` DESC");
        if(_rows($qry))
        {
            while($get = _fetch($qry))
            {
                $show_id = 0;
                if(isset($_GET['show'])) $show_id = convert::ToInt($_GET['show']);
                else if($get['default_server'] != 0) $show_id = $get['id'];
                $url = '../teamspeak/?action=ajax&show='.$show_id.'&sID='.$get['id'];
                $index .= '<tr><td class="contentMainTop">
                <div id="PageTeamspeak_'.$get['id'].'"><div style="width:100%; 0;text-align:center"><img src="../inc/images/ajax-loader-bar.gif" alt="" /></div>
                <script language="javascript" type="text/javascript">DZCP.initPageDynLoader(\'PageTeamspeak_'.$get['id'].'\',\''.$url.'\');</script></div></tr>';
            }

            $index = show($dir."/teamspeak", array("servers" => $index));
        }
        else
            $index = error('<br /><center>'._no_ts_page.'</center><br />',1);
    }
    else
        $index = error(_fopen,1);
}
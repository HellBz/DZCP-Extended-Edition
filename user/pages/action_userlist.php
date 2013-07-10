<?php
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
    ################
    ## Userliste  ##
    ################
    $where = _site_ulist;
    $maxuserlist = config('m_userlist');
    $search = (isset($_GET['search']) && !empty($_GET['search']) ? $_GET['search'] : _nick);

    if($chkMe == 4 || permission('editusers') || permission('activateusers'))
        $sql_filter = "level != '0' OR ( level = '0' AND actkey IS NOT NULL )";
    else
        $sql_filter = "level != '0'";

    switch(isset($_GET['show']) ? $_GET['show'] : '')
    {
        case 'search': $qry = " WHERE nick LIKE '%".$search."%' AND ".$sql_filter." LIMIT ".($page - 1)*$maxuserlist.",".$maxuserlist; break;
        case 'newreg': $qry = " WHERE regdatum > '".$_SESSION['lastvisit']."' AND ".$sql_filter." ORDER BY regdatum DESC LIMIT ".($page - 1)*$maxuserlist.",".$maxuserlist; break;
        case 'lastlogin': $qry = " WHERE ".$sql_filter." ORDER BY time DESC LIMIT ".($page - 1)*$maxuserlist.",".$maxuserlist; break;
        case 'lastreg': $qry = " WHERE ".$sql_filter." ORDER BY regdatum DESC LIMIT ".($page - 1)*$maxuserlist.",".$maxuserlist; break;
        case 'online': $qry = " WHERE ".$sql_filter." ORDER BY time DESC LIMIT ".($page - 1)*$maxuserlist.",".$maxuserlist; break;
        case 'country': $qry = " WHERE ".$sql_filter." ORDER BY country LIMIT ".($page - 1)*$maxuserlist.",".$maxuserlist; break;
        case 'sex': $qry = " WHERE ".$sql_filter." ORDER BY sex DESC LIMIT ".($page - 1)*$maxuserlist.",".$maxuserlist; break;
        case 'banned': $qry = " WHERE ".$sql_filter." AND actkey IS NULL ORDER BY id LIMIT ".($page - 1)*$maxuserlist.",".$maxuserlist; break;
        default: $qry = " WHERE ".$sql_filter." ORDER BY level DESC LIMIT ".($page - 1)*$maxuserlist.",".$maxuserlist; break;
    }

    $entrys = cnt(dba::get('users'),$qry);
    if(!_rows(($qry = db("SELECT id,nick,level,email,hp,xfire,bday,sex,icq,status,position,regdatum FROM ".dba::get('users').$qry))))
        $userliste = show(_no_entrys_yet_all, array("colspan" => "12"));
    else
    {
        $userliste = '';
        while($get = _fetch($qry))
        {
            $hp = (empty($get['hp']) ? '-' : show(_hpicon, array("hp" => $get['hp'])));
            $sex = ($get['sex'] == 1 ? _maleicon : ($get['sex'] == 2 ? _femaleicon : "-"));
            $getstatus = ($get['status'] ? _aktiv_icon : _inaktiv_icon);
            $status = (data($get['id'], "level") > 1 ? $getstatus : ''); $color = 1;
            $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;

            $edit = ""; $delete = "";
            if(permission("editusers"))
            {
                $edit = str_replace("&amp;id=","",show("page/button_edit", array("id" => "", "action" => "action=admin&amp;edit=".$get['id'], "title" => _button_title_edit)));
                $delete = show("page/button_delete", array("id" => $get['id'], "action" => "action=admin&amp;do=delete", "title" => _button_title_del));
            }

            $icq = "-";
            if(!empty($get['icq']))
                $icq = '<a href="http://www.icq.com/whitepages/about_me.php?uin='.$get['icq'].'" target="_blank">'.show(_icqstatus, array("uin" => $get['icq'])).'</a>';

            $xfire = '-';
            if(!empty($get['xfire']))
            $xfire = '<div id="infoXfire_'.string::decode($get['xfire']).'">
            <div style="width:100%;text-align:center"><img src="../inc/images/ajax-loader-mini.gif" alt="" /></div>
            <script language="javascript" type="text/javascript">DZCP.initDynLoader("infoXfire_'.string::decode($get['xfire']).'","xfire","&username='.string::decode($get['xfire']).'");</script></div>';

            $userliste .= show($dir."/userliste_show", array("nick" => autor($get['id'],'','',10),
                                                             "level" => getrank($get['id']),
                                                             "status" => $status,
                                                             "age" => getAge($get['bday']),
                                                             "mf" => $sex,
                                                             "edit" => $edit,
                                                             "delete" => $delete,
                                                             "class" => $class,
                                                             "icq" => $icq,
                                                             "icquin" => $get['icq'],
                                                             "onoff" => onlinecheck($get['id']),
                                                             "hp" => $hp,
                                                             "xfire" => $xfire));
        } //while end
    }

    $edel = (permission("editusers") ? '<td class="contentMainTop" colspan="2">&nbsp;</td>' : '');
    $seiten = nav($entrys,$maxuserlist,"?action=userlist&show=".(isset($_GET['show']) ? $_GET['show'] : 1));
    $show_entrys = show(_userlist_counts, array("cnt_full" => $entrys." ".( $entrys >= 2 ? _users : _user ), "cnt" => _rows($qry)));
    $index = show($dir."/userliste", array("show_entrys" => $show_entrys, "edel" => $edel, "search" => $search, "nav" => $seiten, "show" => $userliste)); //Index Out
}

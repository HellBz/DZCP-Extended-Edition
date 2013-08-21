<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if(_adminMenu != 'true') exit();

        if(isset($_GET['page'])) $page = $_GET['page'];
        else $page = 1;

if(is_numeric($_GET['squad']))	{
$whereqry = ' WHERE squad_id = '.$_GET['squad'].' ';
}

        $qry = db("SELECT * FROM ".dba::get('cw')." ".$whereqry."
ORDER BY datum DESC
LIMIT ".($page - 1)*$maxadmincw.",".$maxadmincw."");
        $entrys = cnt(dba::get('cw'));
         $squads .= show(_cw_edit_select_field_squads, array("name" => _all,
"sel" => "",
"id" => "?admin=cw"));

$qrys = db("SELECT * FROM ".dba::get('squads')."
WHERE status = '1'
ORDER BY game ASC");

        while($gets = _fetch($qrys))
        {
if($gets['id'] == $_GET['squad']) { $sel = ' class="dropdownKat"'; } else { $sel = ""; }

          $squads .= show(_cw_edit_select_field_squads, array("name" => string::decode($gets['name']),
"sel" => $sel,
"id" => "?admin=cw&amp;squad=".$gets['id'].""));
        }
        while($get = _fetch($qry))
        {
          $top = empty($get['top'])
               ? '<a href="?admin=cw&amp;do=top&amp;set=1&amp;id='.$get['id'].'"><img src="../inc/images/no.gif" alt="" title="'._cw_admin_top_set.'" /></a>'
               : '<a href="?admin=cw&amp;do=top&amp;set=0&amp;id='.$get['id'].'"><img src="../inc/images/yes.gif" alt="" title="'._cw_admin_top_unset.'" /></a>';

          $edit = show("page/button_edit_single", array("id" => $get['id'],
                                                        "action" => "admin=cw&amp;do=edit",
                                                        "title" => _button_title_edit));
          $delete = show("page/button_delete_single", array("id" => $get['id'],
                                                            "action" => "admin=cw&amp;do=delete",
                                                            "title" => _button_title_del,
                                                            "del" => _confirm_del_cw));

          $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
          $show_ .= show($dir."/clanwars_show", array("class" => $class,
                                                      "cw" => string::decode($get['clantag'])." - ".string::decode($get['gegner']),
                                                      "datum" => date("d.m.Y H:i",$get['datum'])._uhr,
                                                      "top" => $top,
                                                      "id" => $get['id'],
                                                      "edit" => $edit,
                                                      "delete" => $delete
                                                      ));
        }

        $show = show($dir."/clanwars", array("head" => _clanwars,
                                             "add" => _cw_admin_head,
                                             "date" => _datum,
                                             "titel" => _opponent,
                                              "squads" => $squads,
"what" => _filter,
"sort" => _ulist_sort,
                                             "show" => $show_,
                                             "navi" => nav($entrys,$maxadmincw,"?admin=cw&amp;squad=".$_GET['squad']."")
                                             ));
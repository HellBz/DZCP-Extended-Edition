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
    $maxawards = config('m_awards');
    $qry = db("SELECT * FROM ".dba::get('awards')." ORDER BY pos");
    while($get = _fetch($qry))
    {
        if(!empty($_GET['showsquad']) || !empty($_GET['show']))
        {
            if($_GET['showsquad'] == $get['id'] || $_GET['show'] == $get['id'])
            {
                $shown = show(_klapptext_show, array("id" => $get['id']));
                $display = "";
            } else {
                $shown = show(_klapptext_dont_show, array("id" => $get['id']));
                $display = "none";
            }
        } else {
            $shown = show(_klapptext_dont_show, array("id" => $get['id']));
            $display = "none";
        }

        $squad = show(_member_squad_squadlink, array("squad" => string::decode($get['name']),
                "id" => $get['id'],
                "shown" => $shown));

        $img = show(_gameicon, array("icon" => $get['icon']));

        $qrym = db("SELECT s1.id,s1.squad,s1.date,s1.place,s1.prize,s1.url,s1.event,s2.icon,s2.name
                FROM ".dba::get('awards')." AS s1
                LEFT JOIN ".dba::get('awards')." AS s2 ON s1.squad = s2.id
                WHERE s1.squad='".$get['id']."'
                ORDER BY s1.date DESC
                    LIMIT ".$maxawards."");

        $entrys = cnt(dba::get('awards'), " WHERE squad = ".$get['id']);
        $i = $entrys-($page - 1)*$maxawards;

        $awards = "";
        while($getm = _fetch($qrym))
        {
            $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;

            if($getm['place'] == "1") $replace = _awards_erster_img;
            elseif($getm['place'] == "2") $replace = _awards_zweiter_img;
            elseif($getm['place'] == "3") $replace = _awards_dritter_img;
            else $replace = $getm['place'];

            $event = show(_awards_event, array("event" => string::decode($getm['event']),
                    "url" => $getm['url']));

            $awards .= show($dir."/awards_show", array("class" => $class,
                    "date" => date("d.m.Y", $getm['date']),
                    "place" => $replace,
                    "prize" => string::decode($getm['prize']),
                    "event" => $event));
        }

        if(cnt(dba::get('awards'), " WHERE squad = ".$get['id']) > $maxawards)
            $show_all = show(_list_all_link, array("id" => $get['id']));
        else
            $show_all = "";

        $showawards = show($dir."/awards", array("squad" => _awards_head_squad,
                "date" => _awards_head_date,
                "place" => _awards_head_place,
                "prize" => _awards_head_prize,
                "url" => _awards_head_link,
                "awards" => $awards,
                "show_all" => $show_all));

        if(cnt(dba::get('awards'), " WHERE squad = ".$get['id']) != 0)
        {
            $show .= show($dir."/squads_show", array("id" => $get['id'],
                    "shown" => $shown,
                    "display" => $display,
                    "awards" => $showawards,
                    "squad" => $squad." (".cnt(dba::get('awards'), " WHERE squad = ".$get['id']).")",
                    "img" => $img));
        }
    }

    $qry = db("SELECT game,icon FROM ".dba::get('awards')."
             GROUP BY game
             ORDER BY game ASC");
    while($get = _fetch($qry))
    {
        $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
        $img = squad($get['icon']);
        $legende .= show(_awards_legende, array("game" => string::decode($get['game']),
                "img" => $img,
                "class" => $class));
    }

    $legende = show($dir."/legende", array("legende_head" => _awards_head_legende,
            "legende" => $legende));

    $anz_awards = cnt(dba::get('awards'));
    $anz_place_1 = cnt(dba::get('awards'), " WHERE place = 1 ");
    $anz_place_2 = cnt(dba::get('awards'), " WHERE place = 2 ");
    $anz_place_3 = cnt(dba::get('awards'), " WHERE place = 3 ");

    $place1_percent = @round($anz_place_1*100/$anz_awards, 1);
    $place2_percent = @round($anz_place_2*100/$anz_awards, 1);
    $place3_percent = @round($anz_place_3*100/$anz_awards, 1);
    $place1_rawpercent = @round($anz_place_1*100/$anz_awards, 0);
    $place2_rawpercent = @round($anz_place_2*100/$anz_awards, 0);
    $place3_rawpercent = @round($anz_place_3*100/$anz_awards, 0);

    if($anz_place_1 != "0")
    {
        $place1_balken = show(_votes_balken, array("width" => $place1_rawpercent));
    } else {
        $place1_balken = $place1_balken = show(_votes_balken, array("width" => 1));
    }
    if($anz_place_2 != "0")
    {
        $place2_balken = show(_votes_balken, array("width" => $place2_rawpercent));
    } else {
        $place2_balken = show(_votes_balken, array("width" => 1));
    }
    if($anz_place_3 != "0")
    {
        $place3_balken = show(_votes_balken, array("width" => $place3_rawpercent));
    } else {
        $place3_balken = show(_votes_balken, array("width" => 1));
    }

    $anz_awards_out = show(_awards_stats, array("anz" => $anz_awards));
    $anz_place_1_out = show(_awards_stats_1, array("anz" => $anz_place_1));
    $anz_place_2_out = show(_awards_stats_2, array("anz" => $anz_place_2));
    $anz_place_3_out = show(_awards_stats_3, array("anz" => $anz_place_3));

    $stats = show($dir."/stats", array("head_stats" => _head_stats,
            "stats" => $anz_awards_out,
            "icon_1" => _awards_erster_img,
            "icon_2" => _awards_zweiter_img,
            "icon_3" => _awards_dritter_img,
            "stats1" => $anz_place_1_out,
            "stats2" => $anz_place_2_out,
            "stats3" => $anz_place_3_out,
            "1_perc" => $place1_percent,
            "2_perc" => $place2_percent,
            "3_perc" => $place3_percent,
            "1_balken" => $place1_balken,
            "2_balken" => $place2_balken,
            "3_balken" => $place3_balken));

    if(cnt(dba::get('awards')) != 0) $show_ = $show;
    else $show_ = show(_no_entrys_yet, array("colspan" => "10"));

    $index = show($dir."/main", array("head" => _awards_head,
            "stats" => $stats,
            "legende" => $legende,
            "show" => $show_));
}
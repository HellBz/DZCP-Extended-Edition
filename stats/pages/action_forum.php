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
    $allthreads = cnt($db['f_threads']);
    $allposts = cnt($db['f_posts']);

    if($allthreads > 0 && $allposts >= 0)
    {

        $ppert = round($allposts/$allthreads,2);
        $get = db("SELECT id,forumposts FROM ".$db['userstats']." ORDER BY forumposts DESC",false,true);

        $topposter = autor($get['id'])." (".$get['forumposts']." Posts)";
        $get = db("SELECT t_date FROM ".$db['f_threads']." ORDER BY t_date ASC",false,true);

        $time = time()-$get['t_date'];
        $days = @round($time/86400);

        $ges = $allposts+$allthreads;
        $pperd = @round($ges/$days,2);
    }

    $stats = show($dir."/forum", array("head" => _site_forum,
            "threads" => _forum_threads,
            "nthreads" => $allthreads,
            "posts" => _forum_posts,
            "nposts" => $allposts,
            "ppert" => _stats_forum_ppert,
            "nppert" => $ppert,
            "pperd" => _stats_forum_pperd,
            "npperd" => $pperd,
            "topposter" => _stats_forum_top,
            "ntopposter" => $topposter));

    $index = show($dir."/stats", array("head" => _stats,
            "news" => _site_news,
            "stats" => $stats,
            "user" => _user,
            "dl" => _site_dl,
            "mysql" => _stats_mysql,
            "awards" => _site_awards,
            "cw" => _site_clanwars,
            "gb" =>  _site_gb,
            "forum" => _site_forum));
}
?>
<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

#############################################
##### Code for 'DZCP - Extended Edition #####
###### DZCP - Extended Edition >= 1.0 #######
#############################################

####################################
## Wird in einer Index ausgef�hrt ##
####################################
if (!defined('IS_DZCP'))
    exit();

if (_version < '1.0') //Mindest Version pruefen
    $index = _version_for_page_outofdate;
else
{
    header("Content-type: text/html; charset=utf-8");
    $getkat = db("SELECT katimg FROM ".$db['newskat']." WHERE id = '".(isset($_POST['kat']) ? convert::ToInt($_POST['kat']) : 0)."'",false,true);
    $links1 = (isset($_POST['url1']) && !empty($_POST['url1']) ? show(_news_link, array("link" => re($_POST['link1']), "url" => links($_POST['url1']))) : '');
    $links2 = (isset($_POST['url2']) && !empty($_POST['url2']) ? show(_news_link, array("link" => re($_POST['link2']), "url" => links($_POST['url2']))) : '');
    $links3 = (isset($_POST['url3']) && !empty($_POST['url3']) ? show(_news_link, array("link" => re($_POST['link3']), "url" => links($_POST['url3']))) : '');
    $links = (!empty($links1) || !empty($links2) || !empty($links3) ? show(_news_links, array("link1" => $links1, "link2" => $links2, "link3" => $links3, "rel" => _related_links)) : '');

    $index = show($dir."/show_more", array("titel" => isset($_POST['titel']) ? re($_POST['titel']) : '',
                                           "kat" => re($getkat['katimg']),
                                           "id" => '_prev',
                                           "comments" => _news_comments_prev,
                                           "text" => isset($_POST['artikel']) ? bbcode($_POST['artikel'],1) : '',
                                           "datum" => date("d.m.y H:i", time())._uhr,
                                           "links" => $links,
                                           "autor" => autor($_SESSION['id'])));

    update_user_status_preview();
    exit('<table class="mainContent" cellspacing="1">'.$index.'</table>');
}
?>
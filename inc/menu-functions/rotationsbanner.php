<?php
function rotationsbanner()
{
    $menu_xml = get_menu_xml('rotationsbanner');
    if(!Cache::is_mem() || !$menu_xml['xml'] || Cache::check('nav_rotationsbanner'))
    {
        $rotationbanner = '';
        $qry = db("SELECT * FROM ".dba::get('sponsoren')." WHERE banner = 1 ORDER BY RAND() LIMIT 1");
        if(_rows($qry))
        {
            while($get = _fetch($qry))
            {
                $rotationbanner .= show(_sponsors_bannerlink, array("id" => $get['id'], "title" => htmlspecialchars(str_replace('http://', '', re($get['link']))), "banner" => (empty($get['blink']) ? "../banner/sponsors/banner_".$get['id'].".".$get['bend'] : re($get['blink']))));
            }

            if(Cache::is_mem() && $menu_xml['xml'] && $menu_xml['config']['update'] != '0') //Only Memory Cache
                Cache::set('nav_rotationsbanner',$rotationbanner,$menu_xml['config']['update']);
        }
    }
    else
        $rotationbanner = Cache::get('nav_rotationsbanner');

    return empty($rotationbanner) ? '' : $rotationbanner;
}
<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

#####################
## Admin Menu-File ##
#####################
if(_adminMenu != 'true')
    exit();

$where = $where.': '._config_links;
switch ($do)
{
    case 'new':
        $linktyp = '<tr><td class="contentMainTop" width="25%"><span class="fontBold">'._link_type.':</span></td><td class="contentMainFirst" align="center">
        <table class="hperc" cellspacing="2"><tr><td style="width:20px"><input type="radio" name="type" class="checkbox" value="links" checked=\"checked\" /></td>
        <td>'._link.'</td></tr><tr><td><input type="radio" name="type" class="checkbox" value="sponsoren" /></td><td>'._sponsor.'</td></tr></table></td></tr>';
        $show = show($dir."/form_links", array("head" => _links_admin_head,
                "link" => _links_link,
                "beschreibung" => _links_beschreibung,
                "art" => _links_art,
                "linktyp" => $linktyp,
                "text" => _links_admin_textlink,
                "banner" => _links_admin_bannerlink,
                "bchecked" => "checked=\"checked\"",
                "bnone" => "",
                "tchecked" => "",
                "llink" => "",
                "lbeschreibung" => "",
                "btext" => _links_text,
                "ltext" => "",
                "what" => _button_value_add,
                "do" => "add"));
    break;
    case 'add':
        if(empty($_POST['link']) || empty($_POST['beschreibung']) || (isset($_POST['banner']) && empty($_POST['text'])))
        {
            if(empty($_POST['link']))             $show = error(_links_empty_link, 1);
            elseif(empty($_POST['beschreibung'])) $show = error(_links_empty_beschreibung, 1);
            elseif(empty($_POST['text']))         $show = error(_links_empty_text, 1);
        }
        else
        {
            db("INSERT INTO ".dba::get('links')." SET `url` = '".links($_POST['link'])."',`text` = '".string::encode($_POST['text'])."', `banner` = '".string::encode($_POST['banner'])."', `beschreibung` = '".string::encode($_POST['beschreibung'])."'");
            $show = info(_link_added, "?admin=links");
        }
    break;
    case 'edit':
        if(empty($_POST['link']) || empty($_POST['beschreibung']) || (isset($_POST['banner']) && empty($_POST['text'])))
        {
            if(empty($_POST['link']))
                $show = error(_links_empty_link);
            else if(empty($_POST['beschreibung']))
                $show = error(_links_empty_beschreibung);
            else if(empty($_POST['text']))
                $show = error(_links_empty_text);
        }
        else
        {
            db("UPDATE ".dba::get('links')." SET `url` = '".links($_POST['link'])."', `text` = '".string::encode($_POST['text'])."', `banner` = '".string::encode($_POST['banner'])."', `beschreibung` = '".string::encode($_POST['beschreibung'])."' WHERE id = '".convert::ToInt($_GET['id'])."'");
            $show = info(_link_edited, "?admin=links");
        }

        $get = db("SELECT * FROM ".dba::get($_GET['type'])." WHERE id = '".convert::ToInt($_GET['id'])."'",false,true);
        $bchecked = $get['banner'] ? 'checked="checked"' : '';
        $tchecked = !$get['banner'] ? 'checked="checked"' : '';
        $bnone = !$get['banner'] ? 'display:none' : '';
        $linktyp = '<input type="hidden" name="type" value="'.$_GET['type'].'" />';
        $show = show($dir."/form_links", array("head" => _links_admin_head_edit,
                "link" => _links_link,
                "linktyp" => $linktyp,
                "beschreibung" => _links_beschreibung,
                "art" => _links_art,
                "text" => _links_admin_textlink,
                "banner" => _links_admin_bannerlink,
                "bchecked" => $bchecked,
                "tchecked" => $tchecked,
                "bnone" => $bnone,
                "llink" => $get['url'],
                "lbeschreibung" => string::decode($get['beschreibung']),
                "btext" => _links_text,
                "ltext" => string::decode($get['text']),
                "what" => _button_value_edit,
                "do" => "edit&amp;id=".$_GET['id'].""));
    break;
    case 'delete':
        $qry = db("DELETE FROM ".dba::get($_GET['type'])." WHERE id = '".convert::ToInt($_GET['id'])."'");
        $show = info(_link_deleted, "?admin=links");
    break;
    default:
        $qry = db("SELECT * FROM ".dba::get('links')." ORDER BY banner DESC"); $color = 1; $show = '';
        while($get = _fetch($qry))
        {
            $edit = show("page/button_edit_single", array("id" => $get['id'], "action" => "admin=links&amp;do=edit&amp;type=links", "title" => _button_title_edit));
            $delete = show("page/button_delete_single", array("id" => $get['id'], "action" => "admin=links&amp;do=delete&amp;type=links", "title" => _button_title_del, "del" => _confirm_del_link));
            $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
            $show .= show($dir."/links_show", array("link" => cut(string::decode($get['url']),40), "class" => $class, "type" => "links", "edit" => $edit, "delete" => $delete));
        }

        $show = show($dir."/links", array("show" => $show));
    break;
}
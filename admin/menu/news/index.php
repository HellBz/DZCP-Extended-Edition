<?php
#####################
## Admin Menu-File ##
#####################
if(_adminMenu != 'true')
    exit();

$where = $where.': '._config_newskats_edit_head;

switch ($$GLOBALS) {
    case value:
    ;
    break;
    
    default:
        ;
    break;
}

$qry = db("SELECT * FROM ".dba::get('newskat')." ORDER BY `kategorie`");
$kats = ''; $color = 1;
while($get = _fetch($qry))
{
    $edit = show("page/button_edit_single", array("id" => $get['id'], "action" => "admin=news&amp;do=edit", "title" => _button_title_edit));
    $delete = show("page/button_delete_single", array("id" => $get['id'], "action" => "admin=news&amp;do=delete", "title" => _button_title_del, "del" => _confirm_del_kat));
    $img = show(_config_newskats_img, array("img" => string::decode($get['katimg'])));
    $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
    $kats .= show($dir."/newskats_show", array("mainkat" => string::decode($get['kategorie']), "class" => $class, "img" => $img, "delete" => $delete, "edit" => $edit));
}

$show = show($dir."/newskats", array("head" => _config_newskats_head, "kats" => $kats, "add" => _config_newskats_add_head, "img" => _config_newskats_katbild, "delete" => _deleteicon_blank, "edit" => _editicon_blank, "mainkat" => _config_newskats_kat));
if($_GET['do'] == "delete")
{
        $qry = db("SELECT katimg FROM ".dba::get('newskat')."
                   WHERE id = '".convert::ToInt($_GET['id'])."'");
        $get = _fetch($qry);

        @unlink(basePath."/inc/images/uploads/newskat/".$get['katimg']);

        $del = db("DELETE FROM ".dba::get('newskat')."
                   WHERE id = '".convert::ToInt($_GET['id'])."'");

        $show = info(_config_newskat_deleted, "?admin=news");
      } elseif($_GET['do'] == "add") {
        $files = get_files(basePath.'/inc/images/uploads/newskat/',false,true);
        foreach($files as $file)
        {
          $img .= show(_select_field, array("value" => $file,
                                            "sel" => "",
                                            "what" => $file));
        }

        $show = show($dir."/newskatform", array("head" => _config_newskats_add_head,
                                                "nkat" => _config_katname,
                                                "kat" => "",
                                                "value" => _button_value_add,
                                                "nothing" => "",
                                                "do" => "addnewskat",
                                                "nimg" => _config_newskats_katbild,
                                                "upload" => _config_neskats_katbild_upload,
                                                "img" => $img));
      } elseif($_GET['do'] == "addnewskat") {
        if(empty($_POST['kat']))
        {
          $show = error(_config_empty_katname);
        } else {
          $qry = db("INSERT INTO ".dba::get('newskat')."
                     SET `katimg`     = '".string::encode($_POST['img'])."',
                         `kategorie`  = '".string::encode($_POST['kat'])."'");

          $show = info(_config_newskats_added, "?admin=news");
        }
      } elseif($_GET['do'] == "edit") {
        $qry = db("SELECT * FROM ".dba::get('newskat')."
                   WHERE id = '".convert::ToInt($_GET['id'])."'");
        $get = _fetch($qry);

        $files = get_files(basePath.'/inc/images/uploads/newskat/',false,true);
        foreach($files as $file)
        {
            $img .= show(_select_field, array("value" => $file, "sel" => ($get['katimg'] == $file ? 'selected="selected"' : ''), "what" => $file));
        }

        $upload = show(_config_neskats_katbild_upload_edit, array("id" => $_GET['id']));
        $do = show(_config_newskats_editid, array("id" => $_GET['id']));

        $show = show($dir."/newskatform", array("head" => _config_newskats_edit_head,
                                                "nkat" => _config_katname,
                                                "kat" => string::decode($get['kategorie']),
                                                "value" => _button_value_edit,
                                                "id" => $_GET['id'],
                                                "nothing" => _nothing,
                                                "do" => $do,
                                                "nimg" => _config_newskats_katbild,
                                                "upload" => $upload,
                                                "img" => $img));
      } elseif($_GET['do'] == "editnewskat") {
        if(empty($_POST['kat']))
        {
          $show = error(_config_empty_katname);
        } else {
          if($_POST['img'] == "lazy") $katimg = "";
          else $katimg = "`katimg` = '".string::encode($_POST['img'])."',";

          $qry = db("UPDATE ".dba::get('newskat')."
                     SET ".$katimg."
                         `kategorie` = '".string::encode($_POST['kat'])."'
                     WHERE id = '".convert::ToInt($_GET['id'])."'");

          $show = info(_config_newskats_edited, "?admin=news");
        }
      }
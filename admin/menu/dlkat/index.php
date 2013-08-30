<?php
#####################
## Admin Menu-File ##
#####################
if(_adminMenu != 'true')
    exit();

    $where = $where.': '._admin_dlkat;
      $qry = db("SELECT * FROM ".dba::get('dl_kat')." ORDER BY name");
      while($get = _fetch($qry))
      {
        $edit = show("page/button_edit_single", array("id" => $get['id'],
                                                      "action" => "admin=dlkat&amp;do=edit",
                                                      "title" => _button_title_edit));
        $delete = show("page/button_delete_single", array("id" => $get['id'],
                                                          "action" => "admin=dlkat&amp;do=delete",
                                                          "title" => _button_title_del,
                                                          "del" => _confirm_del_kat));
        $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;

        $show_ .= show($dir."/dlkats_show", array("gameicon" => $gameicon,
                                                 "edit" => $edit,
                                                 "name" => string::decode($get['name']),
                                                 "class" => $class,
                                                 "delete" => $delete));
      }

      $show = show($dir."/dlkats", array("head" => _admin_dlkat,
                                         "show" => $show_,
                                         "add" => _dl_new_head,
                                         "whatkat" => 'dlkat',
                                         "download" => _admin_download_kat,
                                         "edit" => _editicon_blank,
                                         "delete" => _deleteicon_blank));

      if($_GET['do'] == "edit")
      {
        $qry = db("SELECT * FROM ".dba::get('dl_kat')."
                   WHERE id = '".convert::ToInt($_GET['id'])."'");
        $get = _fetch($qry);

        $show = show($dir."/dlkats_form", array("newhead" => _dl_edit_head,
                                                "do" => "editkat&amp;id=".$_GET['id']."",
                                                "kat" => string::decode($get['name']),
                                                "what" => _button_value_edit,
                                                "dlkat" => _dl_dlkat));
      } elseif($_GET['do'] == "editkat") {
        if(empty($_POST['kat']))
        {
          $show = error(_dl_empty_kat);
        } else {
          $qry = db("UPDATE ".dba::get('dl_kat')."
                     SET `name` = '".string::encode($_POST['kat'])."'
                     WHERE id = '".convert::ToInt($_GET['id'])."'");

          $show = info(_dl_admin_edited, "?admin=dlkat");
        }
      } elseif($_GET['do'] == "delete") {
        $qry = db("DELETE FROM ".dba::get('dl_kat')."
                   WHERE id = '".convert::ToInt($_GET['id'])."'");

        $show = info(_dl_admin_deleted, "?admin=dlkat");

      } elseif($_GET['do'] == "new") {
        $show = show($dir."/dlkats_form", array("newhead" => _dl_new_head,
                                                "do" => "add",
                                                "kat" => "",
                                                "what" => _button_value_add,
                                                "dlkat" => _dl_dlkat));
      } elseif($_GET['do'] == "add") {
        if(empty($_POST['kat']))
        {
          $show = error(_dl_empty_kat);
        } else {
          $qry = db("INSERT INTO ".dba::get('dl_kat')."
                     SET `name` = '".string::encode($_POST['kat'])."'");

          $show = info(_dl_admin_added, "?admin=dlkat");
        }
      }
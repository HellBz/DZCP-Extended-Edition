<?php
#####################
## Admin Menu-File ##
#####################
if(_adminMenu != 'true')
    exit();

    $where = $where.': '._server_admin_head;
    if(!permission("glossar"))
    {
      $show = error(_error_wrong_permissions, 1);
    } else {
      if($_GET['do'] == 'add')
      {
        $show = show($dir."/form_glossar", array("head" => _admin_glossar_add,
                                                 "link" => _glossar_bez,
                                                 "beschreibung" => _glossar_erkl,
                                                 "llink" => "",
                                                 "lbeschreibung" => "",
                                                 "do" => "insert",
                                                 "value" => _button_value_add
                                                 ));
      } elseif($_GET['do'] == 'insert') {
        if(empty($_POST['link']) || empty($_POST['beschreibung']) || preg_match("#[[:punct:]]]#is",$_POST['link']))
        {
          if(empty($_POST['link']))       $show = error(_admin_error_glossar_word);
          elseif($_POST['beschreibung'])  $show = error(_admin_error_glossar_desc);
          elseif(preg_match("#[[:punct:]]#is",$_POST['link'])) $show = error(_glossar_specialchar);
        } else {
          $ins = db("INSERT INTO ".$db['glossar']."
                     SET `word`    = '".up($_POST['link'])."',
                         `glossar` = '".up($_POST['beschreibung'],1)."'");

          $show = info(_admin_glossar_added,'?admin=glossar');
        }
      } elseif($_GET['do'] == 'edit') {
        $qry = db("SELECT * FROM ".$db['glossar']."
                   WHERE id = '".convert::ToInt($_GET['id'])."'");
        $get = _fetch($qry);

        $show = show($dir."/form_glossar", array("head" => _admin_glossar_add,
                                                 "link" => _glossar_bez,
                                                 "beschreibung" => _glossar_erkl,
                                                 "llink" => re($get['word']),
                                                 "lbeschreibung" => re_bbcode($get['glossar']),
                                                 "do" => "update&amp;id=".$_GET['id'],
                                                 "value" => _button_value_edit
                                                 ));
      } elseif($_GET['do'] == 'update') {
        if(empty($_POST['link']) || empty($_POST['beschreibung']) || preg_match("#[[:punct:]]]#is",$_POST['link']))
        {
          if(empty($_POST['link']))       $show = error(_admin_error_glossar_word);
          elseif($_POST['beschreibung'])  $show = error(_admin_error_glossar_desc);
          elseif(preg_match("#[[:punct:]]#is",$_POST['link'])) $show = error(_glossar_specialchar);
        } else {
          $ins = db("UPDATE ".$db['glossar']."
                     SET `word`    = '".up($_POST['link'])."',
                         `glossar` = '".up($_POST['beschreibung'],1)."'
                     WHERE id = '".convert::ToInt($_GET['id'])."'");

          $show = info(_admin_glossar_edited,'?admin=glossar');
        }
      } elseif($_GET['do'] == 'delete') {
        $del = db("DELETE FROM ".$db['glossar']."
                   WHERE id = '".convert::ToInt($_GET['id'])."'");

        $show = info(_admin_glossar_deleted,'?admin=glossar');
      } else {
        $maxglossar = 20;
        if(isset($_GET['page']))  $page = $_GET['page'];
        else $page = 1;
        $entrys = cnt($db['glossar']);

        $qry = db("SELECT * FROM ".$db['glossar']."
                   ORDER BY word
                   LIMIT ".($page - 1)*$maxglossar.",".$maxglossar."");
        while($get = _fetch($qry))
        {
          $edit = show("page/button_edit_single", array("id" => $get['id'],
                                                        "action" => "admin=glossar&amp;do=edit",
                                                        "title" => _button_title_edit));
          $delete = show("page/button_delete_single", array("id" => $get['id'],
                                                            "action" => "admin=glossar&amp;do=delete",
                                                            "title" => _button_title_del,
                                                            "del" => convSpace(_confirm_del_entry)));

          $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;

          $show_ .= show($dir."/glossar_show", array("word" => re($get['word']),
                                                     "class" => $class,
                                                     "edit" => $edit,
                                                     "delete" => $delete,
                                                     "glossar" => bbcode($get['glossar'])));
        }

        $show = show($dir."/glossar", array("head" => _glossar_head,
                                            "word" => _glossar_bez,
                                            "bez" => _glossar_erkl,
                                            "show" => $show_,
                                            "cnt" => $entrys,
                                            "nav" => nav($entrys,$maxglossar,"?admin=glossar"),
                                            "add" => _admin_glossar_add
                                            ));
      }
    }
?>
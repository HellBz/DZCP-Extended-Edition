<?php
#####################
## Admin Menu-File ##
#####################
if(_adminMenu != 'true')
    exit();

    $where = $where.': '._slist_head_admin;
      $qry = db("SELECT id,ip,port,clanname,clanurl,pwd,checked FROM ".dba::get('serverliste')."");

      while ($get = _fetch($qry))
      {
        $delete = show("page/button_delete_single", array("id" => $get['id'],
                                                          "action" => "admin=serverlist&amp;do=delete",
                                                          "title" => _button_title_del,
                                                          "del" => _confirm_del_server));

        if(empty($get['clanurl']))
        {
          $clanname = show(_slist_clanname_without_url, array("name" => string::decode($get['clanname'])));
        } else {
          $clanname = show(_slist_clanname_with_url, array("name" => string::decode($get['clanname']),
                                                           "url" => string::decode($get['clanurl'])));
        }
        $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
        $show_ .= show($dir."/slist_show", array("id" => $get['id'],
                                                 "clanname" => $clanname,
                                                 "serverip" => string::decode($get['ip']),
                                                 "serverpwd" => string::decode($get['pwd']),
                                                 "class" => $class,
                                                 "delete" => $delete,
                                                 "selected" => ($get['checked'] ? 'selected="selected"' : ''),
                                                 "check" => $get['check'],
                                                 "serverport" => $get['port']));
      }

      $show = show($dir."/slist", array("show" => $show_,
                                        "slisthead" => _slist_head_admin,
                                        "clan" => _profil_clan,
                                        "delete" => _deleteicon_blank,
                                        "serverip" => _slist_serverip));

    if($_GET['do'] == "accept")
    {
      $qry = db("UPDATE ".dba::get('serverliste')."
                 SET `checked` = '".convert::ToInt($_POST['checked'])."'
                 WHERE id = '".convert::ToInt($_POST['id'])."'");

      if($_POST['checked'] == "1") $show = info(_error_server_accept, "?admin=serverlist");
      else $show = info(_error_server_dont_accept, "?admin=serverlist");

    } elseif($_GET['do'] == "delete") {
      $qry = db("DELETE FROM ".dba::get('serverliste')."
                 WHERE id = '".convert::ToInt($_GET['id'])."'");

      $show = info(_slist_server_deleted, "?admin=serverlist");
    }
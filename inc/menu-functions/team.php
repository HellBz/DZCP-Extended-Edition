<?php
//-> Teamausgabe in der Navigation
function team($tID = '')
{
  $teamRow = config('teamrow');

//SQL
    if(!empty($tID)) $where = "WHERE id = '".convert::ToInt($tID)."' AND navi = 1";
    else             $where = "WHERE navi = '1' ORDER BY RAND()";

    $get = db("SELECT * FROM ".dba::get('squads')." ".$where."",false,true);

//Members
    $qrym = db("SELECT s1.squad,s2.id,s2.level,s2.nick,s2.status,s2.rlname,s2.bday,s4.position
                FROM ".dba::get('squaduser')." AS s1
                LEFT JOIN ".dba::get('users')." AS s2
                ON s2.id=s1.user
                LEFT JOIN ".dba::get('userpos')." AS s3
                ON s3.squad=s1.squad AND s3.user=s1.user
                LEFT JOIN ".dba::get('pos')." AS s4
                ON s4.id=s3.posi
                WHERE s1.squad='".$get['id']."'
                AND s2.level != 0
                ORDER BY s4.pid");
    $i=1;
    $cnt=0;
    $member = "";
    while($getm = _fetch($qrym))
    {
      $tr1 = "";
      $tr2 = "";

      if($i == 0 || $i == 1) $tr1 = "<tr>";
      if($i == $teamRow)
      {
        $tr2 = "</tr>";
        $i = 0;
      }

      $status = ($getm['status'] == 1 || $getm['level'] == 1) ? "aktiv" : "inaktiv";
      $info = 'onmouseover="DZCP.showInfo(\''.fabo_autor($getm['id']).'\', \''._posi.';'._status.';'._age.'\', \''.getrank($getm['id'],$get['id']).';'.$status.';'.getAge($getm['bday']).'\', \''.hoveruserpic($getm['id']).'\')" onmouseout="DZCP.hideInfo()"';
      $member .= show("menu/team_show", array("pic" => userpic($getm['id'],40,50),
                                              "tr1" => $tr1,
                                              "tr2" => $tr2,
                                              "squad" => $get['id'],
                                              "info" => $info,
                                              "id" => $getm['id'],
                                              "width" => round(100/$teamRow,0)));
      $i++;
      $cnt++;
    }

    $end = '';
    if(is_float($cnt/$teamRow))
    {
        for($e=$i;$e<=$teamRow;$e++)
        {
            $end .= '<td></td>';
        }
        $end = $end."</tr>";
    }

// Next / last ID
    $all = cnt(dba::get('squads'), "WHERE `navi` = '1'");
    $next = _fetch(db("SELECT id FROM ".dba::get('squads')." WHERE `navi` = '1' AND `id` > '".$get['id']."' ORDER BY `id` ASC LIMIT 1"));
    if(empty($next)) $next = _fetch(db("SELECT id FROM ".dba::get('squads')." WHERE `navi` = '1' ORDER BY `id` ASC LIMIT 1"));
    $last = _fetch(db("SELECT id FROM ".dba::get('squads')." WHERE `navi` = '1' AND `id` < '".$get['id']."' ORDER BY `id` DESC LIMIT 1"));
    if(empty($last)) $last = _fetch(db("SELECT id FROM ".dba::get('squads')." WHERE `navi` = '1' ORDER BY `id` DESC LIMIT 1"));


//Output
    $team = show("menu/team", array("row" => $teamRow,
                                    "team" => re($get['name']),
                                    "id" => $get['id'],
                                    "next" => $next['id'],
                                    "last" => $last['id'],
                                    "br1" => ($all <= 1 ? '<!--' : ''),
                                    "br2" => ($all <= 1 ? '-->' : ''),
                                    "member" => $member,
                                    "end" => $end));

  return '<div id="navTeam">'.$team.'</div>';
}
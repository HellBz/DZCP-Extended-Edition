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
else if($chkMe == "unlogged")
    $index = error(_error_have_to_be_logged, 1);
else
{
    ######################
    ## Profil editieren ##
    ######################
    $where = _site_user_editprofil;
    $gallery_do = (isset($_GET['gallery']) ? $_GET['gallery'] : '');

    #####################
    ## Functions START ##
    #####################

    //Generate custom profil fields
    function custom_fields($userid,$kid)
    {
        $qrycustom = db("SELECT * FROM ".dba::get('profile')." WHERE kid = ".convert::ToInt($kid)." AND shown = '1' ORDER BY id ASC"); $custom = '';
        while($getcustom = _fetch($qrycustom))
        {
            $getcontent = db("SELECT ".$getcustom['feldname']." FROM ".dba::get('users')." WHERE id = '".convert::ToInt($userid)."'",false,true);
            $custom .= show(_profil_edit_custom, array("name" => pfields_name($getcustom['name']).":", "feldname" => $getcustom['feldname'], "value" => re($getcontent[$getcustom['feldname']])));
        } //while end
        unset($qrycustom,$getcustom);
        return $custom;
    }

    ###################
    ## Functions END ##
    ###################

    switch($do)
    {
        case 'edit':
            $check_user = 0; $check_nick = 0; $check_email = 0;
            if(db("SELECT user,nick,email FROM ".dba::get('users')." AS u1 WHERE (u1.user = '".$_POST['user']."' OR u1.nick = '".$_POST['nick']."' OR u1.email = '".$_POST['email']."') AND id != ".convert::ToInt($userid),true))
            {
                $check_user = db("SELECT id FROM ".dba::get('users')." WHERE user = '".$_POST['user']."' AND id != '".convert::ToInt($userid)."'",true);
                $check_nick = db("SELECT id FROM ".dba::get('users')." WHERE nick = '".$_POST['nick']."' AND id != '".convert::ToInt($userid)."'",true);
                $check_email = db("SELECT id  FROM ".dba::get('users')." WHERE email = '".$_POST['email']."' AND id != '".convert::ToInt($userid)."'",true);
            }

            if(empty($_POST['user']))
                $index = error(_empty_user, 1);
            else if(empty($_POST['nick']))
                $index = error(_empty_nick, 1);
            else if(empty($_POST['email']))
                $index = error(_empty_email, 1);
            else if(!check_email($_POST['email']))
                $index = error(_error_invalid_email, 1);
            else if(check_email_trash_mail($_POST['email']))
                $index = error(_error_trash_mail, 1);
            else if(!empty($_POST['pwd']) && $_POST['pwd'] != $_POST['pwd2'])
                $index = error(_wrong_pwd, 1);
            else if($check_user)
                $index = error(_error_user_exists, 1);
            else if($check_nick)
                $index = error(_error_nick_exists, 1);
            else if($check_email)
                $index = error(_error_email_exists, 1);
            else
            {
                if(isset($_POST['pwd']) && !empty($_POST['pwd']))
                {
                    $newpwd = "`pwd` = '".($passwd=pass_hash($_POST['pwd'],($default_pwd_encoder = settings('default_pwd_encoder'))))."', `pwd_encoder` = ".$default_pwd_encoder.",";
                    $_SESSION['pwd'] = $passwd; unset($passwd);
                    $index = info(_info_edit_profile_done, "?action=user&amp;id=".convert::ToInt($userid)."");
                }
                else
                {
                    $newpwd = "";
                    $index = info(_info_edit_profile_done, "?action=user&amp;id=".convert::ToInt($userid)."");
                }

                $icq = preg_replace("=-=Uis","",$_POST['icq']);
                $bday = (isset($_POST['t']) && isset($_POST['m']) && isset($_POST['j']) ? cal($_POST['t']).".".cal($_POST['m']).".".$_POST['j'] : '');

                $qrycustom = db("SELECT feldname,type FROM ".dba::get('profile')); $customfields = '';
                if(_rows($qrycustom))
                {
                    while($getcustom = _fetch($qrycustom))
                    {
                        if($getcustom['type'] == 2)
                            $customfields .= " ".$getcustom['feldname']." = '".convert::ToString(links($_POST[$getcustom['feldname']]))."', ";
                        else
                            $customfields .= " ".$getcustom['feldname']." = '".convert::ToString(up($_POST[$getcustom['feldname']]))."', ";
                    } //while end
                }

                $get = db("SELECT user,xfire FROM ".dba::get('users')." WHERE id = '".convert::ToInt($userid)."'",false,true);

                if($get['xfire'] != convert::ToString(up($_POST['xfire'])))
                { Cache::delete_binary('xfire_'.$get['user']); } //Delete XFire Cache

                db("UPDATE ".dba::get('users')." SET	".$newpwd." ".$customfields."
                                                 `country`          = '".convert::ToString($_POST['land'])."',
                                                 `user`             = '".convert::ToString(up($_POST['user']))."',
                                                 `nick`             = '".convert::ToString(up($_POST['nick']))."',
                                                 `rlname`           = '".convert::ToString(up($_POST['rlname']))."',
                                                 `sex`              = '".convert::ToInt($_POST['sex'])."',
                                                 `status`           = '".convert::ToInt($_POST['status'])."',
                                                 `bday`             = '".convert::ToString($bday)."',
                                                 `email`            = '".convert::ToString(up($_POST['email']))."',
                                                 `nletter`          = '".convert::ToInt($_POST['nletter'])."',
                                                 `pnmail`           = '".convert::ToInt($_POST['pnmail'])."',
                                                 `city`             = '".convert::ToString(up($_POST['city']))."',
                                                 `gmaps_koord`      = '".convert::ToString(up($_POST['gmaps_koord']))."',
                                                 `language`         = '".convert::ToString(up($_POST['language']))."',
                                                 `hp`               = '".convert::ToString(links($_POST['hp']))."',
                                                 `icq`              = '".convert::ToInt($icq)."',
                                                 `xfire`            = '".convert::ToString(up($_POST['xfire']))."',
                                                 `signatur`         = '".convert::ToString(up($_POST['sig'],1))."',
                                                 `profile_access`   = '".convert::ToInt(isset($_POST['paccess']) ? $_POST['paccess'] : 0)."',
                                                 `beschreibung`     = '".convert::ToString(up($_POST['ich'],1))."'
                                                  WHERE id = ".convert::ToInt($userid));

                //-> Change Language
                if(($language=data($userid,'language')) != 'default')
                    language::set_language(re($language));
            }
        break;
        case 'editrss':
            db("UPDATE ".dba::get('rss')." SET `show_public_news`     = '".convert::ToInt(empty($_POST['rss_pub_news']) ? 0 : $_POST['rss_pub_news'])."',
                                          `show_intern_news`     = '".convert::ToInt(empty($_POST['rss_int_news']) ? 0 : $_POST['rss_int_news'])."',
                                          `show_artikel`         = '".convert::ToInt(empty($_POST['rss_artikel']) ? 0 : $_POST['rss_artikel'])."',
                                          `show_downloads`       = '".convert::ToInt(empty($_POST['rss_downloads']) ? 0 : $_POST['rss_downloads'])."',
                                          `show_public_news_max` = '".convert::ToInt(empty($_POST['rss_pub_news_max']) ? 0 : $_POST['rss_pub_news_max'])."',
                                          `show_intern_news_max` = '".convert::ToInt(empty($_POST['rss_int_news_max']) ? 0 : $_POST['rss_int_news_max'])."',
                                          `show_artikel_max`     = '".convert::ToInt(empty($_POST['rss_artikel_max']) ? 0 : $_POST['rss_artikel_max'])."',
                                          `show_downloads_max`   = '".convert::ToInt(empty($_POST['rss_downloads_max']) ? 0 : $_POST['rss_downloads_max'])."'
                                          WHERE id = ".convert::ToInt($userid));

            Cache::delete('private_news_rss_userid_'.$get['user']); //Delete RSS Cache
            $index = info(_info_edit_rss_done, '../user/?action=editprofile&show=rss');
        break;
        case 'delete':
            $getdel = db("SELECT id,nick,email,hp,user FROM ".dba::get('users')." WHERE id = '".convert::ToInt($userid)."'",false,true);
            Cache::delete('xfire_'.$getdel['user']);

            db("UPDATE ".dba::get('f_threads')." SET `t_nick` = '".$getdel['nick']."', `t_email` = '".$getdel['email']."', `t_hp` = '".$getdel['hp']."', `t_reg` = '0' WHERE t_reg = '".$getdel['id']."'");
            db("UPDATE ".dba::get('f_posts')." SET `nick` = '".$getdel['nick']."', `email` = '".$getdel['email']."', `hp` = '".$getdel['hp']."', `reg` = '0' WHERE reg = '".$getdel['id']."'");
            db("UPDATE ".dba::get('newscomments')." SET `nick` = '".$getdel['nick']."', `email` = '".$getdel['email']."', `hp` = '".$getdel['hp']."', `reg` = '0' WHERE reg = '".$getdel['id']."'");
            db("UPDATE ".dba::get('acomments')." SET `nick` = '".$getdel['nick']."', `email` = '".$getdel['email']."', `hp` = '".$getdel['hp']."', `reg` = '0' WHERE reg = '".$getdel['id']."'");
            db("UPDATE ".dba::get('dl_comments')." SET `nick` = '".$getdel['nick']."', `email` = '".$getdel['email']."', `hp` = '".$getdel['hp']."', `reg` = '0' WHERE reg = '".$getdel['id']."'");
            db("UPDATE ".dba::get('gb_comments')." SET `nick` = '".$getdel['nick']."', `email` = '".$getdel['email']."', `hp` = '".$getdel['hp']."', `reg` = '0' WHERE reg = '".$getdel['id']."'");
            db("UPDATE ".dba::get('gb')." SET `nick` = '".$getdel['nick']."', `email` = '".$getdel['email']."', `hp` = '".$getdel['hp']."', `reg` = '0' WHERE reg = '".$getdel['id']."'");

            db("DELETE FROM ".dba::get('clicks_ips')." WHERE `uid` = ".$getdel['id']);

            db("DELETE FROM ".dba::get('acomments')." WHERE von = '".$getdel['id']."' OR an = '".$getdel['id']."'");
            db("DELETE FROM ".dba::get('news')." WHERE autor = '".$getdel['id']."'");
            db("DELETE FROM ".dba::get('permissions')." WHERE user = '".$getdel['id']."'");
            db("DELETE FROM ".dba::get('squaduser')." WHERE user = '".$getdel['id']."'");
            db("DELETE FROM ".dba::get('buddys')." WHERE user = '".$getdel['id']."' OR buddy = '".$getdel['id']."'");
            db("UPDATE ".dba::get('usergb')." SET `reg` = 0 WHERE reg = ".$getdel['id']."");
            db("DELETE FROM ".dba::get('userpos')." WHERE user = '".$getdel['id']."'");
            db("DELETE FROM ".dba::get('userstats')." WHERE user = '".$getdel['id']."'");
            db("DELETE FROM ".dba::get('rss')." WHERE `userid` = '".$getdel['id']."'");

            foreach($picformat as $tmpendung)
            {
                if(file_exists(basePath."/inc/images/uploads/userpics/".$getdel['id'].".".$tmpendung))
                    @unlink(basePath."/inc/images/uploads/userpics/".$getdel['id'].".".$tmpendung);

                if(file_exists(basePath."/inc/images/uploads/useravatare/".$getdel['id'].".".$tmpendung))
                    @unlink(basePath."/inc/images/uploads/useravatare/".$getdel['id'].".".$tmpendung);
            }

            $qrygl = db("SELECT pic FROM ".dba::get('usergallery')." WHERE user = '".$getdel['id']."'");
            if(_rows($qrygl) >= 1)
            {
                while($getgl = _fetch($qrygl))
                {
                    @unlink(basePath."inc/images/uploads/usergallery/".$getdel['id']."_".$getgl['pic']);
                } //while end

                db("DELETE FROM ".dba::get('usergallery')." WHERE user = '".$getdel['id']."'");
            }

            db("DELETE FROM ".dba::get('users')." WHERE id = '".$getdel['id']."'");
            $index = info(_info_account_deletet, '../news/');
        break;
        default: ## Profil editieren ##
            if($gallery_do == "delete")
            {
                $qrygl = db("SELECT gid FROM ".dba::get('usergallery')." WHERE user = '".convert::ToInt($userid)."' AND id = '".convert::ToInt($_GET['gid'])."'");
                if(_rows($qrygl))
                {
                    while($getgl = _fetch($qrygl))
                    {
                        db("DELETE FROM ".dba::get('usergallery')." WHERE id = '".convert::ToInt($_GET['gid'])."'");
                    } //while end
                }

                $index = info(_info_edit_gallery_done, "?action=editprofile&show=gallery");
            }
            else
            {
                $qry = db("SELECT * FROM ".dba::get('users')." WHERE id = '".convert::ToInt($userid)."'");
                if(!_rows($qry))
                    $index = error(_user_dont_exist, 1);
                else
                {
                    $get = _fetch($qry);
                    switch(isset($_GET['show']) ? $_GET['show'] : '')
                    {
                        case 'gallery':
                            $qrygl = db("SELECT * FROM ".dba::get('usergallery')." WHERE user = '".convert::ToInt($userid)."' ORDER BY id DESC");
                                $color = 1; $gal = '';
                                if(_rows($qrygl) >= 1)
                                {
                                    while($getgl = _fetch($qrygl))
                                    {
                                        $pic = show(_gallery_pic_link, array("img" => $getgl['pic'], "user" => convert::ToInt($userid)));
                                        $delete = show(_gallery_deleteicon, array("id" => $getgl['id']));
                                        $edit = show(_gallery_editicon, array("id" => $getgl['id']));
                                        $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
                                        $gal .= show($dir."/edit_gallery_show", array("picture" => img_size("inc/images/uploads/usergallery"."/".convert::ToInt($userid)."_".$getgl['pic']),
                                                                                      "beschreibung" => bbcode($getgl['beschreibung']),
                                                                                      "class" => $class,
                                                                                      "delete" => $delete,
                                                                                      "edit" => $edit));
                                    } //while end
                                }

                                $gal = empty($gal) ? show(_no_entrys_yet_all, array("colspan" => "3")) : $gal;
                                $show = show($dir."/edit_gallery", array("showgallery" => $gal));
                        break;
                        case 'rss':
                            $rss_uconf = db("SELECT * FROM `".dba::get('rss')."` WHERE `userid` = '".convert::ToInt($userid)."' LIMIT 1",false,true);
                            $rsspn = ($rss_uconf['show_public_news'] ? 'checked="checked"' : '');
                            $rssin = ($rss_uconf['show_intern_news'] ? 'checked="checked"' : '');
                            $rssa = ($rss_uconf['show_artikel'] ? 'checked="checked"' : '');
                            $rssd = ($rss_uconf['show_downloads'] ? 'checked="checked"' : '');
                            $show = show($dir."/edit_rss", array('rssd' => $rssd, 'rssa' => $rssa, 'rssin' => $rssin, 'rsspn' => $rsspn,
                            'rss_pub_news_max' => $rss_uconf['show_public_news_max'], 'rss_int_news_max' => $rss_uconf['show_intern_news_max'],
                            'rss_artikel_max' => $rss_uconf['show_artikel_max'], 'rss_downloads_max' => $rss_uconf['show_downloads_max']));
                        break;
                        default:
                            $sex = ($get['sex'] == 1 ? _pedit_male : ($get['sex'] == 2 ? _pedit_female : _pedit_sex_ka));
                            $status = ($get['status'] ? _pedit_aktiv : _pedit_inaktiv);

                            ## Clan ##
                            if($get['level'] == 1)
                                $clan = '<input type="hidden" name="status" value="1" />';
                            else
                            {
                                $custom_clan = custom_fields(convert::ToInt($userid),2);
                                $clan = show($dir."/edit_clan", array("status" => $status, "custom_clan" => $custom_clan));
                            }

                            $bdayday = 0; $bdaymonth = 0; $bdayyear = 0;
                            if(!empty($get['bday']))
                                list($bdayday, $bdaymonth, $bdayyear) = explode('.', $get['bday']);

                                $dropdown_age = show(_dropdown_date, array("day" => dropdown("day",$bdayday,1), "month" => dropdown("month",$bdaymonth,1), "year" => dropdown("year",$bdayyear,1)));

                                $icq = (!empty($get['icq']) && $get['icq'] != 0 ? $get['icq'] : '');
                                $gmaps = show('membermap/geocoder', array('form' => 'editprofil'));
                                $pnl = ($get['nletter'] ? 'checked="checked"' : '');
                                $pnm = ($get['pnmail'] ? 'checked="checked"' : '');
                                $paccess = ($get['profile_access'] ? 'checked="checked"' : '');
                                $pic = userpic($get['id']); $avatar = useravatar($get['id']);
                                $deletepic = (!preg_match("#nopic#",$pic) ? "| "._profil_delete_pic : '');
                                $deleteava = (!preg_match("#noavatar#",$avatar) ? "| "._profil_delete_ava : '');

                                $delete = (convert::ToInt($userid) == convert::ToInt($rootAdmin) ? _profil_del_admin : show("page/button_delete_account", array("id" => $get['id'],"action" => "action=editprofile&amp;do=delete", "value" => _button_title_del_account, "del" => convSpace(_confirm_del_account))));
                                $show = show($dir."/edit_profil", array("country" => show_countrys($get['country']),
                                        "city" => re($get['city']),
                                        "pnl" => $pnl,
                                        "pnm" => $pnm,
                                        "paccess" => $paccess,
                                        "pwd" => "",
                                        "dropdown_age" => $dropdown_age,
                                        "ava" => $avatar,
                                        "hp" => re($get['hp']),
                                        "gmaps" => $gmaps,
                                        "nick" => re($get['nick']),
                                        "name" => re($get['user']),
                                        "gmaps_koord" => re($get['gmaps_koord']),
                                        "rlname" => re($get['rlname']),
                                        "bdayday" => $bdayday,
                                        "bdaymonth" => $bdaymonth,
                                        "bdayyear" =>$bdayyear,
                                        "sex" => $sex,
                                        "email" => re($get['email']),
                                        "icqnr" => $icq,
                                        "sig" => re_bbcode($get['signatur']),
                                        "xfire" => $get['xfire'],
                                        "clan" => $clan,
                                        "pic" => $pic,
                                        "deleteava" => $deleteava,
                                        "deletepic" => $deletepic,
                                        "position" => getrank($get['id']),
                                        "language" => language::get_menu($get['language']),
                                        "status" => $status,
                                        "custom_about" => custom_fields(convert::ToInt($userid),1),
                                        "custom_contact" => custom_fields(convert::ToInt($userid),3),
                                        "custom_favos" => custom_fields(convert::ToInt($userid),4),
                                        "custom_hardware" => custom_fields(convert::ToInt($userid),5),
                                        "ich" => re_bbcode($get['beschreibung']),
                                        "delete" => $delete));
                        break;
                    }

                    $index = show($dir."/edit", array("show" => $show), array("nick" => autor($get['id'])));
                }
            }
        break;
    }
}
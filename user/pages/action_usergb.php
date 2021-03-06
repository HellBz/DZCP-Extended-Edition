<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if (!defined('IS_DZCP')) exit();
if (_version < '1.0')
    $index = _version_for_page_outofdate;
else
{
    ##################
    ## Usergätebuch ##
    ##################
    $where = _site_user_profil;
    if(db("SELECT id FROM ".dba::get('users')." WHERE `id` = '".convert::ToInt($_GET['id'])."'",true) != 0)
    {
        switch($do)
        {
            case 'add':
                if(userid() != 0)
                    $toCheck = empty($_POST['eintrag']);
                else
                    $toCheck = empty($_POST['nick']) || empty($_POST['email']) || empty($_POST['eintrag']) || !check_email($_POST['email']) || !$securimage->check($_POST['secure']);

                if($toCheck)
                {
                    if(userid() != 0)
                    {
                        if(empty($_POST['eintrag']))
                            $error = _empty_eintrag;

                        $form = show("page/editor_regged", array("nick" => autor()));
                    }
                    else
                    {
                        if(!$securimage->check($_POST['secure']))
                            $error = captcha_mathematic ? _error_invalid_regcode_mathematic : _error_invalid_regcode;
                        else if(empty($_POST['nick']))
                            $error = _empty_nick;
                        else if(empty($_POST['email']))
                            $error = _empty_email;
                        else if(!check_email($_POST['email']))
                            $error = _error_invalid_email;
                        else if(check_email_trash_mail($_POST['email']))
                            $error = _error_trash_mail;
                        else if(empty($_POST['eintrag']))
                            $error = _empty_eintrag;

                        $form = show("page/editor_notregged", array("postemail" => $_POST['email'], "posthp" => $_POST['hp'], "postnick" => $_POST['nick']));
                    }

                    $error = show("errors/errortable", array("error" => $error));
                    $index = show($dir."/usergb_add", array("ed" => "&uid=".$_GET['id'],
                                                            "whaturl" => "add",
                                                            "id" => $_GET['id'],
                                                            "reg" => $_POST['reg'],
                                                            "form" => $form,
                                                            "posteintrag" => string::decode($_POST['eintrag']),
                                                            "error" => $error));
                }
                else
                {

                    if(userid() != 0)
                    {
                        $userdata = data(userid(), array('email','nick','hp'));
                        db("INSERT INTO ".dba::get('usergb')." SET
                               `user`       = '".convert::ToInt($_GET['id'])."',
                               `datum`      = '".time()."',
                               `nick`       = '".convert::ToString(string::encode($userdata['nick']))."',
                               `email`      = '".convert::ToString(string::encode($userdata['email']))."',
                               `hp`         = '".convert::ToString(links($userdata['hp']))."',
                               `reg`        = '".userid()."',
                               `nachricht`  = '".convert::ToString(string::encode($_POST['eintrag']))."',
                               `ip`         = '".convert::ToString(visitorIp())."'");
                        unset($userdata);
                    }
                    else
                    {
                        db("INSERT INTO ".dba::get('usergb')." SET
                               `user`       = '".convert::ToInt($_GET['id'])."',
                               `datum`      = '".time()."',
                               `nick`       = '".convert::ToString(string::encode($_POST['nick']))."',
                               `email`      = '".convert::ToString(string::encode($_POST['email']))."',
                               `hp`         = '".convert::ToString(links($_POST['hp']))."',
                               `reg`        = '".userid()."',
                               `nachricht`  = '".convert::ToString(string::encode($_POST['eintrag']))."',
                               `ip`         = '".convert::ToString(visitorIp())."'");
                    }

                    wire_ipcheck("mgbid(".$_GET['id'].")");
                    $index = info(_usergb_entry_successful, "?index=user&amp;action=user&amp;id=".$_GET['id']."&show=gb");
                }
            break;
            default:
                if($_POST['reg'] == userid() || permission('editusers'))
                {
                    $addme = (!$_POST['reg'] ? "`nick` = '".string::encode($_POST['nick'])."', `email` = '".string::encode($_POST['email'])."', `hp` = '".links($_POST['hp'])."'," : '');
                    $editedby = show(_edited_by, array("autor" => autor(), "time" => date("d.m.Y H:i", time())._uhr));
                    db("UPDATE ".dba::get('usergb')." SET ".$addme." `nachricht` = '".convert::ToString(string::encode($_POST['eintrag']))."', `reg` = '".convert::ToInt($_POST['reg'])."', `editby` = '".convert::ToString(addslashes($editedby))."' WHERE id = '".convert::ToInt($_GET['gbid'])."'");
                    $index = info(_gb_edited, "?index=user&amp;action=user&show=gb&id=".$_GET['id']);
                }
                else
                    $index = error(_error_edit_post);
            break;
        }
    }
    else
        $index = error(_user_dont_exist);
}
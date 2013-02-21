﻿<?php
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
else
{
    if($chkMe != 'unlogged')
    {
        $infos = show(_upload_userava_info, array("userpicsize" => $upicsize));

        $index = show($dir."/upload", array("uploadhead" => _upload_ava_head,
                "file" => _upload_file,
                "name" => "file",
                "action" => "?action=avatar&amp;do=upload",
                "upload" => _button_value_upload,
                "info" => _upload_info,
                "infos" => $infos));
        if($_GET['do'] == "upload")
        {
            $tmpname = $_FILES['file']['tmp_name'];
            $name = $_FILES['file']['name'];
            $type = $_FILES['file']['type'];
            $size = $_FILES['file']['size'];


            $endung = explode(".", $_FILES['file']['name']);
            $endung = strtolower($endung[count($endung)-1]);

            if(!$tmpname)
            {
                $index = error(_upload_no_data, 1);
            } elseif($size > $upicsize."000") {
                $index = error(_upload_wrong_size, 1);
            } else {
                foreach($picformat as $tmpendung)
                {
                    if(file_exists(basePath."/inc/images/uploads/useravatare/".convert::ToInt($userid).".".$tmpendung))
                    {
                        @unlink(basePath."/inc/images/uploads/useravatare/".convert::ToInt($userid).".".$tmpendung);
                    }
                }
                copy($tmpname, basePath."/inc/images/uploads/useravatare/".convert::ToInt($userid).".".strtolower($endung));
                @unlink($_FILES['file']['tmp_name']);

                $index = info(_info_upload_success, "../user/?action=editprofile");
            }
        } elseif($_GET['do'] == "delete") {
            foreach($picformat as $tmpendung)
            {
                if(file_exists(basePath."/inc/images/uploads/useravatare/".convert::ToInt($userid).".".$tmpendung))
                {
                    @unlink(basePath."/inc/images/uploads/useravatare/".convert::ToInt($userid).".".$tmpendung);
                    $index = info(_delete_pic_successful, "../user/?action=editprofile");
                }
            }
        }
    } else {
        $index = error(_error_wrong_permissions, 1);
    }
}
?>
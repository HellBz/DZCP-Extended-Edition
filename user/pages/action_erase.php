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
else
{
    ################
    ## User Erase ##
    ################
    $_SESSION['lastvisit'] = data(convert::ToInt($userid), "time");
    db("UPDATE ".$db['userstats']." SET `lastvisit` = '".convert::ToInt($_SESSION['lastvisit'])."' WHERE user = '".convert::ToInt($userid)."'");
    header("Location: ?action=userlobby");
}
?>


<?php
/**
 * This file is part of GameQ.
 *
 * GameQ is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * GameQ is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
* Starbound Protocol Class
*
* Unable to test if player information is returned. Also appears the challenge procedure
* is ignored.
*
* @author Austin Bischoff <austin@codebeard.com>
*/
class GameQ_Protocols_Starbound extends GameQ_Protocols_Source
{
    //Game or Mod
    protected $name = "starbound";
    protected $name_long = "Starbound";
    protected $name_short = "Starbound";
    protected $port = 21025;

    //Basic Game
    protected $basic_game_dir = 'starbound';

    //Settings
    protected $goldsource = false;
    protected $is_mod = false;
    protected $modlist = array();
}
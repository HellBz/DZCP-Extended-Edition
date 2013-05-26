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
 * Natural Selection 2 Protocol Class
 *
 * Note that the query port is the server connect port + 1
 *
 * @author Austin Bischoff <austin@codebeard.com>
 */
class GameQ_Protocols_Ns2 extends GameQ_Protocols_Source
{
    /**
     * Default port for this server type
     *
     * @var int
     */
    protected $port = 27016;

    //Game or Mod
    protected $name = "ns2";
    protected $name_long = "Natural Selection 2";
    protected $name_short = "NS2";

    //Basic Game
    protected $basic_game_dir = 'naturalselection2';

    //Settings
    protected $goldsource = false;
    protected $is_mod = false;
    protected $modlist = array();
}

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
 * Battlefield 4 Protocol Class
 *
 * Good place for doc status and info is http://battlelog.battlefield.com/bf4/forum/view/2955064768683911198/
 *
 * @author Austin Bischoff <austin@codebeard.com>
 */
class GameQ_Protocols_Bf4 extends GameQ_Protocols_Bf3
{
    /**
     * Normalization for this protocol class
     *
     * @var array
     */
    protected $normalize = array(
            // General
            'general' => array(
                    'dedicated' => array('dedicated'),
                    'hostname' => array('hostname'),
                    'password' => array('password'),
                    'numplayers' => array('numplayers'),
                    'maxplayers' => array('maxplayers'),
                    'mapname' => array('map'),
                    'gametype' => array('gametype'),
                    'players' => array('players'),
                    'teams' => array('team'),
            ),

            // Player
            'player' => array(
                    'score' => array('score'),
            ),

            // Team
            'team' => array(
                    'score' => array('tickets'),
            ),
    );

    /**
     * The protocol being used
     *
     * @var string
     */
    protected $protocol = 'bf4';

    /**
     * String name of this protocol class
     *
     * @var string
     */
    protected $name = 'bf4';

    /**
     * Longer string name of this protocol class
     *
     * @var string
     */
    protected $name_long = "Battlefield 4";
    protected $name_short = "BF4";

    //Basic
    protected $basic_game_long = '';
    protected $basic_game_short = '';
    protected $basic_game_dir = 'bf4';

    /**
     * Team & Squad List
     */
    protected $squadlist = array(0  => 'No Squad', 1 => 'Alpha', 2 => 'Bravo', 3 => 'Charlie', 4 => 'Delta', 5 => 'Echo', 6 => 'Foxtrot', 7 => 'Golf', 8 => 'Hotel', 9 => 'India', 10 => 'Juliet', 11 => 'Kilo', 12 => 'Lima',
            13 => 'Mike', 14 => 'November', 15 => 'Oscar', 16 => 'Papa', 17 => 'Quebec', 18 => 'Romeo', 19 => 'Sierra', 20 => 'Tango', 21 => 'Uniform', 22 => 'Victor', 23 => 'Whiskey', 24 => 'Xray', 25 => 'Yankee',
            26 => 'Zulu', 27 => 'Haggard', 28 => 'Sweetwater', 29 => 'Preston', 30 => 'Redford', 31 => 'Faith', 32 => 'Celeste');

    protected $teamlist = array(0 => 'Spectator', 1 => 'USA', 2 => 'RUS');

    /**
     * Gamemodes
    */
    protected $gamemode = array('conquestlarge' => 'Conquest', 'conquestsmall' => 'Conquest', 'rushlarge' => 'Rush', 'squaddeathmatch' => 'Squad Deathmatch', 'squadrush' => 'Squad Rush', 'teamdeathmatch' => 'Team Deathmatch');

    /**
     * Set settings filter
     *
     * @var array
    */
    protected $settings_filter = array();

    /**
     * Show Stats Options
     *
     * @var array
    */
    protected $stats_options = array();

    /*
     * Internal Methods
     */
    protected function process_status()
    {
        // Make sure we have a valid response
        if(!$this->hasValidResponse(self::PACKET_STATUS))
        {
            return array();
        }

        // Make buffer for data
        $buf = new GameQ_Buffer($this->preProcess_status($this->packets_response[self::PACKET_STATUS]));

        $buf->skip(8); /* skip header */

        // Decode the words into an array so we can use this data
        $words = $this->decodeWords($buf);

        // Make sure we got OK
        if (!isset ($words[0]) || $words[0] != 'OK')
        {
            throw new GameQ_ProtocolsException('Packet Response was not OK! Buffer:'.$buf->getBuffer());
        }

        // Set the result to a new result instance
        $result = new GameQ_Result();

        // Server is always dedicated
        $result->add('dedicated', TRUE);

        // No mods, as of yet
        $result->add('mod', FALSE);

        // These are the same no matter what mode the server is in
        $result->add('hostname', $words[1]);
        $result->add('numplayers', $words[2]);
        $result->add('maxplayers', $words[3]);
        $result->add('gametype', $words[4]);
        $result->add('map', $words[5]);

        $result->add('roundsplayed', $words[6]);
        $result->add('roundstotal', $words[7]);

        // Figure out the number of teams
        $num_teams = intval($words[8]);

        // Set the current index
        $index_current = 9;

        // Loop for the number of teams found, increment along the way
        for($id=1; $id<=$num_teams; $id++)
        {
            $result->addSub('teams', 'tickets', $words[$index_current]);
            $result->addSub('teams', 'id', $id);

            // Increment
            $index_current++;
        }

        // Get and set the rest of the data points.
        $result->add('targetscore', $words[$index_current]);
        $result->add('online', TRUE); // Forced TRUE, it seems $words[$index_current + 1] is always empty
        $result->add('ranked', $words[$index_current + 2] === 'true');
        $result->add('punkbuster', $words[$index_current + 3] === 'true');
        $result->add('password', $words[$index_current + 4] === 'true');
        $result->add('uptime', $words[$index_current + 5]);
        $result->add('roundtime', $words[$index_current + 6]);

        $result->add('ip_port', $words[$index_current + 7]);
        $result->add('punkbuster_version', $words[$index_current + 8]);
        $result->add('join_queue', $words[$index_current + 9] === 'true');
        $result->add('region', $words[$index_current + 10]);
        $result->add('pingsite', $words[$index_current + 11]);
        $result->add('country', $words[$index_current + 12]);

        // @todo: Supposed to be a field here <matchMakingEnabled: boolean>, its in R13 docs but doesnt return in response
        $result->add('blaze_player_count', $words[$index_current + 13]);
        $result->add('blaze_game_state', $words[$index_current + 14]);

        unset($buf, $words);

        return $result->fetch();
    }

    protected function process_players()
    {
        // Make sure we have a valid response
        if(!$this->hasValidResponse(self::PACKET_PLAYERS))
        {
            return array();
        }

        // Set the result to a new result instance
        $result = new GameQ_Result();

        // Make buffer for data
        $buf = new GameQ_Buffer($this->preProcess_players($this->packets_response[self::PACKET_PLAYERS]));

        $buf->skip(8); /* skip header */

        $words = $this->decodeWords($buf);

        // Not too important if players are missing.
        if (!isset ($words[0]) || $words[0] != 'OK')
        {
            return array();
        }

        // Count the number of words and figure out the highest index.
        $words_total = count($words)-1;

        // The number of player info points
        $num_tags = $words[1];

        // Pull out the tags, they start at index=3, length of num_tags
        $tags = array_slice($words, 2, $num_tags);

        // Just incase this changed between calls.
        $result->add('numplayers', $words[($num_tags+2)]);

        // Loop until we run out of positions
        for($pos=(3+$num_tags);$pos<=$words_total;$pos+=$num_tags)
        {
            // Pull out this player
            $player = array_slice($words, $pos, $num_tags);

            // Loop the tags and add the proper value for the tag.
            foreach($tags AS $tag_index => $tag)
            {
                $result->addPlayer($tag, $player[$tag_index]);
            }
        }

        // @todo: Add some team definition stuff

        unset($buf, $tags, $words, $player);

        return $result->fetch();
    }
}
<?php
/**
 * @author Johannes Griesser (2014)
 */
class Admin
{
    /**
     * @var object $db_connection The database connection
     */
    private $db_connection = null;
    /**
     * @var array $errors Collection of error messages
     */
    public $errors = array();
    /**
     * @var array $messages Collection of success / neutral messages
     */
    public $messages = array();
    /**
     * @var array $status Collection of bet status codes
     */
    public $status = array();

    /**
     * the function "__construct()" automatically starts whenever an object of this class is created,
     * you know, when you do "$bet = new Bet();"
     */
    public function __construct()
    {
        if (isset($_POST["finalscoresubmit"])) {
            $this->saveFinalScores($_POST["homescore"], $_POST["awayscore"]);
        }
    }
    
    /**
     * Checks if database connection is opened. If not, then this method tries to open it.
     * @return bool Success status of the database connecting process
     */
    private function databaseConnection()
    {
        // if connection already exists
        if ($this->db_connection != null) {
            return true;
        } else {
            try {
                // Generate a database connection, using the PDO connector
                // Also important: We include the charset, as leaving it out seems to be a security issue:
                // @see http://wiki.hashphp.org/PDO_Tutorial_for_MySQL_Developers#Connecting_to_MySQL says:
                // "Adding the charset to the DSN is very important for security reasons"
                $this->db_connection = new PDO('mysql:host='. DB_HOST .';dbname='. DB_NAME . ';charset=utf8', DB_USER, DB_PASS);
                // TODO: comment in Production
                $this->db_connection->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
                return true;
            } catch (PDOException $e) {
                $this->errors[] = MESSAGE_DATABASE_ERROR . $e->getMessage();
            }
        }
        // default return
        return false;
    }
    
    /**
     * Get match data
     * @return match data as an object if existing user
     * @return false if user_name is not found in the database
     */
    public function getMatchData()
    {
        // if database connection opened
        if ($this->databaseConnection()) {
            // database query, getting all the match data for the selected user
            $match_data = $this->db_connection->prepare('SELECT m.match_id, g.group_name, g.wiki_link group_wiki,
                                                            m.kickoff_datetime, m.broadcaster_name,
                                                        	ht.team_name home_team, at.team_name away_team,
                                                            m.goals_home, m.goals_away,
                                                            ht.flag_filename home_flag, at.flag_filename away_flag,
                                                            ht.wiki_link home_wiki, at.wiki_link away_wiki,
                                                        	v.city_name, v.stadium_name, v.wiki_city_link, v.wiki_stadium_link
                                                        FROM matches m
                                                        JOIN groups g
                                                        	ON m.group_id = g.group_id
                                                        JOIN venues v
                                                        	ON m.venue_id = v.venue_id
                                                        JOIN teams ht
                                                        	ON m.home_team_id = ht.team_id
                                                        JOIN teams at
                                                        	ON m.away_team_id = at.team_id
                                                        ORDER BY m.match_id');
            $match_data->execute();
            // get result rows (as an array of arrays)
            return $match_data->fetchAll();
        } else {
            return false;
        }
    }

    /**
     * Update submitted scores and player points
     */    
    private function saveFinalScores($homescores, $awayscores)
    {
        if ($this->databaseConnection()) {
            foreach($homescores as $index => $homescore) {
                $awayscore = $awayscores[$index];
                
                // positive integer values only
                if (ctype_digit($homescore) && ctype_digit($awayscore)) {
                    // update instead of insert if entry already exists
                    // only update if changed values & no updates after kick-off
                    $score_update = $this->db_connection->prepare('UPDATE matches SET goals_home = :goals_home, goals_away = :goals_away
                                                                   WHERE match_id = :match_id');
                    $score_update->bindValue(':match_id', $index, PDO::PARAM_INT);
                    $score_update->bindValue(':goals_home', $homescore, PDO::PARAM_INT);
                    $score_update->bindValue(':goals_away', $awayscore, PDO::PARAM_INT);
                    $score_update->execute();
                    
                    if ($score_update->rowCount()) {                        
                        // assign points
                        $points_update = $this->db_connection->prepare('UPDATE bets b 
                                                                            JOIN matches m ON b.match_id = m.match_id
                                                                        SET b.points = 
                                                                        		CASE
                                                                        			WHEN (b.goals_home = :goals_home AND b.goals_away = :goals_away) THEN 4
                                                                        			WHEN ((b.goals_home != b.goals_away)
                                                                        					AND (b.goals_home - b.goals_away = :goals_home - :goals_away)) THEN 3
                                                                        			WHEN (b.goals_home - b.goals_away = :goals_home - :goals_away)
                                                                        					OR ((b.goals_home - b.goals_away < 0) AND (:goals_home - :goals_away < 0))
                                                                        					OR ((b.goals_home - b.goals_away > 0) AND (:goals_home - :goals_away > 0))
                                                                        				THEN 2
                                                                        			ELSE 0
                                                                        		END
                                                                        WHERE b.match_id = :match_id
                                                                        AND b.updated_datetime < m.kickoff_datetime
                                                                        AND m.kickoff_datetime < utc_timestamp()');
                        $points_update->bindValue(':match_id', $index, PDO::PARAM_INT);
                        $points_update->bindValue(':goals_home', $homescore, PDO::PARAM_INT);
                        $points_update->bindValue(':goals_away', $awayscore, PDO::PARAM_INT);
                        $points_update->execute();
                        
                        // 0 points for late entries
                        $points_update = $this->db_connection->prepare('UPDATE bets b
                                                                            JOIN matches m ON b.match_id = m.match_id
                                                                        SET b.points = 0
                                                                        WHERE b.match_id = :match_id
                                                                        AND b.updated_datetime >= m.kickoff_datetime
                                                                        AND m.kickoff_datetime < utc_timestamp()');
                        $points_update->bindValue(':match_id', $index, PDO::PARAM_INT);
                        $points_update->execute();                  
                    }
                } else if (!empty($homescore) || !empty($awayscore)) {
                    $this->status[$index] = 'Error';
                }
            }
        }
    }
}

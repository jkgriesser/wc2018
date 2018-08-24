<?php
/**
 * @author Johannes Griesser (2014)
 */
class Bet
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
        if (isset($_POST["groupstagesubmit"])) {
            $this->saveGroupStageScores($_POST["homebet"], $_POST["awaybet"]);
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
     * Search into database for the stats of user_id specified as parameter
     */
    public function getGroupStageStatsByUser($user_id)
    {
        // if database connection opened
        if ($this->databaseConnection()) {
            // database query, getting all the stats for the selected user
            $user_stats = $this->db_connection->prepare('SELECT COALESCE(SUM(b.points), 0) total_points,
                                                            COUNT(CASE WHEN b.points = 2 THEN 1 END) correct_tendencies,
                                                            COUNT(CASE WHEN b.points = 3 THEN 1 END) correct_goal_differences, 
                                                            COUNT(CASE WHEN b.points = 4 THEN 1 END) correct_scores, 
                                                        	COUNT(b.bet_id) total_bets,
                                                            COUNT(m.match_id) matches_played
                                                        FROM bets b
                                                        JOIN matches m
                                                        ON b.match_id = m.match_id
                                                        JOIN groups g
                                                        ON m.group_id = g.group_id
                                                        WHERE b.user_id = :user_id
                                                        AND b.bet_valid = 1
                                                        AND (m.goals_home IS NOT NULL AND m.goals_away IS NOT NULL)
                                                        AND g.stage_name LIKE "Group%"');
            $user_stats->bindValue(':user_id', $user_id, PDO::PARAM_INT);
            $user_stats->execute();
            // get result rows (as an array of arrays)
            return $user_stats->fetchObject();
        } else {
            return false;
        }
    }
    
    /**
     * Get match data from current session user_id
     * @return match data as an object if existing user
     * @return false if user_name is not found in the database
     */
    public function getMyGroupStageMatchData()
    {
        // if database connection opened
        if (isset($_SESSION['user_id']) && $this->databaseConnection()) {
            // database query, getting all the match data for the selected user
            $match_data = $this->db_connection->prepare('SELECT m.match_id, g.group_name, g.wiki_link group_wiki,
                                                            m.kickoff_datetime, m.broadcaster_name,
                                                        	ht.team_name home_team, at.team_name away_team,
                                                            m.goals_home, m.goals_away,
                                                            b.goals_home goals_home_bet, b.goals_away goals_away_bet,
                                                            b.points, b.bet_valid,
                                                            ht.flag_filename home_flag, at.flag_filename away_flag,
                                                            ht.wiki_link home_wiki, at.wiki_link away_wiki,
                                                        	v.city_name, v.stadium_name, v.wiki_city_link, v.wiki_stadium_link,
                                                        	IF(m.kickoff_datetime > utc_timestamp(), "Y","N") is_open
                                                        FROM matches m
                                                        LEFT JOIN bets b
                                                        	ON m.match_id = b.match_id
                                                        	AND b.user_id = :user_id
                                                        JOIN groups g
                                                        	ON m.group_id = g.group_id
                                                            AND g.stage_name LIKE "Group%"
                                                        JOIN venues v
                                                        	ON m.venue_id = v.venue_id
                                                        JOIN teams ht
                                                        	ON m.home_team_id = ht.team_id
                                                        JOIN teams at
                                                        	ON m.away_team_id = at.team_id
                                                        ORDER BY m.match_id');
            $match_data->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
            $match_data->execute();
            // get result rows (as an array of arrays)
            return $match_data->fetchAll();
        } else {
            return false;
        }
    }
    
    /**
     * Get match data from current session user_id
     * @return match data as an object if existing user
     * @return false if user_name is not found in the database
     */
    public function getMyKnockoutStageMatchData()
    {
        // if database connection opened
        if (isset($_SESSION['user_id']) && $this->databaseConnection()) {
            // database query, getting all the match data for the selected user
            $match_data = $this->db_connection->prepare('SELECT m.match_id, g.stage_name, g.wiki_link group_wiki,
                                                            m.kickoff_datetime, m.broadcaster_name,
                                                        	ht.team_name home_team, at.team_name away_team,
                                                            m.goals_home, m.goals_away,
                                                            b.goals_home goals_home_bet, b.goals_away goals_away_bet,
                                                            b.points, b.bet_valid,
                                                            ht.flag_filename home_flag, at.flag_filename away_flag,
                                                            ht.wiki_link home_wiki, at.wiki_link away_wiki,
                                                        	v.city_name, v.stadium_name, v.wiki_city_link, v.wiki_stadium_link,
                                                        	IF(m.kickoff_datetime > utc_timestamp(), "Y","N") is_open
                                                        FROM matches m
                                                        LEFT JOIN bets b
                                                        	ON m.match_id = b.match_id
                                                        	AND b.user_id = :user_id
                                                        JOIN groups g
                                                        	ON m.group_id = g.group_id
                                                            AND g.stage_name NOT LIKE "Group%"
                                                        JOIN venues v
                                                        	ON m.venue_id = v.venue_id
                                                        JOIN teams ht
                                                        	ON m.home_team_id = ht.team_id
                                                        JOIN teams at
                                                        	ON m.away_team_id = at.team_id
                                                        ORDER BY m.match_id');
            $match_data->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
            $match_data->execute();
            // get result rows (as an array of arrays)
            return $match_data->fetchAll();
        } else {
            return false;
        }
    }
    
    /**
     * Search into database for the user data for given match_id
     */
    public function getUserDataByMatch($match_id)
    {
        // if database connection opened
        if ($this->databaseConnection()) {
            // database query, getting all the match data for the selected user
            $match_data = $this->db_connection->prepare('SELECT u.user_id, u.user_name, u.user_first_name, u.user_last_name, u.user_email,
                                                            CASE WHEN u.user_sex = 1 THEN "Female"
                                                                 WHEN u.user_sex = 2 THEN "Male"
                                                                 WHEN u.user_sex = 3 THEN "Non-binary"
                                                            END user_sex,
                                                        cnt.country_name, cnt.flag_filename country_flag,
                                                        t.team_name, t.flag_filename team_flag,
                                                        c.club_name, c.badge_filename club_badge,
                                                        d.department_name,
                                                        b.goals_home, b.goals_away, b.bet_valid, b.points
                                                        FROM users u                                                        
                                                        LEFT JOIN bets b
                                                        	ON u.user_id = b.user_id  
                                                        LEFT JOIN matches m
                                                        	ON b.match_id = m.match_id                                                       
                                                        LEFT JOIN countries cnt
                                                        	ON cnt.country_id = u.user_country_id
                                                        LEFT JOIN teams t
                                                        	ON t.team_id = u.user_team_id
                                                        LEFT JOIN clubs c
                                                        	ON c.club_id = u.user_club_id
                                                        LEFT JOIN departments d
                                                        	ON d.department_id = u.user_department_id
                                                        WHERE m.match_id = :match_id
                                                        AND u.user_active = 1
                                                        AND utc_timestamp() > m.kickoff_datetime
                                                        ORDER BY b.points DESC, u.user_name');
            $match_data->bindValue(':match_id', $match_id, PDO::PARAM_INT);
            $match_data->execute();
            // get result rows (as an array of arrays)
            return $match_data->fetchAll();
        } else {
            return false;
        }
    }
    
    /** 
     * Info on all played matches
     */
    public function getMatchesPlayed()
    {
        // if database connection opened
        if ($this->databaseConnection()) {
            // database query, getting all the match data for the selected user
            $match_data = $this->db_connection->prepare('SELECT m.match_id, g.group_name, g.wiki_link group_wiki,
                                                        	ht.team_name home_team, at.team_name away_team,
                                                        	ht.flag_filename home_flag, at.flag_filename away_flag,
                                                        	ht.wiki_link home_wiki, at.wiki_link away_wiki,
                                                            m.goals_home, m.goals_away
                                                        FROM matches m
                                                        JOIN groups g
                                                        	ON m.group_id = g.group_id
                                                        JOIN teams ht
                                                        	ON m.home_team_id = ht.team_id
                                                        JOIN teams at
                                                        	ON m.away_team_id = at.team_id
                                                        WHERE utc_timestamp() > m.kickoff_datetime
                                                        ORDER BY m.match_id');
            $match_data->execute();
            // get result rows (as an array of arrays)
            return $match_data->fetchAll();
        } else {
            return false;
        }
    }
    
    /**
     * Search into database for the match data of user_id specified as parameter
     */
    public function getMatchDataByUser($user_id)
    {
        // if database connection opened
        if ($this->databaseConnection()) {
            // database query, getting all the match data for the selected user
            $match_data = $this->db_connection->prepare('SELECT m.match_id, g.group_name, g.wiki_link group_wiki,
                                                            m.kickoff_datetime, m.broadcaster_name,
                                                        	ht.team_name home_team, at.team_name away_team,
                                                            m.goals_home, m.goals_away,
                                                            b.goals_home goals_home_bet, b.goals_away goals_away_bet,
                                                            b.points, b.bet_valid,
                                                            u.user_id, u.user_name, u.user_email,
                                                            ht.flag_filename home_flag, at.flag_filename away_flag,
                                                            ht.wiki_link home_wiki, at.wiki_link away_wiki,
                                                        	v.city_name, v.stadium_name, v.wiki_city_link, v.wiki_stadium_link
                                                        FROM matches m
                                                        LEFT JOIN bets b
                                                        	ON m.match_id = b.match_id
                                                        	AND b.user_id = :user_id
                                                        JOIN users u
                                                            ON u.user_id = b.user_id
                                                        JOIN groups g
                                                        	ON m.group_id = g.group_id
                                                        JOIN venues v
                                                        	ON m.venue_id = v.venue_id
                                                        JOIN teams ht
                                                        	ON m.home_team_id = ht.team_id
                                                        JOIN teams at
                                                        	ON m.away_team_id = at.team_id
                                                        WHERE utc_timestamp() > m.kickoff_datetime
                                                        ORDER BY m.match_id');
            $match_data->bindValue(':user_id', $user_id, PDO::PARAM_INT);
            $match_data->execute();
            // get result rows (as an array of arrays)
            return $match_data->fetchAll();
        } else {
            return false;
        }
    }
    
    /** 
     * Info on all players
     */
    public function getAllPlayers()
    {
        // if database connection opened
        if ($this->databaseConnection()) {
            // database query, getting user info
            $match_data = $this->db_connection->prepare('SELECT u.user_id, u.user_name, u.user_first_name, u.user_last_name, u.user_email,
                                                            CASE WHEN u.user_sex = 1 THEN "Female"
                                                                 WHEN u.user_sex = 2 THEN "Male"
                                                                 WHEN u.user_sex = 3 THEN "Non-binary"
                                                            END user_sex,
                                                        cnt.country_name, cnt.flag_filename country_flag,
                                                        t.team_name, t.flag_filename team_flag,
                                                        c.club_name, c.badge_filename club_badge,
                                                        d.department_name,
                                                            (SELECT COALESCE(sum(b.points), 0)
                                                                FROM bets b
                                                                WHERE b.user_id = u.user_id) total_points
                                                        FROM users u
                                                        LEFT JOIN countries cnt
                                                        	ON cnt.country_id = u.user_country_id
                                                        LEFT JOIN teams t
                                                        	ON t.team_id = u.user_team_id
                                                        LEFT JOIN clubs c
                                                        	ON c.club_id = u.user_club_id
                                                        LEFT JOIN departments d
                                                        	ON d.department_id = u.user_department_id
                                                        WHERE u.user_active = 1
                                                        ORDER BY u.user_name');
            $match_data->execute();
            // get result rows (as an array of arrays)
            return $match_data->fetchAll();
        } else {
            return false;
        }
    }
    
    /**
     * Retrieve the overall leaderboard
     */
    public function getOverallTable()
    {
        // if database connection opened
        if ($this->databaseConnection()) {
            // database query, getting overall table
            $match_data = $this->db_connection->prepare('SELECT o.*, ROUND(o.total_points / o.total_bets, 2) avg_points,
                                        (@rnk:=@rnk+1) rnk,
                                        (@rank:=IF(@curpoints=total_points
                                        	AND @curscore=correct_scores
                                            AND @curgd=correct_goal_diffs,@rank,@rnk)) rank,
                                        (@curpoints:=total_points) newpoints,
                                        (@curscore:=correct_scores) newscores,
                                        (@curgd:=correct_goal_diffs) newgds
                                        FROM (
                                            SELECT DISTINCT u.user_id, u.user_name, u.user_first_name, u.user_last_name, u.user_email,
                                                CASE WHEN u.user_sex = 1 THEN "F"
                                                     WHEN u.user_sex = 2 THEN "M"
                                                     WHEN u.user_sex = 3 THEN "W"
                                                END user_sex,
                                            cnt.country_name, cnt.flag_filename country_flag,
                                            t.team_name, t.flag_filename team_flag,
                                            c.club_name, c.badge_filename club_badge,
                                            d.department_name,
                                                (SELECT COALESCE(SUM(points),0)
                                                    FROM bets WHERE user_id = u.user_id) total_points,
                                                (SELECT COUNT(bet_id)
                                                 	FROM bets WHERE user_id = u.user_id
                                                	AND points IS NOT NULL) total_bets,
                                                (SELECT COUNT(CASE WHEN points = 4 THEN 1 END)
                                                    FROM bets WHERE user_id = u.user_id) correct_scores,
                                                (SELECT COUNT(CASE WHEN points = 3 THEN 1 END)
                                                    FROM bets WHERE user_id = u.user_id) correct_goal_diffs,
                                                (SELECT COUNT(CASE WHEN points = 2 THEN 1 END)
                                                    FROM bets WHERE user_id = u.user_id) correct_tendencies
                                            FROM users u
                                            LEFT JOIN bets b
                                                ON u.user_id = b.user_id  
                                            LEFT JOIN matches m
                                                ON b.match_id = m.match_id                                                       
                                            LEFT JOIN countries cnt
                                                ON cnt.country_id = u.user_country_id
                                            LEFT JOIN teams t
                                                ON t.team_id = u.user_team_id
                                            LEFT JOIN clubs c
                                                ON c.club_id = u.user_club_id
                                            LEFT JOIN departments d
                                                ON d.department_id = u.user_department_id
                                            WHERE u.user_active = 1
                                            AND b.bet_valid = 1
                                            ORDER BY total_points DESC, correct_scores DESC, correct_goal_diffs DESC, u.user_name) o,
                                            (SELECT @rnk := 0, @rank := 0, @curpoints := 0, @curscore := 0, @curgd := 0) r');
            $match_data->execute();
            // get result rows (as an array of arrays)
            return $match_data->fetchAll();
        } else {
            return false;
        }
    }
    
    /**
     * Retrieve the Battle of Sexes leaderboard
     */
    public function getBattleofSexes()
    {
        // if database connection opened
        if ($this->databaseConnection()) {
            // database query, getting battle table
            $battle_data = $this->db_connection->prepare('SELECT battle.*, (battle.points / battle.userno) avg_points,
                                                            (@rnk:=@rnk+1) rnk,
                                                            (@rank:=IF(@curscore=(battle.points / battle.userno),@rank,@rnk)) rank,
                                                            (@curscore:=(battle.points / battle.userno)) newscore
                                                         FROM
                                                                (SELECT CASE u.user_sex
                                                                        WHEN 1 THEN "Female"
                                                                        WHEN 2 THEN "Male"
                                                                        WHEN 3 THEN "Non-binary"
                                                                 		ELSE "???"
                                                                    END user_sex,
                                                                SUM(b.points) points,
                                                                COUNT(DISTINCT u.user_id) userno
                                                                FROM users u
                                                                JOIN bets b
                                                                ON u.user_id = b.user_id
                                                                WHERE u.user_active = 1
                                                                GROUP BY u.user_sex) battle,
                                                                (SELECT @rnk := 0, @rank := 0, @curpoints := 0, @curscore := 0, @curgd := 0) r
                                                         ORDER BY (battle.points / battle.userno) DESC, battle.user_sex');
            $battle_data->execute();
            // get result rows (as an array of arrays)
            return $battle_data->fetchAll();
        } else {
            return false;
        }
    }
    
    /**
     * Retrieve the Battle of Teams leaderboard
     */
    public function getBattleofTeams()
    {
        // if database connection opened
        if ($this->databaseConnection()) {
            // database query, getting battle table
            $battle_data = $this->db_connection->prepare('SELECT battle.*, (battle.points / battle.userno) avg_points,
                                                            (@rnk:=@rnk+1) rnk,
                                                            (@rank:=IF(@curscore=(battle.points / battle.userno),@rank,@rnk)) rank,
                                                            (@curscore:=(battle.points / battle.userno)) newscore
                                                          FROM
                                                            (SELECT IFNULL(t.team_name,"???") team_name, t.wiki_link, t.flag_filename,
                                                                SUM(b.points) points,
                                                                COUNT(DISTINCT u.user_id) userno
                                                            FROM users u
                                                            JOIN bets b
                                                            ON u.user_id = b.user_id
                                                            LEFT JOIN teams t
                                                            ON u.user_team_id = t.team_id
                                                            WHERE u.user_active = 1
                                                            GROUP BY u.user_team_id) battle,
                                                            (SELECT @rnk := 0, @rank := 0, @curpoints := 0, @curscore := 0, @curgd := 0) r
                                                          ORDER BY (battle.points / battle.userno) DESC, battle.team_name');
            $battle_data->execute();
            // get result rows (as an array of arrays)
            return $battle_data->fetchAll();
        } else {
            return false;
        }
    }
    
    /**
     * Retrieve the Battle of Clubs leaderboard
     */
    public function getBattleofClubs()
    {
        // if database connection opened
        if ($this->databaseConnection()) {
            // database query, getting battle table
            $battle_data = $this->db_connection->prepare('SELECT battle.*, (battle.points / battle.userno) avg_points,
                                                            (@rnk:=@rnk+1) rnk,
                                                            (@rank:=IF(@curscore=(battle.points / battle.userno),@rank,@rnk)) rank,
                                                            (@curscore:=(battle.points / battle.userno)) newscore
                                                          FROM
                                                            (SELECT IFNULL(c.club_name,"???") club_name, c.league_name, c.badge_filename,
                                                                SUM(b.points) points,
                                                                COUNT(DISTINCT u.user_id) userno
                                                            FROM users u
                                                            JOIN bets b
                                                            ON u.user_id = b.user_id
                                                            LEFT JOIN clubs c
                                                            ON u.user_club_id = c.club_id
                                                            WHERE u.user_active = 1
                                                            GROUP BY u.user_club_id) battle,
                                                            (SELECT @rnk := 0, @rank := 0, @curpoints := 0, @curscore := 0, @curgd := 0) r
                                                          ORDER BY (battle.points / battle.userno) DESC, battle.club_name');
            $battle_data->execute();
            // get result rows (as an array of arrays)
            return $battle_data->fetchAll();
        } else {
            return false;
        }
    }
    
    /**
     * Retrieve the Battle of Countries leaderboard
     */
    public function getBattleofCountries()
    {
        // if database connection opened
        if ($this->databaseConnection()) {
            // database query, getting battle table
            $battle_data = $this->db_connection->prepare('SELECT battle.*, (battle.points / battle.userno) avg_points,
                                                            (@rnk:=@rnk+1) rnk,
                                                            (@rank:=IF(@curscore=(battle.points / battle.userno),@rank,@rnk)) rank,
                                                            (@curscore:=(battle.points / battle.userno)) newscore
                                                          FROM
                                                            (SELECT IFNULL(c.country_name,"???") country_name, c.flag_filename,
                                                                SUM(b.points) points,
                                                                COUNT(DISTINCT u.user_id) userno
                                                            FROM users u
                                                            JOIN bets b
                                                            ON u.user_id = b.user_id
                                                            LEFT JOIN countries c
                                                            ON u.user_country_id = c.country_id
                                                            WHERE u.user_active = 1
                                                            GROUP BY u.user_country_id) battle,
                                                            (SELECT @rnk := 0, @rank := 0, @curpoints := 0, @curscore := 0, @curgd := 0) r
                                                          ORDER BY (battle.points / battle.userno) DESC, battle.country_name');
            $battle_data->execute();
            // get result rows (as an array of arrays)
            return $battle_data->fetchAll();
        } else {
            return false;
        }
    }
    
    /**
     * Retrieve the Battle of Departments leaderboard
     */
    public function getBattleofDepts()
    {
        // if database connection opened
        if ($this->databaseConnection()) {
            // database query, getting battle table
            $battle_data = $this->db_connection->prepare('SELECT battle.*, (battle.points / battle.userno) avg_points,
                                                            (@rnk:=@rnk+1) rnk,
                                                            (@rank:=IF(@curscore=(battle.points / battle.userno),@rank,@rnk)) rank,
                                                            (@curscore:=(battle.points / battle.userno)) newscore
                                                          FROM
                                                            (SELECT IFNULL(d.department_name,"???") department_name, bus.business_name,
                                                                SUM(b.points) points,
                                                                COUNT(DISTINCT u.user_id) userno
                                                            FROM users u
                                                            JOIN bets b
                                                            ON u.user_id = b.user_id
                                                            LEFT JOIN departments d
                                                            ON u.user_department_id = d.department_id
                                                            LEFT JOIN businesses bus
                                                            ON d.business_id = bus.business_id
                                                            WHERE u.user_active = 1
                                                            GROUP BY u.user_department_id) battle,
                                                            (SELECT @rnk := 0, @rank := 0, @curpoints := 0, @curscore := 0, @curgd := 0) r
                                                          ORDER BY (battle.points / battle.userno) DESC, battle.department_name');
            $battle_data->execute();
            // get result rows (as an array of arrays)
            return $battle_data->fetchAll();
        } else {
            return false;
        }
    }
    
    /**
     * Get stats for overall table page
     */
    public function getOverallStats()
    {
        // if database connection opened
        if ($this->databaseConnection()) {
            // database query, getting all the stats for the selected user
            $user_stats = $this->db_connection->prepare('SELECT stats.*,
                                                        	(ROUND(stats.total_points / stats.total_players, 2)) avg_pts_player,
                                                            (ROUND(stats.total_points / stats.matches_played / stats.total_players, 2)) avg_pts_match,
                                                            (4 * stats.matches_remaining) pts_remaining
                                                        FROM
                                                        (SELECT
                                                            (SELECT count(u.user_id) from users u
                                                                WHERE user_active = 1
                                                                AND EXISTS (SELECT *
                                                                    FROM bets b
                                                                    WHERE u.user_id = b.user_id)) total_players,
                                                            (SELECT SUM(points) from bets
                                                            WHERE bet_valid = 1) total_points,
                                                            (SELECT count(bet_id) from bets
                                                            WHERE bet_valid = 1) total_bets,
                                                            (SELECT count(match_id) from matches
                                                            WHERE goals_home IS NOT NULL) matches_played,
                                                            (SELECT count(match_id) from matches
                                                            WHERE goals_home IS NULL) matches_remaining
                                                        FROM dual) stats');
            $user_stats->execute();
            // get result rows (as an array of arrays)
            return $user_stats->fetchObject();
        } else {
            return false;
        }
    }

    /**
     * Insert or update submitted scores 
     */    
    private function saveGroupStageScores($homescores, $awayscores)
    {
        if ($this->databaseConnection()) {
            foreach($homescores as $index => $homescore) {
                $awayscore = $awayscores[$index];
                
                // positive integer values only
                if (ctype_digit($homescore) && ctype_digit($awayscore)) {
                    // update instead of insert if entry already exists
                    // only update if changed values & no updates after kick-off
                    $score_insert_update = $this->db_connection->prepare('INSERT INTO bets (user_id, match_id, goals_home, goals_away,
                                                                                bet_valid, points, created_datetime, updated_datetime)
                                                                            SELECT :user_id, :match_id, :goals_home, :goals_away, 1, NULL, utc_timestamp(), utc_timestamp()
                                                                            FROM matches m
                                                                            WHERE m.match_id = :match_id
                                                                            AND m.kickoff_datetime > utc_timestamp()
                                                                         ON DUPLICATE KEY UPDATE
                                                                    	    bets.goals_home = CASE WHEN (@home_updated := bets.goals_home <> VALUES(goals_home)) 
                                                                                    THEN VALUES(goals_home) ELSE bets.goals_home END,
                                                                            bets.goals_away = CASE WHEN (@away_updated := bets.goals_away <> VALUES(goals_away))
                                                                    				THEN VALUES(goals_away) ELSE bets.goals_away END,
                                                                            bets.updated_datetime = CASE WHEN @home_updated OR @away_updated
                                                                    				THEN VALUES(updated_datetime) ELSE bets.updated_datetime END');
                    $score_insert_update->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
                    $score_insert_update->bindValue(':match_id', $index, PDO::PARAM_INT);
                    $score_insert_update->bindValue(':goals_home', $homescore, PDO::PARAM_INT);
                    $score_insert_update->bindValue(':goals_away', $awayscore, PDO::PARAM_INT);
                    $score_insert_update->execute();
                    
                    if ($score_insert_update->rowCount()) {
                        $this->status[$index] = 'OK';
                    } else {
                        $this->status[$index] = 'No change';
                    }
                } else if (!empty($homescore) || !empty($awayscore)) {
                    $this->status[$index] = 'Error';
                }
            }
        }
    }
}

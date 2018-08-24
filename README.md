# wc2018
World Cup betting game, 2018 edition.

Players are to guess scores for each game durning the group and subsequently the knockout stages.
This is only possible until kick-off of each fixture.

Once a result is known and entered into the system, the application calculates each participant's points.

Following this entry, players can see the results by match and player.

Standings are presented as follows:
* Overall Leaderboard (total points by player)
* Battle of the...
  * Sexes (Note: This can be turned off via config.php; chosen by players during registration)
  * Teams (Aggregated national side score; side chosen by players during registration)
  * Clubs (Aggregated club side score; side chosen by players during registration)
  * Countries (Aggregated country score; scountryide chosen by players during registration)
  * Departments (Note: This can be turned off via config.php; department chosen by players during registration)

## Scoring
4 points are awarded for the correct score
3 points are awarded for the correct result and correct number of goals
2 points are awarded for the correct result

## Setup
First, set up a MySQL database and run the dbuser.sql (alter the password in the "grant usage" query),
tables.sql and insert.sql scripts in the installation folder.

To note: insert.sql contains information regarding the participating teams, groups, venues and fixutres.
It needs to be updated accordingly.

Next, configure the following in config.php (config folder):
* DB_HOST, DB_NAME, DB_USER, DB_PASS: Database credentials
* Optional (for BB Forum): DB_NAME_PHPBB, DB_USER_PHPBB, DB_PASS_PHPBB
* COOKIE_SECRET_KEY: Random key
* EMAIL_SMTP_USERNAME, EMAIL_SMTP_PASSWORD, EMAIL_PASSWORDRESET_FROM, EMAIL_VERIFICATION_FROM: Gmail account credentials
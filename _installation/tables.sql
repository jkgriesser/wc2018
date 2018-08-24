CREATE TABLE IF NOT EXISTS `wc2018`.`groups` (
	`group_id` tinyint(2) unsigned NOT NULL,
	`group_name` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
	`stage_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
	`wiki_link` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
 	PRIMARY KEY (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `wc2018`.`teams` (
	`team_id` tinyint(2) unsigned NOT NULL,
	`team_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
	`group_id` tinyint(2) unsigned NOT NULL,
	`team_active` tinyint(1) NOT NULL DEFAULT '1',
	`wiki_link` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
	`flag_filename` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
	PRIMARY KEY (`team_id`),
	FOREIGN KEY (`group_id`) REFERENCES groups(group_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `wc2018`.`venues` (
	`venue_id` tinyint(2) unsigned NOT NULL,
	`city_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
	`stadium_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
	`timezone_name` varchar(5) COLLATE utf8_unicode_ci DEFAULT NULL,
	`timezone_offset` tinyint(2) DEFAULT NULL,
	`iana_timezone_name` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
	`wiki_city_link` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
	`wiki_stadium_link` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
 	PRIMARY KEY (`venue_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `wc2018`.`countries` (
	`country_id` tinyint(3) unsigned NOT NULL,
	`country_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
	`flag_filename` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
	PRIMARY KEY (`country_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `wc2018`.`businesses` (
	`business_id` tinyint(3) unsigned NOT NULL,
	`business_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
	PRIMARY KEY (`business_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `wc2018`.`departments` (
	`department_id` tinyint(3) unsigned NOT NULL,
	`business_id` tinyint(3) unsigned NOT NULL,
	`department_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
	PRIMARY KEY (`department_id`),
	FOREIGN KEY (`business_id`) REFERENCES businesses(business_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `wc2018`.`players` (
	`player_id` tinyint(3) unsigned NOT NULL,
	`team_id` tinyint(2) unsigned NOT NULL,
	`first_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
	`last_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
	`club_name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
	`wiki_link` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
	`goals_scored` tinyint(2) unsigned NOT NULL,
	`yellow_cards` tinyint(1) unsigned NOT NULL,
	`red_cards` tinyint(1) unsigned NOT NULL,
	`golden_ball` tinyint(1) unsigned NOT NULL,
	`golden_boot` tinyint(1) unsigned NOT NULL,
	`golden_glove` tinyint(1) unsigned NOT NULL,
	`best_young_player` tinyint(1) unsigned NOT NULL,
	PRIMARY KEY (`player_id`),
	FOREIGN KEY (`team_id`) REFERENCES teams(team_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `wc2018`.`managers` (
	`manager_id` tinyint(2) unsigned NOT NULL,
	`team_id` tinyint(2) unsigned NOT NULL,
	`first_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
	`last_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
	`all_star_manager` tinyint(1) unsigned NOT NULL,
	PRIMARY KEY (`manager_id`),
	FOREIGN KEY (`team_id`) REFERENCES teams(team_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `wc2018`.`clubs` (
	`club_id` tinyint(3) unsigned NOT NULL,
	`club_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
	`league_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
	`badge_filename` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
	PRIMARY KEY (`club_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `wc2018`.`users` (
	`user_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'auto incrementing user_id of each user, unique index',
	`user_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT 'user''s name, unique',
	`user_first_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
	`user_last_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
	`user_password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'user''s password in salted and hashed format',
	`user_email` varchar(254) COLLATE utf8_unicode_ci NOT NULL COMMENT 'user''s email, unique',
	`user_access_level` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT 'user''s access level (between 0 and 255, 255 = administrator)',
	`user_active` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'user''s activation status',
	`user_activation_hash` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'user''s email verification hash string',
	`user_password_reset_hash` char(40) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'user''s password reset code',
	`user_password_reset_timestamp` bigint(20) DEFAULT NULL COMMENT 'timestamp of the password reset request',
	`user_failed_logins` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'user''s failed login attemps',
	`user_last_failed_login` int(10) DEFAULT NULL COMMENT 'unix timestamp of last failed login attempt',
	`user_registration_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
	`user_update_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
	`user_registration_ip` varchar(39) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0.0.0.0',
	`user_sex` tinyint(1) unsigned DEFAULT NULL COMMENT '1-Female, 2-Male, 3-Non-binary',
	`user_country_id` tinyint(3) unsigned DEFAULT NULL,
	`user_team_id` tinyint(2) unsigned DEFAULT NULL,
	`user_club_id` tinyint(3) unsigned DEFAULT NULL,
	`user_department_id` tinyint(3) unsigned DEFAULT NULL,
	PRIMARY KEY (`user_id`),
	UNIQUE KEY `user_name` (`user_name`),
	UNIQUE KEY `user_email` (`user_email`),
	FOREIGN KEY (`user_country_id`) REFERENCES countries(country_id),
	FOREIGN KEY (`user_team_id`) REFERENCES teams(team_id),
	FOREIGN KEY (`user_club_id`) REFERENCES clubs(club_id),
	FOREIGN KEY (`user_department_id`) REFERENCES departments(department_id)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='user data';

CREATE TABLE IF NOT EXISTS `wc2018`.`user_connections` (
	`id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
	`user_id` int(11) unsigned NOT NULL,
	`user_rememberme_token` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
	`user_last_visit` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
	`user_last_visit_agent` text COLLATE utf8_unicode_ci,
	`user_login_ip` varchar(39) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0.0.0.0',
	`user_login_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
	`user_login_agent` text COLLATE utf8_unicode_ci,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `wc2018`.`matches` (
	`match_id` tinyint(2) unsigned NOT NULL,
	`kickoff_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
	`broadcaster_name` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
	`venue_id` tinyint(2) unsigned NOT NULL,
	`group_id` tinyint(2) unsigned NOT NULL,
	`home_team_id` tinyint(2) unsigned DEFAULT NULL,
	`away_team_id` tinyint(2) unsigned DEFAULT NULL,
	`goals_home` tinyint(2) unsigned DEFAULT NULL,
	`goals_away` tinyint(2) unsigned DEFAULT NULL,
	`et_goals_home` tinyint(2) unsigned DEFAULT NULL,
	`et_goals_away` tinyint(2) unsigned DEFAULT NULL,
	`shootout_goals_home` tinyint(2) unsigned DEFAULT NULL,
	`shootout_goals_away` tinyint(2) unsigned DEFAULT NULL,
	`pens_awarded_home` tinyint(2) unsigned DEFAULT NULL,
	`pens_awarded_away` tinyint(2) unsigned DEFAULT NULL,
	`pens_scored_home` tinyint(2) unsigned DEFAULT NULL,
	`pens_scored_away` tinyint(2) unsigned DEFAULT NULL,
	`yellow_cards_home` tinyint(2) unsigned DEFAULT NULL,
	`yellow_cards_away` tinyint(2) unsigned DEFAULT NULL,
	`red_cards_home` tinyint(1) unsigned DEFAULT NULL,
	`red_cards_away` tinyint(1) unsigned DEFAULT NULL,
	PRIMARY KEY (`match_id`),
	FOREIGN KEY (`venue_id`) REFERENCES venues(venue_id),
	FOREIGN KEY (`group_id`) REFERENCES groups(group_id),
	FOREIGN KEY (`home_team_id`) REFERENCES teams(team_id),
	FOREIGN KEY (`away_team_id`) REFERENCES teams(team_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `wc2018`.`bets` (
	`bet_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
	`user_id` int(11) unsigned NOT NULL,
	`match_id` tinyint(2) unsigned NOT NULL,
	`goals_home` tinyint(2) NOT NULL,
	`goals_away` tinyint(2) NOT NULL,
	`bet_valid` tinyint(1) unsigned NOT NULL,
	`points` tinyint(1) unsigned DEFAULT NULL,
	`created_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
	`updated_datetime` datetime DEFAULT NULL,
	PRIMARY KEY (`bet_id`),
	FOREIGN KEY (`user_id`) REFERENCES users(user_id),
	FOREIGN KEY (`match_id`) REFERENCES matches(match_id),
	CONSTRAINT uc_user_match UNIQUE (user_id, match_id)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `wc2018`.`bonus_bets` (
	`bonus_bets_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	`user_id` int(11) unsigned NOT NULL,
	`winner_team_id` tinyint(2) unsigned DEFAULT NULL,
	`runners_up_team_id` tinyint(2) unsigned DEFAULT NULL,
	`third_place_team_id` tinyint(2) unsigned DEFAULT NULL,
	`goals_scored` tinyint(2) unsigned DEFAULT NULL,
	`yellow_cards` tinyint(1) unsigned DEFAULT NULL,
	`red_cards` tinyint(1) unsigned DEFAULT NULL,
	`fair_play_team_id` tinyint(2) unsigned DEFAULT NULL,
	`worst_discipline_team_id` tinyint(2) unsigned DEFAULT NULL,
	`most_entertaining_team_id` tinyint(2) unsigned DEFAULT NULL,
	`golden_ball_player_id` tinyint(3) unsigned DEFAULT NULL,
	`golden_boot_player_id` tinyint(3) unsigned DEFAULT NULL,
	`golden_glove_player_id` tinyint(3) unsigned DEFAULT NULL,
	`best_young_player_id` tinyint(3) unsigned DEFAULT NULL,
	`all_star_manager_id` tinyint(2) unsigned DEFAULT NULL,
	`bet_valid` tinyint(1) unsigned NOT NULL,
	`points` tinyint(1) unsigned DEFAULT NULL,
	`created_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
	`updated_datetime` datetime DEFAULT NULL,
	PRIMARY KEY (`bonus_bets_id`),
	FOREIGN KEY (`user_id`) REFERENCES users(user_id),
	FOREIGN KEY (`winner_team_id`) REFERENCES teams(team_id),
	FOREIGN KEY (`runners_up_team_id`) REFERENCES teams(team_id),
	FOREIGN KEY (`third_place_team_id`) REFERENCES teams(team_id),
	FOREIGN KEY (`fair_play_team_id`) REFERENCES teams(team_id),
	FOREIGN KEY (`worst_discipline_team_id`) REFERENCES teams(team_id),
	FOREIGN KEY (`most_entertaining_team_id`) REFERENCES teams(team_id),
	FOREIGN KEY (`golden_ball_player_id`) REFERENCES players(player_id),
	FOREIGN KEY (`golden_boot_player_id`) REFERENCES players(player_id),
	FOREIGN KEY (`golden_glove_player_id`) REFERENCES players(player_id),
	FOREIGN KEY (`best_young_player_id`) REFERENCES players(player_id),
	FOREIGN KEY (`all_star_manager_id`) REFERENCES managers(manager_id)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
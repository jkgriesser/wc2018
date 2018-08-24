<?php

/**
 * Please note: we can use unencoded characters like ö, é etc here as we use the html5 doctype with utf8 encoding
 * in the application's header (in views/_header.php). To add new languages simply copy this file,
 * and create a language switch in your root files.
 */

// login & registration classes
define("MESSAGE_ACCOUNT_NOT_ACTIVATED", "Your account is not activated yet. Please click on the confirm link in the mail.");
define("MESSAGE_CAPTCHA_WRONG", "Captcha not completed!");
define("MESSAGE_COOKIE_INVALID", "Invalid cookie");
define("MESSAGE_DATABASE_ERROR", "Database connection problem.");
define("MESSAGE_EMAIL_ALREADY_EXISTS", "This email address is already registered. Please use the \"I forgot my password\" page if you don't remember it.");
define("MESSAGE_EMAIL_CHANGE_FAILED", "Sorry, your email change failed.");
define("MESSAGE_EMAIL_CHANGED_SUCCESSFULLY", "Your email address has been changed successfully. New email address is ");
define("MESSAGE_EMAIL_EMPTY", "Email cannot be empty");
define("MESSAGE_EMAIL_INVALID", "Your email address is not in a valid email format");
define("MESSAGE_EMAIL_SAME_LIKE_OLD_ONE", "Sorry, that email address is the same as your current one. Please choose another one.");
define("MESSAGE_EMAIL_TOO_LONG", "Email cannot be longer than 254 characters");
define("MESSAGE_LINK_PARAMETER_EMPTY", "Empty link parameter data.");
define("MESSAGE_LOGGED_OUT", "You have been logged out.");
// The "login failed"-message is a security improved feedback that doesn't show a potential attacker if the user exists or not
define("MESSAGE_LOGIN_FAILED", "Login failed.");
define("MESSAGE_OLD_PASSWORD_WRONG", "Your OLD password was wrong.");
define("MESSAGE_OPTIONAL_CHANGE_FAILED", "Sorry, your optional detail change failed.");
define("MESSAGE_OPTIONAL_CHANGED_SUCCESSFULLY", "Optional details changed!");
define("MESSAGE_PASSWORD_BAD_CONFIRM", "Passwords are not the same");
define("MESSAGE_PASSWORD_CHANGE_FAILED", "Sorry, your password changing failed.");
define("MESSAGE_PASSWORD_CHANGED_SUCCESSFULLY", "Password changed!");
define("MESSAGE_PASSWORD_EMPTY", "Password field was empty");
define("MESSAGE_PASSWORD_RESET_MAIL_FAILED", "Password reset mail NOT successfully sent! Error: ");
define("MESSAGE_PASSWORD_RESET_MAIL_SUCCESSFULLY_SENT", "Password reset mail successfully sent!");
define("MESSAGE_PASSWORD_TOO_SHORT", "Password has a minimum length of 6 characters");
define("MESSAGE_PASSWORD_WRONG", "Wrong password. Try again.");
define("MESSAGE_PASSWORD_WRONG_3_TIMES", "You have entered an incorrect password 3 or more times already. Please wait 30 seconds to try again.");
define("MESSAGE_REGISTRATION_ACTIVATION_NOT_SUCCESSFUL", "Sorry, no such id/verification code combination here...");
define("MESSAGE_REGISTRATION_ACTIVATION_SUCCESSFUL", "Activation was successful! You can now log in!");
define("MESSAGE_REGISTRATION_FAILED", "Sorry, your registration failed. Please go back and try again.");
define("MESSAGE_RESET_LINK_HAS_EXPIRED", "Your reset link has expired. Please use the reset link within one hour.");
define("MESSAGE_VERIFICATION_MAIL_ERROR", "Sorry, we could not send you an verification mail. Your account has NOT been created.");
define("MESSAGE_VERIFICATION_MAIL_NOT_SENT", "Verification Mail NOT successfully sent! Error: ");
define("MESSAGE_VERIFICATION_MAIL_SENT", "Your account has been created successfully and we have sent you an email. Please click the VERIFICATION LINK within that mail. Check your spam / junk folder if the message does not appear in your inbox.");
define("MESSAGE_USER_DOES_NOT_EXIST", "This user does not exist");
define("MESSAGE_USERNAME_BAD_LENGTH", "Username cannot be shorter than 2 or longer than 64 characters");
define("MESSAGE_FIRSTNAME_BAD_LENGTH", "First Name cannot be longer than 64 characters");
define("MESSAGE_LASTNAME_BAD_LENGTH", "Last Name cannot be longer than 64 characters");
define("MESSAGE_USERNAME_CHANGE_FAILED", "Sorry, your chosen username renaming failed");
define("MESSAGE_USERNAME_CHANGED_SUCCESSFULLY", "Your username has been changed successfully. New username is ");
define("MESSAGE_USERNAME_EMPTY", "Username field was empty");
define("MESSAGE_FIRSTNAME_EMPTY", "First Name field was empty");
define("MESSAGE_LASTNAME_EMPTY", "Last Name field was empty");
define("MESSAGE_USERNAME_EXISTS", "Sorry, that username is already taken. Please choose another one.");
define("MESSAGE_USERNAME_INVALID", "Username does not fit the name scheme: only a-Z and numbers are allowed, 2 to 64 characters");
define("MESSAGE_USERNAME_SAME_LIKE_OLD_ONE", "Sorry, that username is the same as your current one. Please choose another one.");
define("MESSAGE_FIRSTNAME_INVALID", "First name cannot be empty");
define("MESSAGE_FIRSTNAME_SAME_LIKE_OLD_ONE", "Sorry, that first name is the same as your current one. Please choose another one.");
define("MESSAGE_FIRSTNAME_CHANGE_FAILED", "Sorry, your chosen first name renaming failed");
define("MESSAGE_FIRSTNAME_CHANGED_SUCCESSFULLY", "Your first name has been changed successfully. New first name is ");
define("MESSAGE_LASTNAME_INVALID", "Last name cannot be empty");
define("MESSAGE_LASTNAME_SAME_LIKE_OLD_ONE", "Sorry, that last name is the same as your current one. Please choose another one.");
define("MESSAGE_LASTNAME_CHANGE_FAILED", "Sorry, your chosen last name renaming failed");
define("MESSAGE_LASTNAME_CHANGED_SUCCESSFULLY", "Your last name has been changed successfully. New last name is ");

// views
define("WORDING_BACK_TO_LOGIN", "Back to Login Page");
define("WORDING_CHANGE_EMAIL", "Change email");
define("WORDING_CHANGE_FIRSTNAME", "Change first name");
define("WORDING_CHANGE_LASTNAME", "Change last name");
define("WORDING_CHANGE_PASSWORD", "Change password");
define("WORDING_CHANGE_USERNAME", "Change username");
define("WORDING_CHANGE_OPTIONAL", "Change details");
define("WORDING_CURRENTLY", "currently");
define("WORDING_EDIT_USER_DATA", "Edit user data");
define("WORDING_EDIT_YOUR_CREDENTIALS", "You are logged in and can edit your credentials here");
define("WORDING_FORGOT_MY_PASSWORD", "I forgot my password");
define("WORDING_LOGIN", "Login");
define("WORDING_OPTIONAL", "Optional");
define("WORDING_SIGNIN", "Sign in");
define("WORDING_LOGOUT", "Log out");
define("WORDING_NEW_EMAIL", "Change email address");
define("WORDING_NEW_FIRSTNAME", "Change First Name");
define("WORDING_NEW_LASTNAME", "Change Last Name");
define("WORDING_NEW_PASSWORD", "Change Password");
define("WORDING_NEW_PASSWORD_PLACEHOLDER", "Password - Min. 6 characters");
define("WORDING_NEW_PASSWORD_REPEAT", "Repeat Password");
define("WORDING_NEW_PASSWORD_REPEAT_PLACEHOLDER", "Re-enter Password");
define("WORDING_NEW_USERNAME", "Change Username");
define("WORDING_OLD_PASSWORD", "Your OLD Password");
define("WORDING_PASSWORD", "Password");
define("WORDING_PASSWORD_PLACEHOLDER", "Password");
define("WORDING_PROFILE_PICTURE", "Your profile picture (from gravatar):");
define("WORDING_REGISTRATION", "Registration");
define("WORDING_REGISTER", "Register");
define("WORDING_REGISTER_NEW_ACCOUNT", "Register new account");
define("WORDING_REGISTRATION_EMAIL", "Email Address");
define("WORDING_REGISTRATION_EMAIL_PLACEHOLDER", "Email - Required for verification mail");
define("WORDING_REGISTRATION_PASSWORD", "Password");
define("WORDING_REGISTRATION_PASSWORD_PLACEHOLDER", "Password - Min. 6 characters");
define("WORDING_REGISTRATION_PASSWORD_REPEAT", "Repeat Password");
define("WORDING_REGISTRATION_PASSWORD_REPEAT_PLACEHOLDER", "Re-enter Password");
define("WORDING_REGISTRATION_TEAM", "My Team");
define("WORDING_REGISTRATION_TEAM_SELECT", "Pick a team...");
define("WORDING_REGISTRATION_CLUB", "My Club (England only)");
define("WORDING_REGISTRATION_CLUB_SELECT", "Pick a club...");
define("WORDING_REGISTRATION_COUNTRY", "My Country");
define("WORDING_REGISTRATION_COUNTRY_SELECT", "Pick a country...");
define("WORDING_REGISTRATION_DEPARTMENT", "My Department");
define("WORDING_REGISTRATION_DEPARTMENT_SELECT", "Pick a department...");
define("WORDING_REGISTRATION_USERNAME", "Username");
define("WORDING_REGISTRATION_USERNAME_PLACEHOLDER", "Username - Letters and numbers only");
define("WORDING_REGISTRATION_FIRSTNAME", "First Name");
define("WORDING_REGISTRATION_FIRSTNAME_PLACEHOLDER", "Enter First Name");
define("WORDING_REGISTRATION_LASTNAME", "Last Name");
define("WORDING_REGISTRATION_LASTNAME_PLACEHOLDER", "Enter Last Name");
define("WORDING_REGISTRATION_GRAVATAR", "Add your own logo with Gravatar");
define("WORDING_REGISTRATION_SEX", "Sex");
define("WORDING_REGISTRATION_FEMALE", "Female");
define("WORDING_REGISTRATION_MALE", "Male");
define("WORDING_REGISTRATION_TRANS", "Non-binary");
define("WORDING_REMEMBER_ME", "Keep me logged in");
define("WORDING_REQUEST_PASSWORD_RESET", "Username");
define("WORDING_RESET_PASSWORD", "Reset my password");
define("WORDING_SUBMIT_SCORES", "Submit scores");
define("WORDING_SUBMIT_NEW_PASSWORD", "Submit new password");
define("WORDING_USERNAME", "Username");
define("WORDING_USERNAME_PLACEHOLDER", "Enter username or email");
define("WORDING_WELCOME", "Sejam muito bem-vindos!");
define("WORDING_YOU_ARE_LOGGED_IN_AS", "You are logged in as ");

// navbar
define("NAV_TITLE", "Russia 2018");
define("NAV_TOGGLE", "Toggle navigation");
define("NAV_HOME", "Home");
define("NAV_BETS", "My Bets");
define("NAV_BETS_GROUPSTAGE", "Group Stage");
define("NAV_BETS_KNOCKOUT", "Knockout Stage");
define("NAV_BETS_BONUSCOMP", "Bonus Competition");
define("NAV_TABLES", "Tables");
define("NAV_RESULTS", "Results");
define("NAV_ADMIN", "Admin");
define("NAV_FORUM", "Forum");
define("NAV_HELP", "Help");

// globals
define("AUTHOR", "Johannes Griesser");
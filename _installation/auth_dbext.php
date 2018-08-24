<?php
/**
 * External MySQL auth plug-in for phpBB3
 *
 * Authentication plug-ins is largely down to Sergey Kanareykin, our thanks to him.
 *
 * @package login
 * @version $Id: auth_dbext.php 8602 2009-04-09 16:38:27Z nzeyimana $
 * @copyright NONE: use as you see fit but no guarantees
 * @license NONE: use as you see fit but no guarantees
 *
 */

/**
 * @ignore
 */
if (!defined('IN_PHPBB'))
{
    exit;
}

/**
 *
 * @return boolean|string false if the user is identified and else an error message
 */

function init_dbext()
{
    // TODO: do any needed initialization
}

/**
 * Login function
 */
function login_dbext(&$username, &$password)
{
    global $db;

    include($_SERVER['DOCUMENT_ROOT'] . "/config/config.php");

    if (!$username)
    {
        return array(
            'status'    => LOGIN_ERROR_USERNAME,
            'error_msg' => 'LOGIN_ERROR_USERNAME',
            'user_row'  => array('user_id' => ANONYMOUS),
        );
    }

    $db_connection = new PDO('mysql:host='. DB_HOST .';dbname='. DB_NAME_PHPBB . ';charset=utf8', DB_USER_PHPBB, DB_PASS_PHPBB);
    $db_connection->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );

    // check connection
    if ($db_connection == null)
    {
        return array(
            'status'    => LOGIN_ERROR_EXTERNAL_AUTH,
            'error_msg' => 'LOGIN_ERROR_EXTERNAL_AUTH',
            'user_row'  => array('user_id' => ANONYMOUS),
        );
    }

    $query_user = $db_connection->prepare('SELECT * FROM wc2018.users WHERE user_name = :user_name');
    $query_user->bindValue(':user_name', $username, PDO::PARAM_STR);
    $query_user->execute();
    $result = $query_user->fetchObject();
    $user_email = $result->user_email;

    if (isset($result->user_id)) {
        // Auto-login if in app else check the password
        if(!isset($_SESSION['user_logged_in']))
        {
            // do not allow empty password
            if (!$password)
            {
                return array(
                    'status'    => LOGIN_ERROR_PASSWORD,
                    'error_msg' => 'NO_PASSWORD_SUPPLIED',
                    'user_row'  => array('user_id' => ANONYMOUS),
                );
            }
            if (!password_verify($password, $result->user_password_hash)) {
                return array(
                    'status'    => LOGIN_ERROR_PASSWORD,
                    'error_msg' => 'CUR_PASSWORD_ERROR',
                    'user_row'  => array('user_id' => ANONYMOUS),
                );
            }
        }

        $query_user = $db_connection->prepare('SELECT user_id, username, user_password, user_passchg, user_email, user_type
                                                FROM ' . USERS_TABLE . ' WHERE username = :user_name');
        $query_user->bindValue(':user_name', $username, PDO::PARAM_STR);
        $query_user->execute();
        $result = $query_user->fetch(PDO::FETCH_ASSOC);

        if (!empty($result)) {
            // User inactive...
            if ($result['user_type'] == USER_INACTIVE || $result['user_type'] == USER_IGNORE)
            {
                return array(
                    'status'    => LOGIN_ERROR_ACTIVE,
                    'error_msg' => 'ACTIVE_ERROR',
                    'user_row'  => $result,
                );
            }
            // Successful login...
            return array(
                'status'    => LOGIN_SUCCESS,
                'error_msg' => false,
                'user_row'  => $result,
            );
        } else {
            // this is the user's first login so create an empty profile
            return array(
                'status'    => LOGIN_SUCCESS_CREATE_PROFILE,
                'error_msg' => false,
                'user_row'  => user_row_dbext($db_connection, $username, $user_email, ''),
            );
        }
    } else {
        return array(
            'status'    => LOGIN_ERROR_USERNAME,
            'error_msg' => 'LOGIN_ERROR_USERNAME',
            'user_row'  => array('user_id' => ANONYMOUS),
        );
    }
}

/**
 * This function generates an array which can be passed to the user_add function in order to create a user
 */
function user_row_dbext($db_connection, $username, $user_email, $password)
{
    global $db, $config, $user;

    // first retrieve default group id   
    $query_group = $db_connection->prepare('SELECT group_id
        FROM ' . GROUPS_TABLE . ' WHERE group_name = "REGISTERED"
        AND group_type = ' . GROUP_SPECIAL);
    $query_group->execute();
    $result = $query_group->fetchObject();

    if (!isset($result->group_id))
    {
        trigger_error('NO_GROUP');
    }

    // generate user account data
    return array(
        'username'      => $username,
        //'user_password' => phpbb_hash($password), // Note: on my side, I don't use this because I want all passwords to remain on the remote system
        'user_email'    => $user_email, // You can retrieve this Email at the time the user is authenticated from the external table
        'group_id'      => (int) $result->group_id,
        'user_type'     => USER_NORMAL,
        'user_ip'       => $user->ip,
    );
}

/**
 * Crypt the $password with the PHP 5.5's password_hash()
 * @return 60 character hash password string
 */
function getPasswordHash($password)
{
    // check if we have a constant HASH_COST_FACTOR defined (in config/config.php),
    // if so: put the value into $hash_cost_factor, if not, make $hash_cost_factor = null
    $hash_cost_factor = (defined('HASH_COST_FACTOR') ? HASH_COST_FACTOR : null);

    // crypt the user's password with the PHP 5.5's password_hash() function, results in a 60 character hash string
    // the PASSWORD_DEFAULT constant is defined by the PHP 5.5, or if you are using PHP 5.3/5.4, by the password hashing
    // compatibility library. the third parameter looks a little bit shitty, but that's how those PHP 5.5 functions
    // want the parameter: as an array with, currently only used with 'cost' => XX.
    return password_hash($password, PASSWORD_DEFAULT, array('cost' => $hash_cost_factor));
}

?>
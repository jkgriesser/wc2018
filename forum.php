<?php
// load php-login components
require_once('php-login.php');
// the login object will do all login/logout stuff automatically
// so this single line handles the entire login process.
$login = new Login();

if (!$login->isUserLoggedIn()) {
	header('Location: index.php');
	exit();
}

include('views/header.php');
?>

<script language="javascript" type="text/javascript">
    function resizeIframe(obj) {
        obj.style.height = obj.contentWindow.document.body.scrollHeight + 'px';
    }
</script>

<?php
define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './forum/';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include('forum/common.php');

require_once($phpbb_root_path . 'includes/utf/utf_normalizer.' . $phpEx);

// Start session management
$user->session_begin();
$auth->acl($user->data);
$user->setup();

$username = request_var('username', $_SESSION['user_name']);
$password = request_var('password', '');

// Log out if the user has edited the username / email address in the app
if ($user->data['is_registered']
        && ($user->data['username'] != $_SESSION['user_name']
        || $user->data['user_email'] != $_SESSION['user_email'])) {
    $user->session_kill();
    $user->session_begin();
}

if(!$user->data['is_registered'] && isset($username) && isset($password))
{
    $result = $auth->login($username, $password, true);
    if (!$result['status'] == LOGIN_SUCCESS) {
        echo $user->lang[$result['error_msg']];
    }
}
?>

<!-- Placed here due to navbar conflict (hamburger icon) with phpBB  -->
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="js/bootstrap.min.js"></script>

<?php
$forum_link = '';
if (null !==  $_GET['f']) {
    if (null !== $_GET['t']) {
        $forum_link = '/viewtopic.php?f=' . $_GET['f'] . '&t=' . $_GET['t'];
    } else {
        $forum_link = '/viewforum.php?f=' . $_GET['f'];
    }
}
?>

<div class="container">
	<iframe src="forum<?php echo $forum_link ?>" frameborder="0" scrolling="no" onload='javascript:resizeIframe(this);' />
</div> <!-- /container -->
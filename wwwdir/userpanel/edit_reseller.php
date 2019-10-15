<?php
define('MAIN_DIR', '/home/xtreamcodes/');
define('IPTV_PANEL_DIR', MAIN_DIR . 'iptv_xtream_codes/');
require_once(IPTV_PANEL_DIR . 'wwwdir/includes/utils.php');

$username = is_reseller();
if (!$username) {
    header('Location: ../index.php?error=NO_ADMIN');

} 
$error = false;
if (isset($_GET['action']) && $_GET['action'] == "edit_user" && isset($_GET['user_id']) && isset($_POST['username'])) {
    $check_username = DB::query("select id, username from reg_users  where username=%s and id!=%d ", $_POST['username'], $_GET['user_id']);
    if (!$check_username) {
        $old_money = DB::query("select credits from reg_users WHERE id=%s", $_GET['user_id']);
        $old = $old_money[0]['credits'];
        if (!is_numeric($_POST['credits']) || $username['credits'] < (int)$_POST['credits'] - (int)$old) {
            $no_money = true;
        } else {
            if ($_POST['password']) {
                $result = DB::query('UPDATE `reg_users` SET password=%s WHERE id=%d and parent_member=%d',
                    crypt($_POST['password'], '$6$rounds=20000$xtreamcodes$'),
                    $_GET['user_id'],
                    $_SESSION['user_id']);
            }
            $result = DB::query('UPDATE `reg_users` SET username=%s,email=%s,credits=%d,notes=%s, dnsreseller=%s WHERE id=%d and parent_member=%d',
                $_POST['username'],
                $_POST['email'],
                $_POST['credits'],
                $_POST['notes'],
		$_POST['dnsreseller'],
                $_GET['user_id'],
                $_SESSION['user_id']);
            if ($result) {

                $new_money = (int)$_POST['credits'] - (int)$old;
                $togli_money = DB::query("UPDATE reg_users SET credits = (credits - %d) WHERE id=%d", $new_money, $_SESSION['user_id']);
		    header('Location: reseller.php');


            }
        }

    } else {
die();
        $error = true;
    }
}

$user = get_user();

get_head();
?>

<body>

<!-- file picker -->
<div id="dialog-explorer" title="File Browser" style="display: none;">
    <div id="dialogContent"></div>
</div>

<section id="secondary_bar">
    <div class="user">
        <p><?php echo $username['username'] ?> (<font color="orange"><u><?php echo $username['credits'] ?>
                    Credits</u></font>) - <a href="index.php?action=logout"><u>Logout</u></a></p>

    </div>

</section>

<?php get_sidebar();?>
<section id="main" class="column">
    <?php
    if (isset($check_username) && $check_username) {
        echo "<h4 class='alert_warning'>User with this username already exists!</h4>";
    }
    if (isset($no_money) && $no_money) {
        echo "<h4 class='alert_warning'>No Money!</h4>";
    }


    ?>

    <article class="module width_full">
        <header><h3 class="tabs_involved">Edit Reseller User</h3>
        </header>
        <form method="post" name="form" action="edit_reseller.php?action=edit_user&user_id=<?php echo $_GET['user_id'] ?>">
            <div class="module_content">

                <fieldset>
                    <legend><b>Provide User Details</b></legend>
                    <table id="settings">
                        <tr>
                            <td>Username</td>
                            <td><input type="text" name="username" value="<?php echo $user['username'] ?>" required/>
                            </td>
                        <tr>
                            <td>Password (<font color="red">Complete this if you want to reset</font>)</td>
                            <td><input type="text" name="password"/></td>
                        </tr>
                        <tr>
                            <td>E-Mail</td>
                            <td><input type="text" name="email" value="<?php echo $user['email']; ?>" required/></td>
                        </tr>

                        <tr>
                            <td>Credits</td>
                            <td><input type="text" name="credits" value="<?php echo $user['credits'] ?>"/></td>
                        </tr>
                        <tr>
                            <td>Dns</td>
                            <td><input type="text" name="dnsreseller" value="<?php echo $user['dnsreseller'] ?>"/></td>
                        </tr>

                        <tr>
                            <td>Notes</td>
                            <td><textarea name="notes"><?php echo $user['notes'] ?></textarea></td>
                        </tr>
                    </table>
                </fieldset>
            </div>
            <footer>
                <div class="submit_link">
                    <input type="submit" value="Edit Registered User" class="alt_btn">
                </div>
            </footer>
        </form>
    </article>
    <div class="spacer"></div>
</section>
</body>

<script src="../templates/js/jquery.datetimepicker.js"></script>
<script>
    $('#expire_date').datetimepicker();
</script>
</html>

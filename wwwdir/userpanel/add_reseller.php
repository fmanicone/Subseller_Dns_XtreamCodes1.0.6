<?php
define('MAIN_DIR', '/home/xtreamcodes/');
define('IPTV_PANEL_DIR', MAIN_DIR . 'iptv_xtream_codes/');
require_once(IPTV_PANEL_DIR . 'wwwdir/includes/utils.php');

$username = is_reseller();
if (!$username) {
    header('Location: ../index.php?error=NO_ADMIN');

} 
$no_money = false;
date_default_timezone_set('UTC');
if (isset($_GET['action']) && $_GET['action'] == "add_user" && isset($_POST['username'])) {
    $check_username = DB::query("select id, username from reg_users  where username=%s", $_POST['username']);
    if (!$check_username) {
        if (!is_numeric($_POST['credits']) || $username['credits'] < $_POST['credits']) {
            $no_money = true;
        } else {
            $result = DB::query('INSERT INTO `reg_users` (`username`,`password`,`email`,`date_registered`,`member_group_id`,`parent_member`,`verified`,`credits`,`notes`,`default_lang`, `dnsreseller`) VALUES (%s,%s,%s,%d,6,%d,1,%d,%s,%s,%s)',
                $_POST['username'],
                crypt($_POST['password'], '$6$rounds=20000$xtreamcodes$'),
                $_POST['email'],
                strtotime("now"),
                $_SESSION['user_id'],
                $_POST['credits'],
                $_POST['notes'],
                'English',
                $_POST['dnsreseller']);
            $togli_money = DB::query("UPDATE reg_users SET credits = credits - %d WHERE id=%s", $_POST['credits'], $_SESSION['user_id']);
        }
    }
}


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

<?php get_sidebar(); ?>
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
        <header><h3 class="tabs_involved">Register New User</h3>
        </header>
        <form method="post" name="form" action="add_reseller.php?action=add_user">
            <div class="module_content">

                <fieldset>
                    <legend><b>Provide User Details</b></legend>
                    <table id="settings">
                        <tr>
                            <td>Username</td>
                            <td><input type="text" name="username" required/></td>
                        <tr>
                            <td>Password</td>
                            <td><input type="text" name="password" required/></td>
                        </tr>
                        <tr>
                            <td>E-Mail</td>
                            <td><input type="text" name="email" required/></td>
                        </tr>

                        <tr>
                            <td>Credits</td>
                            <td><input type="text" name="credits"/></td>
                        </tr>
                        <tr>
                            <td>Dns</td>
                            <td><input type="text" name="dnsreseller"/></td>
                        </tr>
                        <tr>
                            <td>Notes</td>
                            <td><textarea name="notes"></textarea></td>
                        </tr>
                    </table>
                </fieldset>

            </div>
            <footer>
                <div class="submit_link">
                    <input type="submit" value="Register New User" class="alt_btn">
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

<?php
define('MAIN_DIR', '/home/xtreamcodes/');
define('IPTV_PANEL_DIR', MAIN_DIR . 'iptv_xtream_codes/');
require_once(IPTV_PANEL_DIR . 'wwwdir/includes/utils.php');
$username = is_admin();
if (!$username) {
    header('Location: ../index.php?error=NO_ADMIN');

} else {
    $username = $username[0];
}
date_default_timezone_set('UTC');
if (isset($_GET['action']) && $_GET['action'] == "add_user" && isset($_POST['username'])) {
    $check_username = DB::query("select id, username from reg_users  where username=%s", $_POST['username']);
    if (!$check_username) {
        $result = DB::query('INSERT INTO `reg_users` (`username`,`password`,`email`,`date_registered`,`member_group_id`,`parent_member`,`verified`,`credits`,`notes`,`default_lang`, `dnsreseller`) VALUES (%s,%s,%s,%d,%d,%d,1,%d,%s,%s,%s)',
            $_POST['username'],
            crypt($_POST['password'], '$6$rounds=20000$xtreamcodes$'),
            $_POST['email'],
            strtotime("now"),
            $_POST['member_group_id'],
            $_POST['parent_member'],
            $_POST['credits'],
            $_POST['notes'],
            $_POST['default_lang'],
	    $_POST['dnsreseller']);
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
        <p><?php echo $username['username'] ?> (<a href="index.php?action=logout"><u>Logout</u></a>)</p>
    </div>

</section>

<?php get_sidebar_admin();?>
<section id="main" class="column">
	<?php
	if(isset($check_username) && $check_username) {
	 echo "<h4 class='alert_warning'>User with this username already exists!</h4>";
	}
	?>

    <article class="module width_full">
        <header><h3 class="tabs_involved">Register New User</h3>
        </header>
        <form method="post" name="form" action="add_reguser.php?action=add_user">
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
                            <td>Default Language</td>
                            <td>
                                <select name="default_lang">
                                    <option value="English">English</option>
                                </select>
                        </tr>

                        <tr>
                            <td>Group Members</td>
                            <td>
                                <select name="member_group_id" required>
                                    <option value="" selected>-</option>
                                    <?php if (get_group_member()) {
                                        foreach (get_group_member() as $group_member) {
                                            echo "<option value='" . $group_member['group_id'] . "'>" . $group_member['group_name'] . "</option>";
                                        }
                                    }
                                    ?>                           </select>
                            </td>
                        </tr>
                        <tr>
                            <td>Assign reseller</td>
                            <td>
                                <select name="parent_member" required>
                                    <option value="0" selected>-</option>
                                    <?php if (get_reseller_list()) {
                                        foreach (get_reseller_list() as $reseller) {
                                            echo "<option value='" . $reseller['id'] . "'>" . $reseller['username'] . "</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <td>Credits (Reseller Only)</td>
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

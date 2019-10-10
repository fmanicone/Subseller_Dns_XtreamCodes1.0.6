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
$error = false;
if (isset($_GET['action']) && $_GET['action'] == "edit_user" && isset($_GET['user_id']) && isset($_POST['username'])) {
    $check_username = DB::query("select id, username from reg_users  where username=%s and id!=%d", $_POST['username'], $_GET['user_id']);
    if (!$check_username) {
        if ($_POST['password']) {
            $result = DB::query('UPDATE `reg_users` SET password=%s WHERE id=%d',
                crypt($_POST['password'], '$6$rounds=20000$xtreamcodes$'),
                $_GET['user_id']);
        }
        $result = DB::query('UPDATE `reg_users` SET username=%s,email=%s,member_group_id=%d,parent_member=%d,credits=%d,notes=%s,default_lang=%s, dnsreseller=%s  WHERE id=%d',
            $_POST['username'],
            $_POST['email'],
            $_POST['member_group_id'],
            $_POST['parent_member'],
            $_POST['credits'],
            $_POST['notes'],
            $_POST['default_lang'],
	    $_POST['dnsreseller'],
            $_GET['user_id']);

} else {
    $error = true;
}
}

$user = get_user_admin();

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
    if ($error && isset($_POST['username'])) {
        echo "<h4 class='alert_warning'>Please fill out all the fields or user with this username already exists!</h4>";
    }
    ?>
    <article class="module width_full">
        <header><h3 class="tabs_involved">Edit Registered User</h3>
        </header>
        <form method="post" name="form" action="edit_reguser.php?action=edit_user&user_id=<?php echo $_GET['user_id']?>">
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
                            <td>Default Language</td>
                            <td>
                                <select name="default_lang">
                                    <option value="English" selected>English</option>
                                </select>
                        </tr>

                        <tr>
                            <td>Group Members</td>
                            <td>
                                <select name="member_group_id" required>
                                    <?php if (get_group_member()) {
                                        foreach (get_group_member() as $group_member) {
                                            if ($group_member['group_id'] == $user['member_group_id']) {
                                                $selected = "selected";
                                            } else {
                                                $selected = "";
                                            }
                                            echo "<option value='" . $group_member['group_id'] . "' " . $selected . ">" . $group_member['group_name'] . "</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>Assign reseller</td>
                            <td>
                                <select name="parent_member">
                                    <option value="0">-</option>
                                    <?php if (get_reseller_list()) {
                                        foreach (get_reseller_list() as $reseller) {
                                            if ($reseller['id'] == $user['parent_member']) {
                                                $selected = "selected";
                                            } else {
                                                $selected = "";
                                            }
                                            echo "<option value='" . $reseller['id'] . "' " . $selected . ">" . $reseller['username'] . "</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </td>
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

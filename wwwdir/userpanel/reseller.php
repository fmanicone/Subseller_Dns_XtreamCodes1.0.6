<?php
define('MAIN_DIR', '/home/xtreamcodes/');
define('IPTV_PANEL_DIR', MAIN_DIR . 'iptv_xtream_codes/');
require_once(IPTV_PANEL_DIR . 'wwwdir/includes/utils.php');
$username = is_reseller();
if (!$username) {
    header('Location: ../index.php?error=NO_ADMIN');
}

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case "load_resellers":
            $result = DB::query("select id as recid, status,  username, credits, notes, dnsreseller from reg_users  where member_group_id=6 and parent_member=%s", $_SESSION['user_id']);
            if ($result) {
                $recods = array();
                $i = 0;
                foreach ($result as $record) {
                    $i++;
                    $enabled = ($record['status'] == 0) ? "enabled" : "disable";
                    $record['enable'] = ($record['status'] == 1) ? "<font color='green'><b>Enabled</b></font>" : "<font color='red'><b>Disabled</b></font>";
                    $record['options'] = "<a href=\"reseller.php?action=" . $enabled . "&current=" . $record['status'] . "&user_id=" . $record['recid'] . "\" class=\"table-icon disable\" title=\"Enable\/Disable\"></a>\r\n
                                          <a href=\"edit_reseller.php?action=edit_user&user_id=" . $record['recid'] . "\" class=\"table-icon edit\" title=\"Edit\"></a>\r\n                          
                                          <a onclick=\"return confirm('Are you sure you want to delete this line?')\" href=\"reseller.php?action=user_delete&user_id=" . $record['recid'] . "\" class=\"table-icon delete\" title=\"Delete User\"></a>\t\t\t     \r\n";
                    $count = DB::query("select count(*) as a from users  where member_id=%d", $record['recid']);
                    $record['users'] = $count[0]['a'];
                    $records['records'][] = $record;
                }
                $records['total'] = $i;
            }
            $json = json_encode($records);
            print_r($json);
            die();
            break;
        case "disable":
            $result = DB::query("update reg_users set status=0  where id=%s", $_GET['user_id']);
            $result = DB::query("update users set enabled=0  where member_id=%s", $_GET['user_id']);
            break;
        case "enabled":
            $result = DB::query("update reg_users set status=1  where id=%s", $_GET['user_id']);
            $result = DB::query("update users set enabled=1  where member_id=%s", $_GET['user_id']);
            break;
        case "user_delete":
            $result = DB::query("delete from reg_users where id=%s", $_GET['user_id']);
            $result = DB::query("delete from users  where member_id=%s", $_GET['user_id']);
            break;
        default:

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


    <article class="module width_full">
        <header><h3 class="tabs_involved">Manage Reseller Panel</h3>
        </header>

        <div id="dialogDiv" style="display: none;">Loading content... Please wait!</div>
        <div id="grid" style="text-align:center; width: 100%; height: 800px;"></div>


        <script>
            $(document).ready(function () {
                $('#grid').w2grid({
                    name: 'grid',
                    header: "Manage Reseller Panel",
                    show: {
                        header: true,
                        lineNumbers: true,
                        toolbar: true,
                        footer: true

                    },
                    columns: [

                        {field: 'enable', caption: "Status", size: '25%', resizable: true, sortable: true},
                        {field: 'username', caption: "Username", size: '35%', resizable: true, sortable: true},
                        {field: 'users', caption: "Users", size: '120px', resizable: true, sortable: true},
                        {field: 'credits', caption: "Credit", size: '120px', resizable: true, sortable: true},
                        {field: 'notes', caption: "Notes", size: '20%', resizable: true, sortable: true},
                        {field: 'dnsreseller', caption: "Dns", size: '20%', resizable: true, sortable: true},
                        {field: 'options', caption: "Options", size: '50%', resizable: true, sortable: true}
                    ],
                    searches: [
                        {type: 'text', field: 'username', caption: "Username"},
                        {type: 'text', field: 'notes', caption: "Notes"},
                    ]
                });

                w2ui['grid'].load('reseller.php?action=load_resellers');

            });
        </script>
        <div class="spacer"></div>
</section>
</body>

<script src="../templates/js/jquery.datetimepicker.js"></script>
<script>
    $('#expire_date').datetimepicker();
</script>
</html>



<?php
session_start();

error_reporting(0);

@ini_set("user_agent", "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:9.0) Gecko/20100101 Firefox/9.0");
@ini_set("default_socket_timeout", 10);

require_once(IPTV_PANEL_DIR . 'wwwdir/includes/meekrodb.2.3.class.php');
$_INFO = json_decode(file_get_contents(IPTV_PANEL_DIR . 'config'), true);

DB::$user = $_INFO['db_user'];
DB::$password = $_INFO['db_pass'];
DB::$dbName = $_INFO['db_name'];
$server_id=$_INFO['server_id'];
function get_user_admin()
{
	$result = DB::queryFirstRow("select id, username,email,member_group_id,parent_member,credits,notes,default_lang, dnsreseller from reg_users where id=%d", $_GET['user_id']);
	return $result;

}



function get_reseller_list()
{
	$result = DB::query("select id, username from reg_users join member_groups On member_group_id=group_id where group_name='Resellers'");
	return $result;
}

function is_admin()
{
	if (isset($_SESSION['user_id'])) {
		$result = DB::query("select username from reg_users join member_groups On member_group_id=group_id where group_name='Administrators' AND id=%s", $_SESSION['user_id']);
		return $result;
	} else {
		return false;
	}
}




function get_logo()
{
    $url_logo = DB::queryFirstField("select logo_url from settings");

    return $url_logo;
}

function get_servername()
{
    $servername = DB::queryFirstField("select server_name from settings");

    return $servername;
}

function get_reseller()
{
    $result = DB::query("select id, username from reg_users join member_groups On member_group_id=group_id where group_name='Resellers'");
    return $result;
}

function get_group_member()
{
    $result = DB::query("select group_id, group_name from member_groups");
    return $result;
}

function is_reseller()
{
    if (isset($_SESSION['user_id'])) {
        $result = DB::queryFirstRow("select username, credits, dnsreseller, group_name  from reg_users join member_groups On member_group_id=group_id where group_name='Resellers' AND id=%s", $_SESSION['user_id']);
        return $result;
    } else {
        return false;
    }
}

function dns_check()
{
    if (isset($_SESSION['user_id'])) {
        $result = DB::queryFirstRow("select username, credits, dnsreseller, group_name  from reg_users join member_groups On member_group_id=group_id where id=%s", $_SESSION['user_id']);
        return $result;
    } else {
        return false;
    }
}



function get_user()
{
    $result = DB::query("select id, username,email,credits,notes, dnsreseller from reg_users where parent_member=%d and id=%d", $_SESSION['user_id'], $_GET['user_id']);
    return $result[0];

}

function get_sidebar()
{
    ?>
    <aside id="sidebar" class="column">
        <form class="logo">
            <img src="<?php echo get_logo(); ?>" width="70%"/>
        </form>
        <hr/>
        <h3>Streaming Lines</h3>
        <ul class="toggle">
            <li class="icn_add_user"><a href="add_user.php">Create New Line</a></li>
            <li class="icn_extend"><a href="extend.php">Extend Line</a></li>
            <li class="icn_user_stats"><a href="user_stats.php">User Stats</a></li>
            <li class="icn_acc"><a href="index.php">Manage Lines</a></li>
        </ul>
        <h3>MAG Devices</h3>
        <ul class="toggle">
            <li class="icn_add_mag"><a href="add_mag.php">Add New MAG Device</a></li>
            <li class="icn_extend"><a href="extend_mag.php">Extend MAG</a></li>
            <li class="icn_manage_mag"><a href="manage_mag.php">Manage MAG Devices</a></li>
        </ul>

        <h3>Profile</h3>
        <ul class="toggle">
            <li class="icn_add_user"><a href="profile.php">Manage profile</a></li>
        </ul>
        <h3>Tickets Support</h3>
        <ul class="toggle">
            <li class="icn_new_ticket"><a href="new_ticket.php">Create Support Ticket</a></li>
            <li class="icn_ticket_system"><a href="manage_tickets.php">Manage Tickets</a></li>
        </ul>
        <footer>
            <hr/>
            <p>
                <strong>Developed By Multififa Copyright &copy; 2019-2020</strong>

            </p>

        </footer>
    </aside>

	<?php

}

function get_head(){
?>
	<!doctype html>
<html lang="en">
	<meta charset="utf-8"/>
    <meta http-equiv="X-Frame-Options" content="deny">
	<title><?php echo get_servername(); ?></title>


	<link rel="stylesheet" href="../templates/css/layout.css" type="text/css" media="screen" />
    <link rel="stylesheet" type="text/css" href="../templates/css/jquery.datetimepicker.css"/>
    <link rel="stylesheet" type="text/css" href="../templates/css/jquery-te-1.4.0.css"/>
    <link rel="stylesheet" type="text/css" href="../templates/css/jqueryFileTree.css" />
    <link rel="stylesheet" type="text/css" href="../templates/css/jquery.dataTables.min.css" />
	<link rel="stylesheet" type="text/css" href="../templates/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="../templates/css/jquery-ui.css" />
    <link rel="stylesheet" type="text/css" href="../templates/css/jquery.jOrgChart.css" />
    <link rel="stylesheet" type="text/css" href="../templates/css/w2ui-1.4.2.min.css" />
    <link rel="stylesheet" type="text/css" href="../templates/css/DDScript.css" />

    <script type="text/javascript" src="../templates/js/jquery.min.js"></script>
    <script type="text/javascript" src="../templates/js/jquery-ui.min.js"></script>
    <script type="text/javascript" src="../templates/js/jquery.blockUI.js"></script>

    <script type="text/javascript" src="../templates/js/jquery.searchit.js"></script>
    <script type="text/javascript" src="../templates/js/jqueryFileTree.js"></script>
    <script type="text/javascript" src="../templates/js/jquery-te-1.4.0.min.js"></script>
    <script type="text/javascript" src="../templates/js/multiselect.js"></script>
    <script type="text/javascript" src="../templates/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="../templates/js/w2ui-1.4.2.min.js"></script>

    <script type="text/javascript" src="../templates/js/DDScript.js"></script>
	<script type="text/javascript">

    function openfileDialog(idInput,ServerID)
    {
        $("#dialog-explorer").dialog({
                autoOpen: false,
                width: $(document).width()*0.5,
                height: $(document).height()*0.5,
                buttons: {
                        "I'm Done": function () {
                            $(this).dialog("close");
                        }
                }
            });

        $("#dialog-explorer").dialog('open');
        $('#dialogContent').fileTree({root: '/', script: 'filexplorer.php', server: ServerID, multiFolder: false, loadMessage: 'Loading files...'},
        		function(file) {
        			$("#" + idInput).val(file).change();

        			$("#dialog-explorer").dialog("close");
        		},
                function(dire){ $("#" + idInput).val(dire).change();   }
        	);


    	return false;
    }

    function loadAjax(url)
    {
        $.blockUI({
            message: "Please wait for this process to be completed. It can take a while. Do not close this window !",
            fadeIn: 700,
            fadeOut: 700,
            css:{
                border: 'none',
                padding: '15px',
                backgroundColor: '#000',
                '-webkit-border-radius': '10px',
                '-moz-border-radius': '10px',
                opacity: .5,
                color: '#fff'
            }
        });

        $.ajax({
            url: url,
            success: function(response)
            {
                $.unblockUI();
                window.location.href=window.location.href
            }
        });

    }

        function selectAll(selectBox,selectAll) {
            for(i=0;i<selectBox.length;i++)
            {
                if (typeof selectBox[i] == "string") {
                    selectBox_form = document.getElementById(selectBox[i]);
                }
                // is the select box a multiple select box?
                if (selectBox_form.type == "select-multiple") {
                    for (var j = 0; j < selectBox_form.options.length; j++) {
                         selectBox_form.options[j].selected = selectAll;
                    }
                }
            }
        }

        function ajax_request_dialog(site_url,title,custom_background,custom_width,custom_height)
        {
            if(typeof(custom_background)==='undefined') custom_background = 'white';
            if(typeof(custom_width)==='undefined') custom_width = '1020';
            if(typeof(custom_height)==='undefined') custom_height = '700';
            if(typeof(debug)==='undefined') debug = '';

            $("#dialogDiv").dialog({autoOpen:false,modal:true,width:custom_width,height:custom_height,resizeable:true});



            $("#dialogDiv").dialog({title: title});
            $("#dialogDiv").dialog('open');
            $('.ui-widget-overlay').css('background', 'white');
            $('.ui-dialog-content').css('background', custom_background);
            $('#dialogDiv').html("<center><img src='../templates/images/big_load.GIF' /><br />Please wait while loading...</center>");
            $.ajax({
                        url: site_url,
                        success: function(response)
                        {
                            $('#dialogDiv').html(response);
                        }
                    });
        }


    	$(document).ready(function() {

           $('#bouquet_selection').draggable({multiple: true});
          	   $('table.display').DataTable({
                    "aaSorting": [],
                    "aLengthMenu": [[50,100, -1], [50,100, "All"]],
                    "iDisplayLength": 100,
                    responsive: true
        });
       	 }
    	);

    </script>
    </head>
    <?php
}

function get_sidebar_admin(){
	?>
	<aside id="sidebar" class="column">
    <form class="logo">
        <img src="<?php echo get_logo(); ?>" width="70%"/>
    </form>
    <hr/>
    <h3>Main</h3>
    <ul class="toggle">
        <li class="icn_dashboard"><a href="index.php">Dashboard</a></li>
        <li class="icn_restreams"><a href="server_connections.php">Server Connections</a></li>
        <li class="icon_connections"><a href="connection_logs.php">Client Connection Logs</a></li>
        <li class="icn_catch_reshares"><a href="catch_reshares.php">Detect VPN/proxies/servers & ISP lock</a></li>
        <li class="icn_ticket_system"><a href="manage_tickets.php">Manage Tickets</a></li>
    </ul>
    <h3>Streaming Lines</h3>
    <ul class="toggle">
        <li class="icn_add_user"><a href="add_user.php">Create New Line</a></li>
        <li class="icn_acc"><a href="users.php">Manage Lines</a></li>
    </ul>
    <h3>Live Streams/VOD (Video On Demand)</h3>
    <ul class="toggle">
        <li class="icn_add"><a href="add_stream.php">Add New Stream</a></li>
        <li class="icn_streams"><a href="streams.php">Manage Streams</a></li>
        <li class="icn_epg"><a href="epg.php">Manage EPG</a></li>
        <br/>
        <li class="icn_addmovie"><a href="add_movie.php">Add New Movie</a></li>
        <li class="icn_add_movies"><a href="import_movies.php">Import Multiple Movies</a></li>
        <li class="icn_movie"><a href="movies.php">Manage Movies</a></li>
        <br/>
        <li class="icn_create_channel"><a href="create_channel.php">Create Channel</a> (<font color="red">BETA</font>)
        </li>
        <li class="icn_mng_channels"><a href="manage_cchannels.php">Manage Created Channels</a></li>
        <br/>
        <li class="icn_massedit"><a href="mass_sedits.php">Mass edit streams</a></li>
        <li class="icn_transcode"><a href="tprofiles.php">Transcoding Profiles</a></li>
        <li class="icn_categories"><a href="categories.php">Manage Categories</a></li>
        <li class="icn_order"><a href="stream_tools.php">Stream tools</a></li>
    </ul>
    <h3>Streaming Servers</h3>
    <ul class="toggle">
        <li class="icn_add_server"><a href="add_server.php">Add New Server</a></li>
        <li class="icn_servers"><a href="servers.php">Manage Servers</a></li>
    </ul>
    <h3>MAG Devices</h3>
    <ul class="toggle">
        <li class="icn_add_mag"><a href="add_mag.php">Add New MAG Device</a></li>
        <li class="icn_manage_mag"><a href="manage_mag.php">Manage MAG Devices</a></li>
        <li class="icn_events"><a href="manage_events.php">Manage Events</a></li>
    </ul>
    <h3>Bouquets</h3>
    <ul class="toggle">
        <li class="icn_add"><a href="add_bouquet.php">Add New Bouquet</a></li>
        <li class="icn_manage"><a href="bouquets.php">Manage Bouquets</a></li>
    </ul>
    <h3>Registered Users</h3>
    <ul class="toggle">
        <li class="icn_add_user"><a href="add_reguser.php">Register New User</a></li>
        <li class="icn_manage_users"><a href="mng_regusers.php">Manage Registered Users</a></li>
        <li class="icn_group"><a href="mng_groups.php">Manage Group Members</a></li>
    </ul>
    <h3>Resellers</h3>
    <ul class="toggle">
        <li class="icn_add_package"><a href="add_packages.php">New Package</a></li>
        <li class="icn_package"><a href="mng_packages.php">Manage Packages</a></li>
    </ul>
    <h3>Statistics</h3>
    <ul class="toggle">
        <li class="icn_streams_stats"><a href="streams_stats.php">Stream Stats</a></li>
        <li class="icn_user_stats"><a href="user_stats.php">User Stats</a></li>
    </ul>
    <h3>Security plug-ins</h3>
    <ul class="toggle">
        <li class="icn_block"><a href="block_ips.php">Block IP/CIDR</a></li>
        <li class="icn_ua"><a href="user_agents.php">Block User Agent</a></li>
    </ul>
    <h3>Logs</h3>
    <ul class="toggle">
        <li class="icn_client_log"><a href="client_request_log.php">Client Request Log</a></li>
        <li class="icn_login_logs"><a href="login_logs.php">Login Logs</a></li>
        <li class="icn_error_log"><a href="panel_error_log.php">Panel Error Log</a></li>
        <li class="icn_error_log"><a href="reg_userlog.php">Line Activity Log</a></li>
    </ul>
    <h3>System</h3>
    <ul class="toggle">
        <li class="icn_video_settings"><a href="video_settings.php">Video Settings</a></li>
        <li class="icn_settings"><a href="settings.php">General Settings</a></li>
        <li class="icn_email"><a href="emailmsg.php">Edit email messages</a></li>
        <li class="icn_database"><a href="database.php">Database Manager</a></li>
        <li class="icn_task"><a href="task_manager.php">Task Manager</a></li>
        <li class="icn_tools"><a href="tools.php">Tools</a></li>
    </ul>
    <h3>Xtream-Codes</h3>
    <ul class="toggle">
        <li class="icn_convert"><a href="convert.php">Migrate To Xtream-Codes</a></li>
        <li class="icn_licence"><a href="licence.php">License</a></li>
        <li class="icn_update"><a href="update.php">Software update</a></li>
        <li class="icn_area"><a href="member_area.php">Members Area</a></li>
    </ul>
    <footer>
        <hr/>
        <p>
			<strong>Developed By Multififa Copyright &copy; 2019-2020</strong
		</p>

    </footer>
</aside>
<?php
}


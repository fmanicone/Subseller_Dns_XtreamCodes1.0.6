<?php
define('MAIN_DIR', '/home/xtreamcodes/');
define('IPTV_PANEL_DIR', MAIN_DIR . 'iptv_xtream_codes/');
require_once(IPTV_PANEL_DIR . 'wwwdir/includes/utils.php');

$username = dns_check();
if (!$username)  {
$value=["isreseller"=>false, "dns"=>"", "dnsserver"=>"" ];
} else {
$dnsserver = DB::queryFirstField("select IF(LENGTH(domain_name)>0, domain_name, server_ip) from streaming_servers where id=%d", $server_id);
if (!$username['dnsreseller'] || $username['dnsreseller']==""){
$username['dnsreseller']=$dnsserver;
}
if ($username['group_name']=="Resellers") {
$value=["isreseller"=>true, "dns"=>$username['dnsreseller'], "dnsserver"=>$dnsserver];
}else {
$value=["isreseller"=>false, "dns"=>$username['dnsreseller'], "dnsserver"=>$dnsserver];
}
}
echo json_encode($value);

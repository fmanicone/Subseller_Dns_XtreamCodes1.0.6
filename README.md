# Subseller_Dns_XtreamCodes1.0.6
Add Subseller and Dns forx Xtream Codes 1.0.6

-Add files in wwwdir


-Execute  query

alter table reg_users add `parent_member` int null;

alter table reg_users add dnsreseller varchar(70) null;

INSERT INTO member_groups (`group_id`, `group_name`, `group_color`, `is_banned`, `is_admin`, `is_reseller`, `total_allowed_gen_trials`, `total_allowed_gen_in`, `can_delete`) VALUES (6,'SubResellers', '#FF9933', 0, 0, 1, 10, 'day', 1);


- Clean up your browser cache.......



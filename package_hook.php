<?php

$module_url		= $vars['svn_path'] . '/modules/branches/' . $vars['rver'] ;
$framework_url	= $vars['svn_path'] . '/freepbx/' . $vars['fwbranch'] . '/amp_conf/htdocs/admin';
$recordings_url	= $vars['svn_path'] . '/freepbx/' . $vars['fwbranch'] . '/amp_conf/htdocs/recordings';
//remove stale files
if(!run_cmd('rm -rf ' . $vars['fw_lang'] . '/htdocs ')
) {
	die('FATAL: failed to remove previously exported directories' . PHP_EOL);
}

//grab frameowrk language files files
$fresh = array(
		$framework_url . '/i18n' 	=> $vars['fw_lang'] . '/htdocs/admin/i18n',
		$recordings_url . '/locale' => $vars['fw_lang'] . '/htdocs/recordings/locale',
);
foreach ($fresh as $f => $d) {
	if(!run_cmd('svn export ' . $f . ' ' . $d)) {
		die('FATAL: failed to export fresh ' . $f . PHP_EOL);
	}
}

//add module language files
$modules = run_cmd('svn list ' . $module_url . '|grep /');
foreach ($modules as $m) {
	if(!run_cmd('svn export ' . $module_url . '/' . $m . '/i18n ' 
				. $vars['fw_lang'] . '/htdocs/admin/modules/' . $m . '/i18n')
) {
		die('FATAL: failed to export i18n for ' . $m . PHP_EOL);
	}
}

//save revision number
if(!run_cmd('echo SVN VERSION: `svn info ' . $vars['svn_path'] 
			. '|grep Revision:|awk \'{print $2}\'` > ' 
			. $vars['fw_lang'] . '/svnversion.txt')
) {
	die('FATAL: ailed to create svnversion.txt' . PHP_EOL);
}


$exclude[] = 'package_hook.php';
?>
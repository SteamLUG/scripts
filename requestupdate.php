<?php
include_once( 'paths.php' );
/* Triggered by admins from SteamLUG.org */
echo shell_exec( $scriptsLocation . '/cronjab' );

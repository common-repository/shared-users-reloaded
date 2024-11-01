<?php

function buildRoleMetaValue($role) {
	$roleLength = strlen($role);
	$roleMeta = 'a:1:{s:' . $roleLength . ':"' . $role . '";s:1:"1";}';
	$unserialized = unserialize($roleMeta);
	return $unserialized;
}

?>
<?php

namespace App\Manager;

class RoleManager{

    public function handleCustomRoleInput($currentRoles, $rawCustomRole){
        $handledCustomRole = str_replace(" ", "_", strtoupper($rawCustomRole));

        if (!str_starts_with($handledCustomRole, 'ROLE_')) {
            $handledCustomRole = 'ROLE_' . $handledCustomRole;
        }

        if (!in_array($handledCustomRole, $currentRoles)) {
            $finalRoles[] = $handledCustomRole;
        }

        return $finalRoles;
    }
}
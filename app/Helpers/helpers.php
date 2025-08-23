<?php

if (!function_exists('isAdmin')) {
    function isAdmin($user): bool
    {
        return $user->role_id == 1;
    }
}

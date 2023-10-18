<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    // unset data user yang berbahaya
    public function unsetUser($user) : object
    {
        unset($user->password);
        unset($user->remember_token);
        unset($user->created_at);
        unset($user->updated_at);
        unset($user->deleted_at);
        unset($user->email_verified_at);
        unset($user->is_approved);
        unset($user->permissions);
        unset($user->pivot);
        unset($user->guard_name);
        unset($user->permissions);
        unset($user->allPermissions);
        unset($user->getPermissionsViaRoles);
        unset($user->getDirectPermissions);
        unset($user->getPermissionsViaRoles);
        unset($user->getPermissionsViaRoles);
        unset($user->getDirectPermissions);
        unset($user->getPermissionsViaRoles);
        unset($user->getPermissionsViaRoles);
        unset($user->getDirectPermissions);
        unset($user->getPermissionsViaRoles);
        unset($user->getPermissionsViaRoles);
        unset($user->getDirectPermissions);
        unset($user->getPermissionsViaRoles);
        unset($user->getPermissionsViaRoles);
        unset($user->getDirectPermissions);
        unset($user->getPermissionsViaRoles);
        unset($user->getPermissionsViaRoles);
        unset($user->getDirectPermissions);
        unset($user->getPermissionsViaRoles);
        unset($user->getPermissionsViaRoles);
        unset($user->getDirectPermissions);
        unset($user->getPermissionsViaRoles);
        unset($user->getPermissionsViaRoles);
        unset($user->getDirectPermissions);
        unset($user->getPermissionsViaRoles);
        unset($user->getPermissionsViaRoles);
        unset($user->getDirectPermissions);
        unset($user->getPermissionsViaRoles);
        unset($user->getPermissionsViaRoles);
        return $user;
    }
}

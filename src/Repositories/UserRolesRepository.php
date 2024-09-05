<?php

namespace App\Repositories;

use App\Core\DB;

class UserRolesRepository extends DB
{
    protected string $tableName = "user_roles";
}

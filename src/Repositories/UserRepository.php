<?php

namespace App\Repositories;

use App\Core\DB;

class UserRepository extends DB
{
    protected string $tableName = "users";
}

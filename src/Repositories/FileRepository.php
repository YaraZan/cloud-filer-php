<?php

namespace App\Repositories;

use App\Core\DB;

class FileRepository extends DB
{
    protected string $tableName = "files";
}

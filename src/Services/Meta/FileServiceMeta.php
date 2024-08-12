<?php

namespace App\Services\Meta;

interface FileServiceMeta
{
    public function getAll(): array;
    public function get(int $id): object;
    public function add($file): void;
    public function rename(int $id, string $newname): void;
    public function remove(int $id): void;
}

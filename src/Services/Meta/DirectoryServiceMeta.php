<?php

namespace App\Services\Meta;

interface DirectoryServiceMeta {
    public function add(string $name): void;
    public function rename(int $id, string $newname): void;
    public function getFiles(int $id): array;
    public function delete(int $id): void;
}
?>
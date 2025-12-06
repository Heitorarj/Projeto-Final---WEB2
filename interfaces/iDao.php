<?php

interface iDao
{
    public static function create(array $data): int;
    public static function read(int $id): ?array;
    public static function update(int $id, array $data): bool;
    public static function delete(int $id): bool;
    public static function findAll(): array;
    public static function count(): int;
}

<?php

interface iDao
{
    public static function create($obj);
    public static function read($id);
    public static function update($obj);
    public static function delete($id);
    public static function all();
}

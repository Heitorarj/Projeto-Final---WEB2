<?php

class Fabricante implements iDao
{
    private int $id;
    private string $nome;
    private string $site;

    /**
     * Getters
     */

    public function getId(): int
    {
        return $this->id;
    }

    public function getNome(): string
    {
        return $this->nome;
    }

    public function getSite(): string
    {
        return $this->site;
    }

    /**
     * Setters
     */

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setNome(string $nome): void
    {
        $this->nome = $nome;
    }

    public function setSite(string $site): void
    {
        $this->site = $site;
    }

    /**
     * iDao Methods
     */

    public static function create($obj) {}

    public static function read($id) {}

    public static function update($obj) {}

    public static function delete($id) {}

    public static function all() {}
}

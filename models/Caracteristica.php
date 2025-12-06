<?php

class Caracteristica
{
    private int $id;
    private string $nome;
    private string $valor;

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

    public function getValor(): string
    {
        return $this->valor;
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

    public function setValor(string $valor): void
    {
        $this->valor = $valor;
    }
}

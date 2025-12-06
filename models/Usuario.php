<?php

class Usuario
{
    private int $id;
    private int $tipo;
    private string $nome;
    private string $login;
    private string $senha;

    /** 
     * Getters 
     */

    public function getId(): int
    {
        return $this->id;
    }

    public function getTipo(): int
    {
        return $this->tipo;
    }

    public function getNome(): string
    {
        return $this->nome;
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function getSenha(): string
    {
        return $this->senha;
    }

    /** 
     * Setters 
     */

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setTipo(int $tipo): void
    {
        $this->tipo = $tipo;
    }

    public function setNome(string $nome): void
    {
        $this->nome = $nome;
    }

    public function setLogin(string $login): void
    {
        $this->login = $login;
    }

    public function setSenha(string $senha): void
    {
        $this->senha = $senha;
    }
}

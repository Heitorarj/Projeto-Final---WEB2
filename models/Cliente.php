<?php

class Cliente extends Usuario
{
    private string $endereco;
    private string $telefone;

    /**
     * Getters
     */

    public function getEndereco(): string
    {
        return $this->endereco;
    }

    public function getTelefone(): string
    {
        return $this->telefone;
    }

    /**
     * Setters
     */

    public function setEndereco(string $endereco): void
    {
        $this->endereco = $endereco;
    }

    public function setTelefone(string $telefone): void
    {
        $this->telefone = $telefone;
    }
}

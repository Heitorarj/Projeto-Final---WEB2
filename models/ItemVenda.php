<?php

class ItemVenda
{
    private int $id;
    private Produto $produto;
    private int $quantidade;

    /**
     * Getters
     */

    public function getId(): int
    {
        return $this->id;
    }

    public function getProduto(): Produto
    {
        return $this->produto;
    }

    public function getQuantidade(): int
    {
        return $this->quantidade;
    }

    /**
     * Setters
     */

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setProduto(Produto $produto): void
    {
        $this->produto = $produto;
    }

    public function setQuantidade(int $quantidade): void
    {
        $this->quantidade = $quantidade;
    }
}

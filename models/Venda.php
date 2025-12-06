<?php

class Venda
{
    private int $id;
    private string $data_venda;
    private float $valor_venda;
    private Cliente $cliente;
    private array $itens_venda = [];

    /**
     * Getters
     */

    public function getId(): int
    {
        return $this->id;
    }

    public function getDataVenda(): string
    {
        return $this->data_venda;
    }

    public function getValorVenda(): float
    {
        return $this->valor_venda;
    }

    public function getCliente(): Cliente
    {
        return $this->cliente;
    }

    public function getItensVenda(): array
    {
        return $this->itens_venda;
    }

    /**
     * Setters
     */

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setDataVenda(string $data_venda): void
    {
        $this->data_venda = $data_venda;
    }

    public function setValorVenda(float $valor_venda): void
    {
        $this->valor_venda = $valor_venda;
    }

    public function setCliente(Cliente $cliente): void
    {
        $this->cliente = $cliente;
    }

    public function setItensVenda(array $itens_venda): void
    {
        $this->itens_venda = $itens_venda;
    }

    /**
     * Methods
     */

    public function adicionarItemVenda(Produto $produto, int $quantidade): void
    {
        $item_venda = new ItemVenda();
        $item_venda->setProduto($produto);
        $item_venda->setQuantidade($quantidade);
        $this->itens_venda[] = $item_venda;
    }
}

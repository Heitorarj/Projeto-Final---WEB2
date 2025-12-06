<?php

class Produto
{
    private int $id;
    private string $nome;
    private string $descricao;
    private string $imagem;
    private int $estoque;
    private float $preco_custo;
    private float $preco_venda;
    private Fabricante $fabricante;
    private Categoria $categoria;
    private array $caracteristicas = [];

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

    public function getDescricao(): string
    {
        return $this->descricao;
    }


    public function getImagem(): string
    {
        return $this->imagem;
    }

    public function getEstoque(): int
    {
        return $this->estoque;
    }

    public function getPrecoCusto(): float
    {
        return $this->preco_custo;
    }

    public function getPrecoVenda(): float
    {
        return $this->preco_venda;
    }

    public function getFabricante(): Fabricante
    {
        return $this->fabricante;
    }

    public function getCategoria(): Categoria
    {
        return $this->categoria;
    }

    public function getCaracteristicas(): array
    {
        return $this->caracteristicas;
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

    public function setDescricao(string $descricao): void
    {
        $this->descricao = $descricao;
    }

    public function setImagem(string $imagem): void
    {
        $this->imagem = $imagem;
    }

    public function setEstoque(int $estoque): void
    {
        $this->estoque = $estoque;
    }

    public function setPrecoCusto(float $preco_custo): void
    {
        $this->preco_custo = $preco_custo;
    }

    public function setPrecoVenda(float $preco_venda): void
    {
        $this->preco_venda = $preco_venda;
    }

    public function setFabricante(Fabricante $fabricante): void
    {
        $this->fabricante = $fabricante;
    }

    public function setCategoria(Categoria $categoria): void
    {
        $this->categoria = $categoria;
    }

    public function adicionarCaracteristica(string $nome, string $valor): void
    {
        $caracteristica = new Caracteristica();
        $caracteristica->setNome($nome);
        $caracteristica->setValor($valor);
        $this->caracteristicas[] = $caracteristica;
    }
}

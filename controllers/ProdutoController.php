<?php

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../interfaces/iDao.php';
require_once __DIR__ . '/../models/Produto.php';
require_once __DIR__ . '/../models/Categoria.php';
require_once __DIR__ . '/../models/Fabricante.php';
require_once __DIR__ . '/../models/Caracteristica.php';

class ProdutoController
{
    private static function startSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function listar(): array
    {
        try {
            return Produto::findAll();
        } catch (Exception $e) {
            self::startSession();
            $_SESSION['erro'] = $e->getMessage();
            return [];
        }
    }

    public static function buscarPorId(int $id): ?array
    {
        try {
            return Produto::read($id);
        } catch (Exception $e) {
            self::startSession();
            $_SESSION['erro'] = $e->getMessage();
            return null;
        }
    }

    public static function criar(): void
    {
        self::startSession();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ../../views/Admin/adminDashboard.php');
            exit();
        }

        try {
            $nome = $_POST['nome'] ?? '';
            $descricao = $_POST['descricao'] ?? '';
            $preco_custo = $_POST['preco_custo'] ?? 0;
            $preco_venda = $_POST['preco_venda'] ?? 0;
            $estoque = $_POST['estoque'] ?? 0;
            $fabricante_id = $_POST['fabricante_id'] ?? null;
            $categoria_id = $_POST['categoria_id'] ?? null;
            $imagem = $_POST['imagem'] ?? '';

            // Validações básicas
            if (empty($nome) || empty($preco_venda)) {
                throw new Exception("Nome e preço de venda são obrigatórios");
            }

            if (empty($fabricante_id) || empty($categoria_id)) {
                throw new Exception("Fabricante e categoria são obrigatórios");
            }

            $data = [
                'nome' => $nome,
                'descricao' => $descricao,
                'imagem' => $imagem,
                'estoque' => (int)$estoque,
                'preco_custo' => (float)$preco_custo,
                'preco_venda' => (float)$preco_venda,
                'fabricante_id' => (int)$fabricante_id,
                'categoria_id' => (int)$categoria_id
            ];

            // Processar características
            if (isset($_POST['caracteristicas']) && is_array($_POST['caracteristicas'])) {
                // Filtrar características vazias
                $caracteristicasFiltradas = array_filter($_POST['caracteristicas'], function ($caracteristica) {
                    return !empty(trim($caracteristica['nome'] ?? '')) && !empty(trim($caracteristica['valor'] ?? ''));
                });

                // Reindexar array
                $data['caracteristicas'] = array_values($caracteristicasFiltradas);
            }

            $produto_id = Produto::create($data);

            $_SESSION['sucesso'] = "Produto cadastrado com sucesso!";
            header('Location: ../../views/Admin/produtosCadastrados.php');
            exit();
        } catch (Exception $e) {
            $_SESSION['erro'] = $e->getMessage();
            header('Location: ../../views/Admin/adminDashboard.php');
            exit();
        }
    }

    public static function atualizar(): void
    {
        self::startSession();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ../../views/Admin/produtosCadastrados.php');
            exit();
        }

        try {
            // Obter o ID do produto do POST, não do parâmetro
            $id = $_POST['id'] ?? 0;

            if (!$id) {
                throw new Exception("ID do produto não fornecido");
            }

            $data = [];

            if (isset($_POST['nome'])) {
                $data['nome'] = $_POST['nome'];
            }

            if (isset($_POST['descricao'])) {
                $data['descricao'] = $_POST['descricao'];
            }

            if (isset($_POST['preco_custo'])) {
                $data['preco_custo'] = (float)$_POST['preco_custo'];
            }

            if (isset($_POST['preco_venda'])) {
                $data['preco_venda'] = (float)$_POST['preco_venda'];
            }

            if (isset($_POST['estoque'])) {
                $data['estoque'] = (int)$_POST['estoque'];
            }

            if (isset($_POST['fabricante_id'])) {
                $data['fabricante_id'] = (int)$_POST['fabricante_id'];
            }

            if (isset($_POST['categoria_id'])) {
                $data['categoria_id'] = (int)$_POST['categoria_id'];
            }

            if (isset($_POST['imagem'])) {
                $data['imagem'] = $_POST['imagem'];
            }

            // Processar características
            if (isset($_POST['caracteristicas']) && is_array($_POST['caracteristicas'])) {
                // Filtrar características vazias
                $caracteristicasFiltradas = array_filter($_POST['caracteristicas'], function ($caracteristica) {
                    return !empty(trim($caracteristica['nome'] ?? '')) && !empty(trim($caracteristica['valor'] ?? ''));
                });

                // Reindexar array para garantir índices sequenciais
                $data['caracteristicas'] = array_values($caracteristicasFiltradas);
            }

            Produto::update($id, $data);

            $_SESSION['sucesso'] = "Produto atualizado com sucesso!";
            header('Location: ../../views/Admin/produtosCadastrados.php');
            exit();
        } catch (Exception $e) {
            $_SESSION['erro'] = $e->getMessage();
            // Redirecionar de volta para a página de edição com o ID
            $id = $_POST['id'] ?? 0;
            header('Location: ../../views/Admin/editarProduto.php?id=' . $id);
            exit();
        }
    }

    public static function deletar(int $id): void
    {
        self::startSession();

        try {
            Produto::delete($id);

            $_SESSION['sucesso'] = "Produto excluído com sucesso!";
            header('Location: ../../views/Admin/produtosCadastrados.php');
            exit();
        } catch (Exception $e) {
            $_SESSION['erro'] = $e->getMessage();
            header('Location: ../../views/Admin/produtosCadastrados.php');
            exit();
        }
    }

    public static function buscarPorCategoria(int $categoria_id): array
    {
        try {
            return Produto::findByCategoria($categoria_id);
        } catch (Exception $e) {
            self::startSession();
            $_SESSION['erro'] = $e->getMessage();
            return [];
        }
    }

    public static function buscarPorFabricante(int $fabricante_id): array
    {
        try {
            return Produto::findByFabricante($fabricante_id);
        } catch (Exception $e) {
            self::startSession();
            $_SESSION['erro'] = $e->getMessage();
            return [];
        }
    }

    public static function buscarPorNome(string $nome): array
    {
        try {
            return Produto::findByNome($nome);
        } catch (Exception $e) {
            self::startSession();
            $_SESSION['erro'] = $e->getMessage();
            return [];
        }
    }

    public static function buscarComEstoque(): array
    {
        try {
            return Produto::getProdutosComEstoque();
        } catch (Exception $e) {
            self::startSession();
            $_SESSION['erro'] = $e->getMessage();
            return [];
        }
    }
}

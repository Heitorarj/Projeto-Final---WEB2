<?php

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../interfaces/iDao.php';
require_once __DIR__ . '/../models/Venda.php';
require_once __DIR__ . '/../models/ItemVenda.php';
require_once __DIR__ . '/../models/Produto.php';
require_once __DIR__ . '/../models/Usuario.php';

class VendaController
{
    private static function startSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function listar(?string $data_inicial = null, ?string $data_final = null): array
    {
        try {
            if ($data_inicial && $data_final) {
                return Venda::findByPeriodo($data_inicial, $data_final);
            }

            return Venda::findAll();
        } catch (Exception $e) {
            self::startSession();
            $_SESSION['erro'] = $e->getMessage();
            return [];
        }
    }

    public static function obterEstatisticas(?string $data_inicial = null, ?string $data_final = null): array
    {
        try {
            $total_vendas = Venda::count();
            $valor_total = Venda::getValorTotalVendas();

            if ($data_inicial && $data_final) {
                $vendas_periodo = Venda::findByPeriodo($data_inicial, $data_final);
                $total_vendas = count($vendas_periodo);
                $valor_total = 0;

                foreach ($vendas_periodo as $venda) {
                    $valor_total += (float)$venda['valor_total'];
                }
            }

            $ticket_medio = $total_vendas > 0 ? $valor_total / $total_vendas : 0;

            return [
                'total_vendas' => $total_vendas,
                'valor_total' => $valor_total,
                'ticket_medio' => $ticket_medio
            ];
        } catch (Exception $e) {
            self::startSession();
            $_SESSION['erro'] = $e->getMessage();
            return [
                'total_vendas' => 0,
                'valor_total' => 0,
                'ticket_medio' => 0
            ];
        }
    }

    public static function buscarPorPeriodo(string $data_inicio, string $data_fim): array
    {
        try {
            return Venda::findByPeriodo($data_inicio, $data_fim);
        } catch (Exception $e) {
            self::startSession();
            $_SESSION['erro'] = $e->getMessage();
            return [];
        }
    }

    public static function buscarPorId(int $id): ?array
    {
        try {
            return Venda::read($id);
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
            header('Location: ../../views/Cliente/clienteCarrinho.php');
            exit();
        }

        try {
            if (!isset($_SESSION['usuario_id'])) {
                throw new Exception("Usuário não autenticado");
            }

            $usuario_id = $_SESSION['usuario_id'];
            $itens = $_POST['itens'] ?? [];

            if (empty($itens)) {
                throw new Exception("Carrinho vazio");
            }

            $valor_total = 0;
            $itens_venda = [];

            foreach ($itens as $item) {
                $produto_id = (int)$item['produto_id'];
                $quantidade = (int)$item['quantidade'];

                $produto = Produto::read($produto_id);

                if (!$produto) {
                    throw new Exception("Produto não encontrado");
                }

                if ($produto['estoque'] < $quantidade) {
                    throw new Exception("Estoque insuficiente para o produto: " . $produto['nome']);
                }

                $preco_unitario = (float)$produto['preco_venda'];
                $subtotal = $preco_unitario * $quantidade;
                $valor_total += $subtotal;

                $itens_venda[] = [
                    'produto_id' => $produto_id,
                    'quantidade' => $quantidade,
                    'preco_unitario' => $preco_unitario
                ];
            }

            $data = [
                'usuario_id' => $usuario_id,
                'valor_total' => $valor_total,
                'itens' => $itens_venda
            ];

            $venda_id = Venda::create($data);

            $_SESSION['sucesso'] = "Compra realizada com sucesso!";
            header('Location: ../../views/Cliente/comprasCliente.php');
            exit();
        } catch (Exception $e) {
            $_SESSION['erro'] = $e->getMessage();
            header('Location: ../../views/Cliente/clienteCarrinho.php');
            exit();
        }
    }

    public static function buscarPorUsuario(int $usuario_id): array
    {
        try {
            return Venda::findByUsuario($usuario_id);
        } catch (Exception $e) {
            self::startSession();
            $_SESSION['erro'] = $e->getMessage();
            return [];
        }
    }

    public static function adicionarAoCarrinho(int $produto_id, int $quantidade = 1): void
    {
        self::startSession();

        try {
            $produto = Produto::read($produto_id);

            if (!$produto) {
                throw new Exception("Produto não encontrado");
            }

            if ($produto['estoque'] < $quantidade) {
                throw new Exception("Estoque insuficiente");
            }

            if (!isset($_SESSION['carrinho'])) {
                $_SESSION['carrinho'] = [];
            }

            if (isset($_SESSION['carrinho'][$produto_id])) {
                $_SESSION['carrinho'][$produto_id]['quantidade'] += $quantidade;
            } else {
                $_SESSION['carrinho'][$produto_id] = [
                    'produto_id' => $produto_id,
                    'nome' => $produto['nome'],
                    'preco' => $produto['preco_venda'],
                    'quantidade' => $quantidade,
                    'imagem' => $produto['imagem']
                ];
            }

            $_SESSION['sucesso'] = "Produto adicionado ao carrinho!";
        } catch (Exception $e) {
            $_SESSION['erro'] = $e->getMessage();
        }
    }

    public static function removerDoCarrinho(int $produto_id): void
    {
        self::startSession();

        if (isset($_SESSION['carrinho'][$produto_id])) {
            unset($_SESSION['carrinho'][$produto_id]);
            $_SESSION['sucesso'] = "Produto removido do carrinho!";
        }
    }

    public static function atualizarQuantidadeCarrinho(int $produto_id, int $quantidade): void
    {
        self::startSession();

        try {
            if ($quantidade <= 0) {
                self::removerDoCarrinho($produto_id);
                return;
            }

            $produto = Produto::read($produto_id);

            if (!$produto) {
                throw new Exception("Produto não encontrado");
            }

            if ($produto['estoque'] < $quantidade) {
                throw new Exception("Estoque insuficiente");
            }

            if (isset($_SESSION['carrinho'][$produto_id])) {
                $_SESSION['carrinho'][$produto_id]['quantidade'] = $quantidade;
                $_SESSION['sucesso'] = "Quantidade atualizada!";
            }
        } catch (Exception $e) {
            $_SESSION['erro'] = $e->getMessage();
        }
    }

    public static function obterCarrinho(): array
    {
        self::startSession();
        return $_SESSION['carrinho'] ?? [];
    }

    public static function limparCarrinho(): void
    {
        self::startSession();
        $_SESSION['carrinho'] = [];
    }

    public static function calcularTotalCarrinho(): float
    {
        $carrinho = self::obterCarrinho();
        $total = 0;

        foreach ($carrinho as $item) {
            $total += $item['preco'] * $item['quantidade'];
        }

        return $total;
    }
}

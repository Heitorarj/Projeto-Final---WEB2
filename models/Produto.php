<?php
require_once __DIR__ . '/../interfaces/iDao.php';
require_once __DIR__ . '/Caracteristica.php';

class Produto implements iDao
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

    public function adicionarCaracteristica(Caracteristica $caracteristica): void
    {
        $caracteristica->setProdutoId($this->id);
        $this->caracteristicas[] = $caracteristica;
    }

    /** 
     * Database Connection
     */
    private static function getPDO(): PDO
    {
        return Database::getInstance();
    }

    /**
     * iDao Methods
     */

    public static function create(array $data): int
    {
        if (empty($data['nome']) || empty($data['preco_venda'])) {
            throw new Exception("Nome e preço de venda são obrigatórios");
        }

        if (empty($data['fabricante_id']) || empty($data['categoria_id'])) {
            throw new Exception("Fabricante e categoria são obrigatórios");
        }

        $sql = "INSERT INTO produtos (nome, descricao, imagem, estoque, preco_custo, preco_venda, fabricante_id, categoria_id) 
                VALUES (:nome, :descricao, :imagem, :estoque, :preco_custo, :preco_venda, :fabricante_id, :categoria_id)";

        try {
            $pdo = self::getPDO();
            $stmt = $pdo->prepare($sql);

            $params = [
                ':nome' => $data['nome'],
                ':descricao' => $data['descricao'] ?? '',
                ':imagem' => $data['imagem'] ?? '',
                ':estoque' => $data['estoque'] ?? 0,
                ':preco_custo' => $data['preco_custo'] ?? 0.0,
                ':preco_venda' => $data['preco_venda'],
                ':fabricante_id' => $data['fabricante_id'],
                ':categoria_id' => $data['categoria_id']
            ];

            $stmt->execute($params);
            $produto_id = (int) $pdo->lastInsertId();

            if (!empty($data['caracteristicas']) && is_array($data['caracteristicas'])) {
                self::salvarCaracteristicas($produto_id, $data['caracteristicas']);
            }

            return $produto_id;
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                throw new Exception("Fabricante ou categoria inválida");
            }
            throw new Exception("Erro ao criar produto: " . $e->getMessage());
        }
    }

    public static function read(int $id): ?array
    {
        $sql = "SELECT p.*, f.nome as fabricante_nome, f.site as fabricante_site, 
                       cat.nome as categoria_nome
                FROM produtos p
                LEFT JOIN fabricantes f ON p.fabricante_id = f.id
                LEFT JOIN categorias cat ON p.categoria_id = cat.id
                WHERE p.id = :id";

        try {
            $pdo = self::getPDO();
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':id' => $id]);
            $produto = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$produto) {
                return null;
            }

            $produto['caracteristicas'] = self::buscarCaracteristicas($id);

            return $produto;
        } catch (PDOException $e) {
            throw new Exception("Erro ao buscar produto: " . $e->getMessage());
        }
    }

    public static function update(int $id, array $data): bool
    {
        $fields = [];
        $params = [':id' => $id];

        if (isset($data['nome'])) {
            $fields[] = "nome = :nome";
            $params[':nome'] = $data['nome'];
        }

        if (isset($data['descricao'])) {
            $fields[] = "descricao = :descricao";
            $params[':descricao'] = $data['descricao'];
        }

        if (isset($data['imagem'])) {
            $fields[] = "imagem = :imagem";
            $params[':imagem'] = $data['imagem'];
        }

        if (isset($data['estoque'])) {
            $fields[] = "estoque = :estoque";
            $params[':estoque'] = $data['estoque'];
        }

        if (isset($data['preco_custo'])) {
            $fields[] = "preco_custo = :preco_custo";
            $params[':preco_custo'] = $data['preco_custo'];
        }

        if (isset($data['preco_venda'])) {
            $fields[] = "preco_venda = :preco_venda";
            $params[':preco_venda'] = $data['preco_venda'];
        }

        if (isset($data['fabricante_id'])) {
            $fields[] = "fabricante_id = :fabricante_id";
            $params[':fabricante_id'] = $data['fabricante_id'];
        }

        if (isset($data['categoria_id'])) {
            $fields[] = "categoria_id = :categoria_id";
            $params[':categoria_id'] = $data['categoria_id'];
        }

        if (empty($fields)) {
            return false;
        }

        $sql = "UPDATE produtos SET " . implode(', ', $fields) . " WHERE id = :id";

        try {
            $pdo = self::getPDO();
            $stmt = $pdo->prepare($sql);
            $result = $stmt->execute($params);

            if (isset($data['caracteristicas']) && is_array($data['caracteristicas'])) {
                $caracteristicasValidas = array_filter($data['caracteristicas'], function ($caracteristica) {
                    return !empty(trim($caracteristica['nome'] ?? '')) && !empty(trim($caracteristica['valor'] ?? ''));
                });

                self::removerCaracteristicas($id);

                if (!empty($caracteristicasValidas)) {
                    self::salvarCaracteristicas($id, array_values($caracteristicasValidas));
                }
            }

            return $result;
        } catch (PDOException $e) {
            throw new Exception("Erro ao atualizar produto: " . $e->getMessage());
        }
    }

    public static function delete(int $id): bool
    {
        $sql_check = "SELECT COUNT(*) as total FROM itens_venda WHERE produto_id = :id";
        $sql_delete = "DELETE FROM produtos WHERE id = :id";

        try {
            $pdo = self::getPDO();

            $stmt_check = $pdo->prepare($sql_check);
            $stmt_check->execute([':id' => $id]);
            $result = $stmt_check->fetch(PDO::FETCH_ASSOC);

            if ((int)$result['total'] > 0) {
                throw new Exception("Não é possível excluir o produto pois existem vendas associadas a ele.");
            }

            $pdo->beginTransaction();

            self::removerCaracteristicas($id);

            $stmt = $pdo->prepare($sql_delete);
            $stmt->execute([':id' => $id]);

            $pdo->commit();
            return true;
        } catch (PDOException $e) {
            if (isset($pdo) && $pdo->inTransaction()) {
                $pdo->rollBack();
            }
            throw new Exception("Erro ao deletar produto: " . $e->getMessage());
        }
    }

    public static function findAll(): array
    {
        $sql = "SELECT p.*, f.nome as fabricante_nome, cat.nome as categoria_nome
                FROM produtos p
                LEFT JOIN fabricantes f ON p.fabricante_id = f.id
                LEFT JOIN categorias cat ON p.categoria_id = cat.id
                ORDER BY p.nome ASC";

        try {
            $pdo = self::getPDO();
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($produtos as &$produto) {
                $produto['caracteristicas'] = self::buscarCaracteristicas($produto['id']);
            }

            return $produtos;
        } catch (PDOException $e) {
            throw new Exception("Erro ao buscar todos os produtos: " . $e->getMessage());
        }
    }

    public static function count(): int
    {
        $sql = "SELECT COUNT(*) as total FROM produtos";

        try {
            $pdo = self::getPDO();
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int) $result['total'];
        } catch (PDOException $e) {
            throw new Exception("Erro ao contar produtos: " . $e->getMessage());
        }
    }

    /**
     * Helper Methods para gerenciar características
     */

    private static function salvarCaracteristicas(int $produto_id, array $caracteristicas): void
    {
        foreach ($caracteristicas as $caracteristica) {
            if (!empty($caracteristica['nome']) && !empty($caracteristica['valor'])) {
                $nome = trim($caracteristica['nome']);
                $valor = trim($caracteristica['valor']);

                if (!empty($nome) && !empty($valor)) {
                    Caracteristica::create([
                        'nome' => $nome,
                        'valor' => $valor,
                        'produto_id' => $produto_id
                    ]);
                }
            }
        }
    }

    private static function buscarCaracteristicas(int $produto_id): array
    {
        try {
            return Caracteristica::findByProdutoId($produto_id);
        } catch (Exception $e) {
            return [];
        }
    }

    private static function removerCaracteristicas(int $produto_id): void
    {
        try {
            Caracteristica::deleteByProdutoId($produto_id);
        } catch (Exception $e) {
            throw new Exception("Erro ao remover características do produto: " . $e->getMessage());
        }
    }

    /**
     * Additional Methods (métodos adicionais úteis)
     */

    public static function findByNome(string $nome): array
    {
        $sql = "SELECT p.*, f.nome as fabricante_nome, cat.nome as categoria_nome
                FROM produtos p
                LEFT JOIN fabricantes f ON p.fabricante_id = f.id
                LEFT JOIN categorias cat ON p.categoria_id = cat.id
                WHERE p.nome LIKE :nome
                ORDER BY p.nome ASC";

        try {
            $pdo = self::getPDO();
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':nome' => "%$nome%"]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Erro ao buscar produtos por nome: " . $e->getMessage());
        }
    }

    public static function findByFabricante(int $fabricante_id): array
    {
        $sql = "SELECT p.*, f.nome as fabricante_nome, cat.nome as categoria_nome
                FROM produtos p
                LEFT JOIN fabricantes f ON p.fabricante_id = f.id
                LEFT JOIN categorias cat ON p.categoria_id = cat.id
                WHERE p.fabricante_id = :fabricante_id
                ORDER BY p.nome ASC";

        try {
            $pdo = self::getPDO();
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':fabricante_id' => $fabricante_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Erro ao buscar produtos por fabricante: " . $e->getMessage());
        }
    }

    public static function findByCategoria(int $categoria_id): array
    {
        $sql = "SELECT p.*, f.nome as fabricante_nome, cat.nome as categoria_nome
                FROM produtos p
                LEFT JOIN fabricantes f ON p.fabricante_id = f.id
                LEFT JOIN categorias cat ON p.categoria_id = cat.id
                WHERE p.categoria_id = :categoria_id
                ORDER BY p.nome ASC";

        try {
            $pdo = self::getPDO();
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':categoria_id' => $categoria_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Erro ao buscar produtos por categoria: " . $e->getMessage());
        }
    }

    public static function findByFaixaPreco(float $preco_min, float $preco_max): array
    {
        $sql = "SELECT p.*, f.nome as fabricante_nome, cat.nome as categoria_nome
                FROM produtos p
                LEFT JOIN fabricantes f ON p.fabricante_id = f.id
                LEFT JOIN categorias cat ON p.categoria_id = cat.id
                WHERE p.preco_venda BETWEEN :preco_min AND :preco_max
                ORDER BY p.preco_venda ASC";

        try {
            $pdo = self::getPDO();
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':preco_min' => $preco_min,
                ':preco_max' => $preco_max
            ]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Erro ao buscar produtos por faixa de preço: " . $e->getMessage());
        }
    }

    public static function getProdutosBaixoEstoque(int $limite = 10): array
    {
        $sql = "SELECT p.*, f.nome as fabricante_nome, cat.nome as categoria_nome
                FROM produtos p
                LEFT JOIN fabricantes f ON p.fabricante_id = f.id
                LEFT JOIN categorias cat ON p.categoria_id = cat.id
                WHERE p.estoque <= :limite
                ORDER BY p.estoque ASC";

        try {
            $pdo = self::getPDO();
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':limite' => $limite]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Erro ao buscar produtos com baixo estoque: " . $e->getMessage());
        }
    }

    public static function atualizarEstoque(int $produto_id, int $quantidade): bool
    {
        $sql = "UPDATE produtos SET estoque = estoque + :quantidade WHERE id = :id";

        try {
            $pdo = self::getPDO();
            $stmt = $pdo->prepare($sql);
            return $stmt->execute([
                ':id' => $produto_id,
                ':quantidade' => $quantidade
            ]);
        } catch (PDOException $e) {
            throw new Exception("Erro ao atualizar estoque: " . $e->getMessage());
        }
    }

    public static function getProdutosComEstoque(): array
    {
        $sql = "SELECT p.*, f.nome as fabricante_nome, cat.nome as categoria_nome
            FROM produtos p
            LEFT JOIN fabricantes f ON p.fabricante_id = f.id
            LEFT JOIN categorias cat ON p.categoria_id = cat.id
            WHERE p.estoque > 0
            ORDER BY p.nome ASC";

        try {
            $pdo = self::getPDO();
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($produtos as &$produto) {
                $produto['caracteristicas'] = self::buscarCaracteristicas($produto['id']);
            }

            return $produtos;
        } catch (PDOException $e) {
            throw new Exception("Erro ao buscar produtos com estoque: " . $e->getMessage());
        }
    }

    public static function getValorTotalEstoque(): float
    {
        $sql = "SELECT SUM(estoque * preco_custo) as total FROM produtos";

        try {
            $pdo = self::getPDO();
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return (float) ($result['total'] ?? 0);
        } catch (PDOException $e) {
            throw new Exception("Erro ao calcular valor total do estoque: " . $e->getMessage());
        }
    }
}

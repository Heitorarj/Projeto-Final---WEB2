<?php
require_once __DIR__ . '/../interfaces/iDao.php';
class ItemVenda implements iDao
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
        if (empty($data['venda_id']) || empty($data['produto_id']) || empty($data['quantidade'])) {
            throw new Exception("Venda, produto e quantidade são obrigatórios");
        }

        if ($data['quantidade'] <= 0) {
            throw new Exception("Quantidade deve ser maior que zero");
        }

        $sql = "INSERT INTO itens_venda (venda_id, produto_id, quantidade) 
                VALUES (:venda_id, :produto_id, :quantidade)";

        try {
            $pdo = self::getPDO();
            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                ':venda_id' => $data['venda_id'],
                ':produto_id' => $data['produto_id'],
                ':quantidade' => $data['quantidade']
            ]);

            return (int) $pdo->lastInsertId();
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                throw new Exception("Venda ou produto inválido");
            }
            throw new Exception("Erro ao criar item de venda: " . $e->getMessage());
        }
    }

    public static function read(int $id): ?array
    {
        $sql = "SELECT iv.*, p.nome as produto_nome, p.descricao as produto_descricao,
                       v.data_venda, v.valor_total as venda_total,
                       u.nome as usuario_nome
                FROM itens_venda iv
                INNER JOIN produtos p ON iv.produto_id = p.id
                INNER JOIN vendas v ON iv.venda_id = v.id
                INNER JOIN usuarios u ON v.usuario_id = u.id
                WHERE iv.id = :id";

        try {
            $pdo = self::getPDO();
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':id' => $id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result ?: null;
        } catch (PDOException $e) {
            throw new Exception("Erro ao buscar item de venda: " . $e->getMessage());
        }
    }

    public static function update(int $id, array $data): bool
    {
        $fields = [];
        $params = [':id' => $id];

        if (isset($data['quantidade'])) {
            if ($data['quantidade'] <= 0) {
                throw new Exception("Quantidade deve ser maior que zero");
            }
            $fields[] = "quantidade = :quantidade";
            $params[':quantidade'] = $data['quantidade'];
        }

        if (isset($data['produto_id'])) {
            $fields[] = "produto_id = :produto_id";
            $params[':produto_id'] = $data['produto_id'];
        }

        if (empty($fields)) {
            return false;
        }

        $sql = "UPDATE itens_venda SET " . implode(', ', $fields) . " WHERE id = :id";

        try {
            $pdo = self::getPDO();
            $stmt = $pdo->prepare($sql);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            throw new Exception("Erro ao atualizar item de venda: " . $e->getMessage());
        }
    }

    public static function delete(int $id): bool
    {
        $sql = "DELETE FROM itens_venda WHERE id = :id";

        try {
            $pdo = self::getPDO();
            $stmt = $pdo->prepare($sql);
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            throw new Exception("Erro ao deletar item de venda: " . $e->getMessage());
        }
    }

    public static function findAll(): array
    {
        $sql = "SELECT iv.*, p.nome as produto_nome, p.preco_venda as produto_preco_atual,
                       v.data_venda, u.nome as usuario_nome
                FROM itens_venda iv
                INNER JOIN produtos p ON iv.produto_id = p.id
                INNER JOIN vendas v ON iv.venda_id = v.id
                INNER JOIN usuarios u ON v.usuario_id = u.id
                ORDER BY iv.id DESC";

        try {
            $pdo = self::getPDO();
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Erro ao buscar todos os itens de venda: " . $e->getMessage());
        }
    }

    public static function count(): int
    {
        $sql = "SELECT COUNT(*) as total FROM itens_venda";

        try {
            $pdo = self::getPDO();
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int) $result['total'];
        } catch (PDOException $e) {
            throw new Exception("Erro ao contar itens de venda: " . $e->getMessage());
        }
    }
}

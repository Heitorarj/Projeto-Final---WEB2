<?php

class Categoria implements iDao
{
    private int $id;
    private string $nome;

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
        if (empty($data['nome'])) {
            throw new Exception("Nome da categoria é obrigatório");
        }

        $sql = "INSERT INTO categorias (nome) 
                VALUES (:nome)";

        try {
            $pdo = self::getPDO();
            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                ':nome' => $data['nome']
            ]);

            return (int) $pdo->lastInsertId();
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                throw new Exception("Categoria com este nome já existe");
            }
            throw new Exception("Erro ao criar categoria: " . $e->getMessage());
        }
    }

    public static function read(int $id): ?array
    {
        $sql = "SELECT id, nome 
                FROM categorias 
                WHERE id = :id";

        try {
            $pdo = self::getPDO();
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':id' => $id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result ?: null;
        } catch (PDOException $e) {
            throw new Exception("Erro ao buscar categoria: " . $e->getMessage());
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

        if (empty($fields)) {
            return false;
        }

        $sql = "UPDATE categorias SET " . implode(', ', $fields) . " WHERE id = :id";

        try {
            $pdo = self::getPDO();
            $stmt = $pdo->prepare($sql);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                throw new Exception("Categoria com este nome já existe");
            }
            throw new Exception("Erro ao atualizar categoria: " . $e->getMessage());
        }
    }

    public static function delete(int $id): bool
    {
        $sql_check = "SELECT COUNT(*) as total FROM produtos WHERE categoria_id = :id";
        $sql_delete = "DELETE FROM categorias WHERE id = :id";

        try {
            $pdo = self::getPDO();

            $stmt_check = $pdo->prepare($sql_check);
            $stmt_check->execute([':id' => $id]);
            $result = $stmt_check->fetch(PDO::FETCH_ASSOC);

            if ((int)$result['total'] > 0) {
                throw new Exception("Não é possível excluir a categoria pois existem produtos associados a ela.");
            }

            $stmt = $pdo->prepare($sql_delete);
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            throw new Exception("Erro ao deletar categoria: " . $e->getMessage());
        }
    }

    public static function findAll(): array
    {
        $sql = "SELECT id, nome 
                FROM categorias 
                ORDER BY nome ASC";

        try {
            $pdo = self::getPDO();
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Erro ao buscar todas as categorias: " . $e->getMessage());
        }
    }

    public static function count(): int
    {
        $sql = "SELECT COUNT(*) as total FROM categorias";

        try {
            $pdo = self::getPDO();
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int) $result['total'];
        } catch (PDOException $e) {
            throw new Exception("Erro ao contar categorias: " . $e->getMessage());
        }
    }

    /**
     * Additional Methods
     */

    public static function findByNome(string $nome): ?array
    {
        $sql = "SELECT id, nome 
                FROM categorias 
                WHERE nome LIKE :nome 
                ORDER BY nome ASC";

        try {
            $pdo = self::getPDO();
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':nome' => "%$nome%"]);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $result ?: null;
        } catch (PDOException $e) {
            throw new Exception("Erro ao buscar categoria por nome: " . $e->getMessage());
        }
    }
}

<?php

class Usuario implements iDao
{
    private int $id;
    private int $tipo;
    private string $nome;
    private string $login;
    private string $senha;

    private static function getPDO(): PDO
    {
        return Database::getInstance();
    }

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

    /**
     * DAO Methods
     */

    public static function create(array $data): int
    {
        $sql = "INSERT INTO usuarios (nome, email, senha, data_criacao) 
                VALUES (:nome, :email, :senha, NOW())";

        try {
            $pdo = self::getPDO();
            $stmt = $pdo->prepare($sql);

            // Hash da senha
            if (isset($data['senha'])) {
                $data['senha'] = password_hash($data['senha'], PASSWORD_DEFAULT);
            }

            $stmt->execute([
                ':nome' => $data['nome'] ?? null,
                ':email' => $data['email'] ?? null,
                ':senha' => $data['senha'] ?? null
            ]);

            return (int) $pdo->lastInsertId();
        } catch (PDOException $e) {
            throw new Exception("Erro ao criar usuário: " . $e->getMessage());
        }
    }

    public static function read(int $id): ?array
    {
        $sql = "SELECT id, nome, email, data_criacao 
                FROM usuarios 
                WHERE id = :id";

        try {
            $pdo = self::getPDO();
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':id' => $id]);
            $result = $stmt->fetch();

            return $result ?: null;
        } catch (PDOException $e) {
            throw new Exception("Erro ao buscar usuário: " . $e->getMessage());
        }
    }

    public static function update(int $id, array $data): bool
    {
        $sql = "UPDATE usuarios 
                SET nome = :nome, email = :email, senha = :senha 
                WHERE id = :id";

        try {
            $pdo = self::getPDO();
            $stmt = $pdo->prepare($sql);

            // Hash da senha se for fornecida
            if (isset($data['senha'])) {
                $data['senha'] = password_hash($data['senha'], PASSWORD_DEFAULT);
            }

            return $stmt->execute([
                ':id' => $id,
                ':nome' => $data['nome'] ?? null,
                ':email' => $data['email'] ?? null,
                ':senha' => $data['senha'] ?? null
            ]);
        } catch (PDOException $e) {
            throw new Exception("Erro ao atualizar usuário: " . $e->getMessage());
        }
    }

    public static function delete(int $id): bool
    {
        $sql = "DELETE FROM usuarios WHERE id = :id";

        try {
            $pdo = self::getPDO();
            $stmt = $pdo->prepare($sql);
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            throw new Exception("Erro ao deletar usuário: " . $e->getMessage());
        }
    }

    public static function findAll(): array
    {
        $sql = "SELECT id, nome, email, data_criacao 
                FROM usuarios 
                ORDER BY id DESC";

        try {
            $pdo = self::getPDO();
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            throw new Exception("Erro ao buscar todos os usuários: " . $e->getMessage());
        }
    }

    public static function count(): int
    {
        $sql = "SELECT COUNT(*) as total FROM usuarios";

        try {
            $pdo = self::getPDO();
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch();
            return (int) $result['total'];
        } catch (PDOException $e) {
            throw new Exception("Erro ao contar usuários: " . $e->getMessage());
        }
    }
}

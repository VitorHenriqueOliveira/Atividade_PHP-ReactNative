<?php

include_once 'Conn.php';

class Aluno
{
    private $id;
    private $nome;
    private $idade;
    private $telefone;
    private $con;
    private $table = "tb_aluno";

    public function getId()
    {
        return $this->id;
    }

    public function setId($id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function setNome($nome): self
    {
        $this->nome = $nome;

        return $this;
    }

    public function getIdade()
    {
        return $this->idade;
    }

    public function setIdade($idade): self
    {
        $this->idade = $idade;

        return $this;
    }

    public function getTelefone()
    {
        return $this->telefone;
    }

    public function setTelefone($telefone): self
    {
        $this->telefone = $telefone;

        return $this;
    }

    public function getCon()
    {
        return $this->con;
    }

    public function setCon($con): self
    {
        $this->con = $con;

        return $this;
    }

    //opcao do insert, que se for 1 ele edita no procedure
    public function crud($opcao)
    {
        try { //tentar executar o código
            $this->con = new Conn(); //acessar o nosso atributo de conexão la em cima, criar uma nova conexão
            //tem que seguri a ordem do procedure
            $sql = "Call crud_aluno(?,?,?,?,?)"; //coloca interrogação no lugar dos dados, quando for salvar e excluir tem que receber id, salvar é nulo
            $exec = $this->con->prepare($sql); //Conn é uma biblioteca do php, está preparando o que será executado no sql, se assemelha a criação de método em java, o php se baseia no java
            $exec->bindValue(1, $this->id);
            $exec->bindValue(2, mb_strtoupper($this->nome)); //esse strtoupper deixa maiusculo, controlando pra não chegar de qualquer jeito no formulario
            $exec->bindValue(3, mb_strtoupper($this->idade));  //o strtoupper sozinho, sem o mb, não aumenta o ç e outros
            $exec->bindValue(4, mb_strtoupper($this->telefone));
            $exec->bindValue(5, $opcao); ///não tem this porque não é atributo da classe, esse atributo vai ser disparado principalmente pro editar
            return $exec->execute() == 1 ? true : false;
        } catch (PDOException $err) { //linha de erro caso não consiga - PDOException, PDO é uma biblioteca do php para ter acesso ao banco de dados
            echo $err->getMessage();
        }
    }

    public function consultar($var_id)
    {
        try {
            $this->con = new Conn();
            $sql = "CALL listar_aluno(?)";
            $executar = $this->con->prepare($sql);
            $executar->bindValue(1, $var_id);
            return $executar->execute() == 1 ? $executar->fetchAll() : false;
        } catch (PDOException $exc) {
            echo $exc->getMessage();
        }
    }

    public function pesquisar($filtros)
    {
        $where = "";
        $params = "";
        $this->con = new Conn();

        if (!empty($filtros[0] == 'nome')) {
            $where = "alu_nome LIKE ?";
            $params = "%" . $filtros[1] . "%";
        }
        if (!empty($filtros[0] == 'idade')) {
            $where = "alu_idade LIKE ?";
            $params = "%" . $filtros[1] . "%";
        }
        if (!empty($filtros[0] == 'numero')) {
            $where = "alu_numero LIKE ?";
            $params = "%" . $filtros[1] . "%";;
        }
        $sql = "SELECT * FROM {$this->table} WHERE $where ORDER BY alu_nome ASC";

        $executar = $this->con->prepare($sql);
        $executar->bindValue(1, $params);

        return $executar->execute() == 1 ? $executar->fetchAll() : false;
    }

    /**
     * Retorna o total de registros da tabela
     */
    public function totalRegistros()
    {
        try {
            $this->con = new Conn();
            $sql = "SELECT COUNT(*) as total FROM {$this->table}";
            $executar = $this->con->prepare($sql);
            $executar->execute();
            $row = $executar->fetch(PDO::FETCH_ASSOC);
            return $row['total'];
        } catch (PDOException $exc) {
            echo $exc->getMessage();
        }
    }

    /**
     * Paginação de registros
     * @param int $pagina -> página atual
     * @param int $limite -> quantos registros por página
     */
    public function paginar($pagina = 1, $limite = 10)
    {
        try {
            $this->con = new Conn();

            // Calcula o offset
            $offset = ($pagina - 1) * $limite;

            $sql = "SELECT * FROM {$this->table} 
                ORDER BY alu_nome ASC 
                LIMIT :limite OFFSET :offset";

            $executar = $this->con->prepare($sql);
            $executar->bindValue(":limite", (int) $limite, PDO::PARAM_INT);
            $executar->bindValue(":offset", (int) $offset, PDO::PARAM_INT);

            return $executar->execute() == 1 ? $executar->fetchAll(PDO::FETCH_ASSOC) : false;
        } catch (PDOException $exc) {
            echo $exc->getMessage();
        }
    }
}
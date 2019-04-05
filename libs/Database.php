<?php

/*
 * Concesso in licenza d'uso a LA FENICE IMMOBILIARE
 * Sviluppato da WeDev s.a.s di Ricci Stefano & C.
 */

class Database extends PDO {

    function __construct() {
        parent::__construct(DB_TYPE . ':host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
    }
    
    /*
     * INSERT new record (RETURN ID insered or Error)
     */
    public function insert($table, $data) {
        $nomi = $this->getNamesFromParameters($data);
        $sql = 'INSERT INTO ' . $table . ' (';
        $sql .= implode(',', $nomi) . ') ';
        $sql .= 'VALUES (' . implode(', ', array_keys($data)) . ')';
        try {
            $sth = $this->prepare($sql);
            if ( $sth->execute($data) ) {
                return true;
            } else {
//                echo "err"; print_r($sth->errorInfo());exit;
//                if ($table==TAB_ASTE) {
//                    echo "<br>err"; print_r($sth->errorInfo());
//                }
                return false;
            }
        } catch (PDOException $e) {
            //echo '<br>KO'.$e->getMessage();exit;
            handle_sql_errors($sql, $e->getMessage());
        }
    }

    function handle_sql_errors($query, $error_message) {
        echo '<pre>';
        echo $query;
        echo '</pre>';
        echo $error_message;
        exit;
    }

    // SELECT: ritorna NULL se non trovato oppure l'array (SINGOLO O MULTIPLO)
    public function select($table, $data, $where, $parameters, $isSingle) {
        $sql = 'SELECT ' . $data . ' FROM ' . $table;
        if ($where != NULL) {
            $sql .= ' WHERE ' . $where;
        }
        $sth = $this->prepare($sql);
//        if ( $table==GAME_ACCOUNTS_TABLE) {
//           echo '<br>qui'; 
//           echo '<br>sql: '.$sql; 
//           print_r($parameters);
//        }
        if ($parameters != NULL) {
            $sth->execute($parameters);
        } else {
            $sth->execute();
        }
        if ($sth->rowCount() == 0)
            return null;
        if ($isSingle) {
            return $sth->fetch();
        } else {
            return $sth->fetchAll();
        }
    }
    
    // SELECT: ritorna NULL se non trovato oppure l'array (SINGOLO O MULTIPLO)
    public function selectWithOrder($table, $data, $where, $parameters, $isSingle, $orderBy, $limit) {
        $sql = 'SELECT ' . $data . ' FROM ' . $table;
        if ($where != NULL) {
            $sql .= ' WHERE ' . $where;
        }
        if ($orderBy != NULL) {
            $sql .= ' ORDER BY ' . $orderBy;
        }
        if ($limit != NULL) {
            $sql .= ' LIMIT ' . $limit;
        } 
//        if ($table==TAB_EXPORTS) {
//            echo $sql;exit;
//        }
        $sth = $this->prepare($sql);
        if ($parameters != NULL) {
            $sth->execute($parameters);
            
//            if ($sth->execute($parameters)) {
//                echo "ok".$sth->rowCount();exit;
//            } else {
//                echo "ko";exit;
//            }
            
        } else {
            $sth->execute();
        }
        if ($sth->rowCount() == 0)
            return null;
        if ($isSingle) {
            return $sth->fetch();
        } else {
            return $sth->fetchAll();
        }
    }
    
    // SELECT CON 1 JOIN: ritorna NULL se non trovato oppure l'array (SINGOLO O MULTIPLO)
    public function selectWithOrderAndJoin($table, $data, $where, $parameters, $isSingle, $orderBy, $limit,$joinTable,$joinTableId1,$joinTableId2) {
        $sql = 'SELECT ' . $data . ' FROM ' . $table;
        if ($joinTable != NULL) {
            $sql .= ' INNER JOIN ' . $joinTable .' ON '.$joinTableId1.'='.$joinTableId2;
        }
        if ($where != NULL) {
            $sql .= ' WHERE ' . $where;
        }
        if ($orderBy != NULL) {
            $sql .= ' ORDER BY ' . $orderBy;
        }
        if ($limit != NULL) {
            $sql .= ' LIMIT ' . $limit;
        }
//        echo '<br>sql: '.$sql;
//        echo '<br>';
//        print_r($parameters); 
        $sth = $this->prepare($sql);
        if ($parameters != NULL) {
            $sth->execute($parameters);
        } else {
            $sth->execute();
        }
        if ($sth->rowCount() == 0)
            return null;
        if ($isSingle) {
            return $sth->fetch();
        } else {
            return $sth->fetchAll();
        }
    }
    
    // SELECT CON 2 JOIN: ritorna NULL se non trovato oppure l'array (SINGOLO O MULTIPLO)
    public function selectWithOrderAndJoin2($table, $data, $where, $parameters, $isSingle, $orderBy, $limit,$joinTable,$joinTableId1,$joinTableId2,$joinTable2,$joinTable2Id1,$joinTable2Id2) {
        $sql = 'SELECT ' . $data . ' FROM ' . $table;
        if ($joinTable != NULL) {
            $sql .= ' INNER JOIN ' . $joinTable .' ON '.$joinTableId1.'='.$joinTableId2;
        }
        if ($joinTable2 != NULL) {
            $sql .= ' INNER JOIN ' . $joinTable2 .' ON '.$joinTable2Id1.'='.$joinTable2Id2;
        }
        if ($where != NULL) {
            $sql .= ' WHERE ' . $where;
        }
        if ($orderBy != NULL) {
            $sql .= ' ORDER BY ' . $orderBy;
        }
        if ($limit != NULL) {
            $sql .= ' LIMIT ' . $limit;
        }
        $sth = $this->prepare($sql);
        if ($parameters != NULL) {
            $sth->execute($parameters);
        } else {
            $sth->execute();
        }
        if ($sth->rowCount() == 0)
            return null;
        if ($isSingle) {
            return $sth->fetch();
        } else {
            return $sth->fetchAll();
        }
    }
    
    // SELECT: ritorna numero
    public function selectCount($table, $data, $where, $parameters) { 
        $sql = 'SELECT ' . $data . ' FROM ' . $table;
        if ($where != NULL) { 
            $sql .= ' WHERE ' . $where;
        }
        $sth = $this->prepare($sql);
        if ($parameters != NULL) {
            $sth->execute($parameters);
        } else {
            $sth->execute();
        }
        return $sth->rowCount();
    }
    
    
    // SELECT: ritorna numero SOMMA
    public function selectSum($table, $valueToSum, $where, $parameters) {
        $sql = 'SELECT SUM(' . $valueToSum . ') AS totalSum FROM ' . $table;
        if ($where != NULL) {
            $sql .= ' WHERE ' . $where;
        }
        $sth = $this->prepare($sql);
        if ($parameters != NULL) {
            $sth->execute($parameters);
        } else {
            $sth->execute();
        }
        $result = $sth->fetch();
        return $result['totalSum'];
    }

    public function update($table, $data, $where, $parameters) {
        $nomi = $this->getNamesFromParameters($data);
        $sql = 'UPDATE ' . $table . ' SET ';
        foreach ($nomi as $value) {
            $sql .= ' ' . $value . ' = :' . $value . ',';
        }
        $sql = rtrim($sql, ',');
        $sql .= ' WHERE ' . $where; 
//        if ($table == TAB_USERS) {
//            echo $sql;
//        }
        $sth = $this->prepare($sql);
        $dataAll = ($parameters == NULL) ? $data : array_merge($data, $parameters);
        $sth->execute($dataAll);
        
//        if ($table == TAB_USERS) {
//            print_r($data);
//            print_r($sth->errorInfo());
//            exit;
//        }
    }

    public function delete($table, $where, $parameters) {
        $sql = 'DELETE FROM  ' . $table;
        if ($where != NULL) {
            $sql .= ' WHERE ' . $where;
        }
        $sth = $this->prepare($sql);
        if ($parameters != NULL) {
            $sth->execute($parameters);
        } else {
            $sth->execute();
        }
    }

    function getNamesFromParameters($data) {
        $arrayParameters = array_keys($data);
        $arrayNames = array();
        foreach ($arrayParameters as $value) {
            $stringa = substr($value, 1);
            array_push($arrayNames, $stringa);
        }
        return $arrayNames;
    }


}

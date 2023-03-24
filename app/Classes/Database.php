<?php

namespace Fab\Classes;

/**
 * 
 * Database 
 * 
 *  __construct()
 * select(string $requet, array $params):array | collection
 * selectOne(string $requet, array $params):array
 * query(string $requet, array $params):int|null
 * 
 */

/**
 * Storage database
 */
class Database {

    /**
     * Connexion PDO
     *
     * @var \PDO
     */
    private static $pdo=null;


    /** Contruct a new DataStorageDatabase */
    public function __construct() {
        $defaults = [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
        ];
        try {
            if(!(self::$pdo instanceof  \PDO))
                self::$pdo = new \PDO(DB_DSN, DB_USERNAME, DB_PASSWORD, $defaults);
        } catch (\PDOException $e) {
            // TODO return correct error/information page on PDOException
            echo $e->getMessage();
        }
        
    }

    /** Select an return one/first element from data
     * @param string $request Request to send to Data for retrive information
     * @param array $params Params array to send to Data for retrive information
     * 
     * @return array|null
     */
    public function selectOne(string $request = '', array $params = []):?array {

        $sth = self::$pdo->prepare($request);

        $this->execute($sth, $params);
        
        $result = $sth->fetch();

        return empty($result)?null: $result;
     
    }

    /**
     * Select an return all elements from data.
     *
     * @param string $entityName
     * @param string $request
     * 
     * @return array|null
     * 
     */
    public function select(string $request = '', array $params = []): ?array
    {
        $sth = self::$pdo->prepare($request);

        $this->execute($sth, $params);

        $results = $sth->fetchAll();

        return empty($results) ? null : $results;
    }

    /**
     * Execute any SQL request and return last insert element id if exist or null
     *
     * @param string $request
     * @param array $params
     * 
     * @return int|null
     * 
     */
    public function query(string $request = '', array $params = []) : ?int 
    {
        $sth = self::$pdo->prepare($request);

        $this->execute($sth, $params);

        return self::$pdo->lastInsertId() ?? null;
    }


    /**
     * Execute any request on pdostatement object
     *
     * @param \PDOStatement $sth
     * @param array $params
     * 
     * @return void
     * 
     */
    private function execute(\PDOStatement $sth, array $params = []) {
        try {
            $sth->execute($params);
        } catch (\PDOException $e) {
            // TODO return correct information on PDOException
            echo $e->getMessage();
        }
    }

}
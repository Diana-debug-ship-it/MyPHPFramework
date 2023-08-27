<?php

namespace vendor\core;
class Db
{

    private $pdo;
    private static $instance;
    public static $countSql = 0;
    public static $queries = [];


    private function __construct()
    {
        $db = require ROOT . '/config/config_db.php';
        $options = [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
        ];
        $this->pdo = new \PDO($db['dsn'], $db['user'], $db['pass'], $options);
    }

    public static function instance(): Db
    {
        if (self::$instance === null) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    /**
     * Выполняет запрос к БД, который не требует
     * получения каких-то данных из БД
     * типа просто создать таблицу
     * @param $sql SQL запрос
     * @return bool успех или неуспех операции
     */
    public function execute($sql, $params = [])
    {
        self::$countSql++;
        self::$queries[] = $sql;
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }

    /**
     * готовит выполняет запрос но уже с какими-то данными
     * из таблицы
     * например, выборку всех полей из таблицы
     * @param $sql SQl запрос
     * @return array|false ответ в виде массива или false если операция не прошла
     */
    public function query($sql, $params = [])
    {
        self::$countSql++;
        self::$queries[] = $sql;
        $stmt = $this->pdo->prepare($sql);
        $res = $stmt->execute($params);
        debug($res);
        if ($res!==false) {
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }
        return [];
    }

}
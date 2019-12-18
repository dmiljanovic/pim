<?php

namespace App\Database;

use PDO;
use PDOException;
use Analog\Analog;

/**
 * Class Db
 * @package App\Database
 */
class Db
{
    /**
     * Hostname
     *
     * @var string
     */
    protected static $host = "localhost";

    /**
     * Database name
     *
     * @var string
     */
    protected static $dbname = "pim";

    /**
     * User
     *
     * @var string
     */
    protected static $user = "root";

    /**
     * Password
     *
     * @var string
     */
    protected static $pass = "root";

    /**
     * Private static method for creating connection to db.
     *
     * @return PDO
     */
    protected static function con()
    {
        try {
            $pdo = new PDO("mysql:host=" . self::$host . ";dbname=" . self::$dbname . ";charset=utf8", self::$user,
                self::$pass);
            $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $pdo;
        }
        catch(PDOException $exception) {
            Analog::log('Error while trying to connect to db: ' . $exception);

            die('Error while trying to connect to db, please contact your admin.');
        }
    }

    /**
     * Static method for prepare and execute query statement.
     *
     * @param string $query
     * @param array $params
     * @return boolean
     */
    public static function saveData($query, $params = array())
    {
        $stmt = self::con()->prepare($query);
        return $stmt->execute($params);
    }

    /**
     * Static method for prepare, execute and fetch query statement.
     *
     * @param string $query
     * @param array $params
     * @return array
     */
    public static function getData($query, $params = array())
    {
        $stmt = self::con()->prepare($query);
        $stmt->execute($params);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $data;
    }
}

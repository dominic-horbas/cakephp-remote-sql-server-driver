<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         3.0.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Database\Driver;

use App\Database\Statement\CustomSqlserverStatement;
use Cake\Database\Driver\Sqlserver;
use Cake\Database\Query;
use Cake\Database\Statement\SqlserverStatement;
use PDO;

/**
 * SQLServer driver.
 */
class CustomSqlserver extends Sqlserver
{
    public function connect()
    {
        if ($this->_connection) {
            return true;
        }
        $config = $this->_config;
        $config['flags'] += [
            PDO::ATTR_PERSISTENT => $config['persistent'],
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ];

        if (!empty($config['encoding'])) {
            $config['flags'][PDO::SQLSRV_ATTR_ENCODING] = $config['encoding'];
        }

        $dsn = "sqlsrv:Server={$config['host']};Database={$config['database']};MultipleActiveResultSets=false";
        $this->_connect($dsn, $config);

        $connection = $this->connection();
        if (!empty($config['init'])) {
            foreach ((array)$config['init'] as $command) {
                $connection->exec($command);
            }
        }
        if (!empty($config['settings']) && is_array($config['settings'])) {
            foreach ($config['settings'] as $key => $value) {
                $connection->exec("SET {$key} {$value}");
            }
        }
        return true;
    }

    /**
     * Prepares a sql statement to be executed
     *
     * @param string|\Cake\Database\Query $query The query to prepare.
     * @return \Cake\Database\StatementInterface
     */
    public function prepare($query)
    {
        $this->connect();
        $options = [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY];
        $isObject = $query instanceof Query;
        if ($isObject && $query->bufferResults() === false) {
            $options = [];
        }
        $statement = $this->_connection->prepare($isObject ? $query->sql() : $query, $options);
        $result = new CustomSqlserverStatement(new SqlserverStatement($statement, $this), $this);
        if ($isObject && $query->bufferResults() === false) {
            $result->bufferResults(false);
        }
        return $result;
    }

}

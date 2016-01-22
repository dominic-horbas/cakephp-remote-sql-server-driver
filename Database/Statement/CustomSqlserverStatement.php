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
namespace App\Database\Statement;
use Cake\Database\Statement\SqlserverStatement;
use Cake\Database\Statement\BufferResultsTrait;
use Cake\Database\Statement\BufferedStatement;

/**
 * Statement class meant to be used by an Sqlserver driver
 *
 */
class CustomSqlserverStatement extends SqlserverStatement
{

    use BufferResultsTrait;

    public function __construct(SqlserverStatement $statement = null, $driver = null)
    {
        $this->_statement = $statement;
        $this->_driver = $driver;
    }

    public function execute($params = null)
    {
        if ($this->_statement instanceof BufferedStatement) {
            $this->_statement = $this->_statement->getInnerStatement();
        }

        if ($this->_bufferResults) {
            $this->_statement = new BufferedStatement($this->_statement, $this->_driver);
        }

        return $this->_statement->execute($params);
    }


    /**
     * Returns the number of rows returned of affected by last execution
     *
     * @return int
     */
    public function rowCount()
    {
        return $this->_statement->count();
    }

}

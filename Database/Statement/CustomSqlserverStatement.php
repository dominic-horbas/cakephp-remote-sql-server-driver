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
/**
 * Statement class meant to be used by an Sqlserver driver
 *
 */
class CustomSqlserverStatement extends SqlserverStatement
{

    public function __construct(SqlserverStatement $statement = null, $driver = null)
    {
        $this->_statement = $statement;
        $this->_driver = $driver;
    }
    /**
     * Returns the number of rows returned of affected by last execution
     *
     * @return int
     */
    public function rowCount()
    {

        $changes = $this->_driver->prepare('SELECT @@Rowcount');
        $changes->execute();
        $count = $changes->fetch()[0];
        $changes->closeCursor();
        return (int)$count;

    }

}

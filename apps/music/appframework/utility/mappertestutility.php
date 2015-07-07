<?php
/**
 * ownCloud
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 *
 * @author Alessandro Cosentino <cosenal@gmail.com>
 * @author Bernhard Posselt <dev@bernhard-posselt.com>
 * @copyright Alessandro Cosentino 2012
 * @copyright Bernhard Posselt 2012, 2014
 */

namespace OCA\Music\AppFramework\Utility;

use OCA\Music\AppFramework\Core\Db;


/**
 * Simple utility class for testing mappers
 */
abstract class MapperTestUtility extends \PHPUnit_Framework_TestCase {


	protected $db;
	private $query;
	private $pdoResult;
	private $queryAt;
	private $prepareAt;
	private $fetchAt;
	private $iterators;


	/**
	 * Run this function before the actual test to either set or initialize the
	 * db. After this the db can be accessed by using $this->db
	 */
	protected function beforeEach(){
		$this->db = $this->getMockBuilder(
			'\OCA\Music\AppFramework\Core\Db')
			->disableOriginalConstructor()
			->getMock();

		$this->query = $this->getMock('Query', array('execute', 'bindValue'));
		$this->pdoResult = $this->getMock('Result', array('fetchRow', 'fetchAll'));
		$this->queryAt = 0;
		$this->prepareAt = 0;
		$this->iterators = array();
		$this->fetchAt = 0;
	}


	/**
	 * Create mocks and set expected results for database queries
	 * @param string $sql the sql query that you expect to receive
	 * @param array $arguments the expected arguments for the prepare query
	 * method
	 * @param array $returnRows the rows that should be returned for the result
	 * of the database query. If not provided, it wont be assumed that fetchRow
	 * will be called on the result
	 */
	protected function setMapperResult($sql, $arguments=array(), $returnRows=array(),
		$limit=null, $offset=null){

		$this->iterators[] = new ArgumentIterator($returnRows);

		$iterators = $this->iterators;
		$fetchAt = $this->fetchAt;

		$this->pdoResult->expects($this->any())
			->method('fetchRow')
			->will($this->returnCallback(
				function() use ($iterators, $fetchAt){
					$iterator = $iterators[$fetchAt];
					$result = $iterator->next();

					if($result === false) {
						$fetchAt++;
					}

					return $result;
				}
			));

		$this->pdoResult->expects($this->any())
			->method('fetchAll')
			->will($this->returnValue($returnRows));

		$index = 1;
		foreach($arguments as $argument) {
			switch (gettype($argument)) {
				case 'integer':
					$pdoConstant = \PDO::PARAM_INT;
					break;

				case 'NULL':
					$pdoConstant = \PDO::PARAM_NULL;
					break;

				case 'boolean':
					$pdoConstant = \PDO::PARAM_BOOL;
					break;

				default:
					$pdoConstant = \PDO::PARAM_STR;
					break;
			}
			$this->query->expects($this->at($this->queryAt))
				->method('bindValue')
				->with($this->equalTo($index),
					$this->equalTo($argument),
					$this->equalTo($pdoConstant));
			$index++;
			$this->queryAt++;
		}

		$this->query->expects($this->at($this->queryAt))
			->method('execute')
			->with()
			->will($this->returnValue($this->pdoResult));
		$this->queryAt++;

		if($limit === null && $offset === null) {
			$this->db->expects($this->at($this->prepareAt))
				->method('prepareQuery')
				->with($this->equalTo($sql))
				->will(($this->returnValue($this->query)));
		} elseif($limit !== null && $offset === null) {
			$this->db->expects($this->at($this->prepareAt))
				->method('prepareQuery')
				->with($this->equalTo($sql), $this->equalTo($limit))
				->will(($this->returnValue($this->query)));
		} elseif($limit === null && $offset !== null) {
			$this->db->expects($this->at($this->prepareAt))
				->method('prepareQuery')
				->with($this->equalTo($sql),
					$this->equalTo(null),
					$this->equalTo($offset))
				->will(($this->returnValue($this->query)));
		} else  {
			$this->db->expects($this->at($this->prepareAt))
				->method('prepareQuery')
				->with($this->equalTo($sql),
					$this->equalTo($limit),
					$this->equalTo($offset))
				->will(($this->returnValue($this->query)));
		}
		$this->prepareAt++;
		$this->fetchAt++;

	}


}


class ArgumentIterator {

	private $arguments;

	public function __construct($arguments){
		$this->arguments = $arguments;
	}

	public function next(){
		$result = array_shift($this->arguments);
		if($result === null){
			return false;
		} else {
			return $result;
		}
	}
}


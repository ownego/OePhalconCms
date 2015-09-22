<?php

namespace OE\Behaviour;

use Phalcon\DI;
use Phalcon\DiInterface;

trait DIBehaviour
{
	/**
	 * Dependency injection container.
	 *
	 * @var DIBehaviour|DI
	 */
	private $_di;

	/**
	 * Create object.
	 *
	 * @param DiInterface|DIBehaviour $di Dependency injection container.
	 */
	public function __construct($di = null)
	{
		if ($di == null) {
			$di = DI::getDefault();
		}
		$this->setDI($di);
	}

	/**
	 * Set DI.
	 *
	 * @param DiInterface $di Dependency injection container.
	 *
	 * @return void
	 */
	public function setDI($di)
	{
		$this->_di = $di;
	}

	/**
	 * Get DI.
	 *
	 * @return DIBehaviour|DI
	 */
	public function getDI()
	{
		return $this->_di;
	}
}
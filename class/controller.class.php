<?php declare(strict_types=1);

/**
 * Interface Controller
 */
interface Controller {

	/**
	 * @param array $req
	 */
	public function handleRequest ( array $req );

	/**
	 * @param int $id
	 * @param string $msg
	 */
	public function setError ( int $id, string $msg );

}
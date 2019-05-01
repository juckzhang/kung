<?php
namespace common\helpers;

class PHPLock {

	private $path = null;

	private $fp = null;

	private $hashNum = 100;
	private $name;
	private $eAccelerator = false;

	public function __construct($path, $name) {
		$this->path = $path . ($this->mycrc32 ( $name ) % $this->hashNum) . '.txt';
		$this->eAccelerator = function_exists ( "eaccelerator_lock" );
		$this->name = $name;
	}

	private function mycrc32($string) {
		$crc = abs ( crc32 ( $string ) );
		if ($crc & 0x80000000) {
			$crc ^= 0xffffffff;
			$crc += 1;
		}
		return $crc;
	}

	public function startLock() {
		if (! $this->eAccelerator) {
			$this->fp = fopen ( $this->path, "w+" );
		}
	}

	public function lock() {
		if (! $this->eAccelerator) {
			if ($this->fp === false) {
				return false;
			}
			return flock ( $this->fp, LOCK_EX );
		} else {
			return eaccelerator_lock ( $this->name );
		}
	}

	public function unlock() {
		if (! $this->eAccelerator) {
			if ($this->fp !== false) {
				flock ( $this->fp, LOCK_UN );
				clearstatcache ();
			}
		} else {
			return eaccelerator_unlock ( $this->name );
		}
	}

	public function endLock() {
		if (! $this->eAccelerator) {
			fclose ( $this->fp );
		}
	}
}

?>
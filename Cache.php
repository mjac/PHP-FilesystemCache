<?php

namespace FilesystemCache;

class Cache {
	public $path;

	public function __construct($path) {
		$this->path = $path;
	}

	protected function name($id) {
		return preg_replace('/[^a-z0-9-_]/i', '', $id);
	}
	
	protected function file($id) {
		return $this->path . DIRECTORY_SEPARATOR . $this->name($id);
	}

	public function retrieve($name, $changedTimestamp = FALSE) {
		$filename = $this->file($name);

		if ($changedTimestamp !== FALSE
			&& file_exists($filename)
			&& filemtime($filename) > $changedTimestamp) {
			return unserialize(file_get_contents($filename));
		}

		return NULL;

	}

	public function store($name, $data) {
		$filename = $this->file($name);

		$dirName = dirname($filename);
		if (!is_dir($dirName)) {
			mkdir($dirName, 0700, TRUE);
		}

		$writeResult = file_put_contents($filename, serialize($data));

		return $writeResult !== false;
	}
}

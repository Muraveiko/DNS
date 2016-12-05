<?php

namespace Muraveiko\DNS;

use \Exception;

trait LoadJson
{
    /**
     * LoadJson constructor.
     * @param $file
     * @param string $description
     * @throws Exception
     * @return LoadJson
     */
  private function loadJson($file, $description = 'json config')
  {
        $handle = @fopen($file, "r");
        if(!$handle) {
            throw new Exception('Unable to open '.$description.' file.');
        }

        $json = fread($handle, filesize($file));
        fclose($handle);

        $array = json_decode($json, true);
        if(!$array) {
            throw new Exception('Unable to parse '.$description.' file.');
        }
        return $array; 
  }
}

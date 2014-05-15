<?php

namespace PureMachine\Bundle\SDKBundle\Manager;

class StaticManager
{

    /**
     * Stores the data statically on the .static
     * directory. It uses the key variable to create
     * the filename
     *
     * @param $key
     * @param $data
     * @return int
     */
    public static function storeData($key, $data)
    {
        $keyHashed = "static_".sha1($key);
        $representation = var_export($data, true);
        $filepath = __DIR__.'/.static/'.$keyHashed.'.inc.php';
        if (file_exists($filepath)) {
            unlink($filepath);
        }

        //Dump
        return file_put_contents($filepath, '<?php'.PHP_EOL.'$'.$keyHashed.' = '.$representation.';'.PHP_EOL.'?>');
    }

    /**
     * Retrieves static data from the .static
     * directory
     *
     * @param $key
     * @return null|array
     */
    public static function fetchData($key)
    {
        $keyHashed = "static_".sha1($key);
        $filepath = __DIR__.'/.static/'.$keyHashed.'.inc.php';

        if (!file_exists($filepath)) {
            return null;
        }

        include($filepath);

        return $$keyHashed;
    }

}

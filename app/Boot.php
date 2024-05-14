<?php namespace App;

/**
 * Boot App
 *
 * Created with love and proud by Ghivarra Senandika Rushdie
 *
 * @package Native PHP Dynamic Image Resizer
 *
 * @var https://github.com/ghivarra
 * @var https://facebook.com/bcvgr
 * @var https://twitter.com/ghivarra
 *
**/

class Boot
{
    private function getCleanPath(): string
    {
        // break down url/path and delete the first slash
        $path = str_replace('\\', '/', $_SERVER['REQUEST_URI']);
        $path = substr($path, 1);

        // remove ?get string and remove last slash
        $hasGet = strstr($path, '?', TRUE);

        if ($hasGet !== FALSE)
        {
            $path = rtrim($hasGet, '?');
        }
        
        $path = rtrim($path, '/');

        // remove double slash
        $path = explode('/', $path);
        
        foreach ($path as $key => $item):

            if (empty($item))
            {
                unset($path[$key]);
            }

        endforeach;

        // return
        return implode('/', $path);
    }

    //=========================================================================================

    private function badRequest($message = 'Error 400 - Bad Request'): string
    {
        header('HTTP/1.1 400 Bad Request', true, 400);
        return $message;
    }

    //=========================================================================================

    public function run()
    {
        // load required files
        require_once APPPATH . 'Common.php';
        require_once APPPATH . 'Config.php';

        // check method, if not get and options for ajax purposes then GTFOH
        if ($_SERVER['REQUEST_METHOD'] !== 'GET' && $_SERVER['REQUEST_METHOD'] !== 'OPTIONS')
        {
            return $this->badRequest('Only GET and OPTIONS methods are supported.');
        }

        // set cors
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, OPTIONS');
        header("Access-Control-Allow-Headers: X-Requested-With");

        // get cleaned path & remove dist
        $path = $this->getCleanPath();
        $path = substr($path, strlen('dist/'));

        // get requested image data
        $ext = substr(strrchr($path, '.'), 1);

        if (!$ext)
        {
            return $this->badRequest('File needed to have extensions.');
        }

        // new config
        $ext    = strtolower($ext);
        $config = new Config();

        if (!in_array($ext, $config->allowedExtension))
        {
            return $this->badRequest('File format is not supported.');
        }

        // search file
        $settings     = strstr($path, '--resized-');
        $originalPath = substr($path, 0, (strlen($path) - strlen($settings))) . ".{$ext}";

        if (!file_exists(RESOURCEPATH . $originalPath))
        {
            return $this->badRequest('File not found.');
        }
        
        prettyPrint($originalPath);

        // prettyPrint($config->allowedExtension);

        // prettyPrint($_SERVER);
        echo round((memory_get_peak_usage() * 0.0000001), 3) . ' mb';
    }

    //=========================================================================================
}
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
    private function badRequest(string $message = 'Error 400 - Bad Request'): string
    {
        header('HTTP/1.1 400 Bad Request', true, 400);
        return htmlspecialchars($message);
    }

    //=========================================================================================

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

    private function resize(string $originalPath, string $options, string $extension)
    {
        $options = str_replace(['--resized-', ".{$extension}"], '', $options);
        $options = explode('-', $options);

        // config
        $config = new Config();

        // set
        $set = [
            'width'      => isset($options[0]) ? intval(substr($options[0], 1)) : $config->defaultWidth,
            'height'     => isset($options[1]) ? intval(substr($options[1], 1)) : $config->defaultHeight,
            'constraint' => isset($options[2]) ? substr($options[2], 1) : $config->defaultConstraint,
        ];

        // set allowed
        $allowed = [
            'height'     => $config->allowedHeight,
            'width'      => $config->allowedWidth,
            'constraint' => ['width', 'height', 'forced'],
        ];

        array_push($allowed['width'], $config->defaultWidth);
        array_push($allowed['height'], $config->defaultHeight);

        // validate options
        foreach ($set as $key => $value):

            if(!in_array($value, $allowed[$key]))
            {
                $keyTitle = ucwords($key);
                return $this->badRequest("{$keyTitle} is not allowed in configurations.");
            }

        endforeach;

        // resize image
        

        // prettyPrint($_SERVER);
        echo round((memory_get_peak_usage() * 0.0000001), 3) . ' mb';
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
        $extension = substr(strrchr($path, '.'), 1);

        if (!$extension)
        {
            return $this->badRequest('File needed to have extensions.');
        }

        // new config
        $extension = strtolower($extension);
        $config    = new Config();

        if (!in_array($extension, $config->allowedExtension))
        {
            return $this->badRequest('File format is not supported.');
        }

        // search file
        $options      = strstr($path, '--resized-');
        $originalPath = RESOURCEPATH . substr($path, 0, (strlen($path) - strlen($options))) . ".{$extension}";

        if (!file_exists($originalPath))
        {
            return $this->badRequest('File not found.');
        }

        // run resize
        return $this->resize($originalPath, $options, $extension);
    }

    //=========================================================================================
}
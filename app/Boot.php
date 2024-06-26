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

use \Imagick as Imagick;

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

    private function print(string $imagePath): string
    {
        $mime = mime_content_type($imagePath);
        $size = filesize($imagePath);

        header("Cache-Control: max-age=31536000, immutable");
        header("Vary: Accept-Encoding");
        header("Content-Type: {$mime}");
        header("Content-Length: {$size}");

        // return
        return file_get_contents($imagePath);
    }

    //=========================================================================================

    private function resize(string $originalPath, string $targetPath, string $options, string $extension): string
    {
        $options = str_replace(['--resized-', ".{$extension}"], '', $options);
        $options = explode('-', $options);

        // config
        $config = new Config();

        // set
        foreach ($options as $option):

            $firstLetter = substr($option, 0, 1);

            if (!$firstLetter)
            {
                continue;
            }

            switch ($firstLetter) {
                case 'w':
                    $set['width'] = intval(substr($option, 1));
                    break;

                case 'h':
                    $set['height'] = intval(substr($option, 1));
                    break;
                
                case 'c':
                    $set['constraint'] = substr($option, 1);
                    break;
            }

        endforeach;
        
        // recheck set
        $set['width']      = isset($set['width']) ? $set['width'] : $config->defaultWidth;
        $set['height']     = isset($set['height']) ? $set['height'] : $config->defaultHeight;
        $set['constraint'] = isset($set['constraint']) ? $set['constraint'] : $config->defaultConstraint;

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
                return $this->badRequest("{$keyTitle} {$value} is not allowed in configurations.");
            }

        endforeach;

        $fileName     = substr(strrchr($targetPath, '/'), 1);
        $targetFolder = substr($targetPath, 0, (strlen($targetPath) - strlen($fileName)));

        // check if folder exist and create if not exist
        if(!file_exists($targetFolder) OR !is_dir($targetFolder))
        {
            mkdir($targetFolder, 0755, true);
        }

        // Windows server function normally but for some reason Linux Server cannot load svg file in imagick
        // so we use shell_exec / exec
        // need librsvg2-bin, and exec must be enabled
        if (strtolower($extension) === 'svg' && (strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN'))
        {
            // convert size
            if ($set['constraint'] === 'forced')
            {
                shell_exec("rsvg-convert {$originalPath} -w {$set['width']} -h {$set['height']} -f svg -o {$targetPath}");

            } else {

                $sizeOption = ($set['constraint'] === 'width') ? "-w {$set['width']}" : "-h {$set['height']}";
                shell_exec("rsvg-convert {$originalPath} -a {$sizeOption} -f svg -o {$targetPath}");
            }

        } else {
            
            // resize image
            $image = new ImageMagick();
            $image->readImage($originalPath);
    
            if ($set['constraint'] === 'forced')
            {
                $resize = $image->resizeImage($set['width'], $set['height'], ImageMagick::FILTER_GAUSSIAN, 0.5, false);
    
            } else {
    
                // manipulate set data
                $set['width']  = ($set['constraint'] === 'width') ? $set['width'] : 0;
                $set['height'] = ($set['constraint'] === 'height') ? $set['height'] : 0;
    
                $resize = $image->scaleImage($set['width'], $set['height']);
            }
    
            // destroy imagick instance
            $image->clear();
        }

        // print image
        return $this->print($targetPath);
    }

    //=========================================================================================

    public function run()
    {
        // load required files
        require_once APPPATH . 'Common.php';
        require_once APPPATH . 'Config.php';

        // set environment
        $config = new Config();

        if ($config->env == 'development')
        {
            error_reporting(E_ALL);
            ini_set("display_errors", 1);

        } elseif ($config->env == 'production') {

            error_reporting(0);
            ini_set("display_errors", 0);
        }

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

        if (!in_array($extension, $config->allowedExtension))
        {
            return $this->badRequest('File format is not supported.');
        }

        // search file
        $options      = strstr($path, '--resized-');
        $originalPath = RESOURCEPATH . substr($path, 0, (strlen($path) - strlen($options))) . ".{$extension}";
        $targetPath   = PUBLICPATH . 'dist/' . $path;

        if (!file_exists($originalPath))
        {
            return $this->badRequest('File not found.');
        }

        // run resize
        return $this->resize($originalPath, $targetPath, $options, $extension);
    }

    //=========================================================================================
}
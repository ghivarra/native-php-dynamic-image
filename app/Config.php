<?php namespace App;

/**
 * Config App
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

class Config
{
    // Allowed Extensions
    public $allowedExtension = ['png', 'jpg', 'gif', 'tiff', 'webp', 'svg'];

    // Default Settings
    public $defaultWidth = 450;
    public $defaultHeight = 450;
    public $dedaultConstraint = 'width'; // width, height, or forced
    
    // Allowed Settings, default settings is always allowed
    public $allowedHeight = [ 1366 ];
    public $allowedWidth = [ 1366 ];
}
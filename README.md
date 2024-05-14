# Native PHP Dynamic Resizing Image
Dynamic Resizing Image using Native PHP + ImageMagick, No other dependency needed as I keep it as minimal as possible.

- Tested on PHP 8.3 with Imagick 3.7.0

## Basic Example
For example, you wanted to dynamic resize image named **logo.jpg** with specific size such as: **width = 100px** and **height = 72px** with **non-constraint scaling** or forced resizing so the image is exactly on that specific size.

1. Store your logo.jpg inside resources folder
2. Access your dynamic images using this url: https://yoursite.com/dist/logo--resized-w100-h76-cforced.jpg

As you can see, we use 100 after the letter 'w' which stand for width and 72 after the letter 'h' which stand for height. We also use forced after the letter 'c' which stand for constraint.

## 'c' for Constraint
There are three options for this setting. You can set the default options on app/Config.php.
- forced
The image will be forced to resize based on your width and height settings.
- width
The image will be scaled based on your width settings. For example if you wanted to resize a 1600x1200 image to 800x800 image, you will get 800x600 sized image as the image is scaling based on width.
- height
The image will be scaled based on your height settings. For example if you wanted to resize a 1600x1200 image to 800x800 image, you will get 1200x800 sized image as the image is scaling based on height.

## 'w' for Width
You can set your preferred width for the image on this option. You can set the default options on app/Config.php.

## 'h' for Height
You can set your preferred height for the image on this option. You can set the default options on app/Config.php.

## Configuration
File is located in app\Config.php

### allowedExtension
Store your allowed image extensions on this option. 
__format: Array__

### defaultWidth
Set your default width on this option. 
__format: Integer__

### defaultHeight
Set your default height on this option. 
__format: Integer__

### defaultConstraint
Set your constraint (width, height, or forced) on this option. 
__format: String__

### allowedWidth
Store your allowed width on this option. 
__format: Array__

### allowedHeight
Store your allowed height on this option. 
__format: Array__
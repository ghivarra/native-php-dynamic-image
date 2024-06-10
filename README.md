# Native PHP Dynamic Resizing Image
Dynamic Resizing Image using Native PHP + ImageMagick, No other dependency needed as I keep it as minimal as possible.

- Tested on PHP 7.4 and higher with Imagick on Windows Server
- Tested on PHP 7.4 and higher with Imagick on Linux Server

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

# Dependency
- PHP 7.4 or higher
- PHP-Imagick
- Library librsvg2-bin or Application inkscape (Linux Server Only)
- shell_exec or exec PHP extension enabled

# Supported Image Format
3FR, 3G2, 3GP, AAI, AI, APNG, ART, ARW, AVI, AVIF, AVS, BGR, BGRA, BGRO, BIE, BMP, BMP2, BMP3, BRF, CAL, CALS, CANVAS, CAPTION, CIN, CIP, CLIP, CMYK, CMYKA, CR2, CR3, CRW, CUR, CUT, DATA, DCM, DCR, DCX, DDS, DFONT, DJVU, DNG, DOT, DPX, DXT1, DXT5, EPDF, EPI, EPS, EPS2, EPS3, EPSF, EPSI, EPT, EPT2, EPT3, ERF, EXR, FAX, FILE, FITS, FRACTAL, FTP, FTS, G3, G4, GIF, GIF87, GRADIENT, GRAY, GRAYA, GROUP4, GV, H, HALD, HDR, HEIC, HISTOGRAM, HRZ, HTM, HTML, HTTP, HTTPS, ICB, ICO, ICON, IIQ, INFO, INLINE, IPL, ISOBRL, ISOBRL6, J2C, J2K, JBG, JBIG, JNG, JNX, JP2, JPC, JPE, JPEG, JPG, JPM, JPS, JPT, JSON, K25, KDC, LABEL, M2V, M4V, MAC, MAGICK, MAP, MASK, MAT, MATTE, MEF, MIFF, MKV, MNG, MONO, MOV, MP4, MPC, MPG, MRW, MSL, MSVG, MTV, MVG, NEF, NRW, NULL, ORF, OTB, OTF, PAL, PALM, PAM, PANGO, PATTERN, PBM, PCD, PCDS, PCL, PCT, PCX, PDB, PDF, PDFA, PEF, PES, PFA, PFB, PFM, PGM, PGX, PICON, PICT, PIX, PJPEG, PLASMA, PNG, PNG00, PNG24, PNG32, PNG48, PNG64, PNG8, PNM, POCKETMOD, PPM, PREVIEW, PS, PS2, PS3, PSB, PSD, PTIF, PWP, RADIAL-GRADIENT, RAF, RAS, RAW, RGB, RGBA, RGBO, RGF, RLA, RLE, RMF, RW2, SCR, SCT, SFW, SGI, SHTML, SIX, SIXEL, SPARSE-COLOR, SR2, SRF, STEGANO, SUN, SVG, SVGZ, TEXT, TGA, THUMBNAIL, TIFF, TIFF64, TILE, TIM, TTC, TTF, TXT, UBRL, UBRL6, UIL, UYVY, VDA, VICAR, VID, VIDEO, VIFF, VIPS, VST, WBMP, WEBM, WEBP, WMF, WMV, WMZ, WPG, X, X3F, XBM, XC, XCF, XPM, XPS, XV, XWD, YCbCr, YCbCrA, YUV
<?php
/**
 * Image Resizer Class
 * 
 * @author Michael Akanji <matscode@gmail.com>
 * @package Class
 * @version v1.0
 *  
 */
class ImgResize {

	 public function resizeImage($path, $resampleWidth) {
		  /* -------- METHOD DOC ------------
			 function to resize image using resample image to maintain aspect ratio
			* * Argument to be passed to the
			 function are listed below and comment
			 in front of em

			 $path === path to image to be resized
			 $resampleWidth === desired width of the image to be resized

			* * Values to be return by the function
			 $photo(identity) === the identity of the image resized
			 $resampleWidth === the desired width of the image
			 $resampleHeight === the height of the image return as the function is trying to maitain aspect ratio
			 $photoExt === extention of the photo as jpg|png
			 ---------------------------------- */
		  //find photo's ext to know which photoType to create from e.g. imagecreatefromjpeg|png
		  $pathPart = explode('.', $path);
		  $photoExt = end($pathPart);
		  //check for photos extention
		  if ($photoExt == 'jpg' || $photoExt == 'jpeg') {
				// read photos as jpeg
				$photoType = imagecreatefromjpeg($path);
		  } else {
				// read photo as png
				$photoType = imagecreatefrompng($path);
		  }
		  // width & height of images
		  list($pWidth, $pHeight) = getimagesize($path);
		  // get the ratio of the photo lenght & breath
		  $realSizeRatio = ($pWidth / $pHeight);
		  // set the height
		  $resampleHeight = ($resampleWidth / $realSizeRatio);
		  // Resample
		  $photo = imagecreatetruecolor($resampleWidth, $resampleHeight);
		  // resample == resize the image
		  imagecopyresampled($photo, $photoType, 0, 0, 0, 0, $resampleWidth, $resampleHeight, $pWidth, $pHeight);
		  // return few of the photo character
		  return $phot = array(
				$photo, // resource of photo
				$resampleWidth, // photo width
				$resampleHeight, // photo height
				$photoExt // photo extension
		  );
	 }

}

/**
 * now generate the image based on the requirement sent via the url
 * 
 * size width
 * type - png | jpeg
 * img - src | path to image
 * quality
 * 
 * img tag will be like so
 * <img src="image.php?img=image.jpg&width=450&quality=90&type=png" />
 * 
 */
header("Content-type: image/jpeg"); // change the header(type) of this file to be image file
// initialize the image editing class
$image = new ImgResize();

// start the creation of the image
$img = $image->resizeImage($_GET['img'], (int)$_GET['width']);

// set the after image quality..... range from 1 - 100;
$quality = (isset($_GET['quality']) && !empty($_GET['quality'])) ? $_GET['quality'] : 80;

// return image as jpg
imagejpeg($img[0], NULL, $quality);

// release resource
imagedestroy($img[0]);

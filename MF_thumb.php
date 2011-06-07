<?php
/*
	Magic Fields Thumb class
	
	Paramters
	---------
	w: width
	h: height
	zc: zoom crop (0 or 1)
	q: quality (default is 75 and max is 100)
*/
class mfthumb{

	function mfthumb(){
		require_once(ABSPATH."/wp-admin/includes/image.php");
		require_once(ABSPATH."/wp-includes/media.php");
	}

	/**
	 * This function is almost equal to the image_resize (native function of wordpress)
	 */
	function image_resize( $file, $max_w, $max_h, $crop = false, $far = false, $iar = false, $dest_path = null, $jpeg_quality = 90 ) {
		$image = wp_load_image( $file );
		if ( !is_resource( $image ) )
			return new WP_Error('error_loading_image', $image);

		$size = @getimagesize( $file );
		if ( !$size )
				return new WP_Error('invalid_image', __('Could not read image size'), $file);
		list($orig_w, $orig_h, $orig_type) = $size;
		
		$dims = mf_image_resize_dimensions($orig_w, $orig_h, $max_w, $max_h, $crop, $far, $iar);
		
		if ( !$dims ){
			$dims = array(0,0,0,0,$orig_w,$orig_h,$orig_w,$orig_h);
		}
		list($dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h) = $dims;

    $newimage = imagecreatetruecolor( $dst_w, $dst_h );
    imagealphablending($newimage, false);
    imagesavealpha($newimage, true);
    $transparent = imagecolorallocatealpha($newimage, 255, 255, 255, 127);
    imagefilledrectangle($newimage, 0, 0, $dst_w, $dst_h, $transparent);
    imagecopyresampled( $newimage, $image, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);

		// convert from full colors to index colors, like original PNG.
		if ( IMAGETYPE_PNG == $orig_type && !imageistruecolor( $image ) )
			imagetruecolortopalette( $newimage, false, imagecolorstotal( $image ) );

		// we don't need the original in memory anymore
		imagedestroy( $image );
		$info = pathinfo($dest_path);
		$dir = $info['dirname'];
		$ext = $info['extension'];
		$name = basename($dest_path, ".{$ext}");
		
		$destfilename = "{$dir}/{$name}.{$ext}";
		
		if ( IMAGETYPE_GIF == $orig_type ) {
 			if ( !imagegif( $newimage, $destfilename ) )
				return new WP_Error('resize_path_invalid', __( 'Resize path invalid' ));
		} elseif ( IMAGETYPE_PNG == $orig_type ) {
			if ( !imagepng( $newimage, $destfilename ) )
				return new WP_Error('resize_path_invalid', __( 'Resize path invalid' ));
		} else {
			// all other formats are converted to jpg
                  //Todo: add option for use progresive JPG
                  //imageinterlace($newimage, true); //Progressive JPG 
                  if ( !imagejpeg( $newimage, $destfilename, apply_filters( 'jpeg_quality', $jpeg_quality, 'image_resize' ) ) )
                    return new WP_Error('resize_path_invalid', __( 'Resize path invalid' ));
		}

		imagedestroy( $newimage );

		// Set correct file permissions
		$stat = stat( dirname( $destfilename ));
		$perms = $stat['mode'] & 0000666; //same permissions as parent folder, strip off the executable bits
		@ chmod( $destfilename, $perms );

		return $destfilename;
	}
}


/**
 * Based in the image_resize_dimensions of wordpress
 */
function mf_image_resize_dimensions($orig_w, $orig_h, $dest_w, $dest_h, $crop = false, $far = false, $iar = false) {
        

	if ($orig_w <= 0 || $orig_h <= 0)
		return false;
	// at least one of dest_w or dest_h must be specific
	if ($dest_w <= 0 && $dest_h <= 0)
		return false;

	if ( $crop ) {
		// crop the largest possible portion of the original image that we can size to $dest_w x $dest_h
		$aspect_ratio = $orig_w / $orig_h;
		$new_w = min($dest_w, $orig_w);
		$new_h = min($dest_h, $orig_h);

		if ( !$new_w ) {
			$new_w = intval($new_h * $aspect_ratio);
		}

		if ( !$new_h ) {
			$new_h = intval($new_w / $aspect_ratio);
		}

		$size_ratio = max($new_w / $orig_w, $new_h / $orig_h);

		$crop_w = round($new_w / $size_ratio);
		$crop_h = round($new_h / $size_ratio);

		$s_x = floor( ($orig_w - $crop_w) / 2 );
		$s_y = floor( ($orig_h - $crop_h) / 2 );
                
	} else {
            // don't crop, just resize using $dest_w x $dest_h as a maximum bounding box
		$crop_w = $dest_w;
		$crop_h = $dest_h;

		$s_x = 0;
		$s_y = 0;

		$new_w = $crop_w;
		$new_h = $crop_h;
        }

        if( $far ) {
            switch ( $far ) {
			case 'L':
			case 'TL':
			case 'BL':
				$s_x = 0;
				$s_y = round(($dest_h - $origin_h) / 2);
				break;
			case 'R':
			case 'TR':
			case 'BR':
				$s_x =  round($dest_w  - $origin_w);
				$s_y = round(($dest_h - $origin_h) / 2);
				break;
			case 'T':
			case 'TL':
			case 'TR':
				$s_x = round(($dest_w  - $origin_w)  / 2);
				$s_y = 0;
				break;
			case 'B':
			case 'BL':
			case 'BR':
				$s_x = round(($dest_w  - $origin_w)  / 2);
				$s_y =  round($dest_h - $origin_h);
				break;
			case 'C':
			default:
				$s_x = round(($dest_w  - $origin_w)  / 2);
				$s_y = round(($dest_h - $origin_h) / 2);
                                
		}
                
        }
        if ( $iar ) {
                //ignore aspect radio and resize the image
                $crop_w = $orig_w;
                $crop_h = $orig_h;

                $s_x = 0;
                $s_y = 0;
               
                $new_w = ceil($orig_w * $dest_w / $orig_w);
                $new_h = ceil($orig_h * $dest_h / $orig_h);

	}
 
	// if the resulting image would be the same size we don't want to resize it
	if ( $new_w == $orig_w && $new_h == $orig_h )
		return false;

	// the return array matches the parameters to imagecopyresampled()
	// int dst_x, int dst_y, int src_x, int src_y, int dst_w, int dst_h, int src_w, int src_h
	return array( 0, 0, (int) $s_x, (int) $s_y, (int) $new_w, (int) $new_h, (int) $crop_w, (int) $crop_h );

}
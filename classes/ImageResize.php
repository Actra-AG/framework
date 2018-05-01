<?php
# ------------------------------
# Actra AG - http://www.actra.ch
# ------------------------------
# 06.12.2007	CM	new version
# 22.08.2007	CM	created

namespace classes;

class ImageResize
{
	public function resize_image($tmp_name, $ext, $new_img_loc = '', $new_img_name = '', $thumb_w = 0, $thumb_h = 0, $new_img_q = 90, $cut = 0, $createOrig = 1)
	{
		restore_error_handler();

		$orig_img = $new_img_loc . "orig_" . $new_img_name . "." . $ext;
		if ($createOrig == 1) {
			copy($tmp_name, $orig_img);
			chmod($orig_img, 0777);
		} else {
			$orig_img = $tmp_name;
		}
		if (!file_exists($orig_img)) {
			return 2;
		} else {
			$orig_size = getimagesize($orig_img);
			$orig_w = $orig_size[0];
			$orig_h = $orig_size[1];

			$dst_x = 0;
			$dst_y = 0;

			$src_x = 0;
			$src_y = 0;

			$faktor = 1;

			if ($orig_w < $thumb_w) {
				$thumb_w = $orig_w;
			}
			if ($orig_h < $thumb_h) {
				$thumb_h = $orig_h;
			}

			if ($thumb_w == 0) {
				if ($orig_h > $thumb_h && $thumb_h != 0) {
					$new_w = $orig_w * $thumb_h / $orig_h;
					$thumb_w = $new_w;
				} else {
					$thumb_w = $orig_w;
				}
			}

			if ($thumb_h == 0) {
				if ($orig_w > $thumb_w && $thumb_w != 0) {
					$new_h = $orig_h * $thumb_w / $orig_w;
					$thumb_h = $new_h;
				} else {
					$thumb_h = $orig_h;
				}
			}

			if ($orig_w > $thumb_w && $orig_h > $thumb_h) {
				$faktor_w = $thumb_w / $orig_w;
				$faktor_h = $thumb_h / $orig_h;
				if ($faktor_w > $faktor_h) {
					if ($cut == 1) {
						$faktor = $faktor_w;
					} else {
						$faktor = $faktor_h;
					}
				} else {
					if ($cut == 1) {
						$faktor = $faktor_h;
					} else {
						$faktor = $faktor_w;
					}
				}
				$new_w = round($orig_w * $faktor, 0);
				$new_h = round($orig_h * $faktor, 0);
			} else {
				$new_w = $orig_w;
				$new_h = $orig_h;
			}

			if ($new_w > $thumb_w) {
				$src_x = round(($new_w - $thumb_w) / 2 / $faktor, 0);
			} else if ($new_w < $thumb_w) {
				$dst_x = round(($thumb_w - $new_w) / 2, 0);
			}

			if ($new_h > $thumb_h) {
				$src_y = round(($new_h - $thumb_h) / 2 / $faktor, 0);
			} else if ($new_h < $thumb_h) {
				$dst_y = round(($thumb_h - $new_h) / 2, 0);
			}
			ini_set('gd.jpeg_ignore_warning', 1);
			switch ($ext) {
				case 'gif':
					$orig_image = imagecreatefromgif($orig_img);
					$thumb_image = imagecreate($thumb_w, $thumb_h);
					break;
				case 'jpg':
					$orig_image = imagecreatefromjpeg($orig_img);
					$thumb_image = imagecreatetruecolor($thumb_w, $thumb_h);
					break;
				case 'png':
					$orig_image = imagecreatefrompng($orig_img);
					$thumb_image = imagecreatetruecolor($thumb_w, $thumb_h);
					break;
				default:
					$orig_image = null;
					$thumb_image = null;
					break;
			}
			if (!$orig_image) {
				return false;
			}

			$bgcolor = imagecolorallocate($thumb_image, 255, 255, 255);
			imagefilledrectangle($thumb_image, 0, 0, $thumb_w, $thumb_h, $bgcolor);
			imagecopyresampled($thumb_image, $orig_image, $dst_x, $dst_y, $src_x, $src_y, $new_w, $new_h, $orig_w, $orig_h);
			imagedestroy($orig_image);

			$newimg = $new_img_loc . $new_img_name . '.' . $ext;

			switch ($ext) {
				case 'gif':
					imagegif($thumb_image, $newimg);
					break;
				case 'jpg':
					imagejpeg($thumb_image, $newimg, $new_img_q);
					break;
				case 'png':
					imagepng($thumb_image, $newimg);
					break;
			}

			imagedestroy($thumb_image);

			if (file_exists($newimg)) {
				chmod($newimg, 0777);

				return 1;
			} else {
				return 3;
			}
		}
	}
}

/* EOF */
<?php

namespace AppBundle\Repository;

class EbClosion {

	/**
	 *
	 * Enter description here ...
	 * @param unknown_type $array
	 */
	public static function printr($array) {
		echo "<pre>";
		print_r($array);
		echo "</pre>";
	}
	
	public static function checkPm($moduleId, $pm = 'viewm') {
		$checkModuleView = false;
		foreach ($userModules as $module) {
			if ($module["mcid"] == $moduleId && $pm == 1) {
				$checkModuleView = true;
			}
		}
		return $checkModuleView;
	}
	
	public static function getModulePermission($moduleId, $userModules) {
	    
	    foreach ($userModules as $module) {
	        if ($module["mcid"] == $moduleId) {
	            return $module;
	        }
	    }
	    return false;
	}


	/**
	 *
	 * Enter description here ...
	 * @param unknown_type $files
	 * @param unknown_type $thumbnail
	 * @param unknown_type $path
	 * @param unknown_type $onlyThisExtensions
	 */
	public function uploadImages($files, $thumbnail = false, $path, $onlyThisExtensions = false)
	{
		try {
			if (is_array($files)) {
				foreach ($files as $picture) {
					if (!$picture["error"]) {
						// si no hay error en la subida realizar el upload de la imagen

						// obtener data
						$size = $picture["size"];
						$type = $picture["type"];
						$name = $picture["name"];
						$tmp =  $picture['tmp_name'];
						$file_name = substr($name, 0, strlen($name) - 4);
						$ext = substr($name, strlen($name) - 3, strlen($name));
						$prefix = substr(md5($name . time()),0, 5);
						$xpath = $path . $name . "-" . $prefix . "." . $ext;
						// Validar extenciones
						if ($onlyThisExtensions) {
							if (!in_array($ext, $onlyThisExtensions))
							return false;
						}
						// subir imagen
						if (copy($tmp, $xpath))
						$rsImages[] = $file_name . " - " . $prefix . "." . $ext;
						else
						$rsImages[] = "error";

						if ($thumbnail) {
							$thumb = new Thumbnail($xpath);
							$thumb->size_width(800);
							$thumb->save($xpath);
						}

					}
				}

				if (isset($rsImages))
				return $rsImages;
				else
				return false;

			}
		} catch (Exception $ex) {

			Doctrine::getTable('BackendErrorLog')->saveErrorLog(array(
			'description' => $ex->getMessage(),
			'modules' => "EbClosion:$modelName::uploadImages",
			'backend_action_log_id' => 5,
			));
			return false;
		}
	}

	/**
	 *
	 * @param string $text
	 * @return string
	 */
	static public function slugify($text)
	{
		
		$titleUrl = EbClosion::replaceAccents($text);

		// replace all non letters or digits by -
		//$xtext = preg_replace('/\W+/', '-', $titleUrl);

		// trim and lowercase
		$xtext = strtolower(trim($titleUrl, '-'));

		return $xtext;
	} 

	static public function replaceAccents($str) { 
		$a = array('Ã�', 'Ã‰', 'Ã�', 'Ã“', 'Ãš', 'Ã¡', 'Ã©', 'Ã­', 'Ã³', 'Ãº', ' ', ',', 'Ã±', '.', ' ', '?', 'Â¡', ';', '"', '#', '!', 'Â¿', ':');
		$b = array('A', 'E', 'I', 'O', 'U', 'a', 'e', 'i', 'o', 'u', '-', '-', 'n', '',  '',  '',  '', '',  '',  '',  '',  '',  '-');
		return str_replace($a, $b, $str);
	}
	
	static public function replaceBadWords($str) {
		$bad = array("cerote", "serote", "culo",  "tumadre", "tu madre", "mierda", "culero", "eshta",  "huevo",  "hueco",  "weco",   "verga",  "moronga", "puta",   "talega", "caca",   "pija");
		$good = array("&/#$%#","&/#$%#", "&/#$%#","&/#$%#",  "&/#$%#",   "&/#$%#", "&/#$%#", "&/#$%#", "&/#$%#", "&/#$%#", "&/#$%#", "&/#$%#", "&/#$%#",  "&/#$%#", "&/#$%#", "&/#$%#", "&/#$%#");
		return str_replace($bad, $good, $str);
	}

    static public function base64_to_jpeg($base64_string, $output_file) {

        $ifp = fopen( $output_file, 'wb' );
        $data = explode( ',', $base64_string );

        fwrite( $ifp, base64_decode( $data[ 1 ] ) );
        fclose( $ifp );

        return true;
    }



}

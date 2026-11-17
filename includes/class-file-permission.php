<?php
/**
 *
 * Class 'peer_review_system_file_permission' file upload with permissions
 *
 * @package     Peer_Review_System
 * @subpackage  Peer_Review_System/includes
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
*/
if (!class_exists('Peer_Review_System_file_permission')){
    class Peer_Review_System_file_permission{
  
        private static $instance = null;
        private static $encrpytion_salt  = '^^tbkey^^';
        public function __construct(){
           
        }

        /**
         * Returns the *Singleton* instance of this class.
         *
         * @return
         * @throws error
         * @author Amentotech <theamentotech@gmail.com>
         */
        public static function getInstance(){
            if (self::$instance==null){
                self::$instance = new Peer_Review_System_file_permission();
            }
            return self::$instance;
        }

        /**
		 * uplaod attachments
		 *
		 * @since    1.0.0
		*/
		public static function uploadAttachment($params=array()){			
			$upload 			= wp_upload_dir();
			$upload_dir 		= $upload['path'].'/';
			$attachments 		= $json  = array();          
			$basedir 			= $upload['basedir'] . "/peer-review-system-temp/";
			$baseurl 			= $upload['baseurl'] . "/peer-review-system-temp/"; 
            
			$upload_dir 		= $basedir;
			$upload_url 		= $baseurl;
			$registered_sizes   = array();

			if( $params['sizes']){
				$registered_sizes 	= wp_get_registered_image_subsizes();
			}
			wp_mkdir_p($upload_dir);

			if(!empty($params['files'])){
                $file               = $params['files'];
                $file_info 		    = wp_check_filetype_and_ext($file['tmp_name'], $file['name'], false);
                $ext_verify 	    = empty($file_info['ext']) ? '' : $file_info['ext'];
                $type_verify 	    = empty($file_info['type']) ? '' : $file_info['type'];
    
                if (!$ext_verify || !$type_verify) {
                    $response['message']    = esc_html__('These file types are not allowed', 'peer-review-system');
                    $response['type']       = 'error';
                    $response['file_info']  = $file_info;
                    $response['params']     = $params;
                    return $response;
                }

                $attachmentType     = '';
                $name 				= preg_replace("/[^A-Z0-9._-]/i", "_", $file["name"]);
                //file type check
                $filetype 			= wp_check_filetype($file['name']);
                $not_allowed_types	= array('php','javascript','js','exe','text/javascript','html');
                $file_ext			= !empty($filetype['ext']) ? $filetype['ext'] : '';
                
                if(empty($file_ext) || in_array($file_ext,$not_allowed_types)){
                    $json['type']           = 'error';
                    $json['message_desc']   = esc_html__('These file types are not allowed!', 'peer-review-system');
                    return $json;
                    exit;
                }

                if (!empty($file['size']) && (int) $file['size'] > peer_review_system_max_upload_size_bytes()) {
                    $json['type']           = 'error';
                    $json['message']        = sprintf(
                        esc_html__('File size must not exceed %s.', 'peer-review-system'),
                        peer_review_system_max_upload_size_label()
                    );
                    return $json;
                }

                $i = 0;
                $parts = pathinfo($name);
                while (file_exists($upload_dir . $name)) {
                    $i++;
                    $name = $parts["filename"] . "-" . $i . "." . $parts["extension"];
                }
                
                if(preg_match('/image\/*/', $file['type'])){
                    $attachmentType = 'images';
                }elseif(preg_match('/video\/*/', $file['type'])){
                    $attachmentType = 'video';
                }elseif(preg_match('/audio\/*/', $file['type'])){
                    $attachmentType = 'audio';
                }elseif(preg_match('/pdf\/*/', $file['type']) 
                    || preg_match('/document\/*/', $file['type'])
                    || preg_match('/zip\/*/', $file['type'])
                    || preg_match('/powerpoint\/*/', $file['type'])
                    || preg_match('/text\/*/', $file['type'])
                    || preg_match('/vnd.ms-excel\/*/', $file['type'])
                    || preg_match('/spreadsheet\/*/', $file['type'])
                    ){
                    $attachmentType = 'file';
                }

                $size       	= $file['size'];
                $file_size  	= size_format($size, 2);
                //move file
                $newFile        = $upload_dir .$name;
                $is_moved       = move_uploaded_file($file["tmp_name"], $newFile);

                if($is_moved){
                    $filename   = basename($newFile);
                    $file       = $upload_url.sanitize_file_name($filename);
                    $sizes      = array();
                    
                    if( $attachmentType == 'images' && ! empty( $params['sizes']) ){
                        $image  = wp_get_image_editor( $newFile );
                        if (!is_wp_error( $image ) ) {
                            foreach($params['sizes'] as $size){ 
                                $newSize            = $image->make_subsize( $registered_sizes[$size] );
                                if(!is_wp_error( $newSize )){
                                    $generatedIamge = $upload_url . $newSize['file'];
                                    $sizes[$size] 	= $generatedIamge;
                                }else{
                                    $sizes[$size] 	= $file;
                                }
                            }
                        }
                    }

                    $attachments[] = array(
                        'file' 					=> $file,
                        'fileName'				=> sanitize_file_name($filename),
                        'sizes' 				=> $sizes,
                        'fileSize' 				=> esc_html($file_size),
                        'fileType' 				=> esc_html($filetype['ext']),
                        'attachmentType'		=> $attachmentType, 
                        'upload_url'		    => $upload_url
                    );
                }
			}
			$json['type'] 			= 'success';
			$json['attachments'] 	= $attachments;
			return $json;
		}

        /**
         * Upload file in temp folder
         *
         * @return
         * @throws error
         * @author Amentotech <theamentotech@gmail.com>
         */
        public static function uploadFile($submitted_file){
            $response       = array();
            $upload         = wp_upload_dir();
            $upload_dir     = $upload['basedir'];
            $upload_dir     = $upload_dir . '/peer-review-system-temp/';
            $file_info      = wp_check_filetype_and_ext($submitted_file['tmp_name'], $submitted_file['name'], false);
            $ext_verify 	= empty($file_info['ext']) ? '' : $file_info['ext'];
            $type_verify 	= empty($file_info['type']) ? '' : $file_info['type'];

            if (!$ext_verify || !$type_verify) {
                $response['message'] = esc_html__('These file types are not allowed', 'peer-review-system');
                $response['type']    = 'error';
                return $response;
            }

            if (!empty($submitted_file['size']) && (int) $submitted_file['size'] > peer_review_system_max_upload_size_bytes()) {
                $response['message'] = sprintf(
                    esc_html__('File size must not exceed %s.', 'peer-review-system'),
                    peer_review_system_max_upload_size_label()
                );
                $response['type']    = 'error';
                return $response;
            }
            //create directory if not exists
            if (!is_dir($upload_dir)) {
                wp_mkdir_p($upload_dir);
            }
            $name = preg_replace("/[^A-Z0-9._-]/i", "_", $submitted_file["name"]);
            $i = 0;
            $parts = pathinfo($name);
            while (file_exists($upload_dir . $name)) {
                $i++;
                $name = $parts["filename"] . "-" . $i . "." . $parts["extension"];
            }      
            
            $filetype   = wp_check_filetype($name);
            $file_ext   = !empty($filetype['ext']) ? $filetype['ext'] : '';

            if(preg_match('/image\/*/', $file['type'])){
                $attachmentType = 'images';
            }elseif(preg_match('/video\/*/', $file['type'])){
                $attachmentType = 'video';
            }elseif(preg_match('/audio\/*/', $file['type'])){
                $attachmentType = 'audio';
            }elseif(preg_match('/pdf\/*/', $file['type']) 
                || preg_match('/document\/*/', $file['type'])
                || preg_match('/zip\/*/', $file['type'])
                || preg_match('/powerpoint\/*/', $file['type'])
                || preg_match('/text\/*/', $file['type'])
                || preg_match('/vnd.ms-excel\/*/', $file['type'])
                || preg_match('/spreadsheet\/*/', $file['type'])
                ){
                $attachmentType = 'file';
            }
            //move files
            $is_moved = move_uploaded_file($submitted_file["tmp_name"], $upload_dir . '/' . $name);

            if ($is_moved) {
                $size       = $submitted_file['size'];
                $file_size  = size_format($size, 2);
                $response['type']       = 'success';
                $response['message']    = esc_html__('File uploaded', 'peer-review-system');
                $url                    = $upload['baseurl'] . '/peer-review-system-temp/' . $name;
                $response['thumbnail']  = $upload['baseurl'] . '/peer-review-system-temp/' . $name;
                $response['name']       = $name;
                $response['url']        = $url;
                $response['size']       = $file_size;
            } else {
                $response['title']      = esc_html__('Error', 'peer-review-system');
                $response['message']    = esc_html__('Some errors occurred, please try again later', 'peer-review-system');
                $response['type']       = 'error';
            }
            return $response;
        }

        /**
         * Get encrypt file
         *
         * @return
         * @throws error
         * @author Amentotech <theamentotech@gmail.com>
         */
        public static function getEncryptFile($file, $post_id, $is_upload=false, $encrypt_file=true){
            $result     = array();
            $post_type	= get_post_type($post_id);
            $i          = time();
			
            if(($post_type == 'product' || $post_type == 'jobs') && !empty($encrypt_file)) {
                $file_detail            = pathinfo($file);
                $extension 			    = $file_detail['extension'];
                $filename 			    = $file_detail['filename'];

                if($is_upload) {
                    $filename           = $file_detail['filename'].'-'.$i; 
                }
                $reverse_file_name      = strrev($filename);
                $new_file_name          = strrev(base64_encode($reverse_file_name.self::$encrpytion_salt.$post_id));
                $new_file_name          = $new_file_name. '.' . $extension;
                $result['url']          = $file_detail['dirname'].'/'.$new_file_name;
                $result['name']         = $new_file_name;
                $result['encrypt_file'] = $encrypt_file;
                return $result;
            } else {
                $file_detail        = pathinfo($file);
                $extension 			= $file_detail['extension'];
                $filename 			= $file_detail['filename'];
                
                if($is_upload) {
                    $new_file_name 	= $filename .'-'.$i.'.' . $extension;
                } else {
                    $new_file_name 	= $filename . '.' . $extension;
                }
                $result['url']      = $file_detail['dirname'].'/'.$new_file_name;
                $result['name']     = $new_file_name;                
                $result['encrypt_file']         = $encrypt_file;
                return $result;
            }
        } 

        public static function moveTempToUploaded($params = array() ){
			$response = array();
			$upload	  = wp_upload_dir();
			if( !empty( $params ) ){
				//file type check
				$filetype 			= wp_check_filetype($params['file']);
				$not_allowed_types	= array('php','javascript','js','exe','text/javascript','html');
				$file_ext			= !empty($filetype['ext']) ? $filetype['ext'] : '';
				
				if(empty($file_ext) || in_array($file_ext,$not_allowed_types)){
					$json['type'] = 'error';
					$json['message_desc']   = esc_html__('These file types are not allowed!', 'peer-review-system');
					return $json;
					exit;
				}
				
				$basedir 		= $upload['basedir'] . "/peer-review-system-attachments/";
				$baseurl 		= $upload['baseurl'] . "/peer-review-system-attachments/";
				$foldername 	= $params['articleId'] . '/';
				$upload_dir 	= $basedir.$foldername;
				$upload_url 	= $baseurl.$foldername;
				$tempPath		= $upload['basedir'].'/peer-review-system-temp/'.$params['file'];
				$response['file'] = '';
				wp_mkdir_p($upload_dir);
 
				if( file_exists( $tempPath ) ) {

					$newfile 	= $upload_dir.$params['file'];
					$newfileUrl = $upload_url.$params['file'];
                    

					if ( file_exists($upload_dir.$params['file']) ) {
						$parts = pathinfo($upload_dir.$params['file']);

						$newName = $parts["filename"] . "-" . time() . "." . $parts["extension"];
						$newfile = $upload_dir.$newName;
						$newFilePath = $basedir.$newName;
						$isRenamed = rename($tempPath, $newFilePath);

						if($isRenamed){
							$tempPath 	= $newFilePath;
							$newfileUrl = $upload_url.$newName;
						}
					}
					$renamed = rename($tempPath, $newfile);
					if( ! empty( $renamed ) ){
						$fileInfo = pathinfo($newfile);
						$response['file'] 		= $newfileUrl;
						$response['fileName'] 	= $fileInfo['filename']. '.' . $fileInfo['extension'];
					}
				}
			}

			return $response;
		}
    }
}

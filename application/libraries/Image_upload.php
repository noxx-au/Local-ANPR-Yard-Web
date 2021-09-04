<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Image_upload {

    protected $CI;
    public $allowed_file = array();
    protected $upload_path = array();
    protected $extention = array();
    protected $unlink_path = array();
    protected $image_path = array();
    protected $filesName = array();
    protected $removeFile = array();
    public $data = array();
    public $error = array();
    public $_prifix = 'crop_';
    public $new_file_name = '';
    public $_replace = 'dash'; // dash|underscore use one of them

    public function __construct() {
        $this->CI = & get_instance();
        $this->CI->load->helper(array('url', 'file'));
        $this->CI->load->library('upload');
    }

    /**
     * Set Uploade File path by file name
     * @param mixed $upload_path
     */
    public function setPath($file_names, $path = NULL) {
        if (!is_array($file_names) AND ! is_null($path)) {
            $this->upload_path[$file_names] = $path;
        } else {
            foreach ($file_names as $key => $file) {
                $this->upload_path[$key] = $file;
            }
        }
        return $this;
    }

    /**
     * Get Uploade File path by file name
     * @param mixed $upload_path
     */
    public function getPath($file_name) {
        if ($file_name != "" && array_key_exists($file_name, $this->upload_path)) {
            return $this->upload_path[$file_name];
        }
        return NULL;
    }

    /**
     * Set unlink File path by file name
     * @param mixed $upload_path
     */
    public function setUnlink($file_names, $path = NULL) {
        if (!is_array($file_names) AND ! is_null($path)) {
            $this->unlink_path[$file_names] = $path;
        } else {
            foreach ($file_names as $key => $file) {
                $this->unlink_path[$key] = $file;
            }
        }
        return $this;
    }

    /**
     * Get unlink  File path by file name
     * @param mixed $upload_path
     */
    public function getUnlink($file_name) {
        if ($file_name != "" && array_key_exists($file_name, $this->unlink_path)) {
            return trim($this->unlink_path[$file_name], './');
        }
        return NULL;
    }

    /**
     * Set Allowed File Types
     * @param mixed $types
     */
    public function setAllowedTypes($types, $allow = NUlL) {

        if ($types === '*') {
            $this->allowed_file = [
                '*' => '*'
            ];
        }

        if (!empty($types) AND ! is_null($allow)) {
            $this->allowed_file[$types] = explode('|', $allow);
            $this->checkError($types);
        }

        if (is_array($types) AND $allow === NUll) {
            foreach ($types as $_types_key => $_types_value) {
                $this->allowed_file[$_types_key] = explode('|', $types);
                $this->checkError($_types_key);
            }
        }
        return $this;
    }

    /**
     * check a uploaded file allowed or not if not then set a erroe
     * @param type var error set a error message
     * @return return $this
     */
    public function checkError($name = NULL) {
        // file name is not null then
        $files = ($name !== NULL ) ? $_FILES[$name]['name'] : $_FILES;

        // file name is null and not specify anythig it's true
        if (is_array($files) && sizeof($_FILES) === 1) {
            $file = reset($_FILES);
            $file_name = key($_FILES);
            $ext = $this->getFileExtension($file['name']);
            if (!in_array('*', $this->allowed_file[$file_name])) {
                $this->extention[$file_name] = $ext;
                $this->filesName[$file_name] = $file_name;
                $this->isValid($file_name, $ext);
            }
        }

        // file name is null and and $_FILES size is  > 1  is true
        if (is_array($files) && sizeof($_FILES) > 1) {
            foreach ($files as $key => $file) {
                $ext = $this->getFileExtension($file['name']);
                if (isset($this->allowed_file[$key]) && !in_array('*', $this->allowed_file[$key])) {
                    $this->filesName[$key] = $key;
                    $this->extention[$key] = $ext;
                    $this->isValid($key, $ext);
                } else {
                    continue;
                }
            }
        }
    }

    /**
     * set a error message to partiqular file
     * @param type var error set a error message
     * @return return $this
     */
    public function setErrorMessage($file_name, $error_messge) {
        $this->error[$file_name] = $error_messge;
        return $this;
    }

    /*
     * Check a uploaded file extesion return
     * @return return type string
     */

    public function getFileExtension($name) {
//		return  pathinfo($name, PATHINFO_EXTENSION)  :: OLD CODE;
        /*
         * This code change DEV::SKT DATE::7/8/2018
         */
        $getExtension = pathinfo($name, PATHINFO_EXTENSION);
        return strtolower($getExtension);
    }

    /**
     * check a valide extention or not
     * @return return type $this
     */
    protected function isValid($file, $extention) {

        if (!in_array($extention, $this->allowed_file[$file]) AND $_FILES[$file]['error'] == 0) {
            //print_r($_FILES[$file]);
            $this->error[$file] = 'Please select only gif/jpg/jpeg/png file';
        }

        if (array_key_exists($file, $this->allowed_file)) {
            if (!in_array($extention, $this->allowed_file[$file]) AND $_FILES[$file]['error'] == 0) {
                //print_r($_FILES[$file]);
                $this->error[$file] = 'Please upload only ' . implode(',', $this->allowed_file[$file]) . ' file';
            }
        }
        return $this;
    }

    /**
     * process of uploading if file has a no error
     */
    public function do_upload($file_name) {
        if (array_key_exists($file_name, $this->error)) {
            return FALSE;
        } else {
            if (in_array('*', $this->allowed_file[$file_name], TRUE)) {
                if ($this->file_upload($file_name)) {
                    return TRUE;
                }
            } else {
                if ($this->do_file_upload($file_name)) {
                    return TRUE;
                } else {
                    return FALSE;
                }
            }
        }
    }

    public function upload_multiple() {
        if (!empty($this->allowed_file)) {
            foreach ($this->allowed_file as $key => $value) {
                $this->do_upload($key);
                if (array_key_exists($key, $this->error)) {
                    $this->setErrorMessage($key, 'Please upload correct image( gif / jpeg / jpg / png)');
                    return FALSE;
                }
            }
        }
        return TRUE;
    }

    protected function do_file_upload($file_name) {
        if (isset($_FILES[$file_name]['name']) AND ( $_FILES[$file_name]['error'] === 0) AND ( !array_key_exists($file_name, $this->error)) AND ( $this->CI->input->post($this->_prifix . $file_name) !== "")) {
            $file_info = $this->getFileinfo($file_name);

            $this->checkFileExist($file_name, $file_info['basename']);

            $file_base_name = $this->getPath($file_name) . $this->new_file_name;

            $image = $this->makeImage($file_name);

            if ($image !== NULL) {
                if ($file_info['extension'] == 'png') {
                    if (file_put_contents($file_base_name, $image)) {
                        $this->image_path[$file_name] = $file_base_name;
                        $this->removeFile[] = $file_name;
                        $this->setdata($file_name);
                        return TRUE;
                    }
                } else {
                    $image_file = imagecreatefromstring($image);
                    if (imagejpeg($image_file, $file_base_name)) {
                        $this->image_path[$file_name] = $file_base_name;
                        $this->removeFile[] = $file_name;
                        $this->setdata($file_name);
                        return TRUE;
                    }
                }
                // file_put_contents($file_base_name, $image);
            }
        } else {
            return FALSE;
        }
    }

    protected function file_upload($file_name) {
        if (isset($_FILES[$file_name]['name']) AND $_FILES[$file_name]['error'] === 0 AND ! array_key_exists($file_name, $this->error)) {

            $file_info = $this->getFileinfo($file_name);

            $tmp_name = $_FILES[$file_name]["tmp_name"];

            $this->checkFileExist($file_name, $file_info['basename']);

            $file_base_name = $this->getPath($file_name) . $this->new_file_name;

            if (!is_null($this->getPath($file_name))) {
                if (@move_uploaded_file($tmp_name, $file_base_name)) {
                    $this->image_path[$file_name] = $file_base_name;
                    $this->removeFile[] = $file_name;
                    $this->setdata($file_name);
                    return TRUE;
                }
            }
        }
    }

    protected function checkFileExist($checkFileExist, $name) {
		
	
	
        $file_info = pathinfo($_FILES[$checkFileExist]['name']);
		
	
        if (array_key_exists($checkFileExist, $this->upload_path) AND file_exists($this->upload_path[$checkFileExist] . $name)) {
			
//            $this->new_file_name =  url_title(strtolower($file_info['filename'])).'1'.rand(1,9999999999) * 2 .".". $file_info['extension']; old code here 

            /* New code Start here  DEV::SKT:: DATA::10/12/2018 */
     
            $filepath = str_replace("./", "", $this->upload_path[$checkFileExist] . $name);
            $file_index = 1;
            while (file_exists($filepath)) {
                $this->new_file_name = strtolower($file_info['filename']) . '_' . ($file_index) . "." . strtolower($file_info['extension']);
                if (!file_exists($this->upload_path[$checkFileExist] . $this->new_file_name)) {
                    break;
                }
                $file_index++;
            }
            /* New code End here */
        } else {
            $this->new_file_name = strtolower($file_info['filename']) . '.' . strtolower($file_info['extension']);
        }
        return $this;
    }

    protected function getFileinfo($file_name) {
        // file information name,ext, etc..
        $file_info = pathinfo($_FILES[$file_name]['name']);

        // get a file path form file name
        $file_path = $this->getPath($file_name);

        // if this dir not exit then create
        if (!is_dir($file_path)) {
            @mkdir($file_path, 0755);
        }
        return $file_info;
    }

    protected function makeImage($file_name) {

        $image_name = $this->new_file_name;

        if ($image_name) {
            $filepath = $this->getPath($file_name) . $image_name;

            $corp_image_file = $this->CI->input->post($this->_prifix . $file_name, FALSE);

            $base64img = str_replace('data:image/png;base64,', '', $corp_image_file);

            $data_img = base64_decode(str_replace(' ', '+', $base64img));

            return $data_img;
        }
        return NULL;
    }

    /** Remove Single File */
    public function removeFile($file_name) {
        if (array_key_exists($file_name, $this->unlink_path)) {
            if (file_exists($this->getUnlink($file_name))) {
                if (@unlink($this->getUnlink($file_name))) {
                    $this->removeFile[] = $file_name;
                }
            }
        }
    }

    /** Remove Multiple File */
    public function removeMultipleFile() {
        if ($this->getdata() && !empty($this->getdata())) {
            foreach ($this->getdata() as $key => $value) {
                $this->removeFile($key);
            }
        }
    }

    /** Return file data  */
    public function getdata($file_name = NULL) {

        /*   echo "<pre>";
          echo "-----------------------allowed_file----------------------------------";
          print_r($this->allowed_file);
          echo "-----------------------extention----------------------------------";
          print_r($this->extention);
          echo "-----------------------upload_path----------------------------------";
          print_r($this->upload_path);
          echo "-----------------------unlink_path----------------------------------";
          print_r($this->unlink_path);
          echo "-----------------------error----------------------------------";
          print_r($this->error);
          echo "-----------------------file_name----------------------------------";
          print_r($this->filesName);
          echo "</pre>"; */

        if (array_key_exists($file_name, $this->data)) {
            return $this->data[$file_name]['image_path'];
            //return (object) $this->data[$file_name];
        } else {
            if (!empty($this->data)) {
                return $this->data;
            }
            return NULL;
        }
    }

    protected function setdata($file_name) {
        $this->data[$file_name] = array(
            "file_name" => ($this->filesName[$file_name]) ? $this->filesName[$file_name] : '',
            "extention" => ($this->extention[$file_name]) ? $this->extention[$file_name] : '',
            "upload_path" => ($this->upload_path[$file_name]) ? $this->upload_path[$file_name] : '',
            "unlink_path" => (isset($this->unlink_path[$file_name])) ? $this->unlink_path[$file_name] : '',
            "image_path" => (isset($this->image_path[$file_name])) ? trim($this->image_path[$file_name], './') : '',
            "error" => (isset($this->error[$file_name])) ? $this->error[$file_name] : ''
        );
    }

    public function getLink($name) {
        if (in_array($name, $this->removeFile)) {
            return (isset($this->image_path[$name])) ? trim($this->image_path[$name], './') : NULl;
        }
        return ($this->getUnlink($name) !== "") ? $this->getUnlink($name) : NUll;
    }

    public function validFiles() {
        $this->checkError();
        if (empty($this->error)) {
            return TRUE;
        }
        return FALSE;
    }

}

/* End of file Image_upload.php */
/* Location: ./application/controllers/Image_upload.php */

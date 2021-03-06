<?php

class Keditorupload extends CAction {

    public function run() {
        $dir = isset($_GET['dir']) ? trim($_GET['dir']) : 'file';
        $ext_arr = array(
            'image' => array('gif', 'jpg', 'jpeg', 'png', 'bmp', 'GIF', 'JPG', 'JPEG', 'PNG', 'BMP'),
            'flash' => array('swf', 'flv'),
            'media' => array('swf', 'flv', 'mp3', 'wav', 'wma', 'wmv', 'mid', 'avi', 'mpg', 'asf', 'rm', 'rmvb'),
            'file' => array('doc', 'docx', 'xls', 'xlsx', 'ppt', 'htm', 'html', 'txt', 'zip', 'rar', 'gz', 'bz2'),
        );
        if (empty($ext_arr[$dir])) {
            echo CJSON::encode(array('error' => 1, 'message' => '目录名不正确。'));
            exit;
        }
        $originalurl = '';
        $filename = '';
        $date = date('Ymd');
        $id = 0;
        $max_size = 2097152; //2MBs  

        $upload_image = CUploadedFile::getInstanceByName('imgFile');

        Yii::import('ext.KEditor.KEditor');
        $upload_dir=KEditor::getUploadPath();
        if (!file_exists($upload_dir))
        	mkdir($upload_dir);
        $upload_dir = $upload_dir . '/' . $dir;
        if (!file_exists($upload_dir))
            mkdir($upload_dir);
        $upload_dir = $upload_dir . '/' . $date;
        if (!file_exists($upload_dir))
            mkdir($upload_dir);

        $upload_url = KEditor::getUploadUrl() . '/' . $dir . '/' . $date;

        if (is_object($upload_image) && get_class($upload_image) === 'CUploadedFile') {
            if ($upload_image->size > $max_size) {
                echo CJSON::encode(array('error' => 1, 'message' => '上传文件大小超过限制。'));
                exit;
            }
            //新文件名  
            $filename = date("YmdHis") . '_' . rand(10000, 99999);
            $ext = $upload_image->extensionName;
            if (in_array($ext, $ext_arr[$dir]) === false) {
                echo CJSON::encode(array('error' => 1, 'message' => "上传文件扩展名是不允许的扩展名。\n只允许" . implode(',', $ext_arr[$dir]) . '格式。'));
                exit;
            }
            $uploadfile = $upload_dir . '/' . $filename . '.' . $ext;
            $originalurl = $upload_url . '/' . $filename . '.' . $ext;
            $upload_image->saveAs($uploadfile);
			
				 //水印
			if(isset($_POST['iswater'])&&intval($_POST['iswater'])==1)
			{	 
            Yii::import('ext.Image_lib');
            $image = new Image_lib();
            $config['image_library'] = 'gd2';
            $config['wm_type'] = 'overlay';
            $config['source_image'] = $uploadfile;
            $config['wm_overlay_path'] = Yii::app()->basePath . '/' . Yii::app()->params['mark_img'];
            //$config['quality'] = 100; // 图片清晰度
            $config['wm_vrt_alignment'] = 'bottom';
            $config['wm_hor_alignment'] = 'right';
            $config['wm_vrt_offset'] = 0;
            $config['wm_hor_offset'] = 0;
            $config['wm_opacity'] = 50; // 图像透明度
             if(isset($_POST['imgquality'])){
            	$config['quality'] = $_POST['imgquality'];
            }
            $image->initialize($config);
           // $image->watermark();
			}  
			
			
                     //   CVarDumper::dump($image,11,1);die;
            //远程上传
            $upurl = "http://img.pipaw.net/Uploader.ashx";
            $path = "ARTICLE/Editor/News" . date('/Y/m/d', time());
            $msg = Helper::postdata($upurl, array("filePath" => urlencode($path), "ismark" => "0", "autoName" => "1"), "Filedata", $uploadfile);
           
            //
            $originalurl=  Yii::app()->params['img_url'].$path.'/'.$msg;
            echo CJSON::encode(array('error' => 0, 'url' => $originalurl));
        } else {
            echo CJSON::encode(array('error' => 1, 'message' => '未知错误'));
        }
    }

}


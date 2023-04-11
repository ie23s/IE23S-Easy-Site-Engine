<?php

namespace ie23s\shop\system\files;

use Exception;
use ie23s\shop\engine\utils\breadcrumbs\api\ApiAbstract;

class UploadApi extends ApiAbstract
{

    public function get(): string
    {
        return $this->withCode(400);
    }

    public function put(): string
    {
        return $this->withCode(400);
    }

    /**
     * @throws Exception
     */
    public function post(): string
    {
        $bytes = bin2hex(random_bytes(8));
        $time = time();

        $path = __SHOP_DIR__ . 'uploads/';

        @$img = $_FILES['file']['tmp_name'];
        $dst = "{$bytes}-{$time}";

        if (@($img_info = getimagesize($img)) === FALSE)
            return $this->withCode(400, 'Image not found or not an image');

        $width = $img_info[0];
        $height = $img_info[1];

        switch ($img_info[2]) {
            case IMAGETYPE_GIF  :
                $src = imagecreatefromgif($img);
                break;
            case IMAGETYPE_JPEG :
                $src = imagecreatefromjpeg($img);
                break;
            case IMAGETYPE_PNG  :
                $src = imagecreatefrompng($img);
                break;
            default :
                return $this->withCode(400, 'Unknown filetype');
        }

        $tmp = imagecreatetruecolor($width, $height);
        imagecopyresampled($tmp, $src, 0, 0, 0, 0, $width, $height, $width, $height);
        $bgcolor = imagecolorallocate($tmp, 255, 255, 255);
        imagefill($tmp, 0, 0, $bgcolor);
        imagejpeg($tmp, $path . $dst . ".jpg");


        return $this->withData(200, ['filename' => $dst]);
    }

    public function delete(): string
    {
        return $this->withCode(200);
    }
}
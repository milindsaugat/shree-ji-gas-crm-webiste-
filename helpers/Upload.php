<?php
final class Upload
{
    public static function save(array $file, string $alt, string $stem = ''): array {
        if (($file['error']??UPLOAD_ERR_NO_FILE)!==UPLOAD_ERR_OK) throw new RuntimeException('Upload failed. Check the file and the server upload size limit.');
        if ($file['size']>5*1024*1024) throw new RuntimeException('Files must be smaller than 5 MB.');
        if (mb_strlen(trim($alt))<3) throw new RuntimeException('Add a meaningful image description (at least 3 characters).');
        $mime=(new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);
        $extensions=['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp','application/pdf'=>'pdf'];
        if(!isset($extensions[$mime])) throw new RuntimeException('Only JPG, PNG, WebP and PDF files are accepted.');
        $slug=Slug::make($stem?:pathinfo($file['name'],PATHINFO_FILENAME))?:'equipment';
        $filename=substr($slug,0,80).'-'.bin2hex(random_bytes(5)).'.'.$extensions[$mime];
        $folder=ROOT.'/assets/uploads'; if(!is_dir($folder))mkdir($folder,0755,true);
        if($mime!=='application/pdf'){
            if(!extension_loaded('gd')) throw new RuntimeException('Image processing needs PHP GD. Enable extension=gd in php.ini and restart PHP.');
            $dimensions=getimagesize($file['tmp_name']);
            if(!$dimensions||$dimensions[0]*$dimensions[1]>24000000) throw new RuntimeException('Please use an image smaller than 24 megapixels.');
            $image=match($mime){'image/jpeg'=>imagecreatefromjpeg($file['tmp_name']),'image/png'=>imagecreatefrompng($file['tmp_name']),'image/webp'=>imagecreatefromwebp($file['tmp_name'])};
            if(!$image)throw new RuntimeException('This image could not be decoded.');
            $width=min(1200,imagesx($image));$height=(int)round(imagesy($image)*$width/imagesx($image));
            $output=imagecreatetruecolor($width,$height);imagealphablending($output,false);imagesavealpha($output,true);imagefill($output,0,0,imagecolorallocatealpha($output,255,255,255,127));imagecopyresampled($output,$image,0,0,0,0,$width,$height,imagesx($image),imagesy($image));
            $filename=pathinfo($filename,PATHINFO_FILENAME).'.webp';
            if(!imagewebp($output,$folder.'/'.$filename,80))throw new RuntimeException('Unable to save the processed image.');imagedestroy($image);imagedestroy($output);$mime='image/webp';
        }elseif(!move_uploaded_file($file['tmp_name'],$folder.'/'.$filename))throw new RuntimeException('Unable to store the uploaded file.');
        $data=['filename'=>$filename,'original_name'=>basename($file['name']),'path'=>'assets/uploads/'.$filename,'image_alt'=>$alt,'size'=>filesize($folder.'/'.$filename),'type'=>$mime];
        $data['id']=(new ContentModel('media'))->save($data);return $data;
    }
}

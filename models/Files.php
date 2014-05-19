<?php namespace RabLab\Email\Models;

use Model;

class Files extends Model
{    
    public static function write_view($data)
    {
        $dbData = is_array($data) ? $data : $data->attributes;
        $path = base_path('plugins/rablab/email/views/email/') . $dbData['filename'];
        
        $content = "subject = \"". $dbData['subject'] ."\"\n==\r\n";        
        $content .= $dbData['content'] . "\r\n==\r\n";
        $content .= $dbData['content_html'];

        file_put_contents($path, $content);
        
        return TRUE;
    }
    
    public static function delete_view($data)
    {
        $dbData = is_array($data) ? $data : $data->attributes;     
        $path = base_path('plugins/rablab/email/views/email/') . $dbData['filename'];
        
        if(file_exists($path))
            @unlink ($path);
        
        return TRUE;
    }
}
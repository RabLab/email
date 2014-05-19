<?php namespace RabLab\Email\Models;

use Model;
use October\Rain\Support\Markdown;
use RabLab\Email\Classes\TagProcessor;
use System\Models\EmailSettings;
use Illuminate\Support\Facades\DB;
use RabLab\Email\Models\Files;
use Illuminate\Support\Facades\Mail;

class Template extends Model
{
    public $table = 'rablab_email_templates';    

    /*
     * Validation
     */
    public $rules = [
        'title' => 'required',
        'slug' => ['required', 'regex:/^[a-z0-9\/\:_\-\*\[\]\+\?\|]*$/i'],
        'lang' => 'required',
        'content' => 'required',
        'subject' => 'required',
        'text' => ''
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at'];

    public $preview = null;
    public static $email = null;
    public static $receiver  = null;
    public static $subject_mail = null;
    public static $_table = 'rablab_email_templates';
    
    public static function formatHtml($input, $preview = false)
    {
        $result = Markdown::parse(trim($input));

        if ($preview)
            $result = str_replace('<pre>', '<pre class="prettyprint">', $result);

        $result = TagProcessor::instance()->processTags($result, $preview);

        return $result;
    }

    public function beforeSave()
    {
        $this->filename = 'view-' . $this->slug . '.htm';
        $this->content = strip_tags($this->content);
        $this->content_html = self::formatHtml($this->content);
    }
    
    public function afterSave()
    {
        Files::write_view($this);
    }
    
    public function beforeUpdate()
    {
        Files::delete_view($this->original);
    }

    public function afterDelete()
    {
        Files::delete_view($this);
    }

    public static function use_template($slug, $to = NULL, $receiver  = NULL, $subject = NULL)
    {
        
        $template = DB::table(self::$_table)
                                    ->where('slug', $slug)
                                    ->take(1)
                                    ->get();
        
        if(!count($template))
            return FALSE;
        
        $posts = post();
        if(isset($posts['message']))
        {
            //unset message post if it exists
            $posts['content'] = $posts['message'];
            unset($posts['message']);
        }
        
        self::$email = !is_null($to) ? $to : EmailSettings::get('sender_email');
        self::$receiver  = !is_null($receiver ) ? $receiver  : EmailSettings::get('sender_name');
        self::$subject_mail = !is_null($subject) ? $subject : $template[0]->subject;

        $data = array();
        $template_slug = $template[0]->slug;
        
        if(!is_null(self::$subject_mail))
        {
            $data += array(
                'subject' => self::$subject_mail,
                'filename' =>  'view-tmp-' . $template[0]->slug . '.htm',
                'content' => $template[0]->content,
                'content_html' => $template[0]->content_html
            );
            
            $template_slug = 'tmp-' . $template[0]->slug;
            $file = Files::write_view($data);
            
            if($file)
            {
                //Send an email to
                $result = Mail::send('rablab.email::email.view-' . $template_slug, $posts, function($message) use ($posts){
                    $message->to(self::$email, self::$receiver );           
                });

                Files::delete_view($data);                
            }
        }
        else
        {
            //Send an email to
            $result = Mail::send('rablab.email::email.view-' . $template_slug, $posts, function($message) use ($posts){
                $message->to(self::$email, self::$receiver );           
            });
        }
        
        return $result;
    }
}
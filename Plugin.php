<?php namespace RabLab\Email;

use Backend;
use Illuminate\Support\Facades\Lang;
use System\Classes\PluginBase;
use RabLab\Email\Classes\TagProcessor;
use RabLab\Email\Models\Template;

class Plugin extends PluginBase
{
    public function pluginDetails()
    {
        return [
            'name' => 'Templates',
            'description' => 'Provides email templates to use in frontend.',
            'author' => 'Fabricio Pereira Rabelo',
            'icon' => 'icon-envelope'
        ];
    }

    public function registerNavigation()
    {
        return [
            'email' => [
                'label'       => Lang::get('rablab.email::lang.app.name'),
                'url'         => Backend::url('rablab/email/templates'),
                'icon'        => 'icon-envelope',
                'permissions' => ['templates.*'],
                'order'       => 500,

                'sideMenu' => [
                    'templates' => [
                        'label'       => Lang::get('rablab.email::lang.app.templates'),
                        'icon'        => 'icon-list-alt',
                        'url'         => Backend::url('rablab/email/templates'),
                        'permissions' => ['contact.access_templates'],
                    ]
                ]

            ]
        ];
    }
    
    public function registerPermissions()
    {
        return [
            'rablab.email.access_templates'       => ['label' => 'Manage contact templates'],
        ];
    }
    
    public function registerFormWidgets()
    {
        return [
            'RabLab\Email\FormWidgets\Preview' => [
                'label' => 'Preview',
                'alias' => 'preview'
            ]
        ];
    }
    
    /**
     * Register method, called when the plugin is first registered.
     */
    public function register() 
    {
        /*
         * Register the image tag processing callback
         */

        TagProcessor::instance()->registerCallback(function($input, $preview){
            if (!$preview)
                return $input;

            return preg_replace('|\<img alt="([0-9]+)" src="image" \/>|m', 
                '<span class="image-placeholder" data-index="$1">
                    <span class="dropzone">
                        <span class="label">Click or drop an image...</span>
                        <span class="indicator"></span>
                    </span>
                    <input type="file" class="file" name="image[$1]"/>
                    <input type="file" class="trigger"/>
                </span>', 
            $input);
        });
    }
}

class Send
{
    public static function template($slug = '', $to = NULL, $receiver  = NULL, $subject = NULL)
    {
	return Template::use_template($slug, $to, $receiver, $subject);
    }
}
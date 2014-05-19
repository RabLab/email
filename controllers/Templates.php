<?php namespace RabLab\Email\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use RabLab\Email\Models\Template;

class Templates extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController'
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    public $bodyClass = 'compact-container';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('RabLab.Email', 'email', 'templates');
        $this->addCss('/plugins/rablab/email/assets/css/rablab.email-preview.css');
        $this->addCss('/plugins/rablab/email/assets/css/rablab.email-preview-theme-default.css');

        $this->addCss('/plugins/rablab/email/assets/vendor/prettify/prettify.css');
        $this->addCss('/plugins/rablab/email/assets/vendor/prettify/theme-desert.css');

        $this->addJs('/plugins/rablab/email/assets/js/template-form.js');
        $this->addJs('/plugins/rablab/email/assets/vendor/prettify/prettify.js');
    }

    public function onRefreshPreview()
    {
        $data = post('Template');

        $previewHtml = Template::formatHtml($data['content'], true);

        return [
            'preview' => $previewHtml
        ];
    }
}
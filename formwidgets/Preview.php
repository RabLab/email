<?php namespace RabLab\Email\FormWidgets;

use Backend\Classes\FormWidgetBase;
use RabLab\Email\Models\Template;

/**
 * Preview area for the Create/Edit Template form.
 *
 * @package rainlab\blog
 * @author Alexey Bobkov, Samuel Georges
 */
class Preview extends FormWidgetBase
{
    /**
     * {@inheritDoc}
     */
    public function render()
    {
        $this->vars['preview_html'] = Template::formatHtml($this->model->content, true);

        return $this->makePartial('preview');
    }
}
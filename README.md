# Email Templates Plugin

This plugin adds a class to send emails in frontend using customizable emails' templates [OctoberCMS](http://octobercms.com).

## Using the code

1. Under System> Settings> Email Configuration, set your preferences for sending e-mail.
2. Register at least one e-mail template. You can use twig variables in your template email according to the html form used.
3. On your contact page add the code:


    // Use the class to call the method "get_template()"
    use RabLab\Email\Models\Template;

    // The function name can be on your own
    function onSendMail()
    {
        $post = post();
        $result = Template::get_template('my-template-test', $post['email'], $post['name']);
        $this['result'] = $result;
    }


The get_template () method has 4 parameters that can be passed:

    $ slug = slug to previously registered template in the database. (required)
    $ mail = Mailbox to receive the e-mail sent. (optional)
    $ receiver = The name of the receiver mailbox. (optional)
    $ subject = Subject of the message. This endorses the subject entered orginalmente to register a template.


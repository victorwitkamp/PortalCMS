<?php

namespace PortalCMS\Core\Email\Template;

use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Core\HTTP\Request;
use PortalCMS\Core\Session\Session;

class MailTemplate
{
    public static function new()
    {
        // $type = Request::post('type', true);
        $type = 'member';
        $subject = Request::post('subject', true);
        $body = Request::post('body', false); //TODO: Possible issue!!! Needed for HTML markup in templates
        $status = 1;
        $return = MailTemplateMapper::create($type, $subject, $body, $status);
        if ($return === false) {
            Session::add('feedback_negative', 'Nieuwe template aanmaken mislukt.');
        } else {
            Session::add('feedback_positive', 'Template toegevoegd (ID = ' . $return . ')');
            Redirect::to('mail/templates/');
        }
    }

    public static function edit()
    {
        // $type = Request::post('type', true);
        $type = 'member';
        $id = Request::post('id', true);

        $subject = Request::post('subject', true);
        $body = Request::post('body', false); //TODO: Possible issue!!! Needed for HTML markup in templates
        $status = 1;
        $return = MailTemplateMapper::update($id, $type, $subject, $body, $status);
        if ($return === false) {
            Session::add('feedback_negative', 'Nieuwe template aanmaken mislukt.');
        } else {
            Session::add('feedback_positive', 'Template toegevoegd (ID = ' . $return . ')');
            Redirect::to('mail/templates/');
        }
    }

    public static function replaceholder($placeholder, $placeholdervalue, $body_in)
    {
        $variables = array(
            $placeholder=>$placeholdervalue
        );
        foreach ($variables as $key => $value) {
            $body_out = str_replace('{' . strtoupper($key) . '}', $value, $body_in);
        }
        if (empty($body_out)) {
            return false;
        }
        return $body_out;
    }
}

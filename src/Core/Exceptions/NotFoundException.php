<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

namespace PortalCMS\Core\Exceptions;

class NotFoundException extends \Exception
{
    protected $message = 'Page not found';
    protected $code = 404;
}
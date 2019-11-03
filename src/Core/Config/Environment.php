<?php

namespace PortalCMS\Core\Config;

/**
 * Class Environment
 *
 * Extremely simple way to get the environment, everywhere inside your application.
 * Extend this the way you want.
 */
class Environment
{
    public static function get()
    {
        // APPLICATION_ENV constant can be set in apache config
        //
        // if APPLICATION_ENV exists:  return content of APPLICATION_ENV
        //                      else:  return "development"

        return (getenv('APPLICATION_ENV') ?: 'development');
    }
}

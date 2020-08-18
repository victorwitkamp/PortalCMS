<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Core\View;

/**
 * Class View
 * The part that handles all the output
 */
class HTMLEntities
{
    /**
     * Converts characters to HTML entities
     * This is important to avoid XSS attacks, and attempts to inject malicious code in your page.
     */
    public static function encode(string $string): string
    {
        return htmlentities($string, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Converts HTML entities back to characters
     */
    public function decode(string $string): string
    {
        return html_entity_decode($string, ENT_QUOTES, 'UTF-8');
    }
}

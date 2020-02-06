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
class View
{
    /**
     * Converts characters to HTML entities
     * This is important to avoid XSS attacks, and attempts to inject malicious code in your page.
     *
     * @param  string $string The string.
     * @return string
     */
    public static function encodeHTML(string $string): string
    {
        return htmlentities($string, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Converts HTML entities back to characters
     *
     * @param  string $string The string.
     * @return string
     */
    public function decodeHTML(string $string): string
    {
        return html_entity_decode($string, ENT_QUOTES, 'UTF-8');
    }
}

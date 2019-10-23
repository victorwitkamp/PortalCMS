<?php

namespace PortalCMS\Core\View;

/**
 * Class View
 * The part that handles all the output
 */
class View
{
    /**
     * Render the footer template
     *
     * @return void
     */
    public static function renderFooter()
    {
        include DIR_INCLUDES.'footer.php';
    }

    /**
     * Renders pure JSON to the browser, useful for API construction
     *
     * @param $data
     */
    public function renderJSON($data)
    {
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    /**
     * Converts characters to HTML entities
     * This is important to avoid XSS attacks, and attempts to inject malicious code in your page.
     *
     * @param  string $str The string.
     * @return string
     */
    public function encodeHTML($str)
    {
        return htmlentities($str, ENT_QUOTES, 'UTF-8');
    }
}

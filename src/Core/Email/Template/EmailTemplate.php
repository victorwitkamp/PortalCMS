<?php
/**
 * Copyright Victor Witkamp (c) 2019.
 */

declare(strict_types=1);

namespace PortalCMS\Core\Email\Template;

class EmailTemplate
{
    /**
     * @var int $id
     */
    public $id;

    /**
     * @var string $type
     */
    public $type;

    /**
     * @var string $name
     */
    public $name;

    /**
     * @var string $subject
     */
    public $subject;

    /**
     * @var string $body
     */
    public $body;

    /**
     * @var string $status
     */
    public $status;

    /**
     * @var int $CreatedBy
     */
    public $CreatedBy;

    public function __construct()
    {
    }

        public static function replaceholder($placeholder, $placeholdervalue, $body_in)
    {
        $variables = array(
            $placeholder=>$placeholdervalue
        );
        foreach ($variables as $key => $value) {
            $body_out = str_replace('{'.strtoupper($key).'}', $value, $body_in);
        }
        return $body_out;
    }
}

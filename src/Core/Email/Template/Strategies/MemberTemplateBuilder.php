<?php

class MemberTemplateBuilder implements ITemplateBuilder
{
    public $subject = null;
    public $body = null;

    public function __construct(string $subject = null, string $body = null)
    {
        $this->subject = $subject;
        $this->body = $body;
    }


}

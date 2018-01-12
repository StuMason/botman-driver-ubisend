<?php

namespace JoeDixon\BotManDrivers\Extensions;

class ButtonTemplate implements \JsonSerializable
{
    protected $title;

    protected $type;

    protected $action;

    public function __construct($title)
    {
        $this->title = $title;
    }

    public static function create($title)
    {
        return new static($title);
    }

    public function addType($type)
    {
        $this->type = $type;

        return $this;
    }

    public function addAction($action)
    {
        $this->action = $action;

        return $this;
    }

    public function toArray()
    {
        return [
            'title' => $this->title,
            'type' => $this->type,
            'action' => $this->action,
        ];
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }
}

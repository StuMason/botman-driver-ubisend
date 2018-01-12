<?php

namespace JoeDixon\BotManDrivers\Extensions;

class ActionTemplate implements \JsonSerializable
{
    /** @var string */
    protected $text;

    /** @var array */
    protected $buttons = [];

    /**
     * @param $text
     * @return static
     */
    public static function create($text)
    {
        return new static($text);
    }

    public function __construct($text)
    {
        $this->text = $text;
    }

    /**
     * @param $button
     * @return $this
     */
    public function addButton(ButtonTemplate $button)
    {
        $this->buttons[] = $button->toArray();
        return $this;
    }

    /**
     * @param array $buttons
     * @return $this
     */
    public function addButtons(array $buttons)
    {
        foreach ($buttons as $button) {
            if ($button instanceof ButtonTemplate) {
                $this->buttons[] = $button->toArray();
            }
        }

        return $this;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'type' => 'action',
            'message' => [[
                'text' => $this->text,
                'buttons' => $this->buttons,
            ]],
        ];
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }
}

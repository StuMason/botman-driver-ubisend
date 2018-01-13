<?php

namespace JoeDixon\BotManDrivers\Extensions;

use JoeDixon\BotManDrivers\Extensions\TemplateTemplate as ListItemTemplate;

class ListTemplate implements \JsonSerializable
{
    protected $type;

    /** @var array */
    protected $items = [];

    protected $button;

    public function __construct($type = 'compact')
    {
        $this->type = $type;
    }

    /**
     * @return static
     */
    public static function create($type = 'compact')
    {
        return new static($type);
    }

    /**
     * @param $item
     * @return $this
     */
    public function addItem(ListItemTemplate $item)
    {
        $this->items[] = $item->toTemplateArray();

        return $this;
    }

    /**
     * @param array $items
     * @return $this
     */
    public function addItems(array $items)
    {
        foreach ($items as $item) {
            if ($item instanceof ListItemTemplate) {
                $this->items[] = $item->toTemplateArray();
            }
        }

        return $this;
    }

    public function addButton(ButtonTemplate $button)
    {
        $this->button = $button->toArray();
    }


    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'type' => 'list template',
            'message' => [[
                'type' => $this->type,
                'items' => $this->items,
            ]]
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

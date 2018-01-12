<?php

namespace JoeDixon\BotManDrivers\Extensions;

class TemplateTemplate implements \JsonSerializable
{
    protected $title;

    protected $subtitle;

    protected $url;

    protected $image;

    protected $buttons;

    public function __construct($title)
    {
        $this->title = $title;
    }

    public static function create($title)
    {
        return new static($title);
    }

    public function addSubtitle($subtitle)
    {
        $this->subtitle = $subtitle;

        return $this;
    }

    public function addUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    public function addImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @param $buttons
     * @return $this
     */
    public function addButton(ButtonTemplate $button)
    {
        $this->buttons[] = $button->toArray();
        return $this;
    }

    /**
     * @param array $
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
            'type' => 'template',
            'message' => [[
                'title' => $this->title,
                'subtitle' => $this->subtitle,
                'url' => $this->url,
                'image' => $this->image,
                'buttons' => $this->buttons,
            ]],
        ];
    }

    /**
     * @return array
     */
    public function toTemplateArray()
    {
        return [
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'url' => $this->url,
            'image' => $this->image,
            'buttons' => $this->buttons,
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

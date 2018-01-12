<?php

namespace JoeDixon\BotManDrivers\Extensions;

class ListTemplate implements \JsonSerializable
{
    protected $type;

    /** @var array */
    protected $templates = [];

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
     * @param $template
     * @return $this
     */
    public function addTemplate(TemplateTemplate $template)
    {
        $this->templates[] = $template->toTemplateArray();

        return $this;
    }

    /**
     * @param array $templates
     * @return $this
     */
    public function addTemplates(array $templates)
    {
        foreach ($templates as $template) {
            if ($template instanceof TemplateTemplate) {
                $this->templates[] = $template->toTemplateArray();
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
                'items' => $this->templates,
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

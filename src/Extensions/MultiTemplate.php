<?php

namespace JoeDixon\BotManDrivers\Extensions;

class MultiTemplate implements \JsonSerializable
{
    /** @var array */
    protected $templates = [];

    /**
     * @return static
     */
    public static function create()
    {
        return new static();
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

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'type' => 'multi-template',
            'message' => $this->templates,
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

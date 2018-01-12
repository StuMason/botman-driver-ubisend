<?php

namespace JoeDixon\BotManDrivers\Extensions;

class SurveyTemplate implements \JsonSerializable
{
    /** @var string */
    protected $text;

    /** @var array */
    protected $responses = [];

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
     * @param $response
     * @return $this
     */
    public function addResponse($response)
    {
        $this->responses[] = $response;
        return $this;
    }

    /**
     * @param array $responses
     * @return $this
     */
    public function addResponses(array $responses)
    {
        foreach ($responses as $response) {
            $this->responses[] = $response;
        }

        return $this;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        \Log::info([
            'type' => 'survey',
            'message' => [[
                'text' => $this->text,
                'responses' => $this->responses,
            ]],
        ]);

        return [
            'type' => 'survey',
            'message' => [[
                'text' => $this->text,
                'responses' => $this->responses,
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

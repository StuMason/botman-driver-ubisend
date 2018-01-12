<?php

namespace JoeDixon\BotManDrivers;

use BotMan\BotMan\Users\User;
use Illuminate\Support\Collection;
use BotMan\BotMan\Drivers\HttpDriver;
use BotMan\BotMan\Messages\Incoming\Answer;
use Symfony\Component\HttpFoundation\Request;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;
use BotMan\BotMan\Messages\Outgoing\OutgoingMessage;

class UbisendDriver extends HttpDriver
{
    const DRIVER_NAME = 'Ubisend';

    protected $ubisendEndpoint = 'http://api.ubisend.test/v1/';

    /**
     * Determine if the request is for this driver.
     *
     * @return bool
     */
    public function matchesRequest()
    {
        return $this->event->get('subscriber') && $this->event->get('message');
    }

    /**
     * Retrieve the chat message(s).
     *
     * @return array
     */
    public function getMessages()
    {
        if (empty($this->messages)) {
            $this->loadMessages();
        }

        return $this->messages;
    }

    public function loadMessages()
    {
        $message = $this->event->get('message');
        $subscriber = $this->event->get('subscriber');
        $messages = [
            new IncomingMessage($message['content'][0]['text'], $subscriber['id'], null, $message)
        ];

        if (count($messages) === 0) {
            $messages = [new IncomingMessage('', '', '')];
        }

        $this->messages = $messages;
    }

    /**
     * @return bool
     */
    public function isConfigured()
    {
        return !empty($this->config->get('token'));
    }

    /**
     * Retrieve User information.
     * @param IncomingMessage $matchingMessage
     * @return UserInterface
     */
    public function getUser(IncomingMessage $matchingMessage)
    {
        return new User($matchingMessage->getSender(), $this->event->get('subscriber')['first_name'], $this->event->get('subscriber')['last_name'], null, $this->event->get('subscriber'));
    }

    /**
     * @param IncomingMessage $message
     * @return \BotMan\BotMan\Messages\Incoming\Answer
     */
    public function getConversationAnswer(IncomingMessage $message)
    {
        return Answer::create($message->getText())->setMessage($message);
    }

    /**
     * @param string|\BotMan\BotMan\Messages\Outgoing\Question $message
     * @param IncomingMessage $matchingMessage
     * @param array $additionalParameters
     * @return $this
     */
    public function buildServicePayload($message, $matchingMessage, $additionalParameters = [])
    {
        $parameters['subscriber_id'] = $matchingMessage->getSender();

        if ($message instanceof OutgoingMessage) {
            $parameters['type'] = 'standard';
            $parameters['message'] = [['text' => $message->getText()]];
        } else {
            $parameters += $message->toArray();
        }

        \Log::info(json_encode($parameters));
        return $parameters;
    }

    /**
     * @param mixed $payload
     * @return Response
     */
    public function sendPayload($payload)
    {
        $response = $this->http->post($this->ubisendEndpoint . 'send', [], $payload, [
            "Authorization: Bearer {$this->config->get('token')}",
            'Content-Type: application/json',
            'Accept: application/json',
        ], true);

        \Log::info($response);
        return $response;
    }

    /**
     * Return the driver name.
     *
     * @return string
     */
    public function getName()
    {
        return self::DRIVER_NAME;
    }

    /**
     * Does the driver match to an incoming messaging service event.
     *
     * @return bool|mixed
     */
    public function hasMatchingEvent()
    {
        return false;
    }

    /**
     * Send a typing indicator.
     * @param IncomingMessage $matchingMessage
     * @return mixed
     */
    public function types(IncomingMessage $matchingMessage)
    {
        return false;
    }

    /**
     * @param Request $request
     * @return void
     */
    public function buildPayload(Request $request)
    {
        $this->payload = $request->request->all();
        $this->event = Collection::make($this->payload);
        $this->config = Collection::make($this->config->get('ubisend', []));
    }

    /**
     * Low-level method to perform driver specific API requests.
     *
     * @param string $endpoint
     * @param array $parameters
     * @param \BotMan\BotMan\Messages\Incoming\IncomingMessage $matchingMessage
     * @return void
     */
    public function sendRequest($endpoint, array $parameters, IncomingMessage $matchingMessage)
    {
    }

    /**
     * Tells if the stored conversation callbacks are serialized.
     *
     * @return bool
     */
    public function serializesCallbacks()
    {
        return false;
    }
}

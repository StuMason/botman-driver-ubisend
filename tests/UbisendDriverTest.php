<?php

use BotMan\BotMan\Http\Curl;
use PHPUnit\Framework\TestCase;
use JoeDixon\BotManDrivers\UbisendDriver;
use Symfony\Component\HttpFoundation\Request;

class UbisendDriverTest extends TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }

    private function getDriver($responseData, array $config = null, $signature = '', $htmlInterface = null)
    {
        $request = Request::create('', 'POST', json_decode($responseData, true));

        if (is_null($config)) {
            $config = [
                'ubisend' => [
                    'token' => 'here-is-my-token',
                ],
            ];
        }

        if ($htmlInterface === null) {
            $htmlInterface = Mockery::mock(Curl::class);
        }

        return new UbisendDriver($request, $config, $htmlInterface);
    }

    /** @test */
    public function it_returns_the_driver_name()
    {
        $driver = $this->getDriver('');
        $this->assertSame('Ubisend', $driver->getName());
    }

    /** @test */
    public function it_matches_the_request()
    {
        /*$request = '{}';
        $driver = $this->getDriver($request);
        $this->assertFalse($driver->matchesRequest());*/

        $request = '{"subscriber":{"id":66,"type":"converse","first_name":null,"last_name":null,"locale":null,"timezone":null,"gender":null,"avatar":null,"created_at":"2018-01-07T20:40:40+00:00","updated_at":"2018-01-07T20:40:40+00:00"},"message":{"type":"standard","source":"converse","content":[{"text":"hi"}]}}';
        $driver = $this->getDriver($request);
        $this->assertTrue($driver->matchesRequest());
        $request = '{"object":"page","entry":[{"id":"111899832631525","time":1480279487271,"messaging":[{"sender":{"id":"1433960459967306"},"recipient":{"id":"111899832631525"},"timestamp":1480279487147,"message":{"is_echo":true,"mid":"mid.1480279487147:4388d3b344","seq":36,"text":"Hi"}}]}]}';
        $driver = $this->getDriver($request);
        $this->assertFalse($driver->matchesRequest());
        $config = [
            'ubisend' => [
                'token' => 'here-is-my-token',
            ],
        ];
    }
}

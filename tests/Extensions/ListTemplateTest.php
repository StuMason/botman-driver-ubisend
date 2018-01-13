<?php

use Illuminate\Support\Arr;
use PHPUnit\Framework\TestCase;
use JoeDixon\BotManDrivers\Extensions\ListTemplate;
use JoeDixon\BotManDrivers\Extensions\TemplateTemplate;

class ListTemplateTest extends TestCase
{
    /** @test */
    public function it_can_be_created()
    {
        $template = new ListTemplate;
        $this->assertInstanceOf(ListTemplate::class, $template);
    }

    /** @test */
    public function it_can_add_an_element()
    {
        $template = new ListTemplate;
        $template->addItem(
            TemplateTemplate::create('Here is a title')
                ->addSubtitle('Here is a subtitle')
                ->addUrl('https://www.ubisend.com')
                ->addImage('https://www.ubisend.com/ubi-bot.png'));

        $this->assertSame($template->toArray(), [
            'type' => 'list template',
            'message' => [[
                'type' => 'compact',
                'items' => [[
                    'title' => 'Here is a title',
                    'subtitle' => 'Here is a subtitle',
                    'url' => 'https://www.ubisend.com',
                    'image' => 'https://www.ubisend.com/ubi-bot.png',
                    'buttons' => null
                ]],
            ]]
        ]);
    }

}
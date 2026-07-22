<?php

declare(strict_types=1);

use AiSdk\Content;
use AiSdk\Deepgram;
use AiSdk\Generate;
use AiSdk\Support\Sdk;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\Response;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

afterEach(function (): void {
    Generate::reset();
    Deepgram::reset();
});

it('uses Deepgram prerecorded listen with token authentication', function (): void {
    $client = new class implements ClientInterface {
        public ?RequestInterface $request = null;
        public function sendRequest(RequestInterface $request): ResponseInterface
        {
            $this->request = $request;
            return new Response(200, ['Content-Type' => 'application/json'], json_encode(['metadata' => ['request_id' => 'dg_1', 'duration' => 1.2], 'results' => ['channels' => [['alternatives' => [['transcript' => 'Hello world', 'words' => [['word' => 'Hello', 'start' => 0, 'end' => .5], ['word' => 'world', 'start' => .6, 'end' => 1.2]]]]]]]]));
        }
    };
    $factory = new Psr17Factory();
    Generate::configure(new Sdk($client, $factory, $factory));
    Deepgram::create(['apiKey' => 'deepgram-test']);
    $result = Generate::transcription()->model(Deepgram::model('nova-3'))->audio(Content::audio('https://example.com/audio.mp3'))->run();
    expect($result->output->text)->toBe('Hello world')->and((string) $client->request?->getUri())->toBe('https://api.deepgram.com/v1/listen?model=nova-3')->and($client->request?->getHeaderLine('Authorization'))->toBe('Token deepgram-test');
});

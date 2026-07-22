# Deepgram provider for PHP AI SDK

```bash
composer require aisdk/deepgram
```

```php
use AiSdk\Deepgram;
use AiSdk\Content;
use AiSdk\Generate;

Deepgram::create(['apiKey' => $_ENV['DEEPGRAM_API_KEY']]);

$result = Generate::transcription()
    ->model(Deepgram::model('nova-3'))
    ->audio(Content::audio('https://example.com/audio.mp3'))
    ->run();
```

The provider submits media to Deepgram’s synchronous `/v1/listen` endpoint using `Authorization: Token …`. Provider options map to Deepgram’s listen query parameters. See [Deepgram’s API reference](https://developers.deepgram.com/reference/speech-to-text/listen-pre-recorded).

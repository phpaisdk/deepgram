# PHP AI SDK — Deepgram

Audio transcription with Deepgram for the framework-agnostic [PHP AI SDK](https://phpaisdk.com).

## Installation

```bash
composer require aisdk/deepgram
```

Set `DEEPGRAM_API_KEY` in your environment.

## Usage

```php
use AiSdk\Content;
use AiSdk\Deepgram;
use AiSdk\Generate;

$result = Generate::transcription(Content::audio(__DIR__.'/meeting.mp3'))
    ->model(Deepgram::model('nova-3'))
    ->run();

echo $result->output->text;
```

## Documentation

[Read the documentation](https://phpaisdk.com/docs/0.x/providers/deepgram) for configuration, supported features, and more examples.

[Contributing](https://github.com/phpaisdk/.github/blob/main/CONTRIBUTING.md) · [Support](https://github.com/phpaisdk/.github/blob/main/SUPPORT.md)

## License

The MIT License (MIT). See [LICENSE](LICENSE) for details.

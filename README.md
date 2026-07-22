# aisdk/deepgram

<a href="https://github.com/phpaisdk/deepgram/actions"><img alt="GitHub Workflow Status" src="https://img.shields.io/github/actions/workflow/status/phpaisdk/deepgram/tests.yml?branch=main&label=Tests"></a>
<a href="https://packagist.org/packages/aisdk/deepgram"><img alt="Latest Version" src="https://img.shields.io/packagist/v/aisdk/deepgram"></a>
<a href="https://packagist.org/packages/aisdk/deepgram"><img alt="License" src="https://img.shields.io/packagist/l/aisdk/deepgram"></a>

------

Official Deepgram provider for the PHP AI SDK. Uses Deepgram's native pre-recorded speech-to-text API.

## Installation

```bash
composer require aisdk/deepgram
```

## Basic Usage

```php
use AiSdk\Content;
use AiSdk\Deepgram;
use AiSdk\Generate;

Deepgram::create(['apiKey' => $_ENV['DEEPGRAM_API_KEY']]);

$result = Generate::transcription()
    ->model(Deepgram::model('nova-3'))
    ->audio(Content::audio('https://example.com/audio.mp3'))
    ->run();
```

## Configuration

| Variable | Description | Default |
| --- | --- | --- |
| `DEEPGRAM_API_KEY` | API key for authentication | Required |
| `DEEPGRAM_BASE_URL` | Base URL for API requests | `https://api.deepgram.com` |

## Supported Capabilities

| Capability | Support |
| --- | --- |
| Pre-recorded transcription | Native `/v1/listen` |
| Remote audio URLs | Native |
| Local audio files | Native binary request |
| Word timing and speakers | Parsed when provided by Deepgram |

Use `providerOptions('deepgram', [...])` for Deepgram listen query parameters such as `smart_format`, `diarize`, or `language`.

## Documentation

- [Deepgram pre-recorded speech-to-text reference](https://developers.deepgram.com/reference/speech-to-text/listen-pre-recorded)
- [PHP AI SDK documentation](https://phpaisdk.com/docs/deepgram)

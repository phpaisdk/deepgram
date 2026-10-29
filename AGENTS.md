# aisdk/deepgram: guide for coding agents

Official Deepgram provider for the PHP AI SDK. Part of the framework-agnostic [PHP AI SDK](https://phpaisdk.com); build on `aisdk/core`, keep provider wire details in this package.

## Using it

```php
use AiSdk\Content;
use AiSdk\Deepgram;
use AiSdk\Generate;
use AiSdk\LocalFile;

Deepgram::create(['apiKey' => '...']); // optional: environment variables below are read automatically

$result = Generate::transcription(Content::audio(LocalFile::at('/path/to/audio.mp3')))
    ->model(Deepgram::model('model-id'))
    ->run();

echo $result->text;
```

- Entry point: `AiSdk\Deepgram` (`create()`, `default()`, `reset()`, `model()`), source in `src/Root/Deepgram.php`.
- Namespace: `AiSdk\Deepgram\` in `src/`.
- Provider key for call options: `->providerOptions('deepgram', [...])`.
- Common environment variables (see README.md for the full list): `DEEPGRAM_API_KEY`, `DEEPGRAM_BASE_URL`.

## Supported capabilities

| Capability | Support |
| --- | --- |
| Pre-recorded transcription | Native `/v1/listen` |
| Remote audio URLs | Native |
| Local audio files | Native binary request |
| Word timing and speakers | Parsed when provided by Deepgram |

Use `providerOptions('deepgram', [...])` for Deepgram listen query parameters such as `smart_format`, `diarize`, or `language`.

## Rules for changes

1. **Model ids are opaque strings.** Use `Deepgram::model('id')`. Never validate an id against a list, never ship a model catalog, and never add capability-specific public factories such as `textModel()` or `imageModel()`. The operation (`Generate::text()`, `Generate::image()`, ...) resolves the right adapter when it runs.
2. **Provider wire behavior lives here, portable behavior lives in `aisdk/core`.** Do not leak this provider's request/response shapes into core, and do not put portable logic here.
3. **Keep adapters small.** Each capability has one model class in `src/Models/` and helpers in `src/Support/`; share code across capabilities through those helpers.
4. **Credentials come from config or environment**, never from code. Do not log request headers or bodies that contain keys.
5. **Unsupported features fail fast** with a typed exception from `AiSdk\Exceptions` before any HTTP call, rather than sending a request the provider will reject.

## Layout

| Path | Purpose |
|---|---|
| `src/Root/Deepgram.php` | Public facade |
| `src/DeepgramProvider.php` | Provider: resolves a model id to a capability adapter |
| `src/DeepgramOptions.php` | Configuration (config array and environment) |
| `src/Models/` | `DeepgramTranscriptionModel` |
| `tests/` | Pest tests |

## Commands

```bash
composer lint      # pint --test
composer analyze   # phpstan level 8
composer test      # pest
composer test:all  # all of the above
```

## Testing

- Tests never touch the network. Inject a fake PSR-18 client through `Generate::configure(new Sdk(...))`.
- Cover request shaping (assert the JSON body), response parsing, streaming, and error mapping for every capability you change.
- Do not edit `vendor/`. This package is installed against a released `aisdk/core`; if a test needs unreleased core behavior, run it against the local core checkout rather than loosening assertions.

## Docs

- Package docs: https://phpaisdk.com/docs/deepgram.md
- All documentation for agents: https://phpaisdk.com/llms.txt (full text: https://phpaisdk.com/llms-full.txt)
- Core concepts (providers, models, tools, streaming): https://phpaisdk.com/docs/introduction.md

## Changelog

Keep `CHANGELOG.md` current: add short, user-facing entries (what users can do now, dependency compatibility) for every release. Release notes on GitHub should match.

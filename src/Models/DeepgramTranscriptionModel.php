<?php

declare(strict_types=1);

namespace AiSdk\Deepgram\Models;

use AiSdk\ContentSource;
use AiSdk\Contracts\BaseModel;
use AiSdk\Contracts\TranscriptionModelInterface;
use AiSdk\Deepgram\DeepgramOptions;
use AiSdk\Generate;
use AiSdk\Requests\TranscriptionRequest;
use AiSdk\Responses\TranscriptionResponse;
use AiSdk\Results\TranscriptData;
use AiSdk\Results\TranscriptSegment;
use AiSdk\Utils\Support\Url;

final class DeepgramTranscriptionModel extends BaseModel implements TranscriptionModelInterface
{
    public function __construct(private readonly string $modelId, private readonly DeepgramOptions $options) {}

    public function provider(): string
    {
        return DeepgramOptions::PROVIDER_NAME;
    }
    public function modelId(): string
    {
        return $this->modelId;
    }

    public function transcribe(TranscriptionRequest $request): TranscriptionResponse
    {
        $options = $request->providerOptionsFor($this->provider());
        $query = array_replace(['model' => $this->modelId], $options);
        $url = Url::joinPath($this->options->baseUrl, '/v1/listen') . '?' . http_build_query($query);
        $headers = $this->options->authHeaders();
        $sdk = $this->options->sdk ?? Generate::sdk();

        if ($request->audio->source() === ContentSource::Url) {
            $payload = $this->runner($this->options->sdk)->postJson($url, ['url' => $request->audio->url()], $headers, $this->provider());
        } else {
            $bytes = base64_decode((string) $request->audio->base64Data(), true);
            if ($bytes === false) {
                throw new \RuntimeException('Deepgram audio input could not be encoded.');
            }
            $http = $sdk->requestFactory->createRequest('POST', $url)
                ->withBody($sdk->streamFactory->createStream($bytes))
                ->withHeader('Content-Type', $request->audio->mimeType() ?? 'application/octet-stream');
            foreach ($headers as $name => $value) {
                $http = $http->withHeader($name, $value);
            }
            $response = $this->runner($this->options->sdk)->sendRequest($http, $this->provider());
            $payload = json_decode((string) $response->getBody(), true);
            if (! is_array($payload)) {
                throw new \RuntimeException('Deepgram returned an invalid transcription response.');
            }
        }

        $alternative = $payload['results']['channels'][0]['alternatives'][0] ?? [];
        $segments = [];
        foreach ((array) ($alternative['words'] ?? []) as $word) {
            if (is_array($word)) {
                $segments[] = new TranscriptSegment((string) ($word['punctuated_word'] ?? $word['word'] ?? ''), (float) ($word['start'] ?? 0), (float) ($word['end'] ?? 0), isset($word['speaker']) ? (string) $word['speaker'] : null);
            }
        }

        return new TranscriptionResponse(new TranscriptData((string) ($alternative['transcript'] ?? ''), isset($payload['results']['channels'][0]['detected_language']) ? (string) $payload['results']['channels'][0]['detected_language'] : null, isset($payload['metadata']['duration']) ? (float) $payload['metadata']['duration'] : null, $segments), rawResponse: $payload, providerMetadata: ['deepgram' => ['requestId' => $payload['metadata']['request_id'] ?? null]]);
    }
}

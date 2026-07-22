<?php

declare(strict_types=1);

namespace AiSdk\Deepgram;

use AiSdk\Contracts\BaseProvider;
use AiSdk\Contracts\TranscriptionModelInterface;
use AiSdk\Contracts\TranscriptionProviderInterface;
use AiSdk\Deepgram\Models\DeepgramTranscriptionModel;

final class DeepgramProvider extends BaseProvider implements TranscriptionProviderInterface
{
    public function __construct(public readonly DeepgramOptions $options) {} public function name(): string
    {
        return DeepgramOptions::PROVIDER_NAME;
    } protected function transcriptionModel(string $modelId): TranscriptionModelInterface
    {
        return new DeepgramTranscriptionModel($modelId, $this->options);
    }
}

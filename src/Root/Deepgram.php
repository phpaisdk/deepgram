<?php

declare(strict_types=1);

namespace AiSdk;

use AiSdk\Contracts\Model;
use AiSdk\Deepgram\DeepgramOptions;
use AiSdk\Deepgram\DeepgramProvider;

final class Deepgram
{
    private static ?DeepgramProvider $default = null;
    /** @param array<string,mixed> $config */ public static function create(array $config = []): DeepgramProvider
    {
        return self::$default = new DeepgramProvider(DeepgramOptions::fromArray($config));
    } public static function default(): DeepgramProvider
    {
        return self::$default ??= self::create();
    } public static function reset(): void
    {
        self::$default = null;
    } public static function model(string $modelId): Model
    {
        return self::default()->model($modelId);
    }
}

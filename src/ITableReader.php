<?php

declare(strict_types=1);

namespace Locr\Lib;

interface ITableReader
{
    public function loadFile(string $filename): void;
    /**
     * @return array<int, array<string>>
     */
    public function readDatasets(int $limit = -1, int $offset = -1): array;
    /**
     * @param callable(array<string>, int): void $callback
     */
    public function readDatasetsCallback(callable $callback, int $limit = -1, int $offset = -1): int;
}

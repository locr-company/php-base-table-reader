<?php

declare(strict_types=1);

namespace Locr\Lib;

/**
 * @property int $FieldsCount
 * @property string $Filename
 * @property bool $FirstLineIsHeader
 * @property string[] $HeaderFields
 * @property bool $IgnoreEmptyLines
 */
abstract class BaseTableReader implements ITableReader
{
    /**
     * @see $FieldsCount
     */
    protected int $fieldsCount = 0;
    /**
     * @see $Filename
     */
    protected string $filename = '';
    /**
     * @see $FirstLineIsHeader
     */
    protected bool $firstLineIsHeader = false;
    /**
     * @var string[]
     * @see $HeaderFields
     */
    protected array $headerFields = [];
    /**
     * @see $IgnoreEmptyLines
     */
    protected bool $ignoreEmptyLines = true;

    public function __get(string $name): mixed
    {
        return match ($name) {
            'FieldsCount' => $this->fieldsCount,
            'Filename' => $this->filename,
            'FirstLineIsHeader' => $this->firstLineIsHeader,
            'HeaderFields' => $this->headerFields,
            'IgnoreEmptyLines' => $this->ignoreEmptyLines,
            default => null
        };
    }

    abstract protected function readDatasetsCallbackInternal(
        callable $callback,
        int $limit = -1,
        int $offset = -1
    ): int;

    /**
     * @return array<int, array<mixed>>
     */
    public function readDatasets(int $limit = -1, int $offset = -1): array
    {
        $instance = $this;

        $datasets = [];
        $this->readDatasetsCallbackInternal(function (array $fields, int $lineNumber) use ($instance, &$datasets) {
            if ($instance->FirstLineIsHeader) {
                $rowArray = [];

                $fieldCounter = 0;
                foreach ($instance->HeaderFields as $it) {
                    if ($it !== '') {
                        $rowArray[$it] = $fields[$fieldCounter];
                    } else {
                        $rowArray[(string)$fieldCounter] = $fields[$fieldCounter];
                    }

                    $fieldCounter++;
                }

                $datasets[$lineNumber] = $rowArray;
            } else {
                if (count($instance->HeaderFields) > 0) {
                    $rowArray = [];

                    $fieldCounter = 0;
                    foreach ($instance->HeaderFields as $it) {
                        if ($it !== '') {
                            $rowArray[$it] = $fields[$fieldCounter];
                        } else {
                            $rowArray[(string)$fieldCounter] = $fields[$fieldCounter];
                        }

                        $fieldCounter++;
                    }

                    $datasets[$lineNumber] = $rowArray;
                } else {
                    $datasets[$lineNumber] = $fields;
                }
            }
        }, $limit, $offset);

        return $datasets;
    }

    public function readDatasetsCallback(callable $callback, int $limit = -1, int $offset = -1): int
    {
        $instance = $this;

        return $this->readDatasetsCallbackInternal(function (
            array $fields,
            int $lineNumber
        ) use (
            $instance,
            $callback
        ) {
            if ($instance->FirstLineIsHeader) {
                $rowArray = [];

                $fieldCounter = 0;
                foreach ($instance->HeaderFields as $it) {
                    if ($it !== '') {
                        $rowArray[$it] = $fields[$fieldCounter];
                    } else {
                        $rowArray[(string)$fieldCounter] = $fields[$fieldCounter];
                    }

                    $fieldCounter++;
                }

                $callback($rowArray, $lineNumber);
            } else {
                if (count($instance->HeaderFields) > 0) {
                    $rowArray = [];

                    $fieldCounter = 0;
                    foreach ($instance->HeaderFields as $it) {
                        if ($it !== '') {
                            $rowArray[$it] = $fields[$fieldCounter];
                        } else {
                            $rowArray[(string)$fieldCounter] = $fields[$fieldCounter];
                        }

                        $fieldCounter++;
                    }

                    $callback($rowArray, $lineNumber);
                } else {
                    $callback($fields, $lineNumber);
                }
            }
        }, $limit, $offset);
    }

    /**
     * @param string[] $headerField
     */
    public function setHeaderFields(array $headerField): self
    {
        $this->headerFields = $headerField;

        return $this;
    }

    public function setFirstLineIsHeader(bool $firstLineIsHeader): self
    {
        $this->firstLineIsHeader = $firstLineIsHeader;

        return $this;
    }

    public function setIgnoreEmptyLines(bool $ignoreEmptyLines): self
    {
        $this->ignoreEmptyLines = $ignoreEmptyLines;

        return $this;
    }
}

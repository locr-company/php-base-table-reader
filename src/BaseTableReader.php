<?php

declare(strict_types=1);

namespace Locr\Lib;

/**
 * @property-read int $FieldsCount Gets the number of fields of the table
 * @property-read string $Filename Gets the filename, that was load for this table
 * @property-read bool $FirstLineIsHeader If true, the first line will be handled as the header
 * @property-read array<int, string> $HeaderFields Gets the header field, if there are any
 * @property-read bool $IgnoreEmptyLines If true, then lines with no content, will be skipped
 */
abstract class BaseTableReader implements ITableReader
{
    /**
     * @see self::FieldsCount
     */
    protected int $fieldsCount = 0;
    /**
     * @see self::Filename
     */
    protected string $filename = '';
    /**
     * @see self::FirstLineIsHeader
     */
    protected bool $firstLineIsHeader = false;
    /**
     * @var array<int, string>
     * @see self::HeaderFields
     */
    protected array $headerFields = [];
    /**
     * @see self::IgnoreEmptyLines
     */
    protected bool $ignoreEmptyLines = true;

    /**
     * @internal
     */
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
     * Reads all datasets and returns them as an array.
     *
     * ```php
     * <?php
     *
     * use Locr\Lib\CsvReader; // for an example implementation of this abstract class!
     *
     * $csvReader = new CsvReader();
     * $csvReader->loadFile('file.csv');
     * $rows = $csvReader->readDatasets();
     * print count($rows); // example: 20
     * ```
     *
     * @return array<int, array<string>>
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

    /**
     * Reads all datasets and returns them as an array.
     *
     * ```php
     * <?php
     *
     * use Locr\Lib\CsvReader; // for an example implementation of this abstract class!
     *
     * $csvReader = new CsvReader();
     * $csvReader->loadFile('file.csv');
     * $count = $csvReader->readDatasetsCallback(function (array $row, int $line) {
     *  // do something with the data!
     * });
     * print $count; // example: 20
     * ```
     *
     * @param callable(array<string>, int): void $callback
     */
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
     * If true, the first line will be handled as the header
     */
    public function setFirstLineIsHeader(bool $firstLineIsHeader): self
    {
        $this->firstLineIsHeader = $firstLineIsHeader;

        return $this;
    }

    /**
     * Here you can set a custom header for the table
     *
     * ```php
     * <?php
     *
     * use Locr\Lib\CsvReader; // for an example implementation of this abstract class!
     *
     * $csvReader = new CsvReader();
     * $csvReader->loadFile('file.csv');
     * $csvReader->setHeaderFields(['id', 'country', 'state', 'city', 'postal', 'street']);
     * $rows = $csvReader->readDatasets();
     * print $rows[0]['id']; // example: 1
     * print $rows[0]['country']; // example: DE
     * print $rows[0]['city']; // example: Braunschweig
     * ```
     *
     * @param array<int, string> $headerField
     */
    public function setHeaderFields(array $headerField): self
    {
        $this->headerFields = $headerField;

        return $this;
    }

    /**
     * If true, then lines with no content, will be skipped
     */
    public function setIgnoreEmptyLines(bool $ignoreEmptyLines): self
    {
        $this->ignoreEmptyLines = $ignoreEmptyLines;

        return $this;
    }
}

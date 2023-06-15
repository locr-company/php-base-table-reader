***

# BaseTableReader





* Full name: `\Locr\Lib\BaseTableReader`
* This class implements:
[`\Locr\Lib\ITableReader`](./ITableReader.md)
* This class is an **Abstract class**




## Methods


### readDatasets

Reads all datasets and returns them as an array.

```php
public readDatasets(int $limit = -1, int $offset = -1): array&lt;int,string[]&gt;
```

```php
<?php

use Locr\Lib\CsvReader; // for an example implementation of this abstract class!

$csvReader = new CsvReader();
$csvReader->loadFile('file.csv');
$rows = $csvReader->readDatasets();
print count($rows); // example: 20
```






**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$limit` | **int** |  |
| `$offset` | **int** |  |




***

### readDatasetsCallback

Reads all datasets and returns them as an array.

```php
public readDatasetsCallback(callable $callback, int $limit = -1, int $offset = -1): int
```

```php
<?php

use Locr\Lib\CsvReader; // for an example implementation of this abstract class!

$csvReader = new CsvReader();
$csvReader->loadFile('file.csv');
$count = $csvReader->readDatasetsCallback(function (array $row, int $line) {
 // do something with the data!
});
print $count; // example: 20
```






**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$callback` | **callable** |  |
| `$limit` | **int** |  |
| `$offset` | **int** |  |




***

### setFirstLineIsHeader

If true, the first line will be handled as the header

```php
public setFirstLineIsHeader(bool $firstLineIsHeader): self
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$firstLineIsHeader` | **bool** |  |




***

### setHeaderFields

Here you can set a custom header for the table

```php
public setHeaderFields(array&lt;int,string&gt; $headerField): self
```

```php
<?php

use Locr\Lib\CsvReader; // for an example implementation of this abstract class!

$csvReader = new CsvReader();
$csvReader->loadFile('file.csv');
$csvReader->setHeaderFields(['id', 'country', 'state', 'city', 'postal', 'street']);
$rows = $csvReader->readDatasets();
print $rows[0]['id']; // example: 1
print $rows[0]['country']; // example: DE
print $rows[0]['city']; // example: Braunschweig
```






**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$headerField` | **array<int,string>** |  |




***

### setIgnoreEmptyLines

If true, then lines with no content, will be skipped

```php
public setIgnoreEmptyLines(bool $ignoreEmptyLines): self
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$ignoreEmptyLines` | **bool** |  |




***


***
> Automatically generated from source code comments on 2023-06-15 using [phpDocumentor](http://www.phpdoc.org/) and [saggre/phpdocumentor-markdown](https://github.com/Saggre/phpDocumentor-markdown)

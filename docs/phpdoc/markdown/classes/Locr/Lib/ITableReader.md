***

# ITableReader





* Full name: `\Locr\Lib\ITableReader`



## Methods


### loadFile



```php
public loadFile(string $filename): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$filename` | **string** |  |




***

### readDatasets



```php
public readDatasets(int $limit = -1, int $offset = -1): array&lt;int,string[]&gt;
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$limit` | **int** |  |
| `$offset` | **int** |  |




***

### readDatasetsCallback



```php
public readDatasetsCallback(callable $callback, int $limit = -1, int $offset = -1): int
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$callback` | **callable** |  |
| `$limit` | **int** |  |
| `$offset` | **int** |  |




***


***
> Automatically generated from source code comments on 2023-06-15 using [phpDocumentor](http://www.phpdoc.org/) and [saggre/phpdocumentor-markdown](https://github.com/Saggre/phpDocumentor-markdown)

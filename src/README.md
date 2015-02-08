#WebServices

Class library to ease working with web services.

###Dependencies
 * `Buzz`, `Guzzle`, `Requests`, or `fopen_allow_url`
 
###Example

####Calling a single web service

1. First, set up the web service `Client` with a request adapter:

```php
use xpl\WebServices\Client;
use xpl\WebServices\Client\FopenAdapter;
use xpl\WebServices\Yahoo\Yql\Request as YqlRequest;

$client = new Client(new FopenAdapter); // requires INI fopen_allow_url = 1
```

2. Create a new request:

```php
$yql_request = new YqlRequest("select name from yahoo.finance.sectors");
```

3. Execute the request by invoking the client and passing it the object:

$yql_response = $client($yql_request);
```

4. Fetch the results from the response:

```php
$results = $yql_response->getResults();

var_dump( $results );
```

This should output:
```php
array (size=9)
  0 => 
    object(stdClass)[33]
      public 'name' => string 'Basic Materials' (length=15)
  1 => 
    object(stdClass)[38]
      public 'name' => string 'Conglomerates' (length=13)
  2 => 
    object(stdClass)[39]
      public 'name' => string 'Consumer Goods' (length=14)
  3 => 
    object(stdClass)[40]
      public 'name' => string 'Financial' (length=9)
  4 => 
    object(stdClass)[41]
      public 'name' => string 'Healthcare' (length=10)
  5 => 
    object(stdClass)[42]
      public 'name' => string 'Industrial Goods' (length=16)
  6 => 
    object(stdClass)[43]
      public 'name' => string 'Services' (length=8)
  7 => 
    object(stdClass)[44]
      public 'name' => string 'Technology' (length=10)
  8 => 
    object(stdClass)[45]
      public 'name' => string 'Utilities' (length=9)
```      

####Calling multiple web services

You can issue multiple requests simultaneously if your adapter supports it (currently, only `Requests`).

1. First, set up the web service `Client` with a supported request adapter:

```php
use xpl\WebServices\Client;
use xpl\WebServices\Client\RequestsAdapter;

$client = new Client(new RequestsAdapter); // requires rmccue/requests package
```

2. Then, set up an array of requests. Give each a unique key so that it can be identified later.

```php
use xpl\WebServices\Yahoo\Yql\Request as YqlRequest;
use xpl\WebServices\Yahoo\Pipes\Request as PipeRequest;
use xpl\WebServices\Yahoo\Finance\Request\HistoricalQuote;

$yql_request = new YqlRequest('select name from yahoo.finance.sectors');
$pipe_request = new PipeRequest('9e88fc312b261410c127954bdd705372');
$finance_request = new HistoricalQuote('GE');
$finance_request->setStartDate('2013-12-31');

$requests = array(
	'yql' => $yql_request,
	'pipe' => $pipe_request,
	'finance' => $finance_request,
);
```

3. Pass the array to the client's `multi()` method. This should return an array of responses with the same keys (although, possibly in a different order):

```php
$responses = $client->multi($requests);
```

4. The responses can be retrieved from the array using the same key used to identify the request:

```php
$yql_response = $responses['yql'];
$pipe_response = $responses['pipe'];
// ...
```

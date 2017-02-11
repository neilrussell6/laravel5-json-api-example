JSON API Specs
==============

Content Negotiation :: Client Responsibilities
----------------------------------------------

[JSON API docs :: Client Responsibilities](http://jsonapi.org/format/#content-negotiation-clients)

#### Request Content-Type Header

> **Specs:**
> "Clients MUST send all JSON API data in request documents with the header Content-Type: application/vnd.api+json without any media type parameters."

> **Interpretation:**
> Client MUST always include header: `Content-Type: application/vnd.api+json`.

Tests:

* tests/api/api/api__GET__request_headers.Cept.php #1

#### Request Accept Header

> **Specs:**
> "Clients that include the JSON API media type in their Accept header MUST specify the media type there at least once without any media type parameters."

> **Interpretation:**
> Client CAN OPTIONALLY include header: `Accept: application/vnd.api+json`.
> IF client includes header `Accept: application/vnd.api+json`, then it MUST NOT contain any media type parameters.

Tests:

* tests/api/api/api__GET__request_headers.Cept.php #2
* tests/api/api/api__GET__request_headers.Cept.php #3

Content Negotiation :: Server Responsibilities
----------------------------------------------

[JSON API docs :: Server Responsibilities](http://jsonapi.org/format/#content-negotiation-servers)
[StackOverflow](http://stackoverflow.com/questions/33485488/json-api-spec-server-responsibilities-explanation)

#### Response Content-Type Header

> **Specs:**
> "Servers MUST send all JSON API data in response documents with the header Content-Type: application/vnd.api+json without any media type parameters."

Tests:

* tests/acceptance/api/api__GET__response_headers.Cept.php #1

#### Content-Type Header Media Parameters

> **Specs:**
> "Servers MUST respond with a 415 Unsupported Media Type status code if a request specifies the header Content-Type: application/vnd.api+json with any media type parameters."

Tests:

* tests/api/api/api__GET__request_headers.Cept.php #4

#### Accept Header

> **Specs:**
> "Servers MUST respond with a 406 Not Acceptable status code if a request’s Accept header contains the JSON API media type and all instances of that media type are modified with media type parameters."

Tests:

* tests/api/api/api__GET__request_headers.Cept.php #5

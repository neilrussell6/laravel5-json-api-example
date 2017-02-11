Document Structure
==================

Top Level
---------

[JSON API docs :: Top Level](http://jsonapi.org/format/#document-top-level)

#### JSON API & version

> **Specs:**
> "A document MAY contain ... jsonapi: an object describing the server’s implementation."

Tests:

* tests/api/api/api__GET__endpoint__response_structure__top_level.Cept.php #1

#### Links

> **Specs:**
> "A document MAY contain ... links: a links object related to the primary data."

Tests:

* tests/api/api/api__GET__endpoint__response_structure__top_level.Cept.php #2

#### Links :: self

> **Specs:**
> "The top-level links object MAY contain ... self: the link that generated the current response document."

Tests:

* tests/api/api/api__GET__endpoint__response_structure__top_level.Cept.php #3

#### Links :: related

> **Specs:**
> "The top-level links object MAY contain ... related: a related resource link when the primary data represents a resource relationship."

Tests:

* tests/api/api/api__GET__endpoint__response_structure__top_level.Cept.php #4

#### Primary Data

> **Specs:**
> "The document’s “primary data” is a representation of the resource or collection of resources targeted by a request."
> 
> "Primary data MUST be either:
> 
> * a single resource object, a single resource identifier object, or null, for requests that target single resources
> * an array of resource objects, an array of resource identifier objects, or an empty array ([]), for requests that target resource collections"

Tests:

* tests/api/api/api__GET__endpoint__response_structure__top_level.Cept.php #5

Resource Objects
----------------

[JSON API docs :: Resource Objects](http://jsonapi.org/format/#document-resource-objects)

#### id & type

> **Specs:**
> "A resource object MUST contain ... id."
> "A resource object MUST contain ... type."

Tests:

* tests/api/api/api__GET__endpoint__response_structure__resource_objects.Cept.php #1

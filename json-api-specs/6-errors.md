Errors
======

Processing Errors
-----------------

[JSON API docs :: Processing Errors](http://jsonapi.org/format/#errors-processing)

> **Specs:**
> "A server MAY choose to stop processing as soon as a problem is encountered, or it MAY continue processing and encounter multiple problems"

> **Interpretation:**
> This API will stop processing on all but failed attribute validation errors.

> **Specs:**
> "When a server encounters multiple problems for a single request, the most generally applicable HTTP error code SHOULD be used in the response. For instance, 400 Bad Request might be appropriate for multiple 4xx errors."

> **Interpretation:**
> This API will do the following:
> * If all errors have the same status code that code will be returned, eg.
>   * 403, 403 & 403 = 403
> * If there are multiple different errors, then the error closest to the majority will be returned, eg.
>   * 403, 403 & 422 = 400 (not 403)
>   * 422, 403 & 503 = 400
>   * 422, 501 & 503 = 500

Error Objects
-------------

[JSON API docs :: Error Objects](http://jsonapi.org/format/#error-objects)

#### Request Content-Type Header

> **Specs:**
> ""

Tests:

* tests/api/api/api__GET__response_error_objects.Cept.php #
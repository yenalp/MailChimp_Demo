<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Classes\AppContext;
use App\Http\Formatters\FormatterFactory;

/**
* This examines the incoming request to choose the correct formatter for request
* unwrapping and response wrapping(these can be explicitly enforced by passing the
* optional format parameters on the route when specifiying the middleware if required).
*
* It's pretty basic but could easily be extended for other JSON or non-JSON formats.
* This allows the services and controllers to be decoupled from what the response will
* be returned as, or what format a request was made in.  It essentially adds a
* formatting layer around the controllers so they behave as they would normally and
* here you can tranform the response to anything from CSV, JSON, XML, YAML etc. without
* needing to alter any controllers.
*
* EXAMPLE: if providing data in alternate formats you could simply check the headers
* for the requested content type here and then simply load the required formatters.
*/

class ApiRequestFormat
{
    use \App\Traits\KeyPath;

    public function handle(Request $request, Closure $next, $responseFormat = null, $requestFormat = null)
    {
        $context = app()->make('AppContext');

        $requestFormatter = $this->getFormatter($requestFormat, $request);
        $responseFormatter = $this->getFormatter($responseFormat, $request);

        // Allow loading full data or minimal data based on query param
        if ($request->query('serilize_full')) {
            $responseFormatter->serialiseFull = $request->query('serilize_full');
        }
        

        $context->setFormatters($requestFormatter, $responseFormatter);
        return $next($request);
    }

    public function getFormatter($format, $request)
    {
        if ($format !== null) {
            return FormatterFactory::getFormatter($format);
        }

        $assumedFormat = 'attributes';

        // Attempt to get from request
        $data = $request->get('jsonapi');
        if ($data) {
            $assumedFormat = $this->getValueAtPath($data, 'format', 'v1');
        }

        return FormatterFactory::getFormatter($assumedFormat);
    }
}

<?php
namespace App\Traits;

trait Contextualisable
{
    /**
    * A chainable method for eloquent models that accepts query being chained and an
    * AppContext object.  This just return the query but should be included on your
    * base model and then overridden in subclasses to add things like restricting
    * results by the current user etc.
    *
    * The base controller will call this before returning data, this is just the stub
    * so the base controller can safely call it and you only need to override it if
    * necessary.
    *
    * Warning suppressed because this is intended to be overridden
    *
    * @SuppressWarnings(PHPMD.UnusedFormalParameter)
    */
    public function scopeInContext($query, \App\Classes\AppContext $context)
    {
        return $query;
    }
}

<?php
namespace App\Traits;

use \Exception;

trait SecuredRelationships
{

    use KeyPath;

    /*
    * EXAMPLE:
    * Implement the getRelationAccess method in the class using this trait.
    *
    * public function getRelationAccess() {
    *    return [
    *        "vendors" => [
    *            "ADMIN" => [
    *                "read" => true
    *            ],
    *            "CUSTOMERS" => [
    *                "read" => false
    *            ]
    *        ],
    *        "products" => [
    *            "ADMIN" => array(
    *                "conditions" => function($model, $context, $query) {
    *                    $query->where('active', '=', false);
    *                }
    *            )
    *            "CUSTOMERS" => array(
    *                "conditions" => function($model, $context, $query) {
    *                    $query->where('active', '=', true);
    *                }
    *            )
    *        ]
    *    ];
    * }
    */
    public function getRelationAccess()
    {
        return [];
    }

    /*
    * Overrides the eloquent hasMany and applies access control defined in the
    * getRelationAccess method
    */
    public function hasMany($related, $foreignKey = null, $localKey = null)
    {
        return $this->canAccess(parent::hasMany($related, $foreignKey, $localKey));
    }

    /*
    * Should be called from the method that defines an eloquent relationship.
    * It will automatically determine the relationship being called and checks
    * configuration returned from getRelationAccess.
    */
    public function canAccess($relationShip)
    {
        $dbt = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 4);
        $cardinality = (count($dbt) > 2 && isset($dbt[1]['function'])) ? $dbt[1]['function'] : null;
        $relation = (count($dbt) > 3 && isset($dbt[2]['function'])) ? $dbt[2]['function'] : null;
        $className = get_class();
        if (!$relation) {
            Log::warn('Could not determine relationship name for class ' . $className);
            return $relationShip;
        }

        $context = app()->make('AppContext');

        $relAccess = $this->getRelationAccess();
        if ($this->getValueAtPath($relAccess, "{$relation}.ALL", false) !== false) {
            $this->checkForRole('ALL', $context, $className, $relation, $cardinality, $relAccess, $relationShip);
        }

        $this->checkForRole(
            $context->getRole(),
            $context,
            $className,
            $relation,
            $cardinality,
            $relAccess,
            $relationShip
        );

        return $relationShip;
    }

    public function checkForRole($role, $context, $className, $relation, $cardinality, $relAccess, $relationShip)
    {
        $defaultAccess = ($role === 'ALL') ? true : false;
        $accessible = $this->getValueAtPath($relAccess, "{$relation}.{$role}.read", $defaultAccess);

        if ($accessible === false) {
            $msg = "The current context \"{$role}\" does not have ";
            $msg .= " access to the relation \"{$className}\" ==> \"{$cardinality} ==> {$relation}\"";
            $msg .= " read access == \"{$accessible}\"";
            throw new Exception($msg);
        }

        $conditions = $this->getValueAtPath($relAccess, "{$relation}.{$role}.conditions", false);
        if ($conditions) {
            $conditions($this, $context, $relationShip);
        }
    }
}

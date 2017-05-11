<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Exception;
use Auth;
use Laravel\Scout\Searchable;
use App\Traits\ApiSerialisableInterface;

/**
 *  @SuppressWarnings(PHPMD.NumberOfChildren)
 */
class BaseModel extends Model implements ApiSerialisableInterface
{
    use \App\Traits\ApiSerialisable;
    use \App\Traits\Contextualisable;

    protected $modelLogMessage = '';
    protected $modelType = '';
    protected $fieldsToLog = [];
    protected $domain = '';
    // public $activityLogService;
    public static $logAllModelChanges = true;
    protected $dirtyFieldsToLog = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        // $this->activityLogService = app('ActivityLogService');
    }

    public function appendExclusionQuery($query)
    {
        return $query;
    }

    public function getModelLogDescription()
    {
        return str_replace('_', ' ', title_case($this->modelType));
    }

    /**
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     */
    public function update(array $attributes = [], array $options = [], $isLoggingOnSave = true)
    {
        return $this->fill($attributes)->save($options, $isLoggingOnSave);
    }

    public function fill(array $attributes)
    {
        $totallyGuarded = $this->totallyGuarded();

        foreach ($this->fillableFromArray($attributes) as $key => $value) {
            $key = $this->removeTableFromKey($key);

            // The developers may choose to place some attributes in the "fillable"
            // array, which means only those attributes may be set through mass
            // assignment to the model, and all others will just be ignored.
            if ($this->isFillable($key)) {
                $this->setAttribute($key, $value);
            } elseif ($totallyGuarded) {
                throw new MassAssignmentException($key);
            }
        }

        return $this;
    }

        /**
         * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
         */
    public function save(array $options = [], $isLoggingOnSave = true)
    {
        if (!self::$logAllModelChanges) {
            return parent::save($options);
        }

        if (!$isLoggingOnSave) {
            return parent::save($options);
        }


        $this->dirtyFieldsToLog = $this->fieldsToLog;
        $beforeData = $this->getOriginal();

        $id = isset($beforeData['id']) ? $beforeData['id'] : '';
        $actionType = $id ? 'UPDATE' : 'CREATE';

        if ($id) {
            $this->dirtyFieldsToLog = [];
            foreach ($this->fieldsToLog as $field) {
                if ($this->isDirty($field)) {
                    array_push($this->dirtyFieldsToLog, $field);
                }
            }
        }

        $saved = parent::save($options);

         // Log message
        // $this->logChange($beforeData, $this->toArray(), $actionType);
        //
        return $saved;
    }

    public function delete($action = 'DELETE')
    {
        $beforeData = $this->getOriginal();
        $deleted = parent::delete();
        // $this->logChange($beforeData, [], $action);
        return $deleted;
    }

    public function archive()
    {
        $this->delete('ARCHIVED');
    }
    protected function logChange($beforeData, $afterData, $actionType, $additionalInfo = null)
    {
        $this->modelType = strtoupper(snake_case((new \ReflectionClass($this))->getShortName()));
        $id = isset($afterData['id']) ? $afterData['id'] : null;
        $modelLogDescription = $this->getModelLogDescription();
        $actionDesc = config("constants.ACTIVITY_LOG.TYPE_CODE.{$actionType}");
        $actionMessage = title_case("{$actionDesc}");
        $actionMessage .= " {$modelLogDescription} ";
        $logMessage = "{$actionMessage}. ";

        if ($this->modelLogMessage) {
            $logMessage .= $this->modelLogMessage;
        }

        $changeMsgs = $this->buildFieldChangeMessage($beforeData, $actionType);
        if ($changeMsgs) {
            $logMessage .= $changeMsgs;
        }

        if ($additionalInfo) {
            $logMessage .= ' ' . $additionalInfo;
        }

        $this->activityLogService->logActivity(
            $logMessage,
            $actionType,
            $this->modelType,
            $beforeData,
            $afterData,
            $id,
            $this->domain
        );
    }

    protected function buildFieldChangeMessage($beforeData, $actionType)
    {
        if ($actionType === 'CREATE') {
            return $this->buildModelCreateMessages();
        }
        return $this->buildFieldUpdateMessages($beforeData);
    }

    protected function buildFieldUpdateMessages($beforeData)
    {
        if (count($this->dirtyFieldsToLog) === 0) {
            return '';
        }
        $changeMessages = [];
        foreach ($this->dirtyFieldsToLog as $field) {
            $fieldMessage = '';
            $beforeDesc = '';

            $beforeValue = $beforeData[$field];
            $beforeDesc = "\"{$beforeValue}\" to ";
            $fieldDesc = str_replace('_', ' ', title_case($field));
            $fieldMessage = $fieldDesc . ' from ' . $beforeDesc .'"' . $this[$field] . '"';
            array_push($changeMessages, $fieldMessage) ;
        }
        if (count($changeMessages) === 0) {
            return '';
        }
        return  'Changed - ' . implode($changeMessages, ', ');
    }

    protected function buildModelCreateMessages()
    {
        if (count($this->dirtyFieldsToLog) === 0) {
            return '';
        }
        $changeMessages = [];
        foreach ($this->dirtyFieldsToLog as $field) {
            $fieldMessage = '';

            $fieldDesc = str_replace('_', ' ', title_case($field));
            $fieldMessage = $fieldDesc . ' set to ' .'"' . $this[$field] . '"';
            array_push($changeMessages, $fieldMessage) ;
        }

        return implode($changeMessages, ', ');
    }
}

<?php
namespace Page\Admin\Owner;

use Page\DataTable;
use Page\Button;

class View extends BaseAdminOwnerPage
{
    use \Page\Traits\ViewPage;

    public $dataTableFaciltiy;
    public $dataTableCategory;

    // include url of current page
    public static $URL = '#/owner';

    public static $containerId = "#owner";
    public $tableFacilityId = "table-facility";
    public $tableCategoryId = "table-owner-categories";

    public function __construct(\AdminAcceptanceTester $I)
    {
        parent::__construct($I);
        $containerId = self::$containerId;
        $this->dataTableFaciltiy = new DataTable($I, $this->tableFacilityId);
        $this->dataTableCategory = new DataTable($I, $this->tableCategoryId);
    }

    public function waitUntilLoaded($timeout = 10)
    {
        parent::waitUntilLoaded($timeout);
        $this->dataTableFaciltiy->waitUntilLoaded($timeout);
        $this->dataTableCategory->waitUntilLoaded($timeout);
    }

    public function selectFacilityBy($columnName, $value)
    {
        $this->dataTableFaciltiy->selectByColumnValue($columnName, $value);
    }

    public function getUrl($params)
    {
        return static::$URL . "/{$params['ownerId']}";
    }

    public function countFacilityRows()
    {
        $this->dataTableFaciltiy->waitUntilLoaded();
        return $this->dataTableFaciltiy->getTotalRows();
    }

    public function countCategoryRows()
    {
        $this->dataTableCategory->waitUntilLoaded();
        return $this->dataTableCategory->getTotalRows();
    }
}

<?php
namespace Page\Admin\Owner;

use Page\DataTable;
use Page\Button;

class ListTable extends BaseAdminOwnerPage
{

    use \Page\Traits\ListTablePage;

    public $dataTable;
    public $tableId = "table-owners";
    public static $URL = '#/owner';

    public function __construct(\AdminAcceptanceTester $I)
    {
        parent::__construct($I);
        $this->hasSpinner = false;
        $this->createButton = new Button($I, '#create-entity-btn');
        $this->dataTable = new DataTable($I, $this->tableId);
        $this->dataTable->currentSortColumn = 'name';
        $this->dataTable->currentSortDirection = 'alphabetical';
    }

    public function waitUntilLoaded($timeout = 10)
    {
        parent::waitUntilLoaded($timeout);
        $this->dataTable->waitUntilLoaded($timeout);
    }
}

<?php
namespace Page\Admin\Tool;

use Page\Admin\Tool\BaseAdminToolPage;
use Page\DataTable;

class ListTable extends BaseAdminToolPage
{
    public $tableId = "table-tools";
    public $dataTable;

    public function __construct(\AdminAcceptanceTester $I)
    {
        parent::__construct($I);
        $this->hasSpinner = false;
        $this->dataTable = new DataTable($I, $this->tableId);
    }

    public function waitUntilLoaded($timeout = 10)
    {
        parent::waitUntilLoaded($timeout);
        $this->dataTable->waitUntilLoaded($timeout);
    }
}

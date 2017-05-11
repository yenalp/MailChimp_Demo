<?php
namespace Page\Admin\User;

use Page\DataTable;
use Page\Button;

class ListTable extends BaseAdminUserPage
{

    use \Page\Traits\ListTablePage;

    public $dataTable;
    public $tableId = "table-users";
    public static $URL = '#/user';

    public function __construct(\AdminAcceptanceTester $I)
    {
        parent::__construct($I);
        $this->hasSpinner = false;
        $this->createButton = new Button($I, '#create-user-button');
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

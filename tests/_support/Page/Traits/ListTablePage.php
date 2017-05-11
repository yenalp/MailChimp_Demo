<?php
namespace Page\Traits;

trait ListTablePage
{
    public $dataTable;
    public $createButton;

    public function chooseCreate()
    {
        $this->createButton->click();
    }

    public function waitUntilLoaded($timeout = 10)
    {
        parent::waitUntilLoaded($timeout);
        $this->dataTable->waitUntilLoaded($timeout);
        $this->createButton->waitUntilLoaded($timeout);
    }

    public function columnContains($columnName, $value)
    {
        $this->dataTable->waitUntilLoaded();
        $this->dataTable->columnContains($columnName, $value);
    }

    public function getRowCount()
    {
        return  $this->dataTable->getTotalRows();
    }

    public function clickOnColumnHeader($columnName)
    {
        return $this->dataTable->clickColumnHeader($columnName);
    }

    public function columnNameSortedBy($columnName, $sortDirection)
    {
        return $this->dataTable->checkSortColumnByDirection($columnName, $sortDirection);
    }

    public function sortColumn($columnName, $sortDirection)
    {
        return $this->dataTable->sortByColumn($columnName, $sortDirection);
    }

    public function selectListItemBy($columnName, $value)
    {
        $this->dataTable->selectByColumnValue($columnName, $value);
    }
}

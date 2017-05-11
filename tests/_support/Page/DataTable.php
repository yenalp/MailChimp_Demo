<?php
namespace Page;

class DataTable extends HtmlTable
{
    public $tableId;
    public $currentSortColumn;
    public $currentSortDirection;

    public function __construct(\Codeception\Actor $I, $tableId)
    {
        parent::__construct($I, "#{$tableId}");
        $this->tableId = $tableId;
    }

    public function waitUntilLoaded($timeout = 10)
    {
        $this->tester->waitForElement("#{$this->tableId}", $timeout);
        $this->tester->waitForElementNotVisible("#{$this->tableId}_wrapper .dataTables_processing", $timeout);
    }


    public function sortByColumn($name, $direction)
    {
        if ($this->currentSortColumn === $name && $this->currentSortDirection === $direction) {
            return;
        }

        $this->clickColumnHeader($name);
        $this->waitUntilLoaded();
        $this->currentSortColumn = $name;
        $this->currentSortDirection = $direction;
    }

    public function checkSortColumnByDirection($columnName, $sortDirection)
    {
        $columnValues = $this->getColValues($columnName);
        $columnValuesSorted = $columnValues;

        switch ($sortDirection) {
            case "alphabetical":
                asort($columnValuesSorted);
                break;
        }

        if ($columnValuesSorted === $columnValues) {
            return true;
        }
        return false;
    }
}

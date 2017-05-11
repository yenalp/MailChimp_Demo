<?php
namespace Page;

class HtmlTable
{
    public $columnNames = null;

    public $selector;
    protected $tester;
    public $loadedClass;

    public function __construct(\Codeception\Actor $I, $selector)
    {
        $this->tester = $I;
        $this->selector = $selector;
        $this->loadedClass = "loaded";
    }

    public function waitUntilLoaded($timeout = 10)
    {
        $this->tester->waitForElement("{$this->selector}", $timeout);
        $this->tester->waitForClass($this->selector, $this->loadedClass, $timeout);
    }

    public function selectFirstItem()
    {
        $this->tester->seeElement("{$this->selector} tbody tr:first-child");
        $this->tester->click("{$this->selector} tbody tr:first-child");
    }

    public function selectLastItem()
    {
        $this->tester->seeElement("{$this->selector} tbody tr:last-child");
        $this->tester->click("{$this->selector} tbody tr:last-child");
    }

    public function selectByIndex($index)
    {
        $oneBased = $index + 1;
        $this->tester->seeElement("{$this->selector} tbody tr:nth-child({$oneBased})");
        $this->tester->click("{$this->selector} tbody tr:nth-child({$oneBased})");
    }

    protected function mapNamesToIndex()
    {
        $this->columnNames = $this->getColumnNames();
    }

    public function getColumnNames()
    {
        return $this->tester->grabMultiple("{$this->selector} th");
    }

    public function clickColumnHeader($columnName)
    {
        $this->waitUntilLoaded();
        $oneIndex = $this->getColumnIndexByName($columnName) + 1;
        $this->tester->click("{$this->selector} th:nth-child({$oneIndex})");
        $this->waitUntilLoaded();
    }

    public function columnContains($columnName, $value)
    {
        $colVals = $this->getColValues($columnName);
        if (array_search($value, $colVals) !== false) {
            return true;
        }
        return false;
    }

    public function getColumnIndexByName($columnName)
    {
        $cols = array_map('strtolower', $this->getColumnNames());
        $columnNameNew = str_replace('_', ' ', $columnName);
        $colIndex = array_search(strtolower($columnNameNew), $cols);
        if ($colIndex === false) {
            throw new \Exception("The column '{$columnNameNew}' was not found in table {$this->selector}");
        }

        return $colIndex;
    }

    // @codingStandardsIgnoreStart
    public function selectByColumnValue($columnName, $value)
    {
        $colVals = array_map('strtolower', $this->getColValues($columnName));
        $rowIndex = array_search(strtolower($value), $colVals);
        if ($rowIndex === false) {
            throw new \Exception("The value '{$value}' was not found in column '{$columnName}' was not found in table {$this->selector}");
        }

        return $this->selectByIndex($rowIndex);
    }
    // @codingStandardsIgnoreEnd

    protected function getColValues($columnName)
    {
        $oneIndex = $this->getColumnIndexByName($columnName) +1;
        return $this->tester->grabMultiple("{$this->selector} tbody tr td:nth-child({$oneIndex})");
    }

    public function getTotalRows()
    {
        return count($this->tester->grabMultiple("{$this->selector} tbody tr"));
    }
}

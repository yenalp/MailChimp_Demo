<?php
namespace Page\Admin\ActivityLog;

use Page\DataTable;

class View
{
    // include url of current page
    public static $URL = '#/activity-log';

    public static $containerId = "#activity-logs";
    public $tableId = "table-activity-logs";

    public $dataTable;

    /**
     * Basic route example for your current URL
     * You can append any additional parameter to URL
     * and use it in tests like: Page\Edit::route('/123-post');
     */
    public static function route($param)
    {
        return static::$URL.$param;
    }

    /**
     * @var AcceptanceTester
     */
    protected $tester;


    public function __construct(\AdminAcceptanceTester $I)
    {
        $this->tester = $I;
        $containerId = self::$containerId;
        $this->dataTable = new DataTable($I, $this->tableId);

        $this->fieldIndexes = [
            'user' => 1,
            'user_type' => 2,
            'source' => 3,
            'action' => 4,
            'description' => 5,
            'created_at' => 6,
        ];

        $this->fieldSelectors = [
            'user' => "{$containerId} input[name=\"user\"]",
            'user_type' => "#user-type select",
            'source' => "#source select",
            'action' => "#action select",
            'description' => "{$containerId} input[name=\"description\"]",
            'from_date' => "{$containerId} input[name=\"from-date\"]",
            'to_date' => "{$containerId} input[name=\"to-date\"]",
        ];

        $this->columnHeaderSelectors = [
            'user' => "//*[@id=\"{$this->tableId}\"]/thead/tr/th[{$this->fieldIndexes['user']}]",
            'user_type' =>  "//*[@id=\"{$this->tableId}\"]/thead/tr/th[{$this->fieldIndexes['user_type']}]",
            'source' =>  "//*[@id=\"{$this->tableId}\"]/thead/tr/th[{$this->fieldIndexes['source']}]",
            'action' =>  "//*[@id=\"{$this->tableId}\"]/thead/tr/th[{$this->fieldIndexes['action']}]",
            'created_at' => "//*[@id=\"{$this->tableId}\"]/thead/tr/th[{$this->fieldIndexes['created_at']}]",
            'description' => "//*[@id=\"{$this->tableId}\"]/thead/tr/th[{$this->fieldIndexes['description']}]",
        ];

        $this->columnRowsSelector = [
            'user' => "//*[@id=\"{$this->tableId}\"]/tbody/tr/td[{$this->fieldIndexes['user']}]",
            'user_type' =>  "//*[@id=\"{$this->tableId}\"]/tbody/tr/td[{$this->fieldIndexes['user_type']}]",
            'source' =>  "//*[@id=\"{$this->tableId}\"]/tbody/tr/td[{$this->fieldIndexes['source']}]",
            'action' =>  "//*[@id=\"{$this->tableId}\"]/tbody/tr/td[{$this->fieldIndexes['action']}]",
            'created_at' => "//*[@id=\"{$this->tableId}\"]/tbody/tr/td[{$this->fieldIndexes['created_at']}]",
            'description' => "//*[@id=\"{$this->tableId}\"]/tbody/tr/td[{$this->fieldIndexes['description']}]",
        ];
    }

    public function canSeeFilterOptions()
    {
        $this->tester->waitForElement('#filter-card', 5);

        foreach ($this->fieldSelectors as $key => $value) {
            $this->tester->waitForElement($value, 30);
        }

        $this->tester->see('Filter Results');
    }

    public function canSeeLogsInTable($numberOfRows)
    {
        $this->tester->waitForElement("#{$this->tableId} tbody tr", 5);
        $this->tester->seeNumberOfElements("#{$this->tableId} tbody tr", $numberOfRows);
    }

    public function clickColumnHeader($header)
    {
        $this->tester->click($this->columnHeaderSelectors[$header]);
        $this->tester->wait(1);
    }

    public function checkLogsOrder($order, $field)
    {
        $values = $this->tester->grabMultiple($this->columnRowsSelector[$field]);
        $sorted = $values;

        if ($order === 'alphabetical') {
            asort($sorted);
        } elseif ($order === 'reversed alphabetical') {
            arsort($sorted);
        } elseif ($order === 'date') {
            usort($sorted, function ($a, $b) {
                return strtotime($a) - strtotime($b);
            });
        } elseif ($order === 'reversed date') {
            usort($sorted, function ($a, $b) {
                return strtotime($a) - strtotime($b);
            });
        }

        $this->tester->assertTrue(($sorted === $values));
    }

    public function fillField($name, $value)
    {
        $this->tester->fillField($this->fieldSelectors[$name], $value);
        $this->tester->wait(5);
    }

    public function selectOption($name, $value)
    {
        $this->tester->selectOption($this->fieldSelectors[$name], $value);
        $this->tester->wait(3);
    }

    public function checkLogsRowValues($field, $count, $value)
    {
        $values = $this->tester->grabMultiple($this->columnRowsSelector[$field]);
        $counts = array_count_values($values);
        $this->tester->assertTrue($counts[$value] === (int)$count);
    }

    public function checkLogsRowUniqueValues($field, $value)
    {
        $values = $this->tester->grabMultiple($this->columnRowsSelector[$field]);
        $this->tester->assertTrue(count(array_unique($values)) === 1);
        $this->tester->assertTrue($values[0] === $value);
    }

    public function columnContains($columnName, $value)
    {
        $this->dataTable->waitUntilLoaded();
        $this->dataTable->columnContains($columnName, $value);
    }

    public function checkLogsRowDateValuesInRange($fromDate, $toDate, $field)
    {
        $values = $this->tester->grabMultiple($this->columnRowsSelector[$field]);

        foreach ($values as $value) {
            $dateOnly = date('Y-m-d', strtotime($value));
            $this->tester->assertTrue(strtotime($dateOnly) >= strtotime($fromDate));
            $this->tester->assertTrue(strtotime($dateOnly) <= strtotime($toDate));
        }
    }
}

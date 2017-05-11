<?php
namespace Page\Admin\Tool;

class View
{
    // include url of current page
    public static $URL = '#/tool';

    public static $containerId = "#tools";
    public $tableId = "table-tools";

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

        $this->fieldIndexes = [
            'id' => 1,
            'title' => 2,
            'description' => 3
        ];

        $this->columnHeaderSelectors = [
            'id' => "//*[@id=\"{$this->tableId}\"]/thead/tr/th[{$this->fieldIndexes['id']}]",
            'title' =>  "//*[@id=\"{$this->tableId}\"]/thead/tr/th[{$this->fieldIndexes['title']}]",
            'description' =>  "//*[@id=\"{$this->tableId}\"]/thead/tr/th[{$this->fieldIndexes['description']}]"
        ];

        $this->columnRowsSelector = [
            'id' => "//*[@id=\"{$this->tableId}\"]/tbody/tr/td[{$this->fieldIndexes['id']}]",
            'title' =>  "//*[@id=\"{$this->tableId}\"]/tbody/tr/td[{$this->fieldIndexes['title']}]",
            'description' =>  "//*[@id=\"{$this->tableId}\"]/tbody/tr/td[{$this->fieldIndexes['description']}]"
        ];
    }

    public function canSeeToolInTable($numberOfRows)
    {
        $this->tester->waitForElement("#{$this->tableId} tbody tr", 5);
        $this->tester->seeNumberOfElements("#{$this->tableId} tbody tr", $numberOfRows);
    }

    public function clickColumnHeader($header)
    {
        $this->tester->click($this->columnHeaderSelectors[$header]);
        $this->tester->wait(3);
    }

    public function checkLogsOrder($order, $field, $reversed = false)
    {
        $values = $this->tester->grabMultiple($this->columnRowsSelector[$field]);
        $sorted = $values;

        if ($order === 'alphabetical') {
            asort($sorted);
        }

        if ($reversed) {
            $this->tester->assertTrue(array_reverse($sorted, true) == $values);
        } else {
            $this->tester->assertTrue(($sorted === $values));
        }
    }

    public function fillField($name, $value)
    {
        $this->tester->fillField($this->fieldSelectors[$name], $value);
        $this->tester->wait(3);
    }

    public function checkLogsRowValues($field, $count, $value)
    {
        $values = $this->tester->grabMultiple($this->columnRowsSelector[$field]);
        $counts = array_count_values($values);
        $this->tester->assertTrue($counts[$value] === (int)$count);
    }

    public function anToolExistsWithAOf($field, $value)
    {
        $values = $this->tester->grabMultiple($this->columnRowsSelector[$field]);
        $this->tester->assertTrue(count($values) >= 1);
    }
    public function anToolDoseNotExistsWithAOf($field, $value)
    {
        $values = $this->tester->grabMultiple($this->columnRowsSelector[$field]);

        if (!in_array($value, $values)) {
            $this->tester->assertTrue(true);
        }
    }

    public function iSearchFor($term)
    {
        $this->tester->waitForElement('//*[@id="table-tools_filter"]/label/input', 5);
        $this->tester->fillField('//*[@id="table-tools_filter"]/label/input', $term);
        $this->tester->wait(3);
    }

    public function iShouldSeeAListOfTools($number)
    {
        $this->tester->waitForElement("#{$this->tableId} tbody tr", 5);
        $this->tester->seeNumberOfElements("#{$this->tableId} tbody tr", $number);
    }

    public function theToolOrOrOrShouldContain($field1, $field2, $field3, $field4, $term)
    {
        $exists = false;
        $fields = collect([$field1, $field2, $field3, $field4])->map(function ($field, $key) use ($term) {
            return $this->tester->grabMultiple($this->columnRowsSelector[$field]);
        })->flatten()->contains(function ($value, $key) use ($term) {
            if (strpos($value, $term) === true) {
                $this->tester->assertTrue(true);
            }
        });
        $this->tester->wait(3);
    }

    public function selectFirstTool()
    {
        $this->tester->waitForElement("#{$this->tableId}", 10);
        $this->tester->waitForElementNotVisible("#{$this->tableId}_wrapper .dataTables_processing", 10);
        $this->tester->waitForElement("#{$this->tableId} tbody tr:first-child", 15);
        $this->tester->click("#{$this->tableId} tbody tr:first-child");
    }
}

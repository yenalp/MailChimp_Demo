<?php
namespace Page\Admin\Facility;

class ListTable
{
    // include url of current page
    public static $URL = '#/facility';

    public $tableId = "table-facilities";
    public static $createFacilityBtnSelector = "#create-facility";

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

        $this->fieldIndexes = [
            'facility_id' => 1,
            'name' => 2,
            'service_level' => 3,
            'category' => 4,
            'facility_type' => 5
        ];

        $this->columnRowsSelector = [
            'facility_id' => "//*[@id=\"{$this->tableId}\"]/tbody/tr/td[{$this->fieldIndexes['facility_id']}]",
            'name' =>  "//*[@id=\"{$this->tableId}\"]/tbody/tr/td[{$this->fieldIndexes['name']}]",
            'service_level' =>  "//*[@id=\"{$this->tableId}\"]/tbody/tr/td[{$this->fieldIndexes['service_level']}]",
            'category' =>  "//*[@id=\"{$this->tableId}\"]/tbody/tr/td[{$this->fieldIndexes['category']}]",
            'facility_type' => "//*[@id=\"{$this->tableId}\"]/tbody/tr/td[{$this->fieldIndexes['facility_type']}]",
        ];
    }

    public function createCreateLink()
    {
        $createFacilityBtnSelector = self::$createFacilityBtnSelector;
        $this->tester->click("a{$createFacilityBtnSelector}");
        $this->tester->wait(2);
    }

    public function clickEditOnALevelFacility($level)
    {
        $serviceLevels = $this->tester->grabMultiple($this->columnRowsSelector['service_level']);
        foreach ($serviceLevels as $key => $serviceLevel) {
            $rowIndex = $key + 1;
            if ((int)$serviceLevel == (int)$level) {
                $this->tester->click("//*[@id=\"{$this->tableId}\"]/tbody/tr[$rowIndex]/td[6]/a/span");
                $this->tester->wait(2);
                break;
            }
        }
    }
}

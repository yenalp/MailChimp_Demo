<?php namespace api;

// See here for details: http://codeception.com/docs/'1'0-WebServices#REST
use Codeception\Util\HttpCode;
use Illuminate\Support\Facades\Hash;
use App\Services\UserService;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
 */
class ApiTester extends \Codeception\Actor
{
    use \_generated\ApiTesterActions;

    public $siteId;
    public $baseUrl;

    /**
     * ApiTester constructor.
     * @param Scenario $scenario
     * @param String $siteId
     *
     * @SuppressWarnings("unused")
     */
    public function __construct($scenario, $siteId)
    {
        parent::__construct($scenario);
        $this->siteId = $siteId;
        $this->haveHttpHeader("X-Auth-SITE-ID", $siteId);
    }


    public function setBaseUrl($url)
    {
        $this->baseUrl = $url;
    }

    public function get($id = '', $expectedCode = 200)
    {
        $this->sendGET($this->baseUrl .'/' . $id);
        $this->seeResponseCodeIs($expectedCode);
    }

    public function delete($id, $expectedCode = 200)
    {
        $this->sendDELETE($this->baseUrl .'/' . $id);
        $this->seeResponseCodeIs($expectedCode);
    }

    public function checkFor404()
    {
        $this->seeResponseCodes(404, 'NOT_FOUND');
        $this->seeResponseContainsJson([
            'error' =>
                [
                    'status' => 404,
                    'title' => 'Not Found'
                ]
        ]);
    }

    public function seeResponseCodes($code, $msg)
    {
        $this->seeResponseCodeIs(constant("Codeception\Util\HttpCode::{$msg}"));
        $this->seeResponseCodeIs($code);
    }

    public function getEntityData($type, $id)
    {
        // Get the static data used for comparisons against
        // the JSON returned from the API dynamically from the
        // relevant Helper
        $dataFunc = "{$type}EntityData";
        $eData = $this->{$dataFunc}();
        $entityData = $eData[$id];

        // Wrap in a data element as this is the way the API will return it.
        // Could be extended for paged endpoint later as well perhaps
        return [
            'data' => [
                'attributes' => $entityData
            ]
        ];
    }

    public function loginToSite($siteId, $username, $password)
    {
        $this->haveHttpHeader("X-Auth-SITE-ID", $siteId);
        $this->sendPOST("/user/login", [
            "username" => $username,
            "password" => $password
        ]);
    }

    public function checkForToken($siteId)
    {
        $site = strtolower($siteId);
        $this->seeResponseMatchesJsonType(["token_{$site}" => 'string:regex(/[\s\S]/)'], '$.data.attributes');
    }

    public function createUser($values)
    {
        factory(\App\Models\User::class, 1)->create($values);
    }
}

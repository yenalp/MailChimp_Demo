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

    public function getReadyToRunTests($baseUrl = null, $seeder = null)
    {
        $userId = '1';
        if ($this->siteId === 'CONSULTANT') {
            $userId = '2';
        }
        $this->setAuthHeaders($userId);
        $this->refreshTheDatabase();
        if ($baseUrl !== null) {
            $this->setBaseUrl($baseUrl);
        }
        if ($seeder !== null) {
            $this->loadSeeder($seeder);
        }
    }

    public function loginAsAdmin()
    {
        $context = app()->make('AppContext');
        $context->siteId = 'ADMIN';

        $userFactory = factory(\App\Models\V2\User::class, 1)->create([
            'first_name' => 'Admin',
            'last_name' => 'Tester',
            'email' => 'admin.tester@inoutput.io',
            'user_name' => 'admin',
            'password' => Hash::make('password'),
            'disabled' => false,
            'user_type' => 'ADMIN'
        ]);

        $userService = new UserService();
        $user = $userService->login('admin', 'password');

        $this->haveHttpHeader(
            "X-Auth-Token-{$this->siteId}",
            $user->token_admin
        );
        $this->haveHttpHeader("X-Auth-Id-{$this->siteId}", $user->id);
        return $user;
    }


    public function logInAsConsultant()
    {
        $context = app()->make('AppContext');
        $context->siteId = 'CONSULTANT';

        $userFactory = factory(\App\Models\V2\User::class, 1)->create([
            'first_name' => 'Consultant',
            'last_name' => 'Tester',
            'email' => 'consultant.tester@inoutput.io',
            'user_name' => 'consultant',
            'password' => Hash::make('password'),
            'disabled' => false,
            'user_type' => 'CONSULTANT'
        ]);

        $userService = new UserService();
        $user = $userService->login('consultant', 'password');

        $this->haveHttpHeader(
            "X-Auth-Token-{$this->siteId}",
            $user->token_admin
        );
        $this->haveHttpHeader("X-Auth-Id-{$this->siteId}", $user->id);
        return $user;
    }

    public function setAuthHeaders($id = '1')
    {
        $this->haveHttpHeader(
            "X-Auth-Token-{$this->siteId}",
            'H6zbcBzg0GOnvj3oUU9cVMcQZ5NlhERdwOnal2FQclnJFFeGVNwfThS69xbAjh'
        );
        $this->haveHttpHeader("X-Auth-Id-{$this->siteId}", $id);
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


    //CODESMELL: from this line down

    public function getUserById($id, $code = 200, $msg = 'OK')
    {
        $I = $this;
        $I->haveHttpHeader(
            "X-Auth-Token-{$this->siteId}",
            'H6zbcBzg0GOnvj3oUU9cVMcQZ5NlhERdwOnal2FQclnJFFeGVNwfThS69xbAjh'
        );
        $I->haveHttpHeader("X-Auth-Id-{$this->siteId}", "1");
        $I->sendGET('/user/' . $id);
        $I->seeResponseCodes($code, $msg);
    }

    public function getDepartmentById($id, $code = 200, $msg = 'OK')
    {
        $I = $this;
        $I->haveHttpHeader(
            "X-Auth-Token-{$this->siteId}",
            'H6zbcBzg0GOnvj3oUU9cVMcQZ5NlhERdwOnal2FQclnJFFeGVNwfThS69xbAjh'
        );
        $I->haveHttpHeader("X-Auth-Id-{$this->siteId}", "1");
        $I->sendGET('/department/' . $id);
        $I->seeResponseCodes($code, $msg);
    }

    public function getFacilityById($id, $code = 200, $msg = 'OK')
    {
        $I = $this;
        $I->haveHttpHeader(
            "X-Auth-Token-{$this->siteId}",
            'H6zbcBzg0GOnvj3oUU9cVMcQZ5NlhERdwOnal2FQclnJFFeGVNwfThS69xbAjh'
        );
        $I->haveHttpHeader("X-Auth-Id-{$this->siteId}", "1");
        $I->sendGET('/facility/' . $id);
        $I->seeResponseCodes($code, $msg);
    }


    public function getOwnerById($id, $code = 200, $msg = 'OK')
    {
        $I = $this;
        $I->haveHttpHeader(
            "X-Auth-Token-{$this->siteId}",
            'H6zbcBzg0GOnvj3oUU9cVMcQZ5NlhERdwOnal2FQclnJFFeGVNwfThS69xbAjh'
        );
        $I->haveHttpHeader("X-Auth-Id-{$this->siteId}", "1");
        $I->sendGET('/owner/' . $id);
        $I->seeResponseCodes($code, $msg);
    }

    public function getToolById($id, $code = 200, $msg = 'OK')
    {
        $I = $this;
        $I->haveHttpHeader(
            "X-Auth-Token-{$this->siteId}",
            'H6zbcBzg0GOnvj3oUU9cVMcQZ5NlhERdwOnal2FQclnJFFeGVNwfThS69xbAjh'
        );
        $I->haveHttpHeader("X-Auth-Id-{$this->siteId}", "1");
        $I->sendGET('/tool/' . $id);
        $I->seeResponseCodes($code, $msg);
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

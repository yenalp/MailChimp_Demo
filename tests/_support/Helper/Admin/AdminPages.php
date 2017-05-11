<?php
namespace Helper\Admin;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

use Page\Admin\Dashboard as AdminDashboard;
use Page\Admin\Owner\Edit as EditOwnerPage;
use Page\Admin\Owner\Create as CreateOwnerPage;
use Page\Admin\Owner\ListTable as ListOwnerPage;
use Page\Admin\Owner\View as ViewOwnerPage;
use Page\Admin\Owner\ViewAdmin as ViewAdminOwnerPage;
use Page\Admin\ActivityLog\View as ViewActivityLogPage;
use Page\Admin\Facility\Create as CreateFacilityPage;
use Page\Admin\Facility\ListTable as ListFacilityPage;
use Page\Admin\Facility\View as ViewFacilityPage;
use Page\Admin\Facility\Edit as EditFacilityPage;
use Page\Admin\Login\View as ViewLoginPage;
use Page\Admin\User\View as ViewUserPage;
use Page\Admin\User\ViewAdmin as ViewAdminUserPage;
use Page\Admin\User\Create as CreateUserPage;
use Page\Admin\User\Edit as EditUserPage;
use Page\Admin\User\ListTable as ListUserPage;
use Page\Admin\Tool\View as ViewToolPage;
use Page\Admin\Tool\Create as CreateToolPage;
use Page\Admin\Tool\Edit as EditToolPage;
use Page\Admin\Tool\EditDraft as EditToolDraftPage;
use Page\Admin\Tool\ListTable as ToolListPage;
use Page\Admin\OwnerDefinedCategory\Create as OwnerDefinedCategoryPage;

class AdminPages extends \Codeception\Module
{
    public $pages = [];
    /**
     * Create the page helper instances
     */
    public function setupPages($tester)
    {
        $this->pages = [
            'owner' => [
                'edit' => new EditOwnerPage($tester),
                'list' => new ListOwnerPage($tester),
                'view' => new ViewOwnerPage($tester),
                'view>admin' => new ViewAdminOwnerPage($tester),
                'create' => new CreateOwnerPage($tester)
            ],
            'owner-defined-categories' => [
                'create' => new OwnerDefinedCategoryPage($tester)
            ],
            'dashboard' => [
                'view' => new AdminDashboard($tester)
            ],
            'activity-log' => [
                'view' => new ViewActivityLogPage($tester)
            ],
            'facility' => [
                'create' => new CreateFacilityPage($tester),
                'list' => new ListFacilityPage($tester),
                'view' => new ViewFacilityPage($tester),
                'edit' => new EditFacilityPage($tester),
            ],
            'login' => [
                'view' => new ViewLoginPage($tester)
            ],
            'user' => [
                'create' => new CreateUserPage($tester),
                'list' => new ListUserPage($tester),
                'edit' => new EditUserPage($tester),
                'view' => new ViewuserPage($tester),
                'view>admin' => new ViewAdminUserPage($tester),
            ],
            'tool' => [
                'create' => new CreateToolPage($tester),
                'view' => new ViewToolPage($tester),
                'edit' => new EditToolPage($tester),
                'edit-draft' => new EditToolDraftPage($tester),
                'list' => new ToolListPage($tester)
            ]
        ];
        $tester->pages = $this->pages;
    }


    public function getPage($pagePath)
    {
        $userType = $this->getModule('\Helper\Entities\StateHelper')->stateInfoFindOrFail('user_type');
        return $this->getValueAtPath(
            $this->pages,
            $pagePath .'>' . $userType,
            $this->getValueAtPath(
                $this->pages,
                $pagePath,
                null
            )
        );
    }

    public function getValueAtPath($obj, $path, $default = null, $deliminator = '.')
    {
        $parts = explode($deliminator, $path);
        $current = $obj;
        foreach ($parts as $part) {
            if (!$current && $current !== false) {
                return $default;
            }
            if (!isset($current[$part])) {
                return $default;
            }
            $current = $current[$part];
        }
        return $current;
    }
}

<?php

namespace App\Pages;

use Komma\KMS\Base\Policy;
use App\Pages\Models\Page;
use Illuminate\Auth\Access\HandlesAuthorization;
use Komma\KMS\Users\Models\KmsUser;
use Komma\KMS\Users\Models\KmsUserRole;

final class PagePolicy extends Policy
{
    use HandlesAuthorization;

    protected $modelClassName = Page::class;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
//        parent::$DEBUG = PagePolicy::class;
    }

    public function create(KmsUser $user): bool
    {
        $result = $user->isAtLeast(KmsUserRole::SuperAdmin);
        $this->debug('create', $result);
        return $result;
    }

    public function destroy(KmsUser $user, $modelToDestroy): bool
    {
        $result = $user->isAtLeast(KmsUserRole::SuperAdmin);
        $this->debug('delete', $result);
        return $result;
    }
}

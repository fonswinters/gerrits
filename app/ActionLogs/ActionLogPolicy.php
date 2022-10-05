<?php declare(strict_types=1);


namespace App\ActionLogs;
use Komma\KMS\ActionLog\ActionLogPolicy as KmsActionLogPolicy;
use Komma\KMS\Users\Models\KmsUser;

class ActionLogPolicy extends KmsActionLogPolicy
{
    public function __construct()
    {
        parent::__construct();
        parent::$DEBUG = ActionLogPolicy::class;
    }

    public function before(KmsUser $user, $ability)
    {
        return false; //In basic, nobody may see the action log system
    }

    /**
     * Determine if a use may edit sites
     *
     * @param KmsUser|null $user
     * @return bool
     */
    public function viewLogs(?KmsUser $user): bool
    {
        return false; //In basic, nobody may see the action log system
    }
}
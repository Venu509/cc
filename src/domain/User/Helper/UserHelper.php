<?php

namespace Domain\User\Helper;

use Domain\Team\Actions\StoreTeamAction;
use Domain\Team\Data\TeamData;
use Domain\Team\Models\Team;
use Domain\Team\Models\TeamUser;

class UserHelper
{
    public function addTeam(string $name): Team
    {
        return (new StoreTeamAction())->execute(new TeamData(
            ucfirst($name) . '\'s team',
        ));
    }

    public function addUserToTeam(int $userId, int $teamId, $isParent = false): void
    {
        $tamUser = new TeamUser();

        $tamUser->forceFill([
            'user_id' => $userId,
            'team_id' => $teamId,
            'is_parent' => $isParent,
        ]);

        $tamUser->save();
    }

}
<?php

namespace App\Models\Translations;

use A17\Twill\Models\Model;
use App\Models\TeamMember;

class TeamMemberTranslation extends Model
{
    protected $baseModuleModel = TeamMember::class;
}

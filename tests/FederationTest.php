<?php

namespace Maggomann\FilamentTournamentLeagueAdministration\Tests;

use Maggomann\FilamentTournamentLeagueAdministration\Resources\FederationResource;

class FederationTest extends TestCase
{
    /** test */
    public function test_can_render_post_list_table()
    {
        $this->get(FederationResource::getUrl('index'))->assertSuccessful();
    }
}

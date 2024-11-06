<?php

namespace App\Policies;

use App\Models\Schueler;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Policy class for handling permissions related to Schueler records, specifically for updating Fehlstunden.
 */
class SchuelerFehlstundenPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can update Fehlstunden (absences) for a Schueler (Student) record.
     *
     * @param User $user
     * @param Schueler $schueler
     * @return bool
     */
    public function update(User $user, Schueler $schueler): bool
	{
        // Administrators can always update Fehlstunden.
        if ($user->isAdministrator()) {
			return true;
		}

        // Disallow if the Schueler Klasse doesn't have editable Fehlstunden.
		if (!$schueler->klasse->editable_fehlstunden) {
			return false;
		}

        // Disallow if Schueler Klasse has toggleable Fehlstunden.
		if ($schueler->klasse->toggleable_fehlstunden) {
			return false;
		}

        // Allow if the current user shares a class with the Schueler record.
		return $schueler->sharesKlasseWithCurrentUser();
    }
}

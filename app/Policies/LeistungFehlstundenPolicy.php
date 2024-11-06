<?php

namespace App\Policies;

use App\Models\Leistung;
use App\Models\User;
use App\Settings\MatrixSettings;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Policy class for handling permissions related to Leistung records.
 */
class LeistungFehlstundenPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can update Fehlstunden (absences) for a Leistung record.
     *
     * @param User $user
     * @param Leistung $leistung
     * @param MatrixSettings $settings
     * @return bool
     */
    public function update(User $user, Leistung $leistung, MatrixSettings $settings): bool
    {
        // Administrators can always update Fehlstunden.
        if ($user->isAdministrator()) {
			return true;
		}

        // Dissallow if the edit was not overriden
        if (!$leistung->schueler->klasse->edit_overrideable) {
			return false;
		}

        // Disallow if the Schueler Klasse doesn't have editable Fehlstunden.
		if (!$leistung->schueler->klasse->editable_fehlstunden ) {
			return false;
		}

        // Disallow if the Schueler Klasse doesn't have toggleable Fehlstunden.
		if (!$leistung->schueler->klasse->toggleable_fehlstunden) {
			return false;
		}

        // Allow if the current user shares a class with the Leistung record and Lehrer can override.
		if ($leistung->sharesKlasseWithCurrentUser() && $settings->lehrer_can_override_fachlehrer) {
			return true;
		}

        // Allow if the current user shares a Lerngruppe with the Leistung record.
        return $leistung->sharesLerngruppeWithCurrentUser();
    }
}

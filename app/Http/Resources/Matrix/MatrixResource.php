<?php

namespace App\Http\Resources\Matrix;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * The `MatrixResource` class is a JSON resource for formatting and presenting 'Matrix' data.
 *
 * @package App\Http\Resources\Export
 */
class MatrixResource extends JsonResource
{
    /**
     * Transform the data into a JSON array.
     *
     * @param $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'teilnoten' => $this->permission($this->editable_teilnoten),
            'noten' => $this->permission($this->editable_noten),
            'mahnungen' => $this->permission($this->editable_mahnungen),
            'fehlstunden' => $this->permission($this->editable_fehlstunden & $this->toggleable_fehlstunden),
            'asv' => $this->permission($this->editable_asv),
            'aue' => $this->permission($this->editable_aue),
            'zb' => $this->permission($this->editable_zb),
            'fb' => $this->permission($this->editable_fb),
        ];
    }

    /**
     * Check if the current user has permission based on the provided condition.
     *
     * @param bool $condition
     * @return bool
     */
    private function permission(bool $condition = false): bool
    {
        return auth()->user()->isAdministrator() || ($condition && $this->edit_overrideable);
    }
}

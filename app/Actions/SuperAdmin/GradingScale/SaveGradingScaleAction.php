<?php

namespace App\Actions\SuperAdmin\GradingScale;

use App\Models\GradingScale;

class SaveGradingScaleAction
{
    public function execute(array $data, ?GradingScale $gradingScale = null): GradingScale
    {
        if ($gradingScale) {
            $gradingScale->update($data);

            return $gradingScale->fresh();
        }

        return GradingScale::create($data);
    }
}

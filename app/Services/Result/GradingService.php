<?php

namespace App\Services\Result;

use App\Models\GradingScale;
use Illuminate\Support\Collection;

class GradingService
{
    private ?Collection $scale = null;

    public function scale(): Collection
    {
        if ($this->scale !== null) {
            return $this->scale;
        }

        $scale = GradingScale::where('is_active', true)
            ->orderByDesc('min_score')
            ->get(['min_score', 'max_score', 'grade', 'grade_point'])
            ->map(fn($row) => [
                'min_score'   => (float) $row->min_score,
                'max_score'   => (float) $row->max_score,
                'grade'       => $row->grade,
                'grade_point' => (float) $row->grade_point,
            ]);

        // Fall back to config/uemp.php when no grading scale is set
        if ($scale->isEmpty()) {
            $scale = collect(config('uemp.default_grading_scale'))->sortByDesc('min_score')->values();
        }

        return $this->scale = $scale;
    }

    public function compute(?float $caScore, ?float $examScore): array
    {
        if ($caScore === null || $examScore === null) {
            return ['total_score' => null, 'grade' => null, 'grade_point' => null];
        }

        $total = round($caScore + $examScore, 2);
        $band  = $this->bandFor($total);

        return [
            'total_score' => $total,
            'grade'       => $band['grade'] ?? 'F',
            'grade_point' => $band['grade_point'] ?? 0.0,
        ];
    }

    private function bandFor(float $total): ?array
    {
        // Bands are sorted by min_score desc, so the first band reached wins (avoids gaps like 69.995)
        return $this->scale()->first(fn($band) => $total >= $band['min_score']);
    }
}

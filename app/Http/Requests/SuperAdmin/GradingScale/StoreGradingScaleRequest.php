<?php

namespace App\Http\Requests\SuperAdmin\GradingScale;

use App\Models\GradingScale;
use Illuminate\Foundation\Http\FormRequest;

class StoreGradingScaleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'min_score'   => ['required', 'numeric', 'min:0', 'max:100'],
            'max_score'   => ['required', 'numeric', 'min:0', 'max:100', 'gte:min_score'],
            'grade'       => ['required', 'string', 'max:2'],
            'grade_point' => ['required', 'numeric', 'min:0', 'max:5'],
            'is_active'   => ['sometimes', 'boolean'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            // Active bands must not overlap, otherwise a score maps to two grades
            $overlaps = GradingScale::where('is_active', true)
                ->when($this->route('grading_scale'), fn($q, $scale) => $q->where('id', '!=', $scale->id))
                ->where('min_score', '<=', $this->max_score)
                ->where('max_score', '>=', $this->min_score)
                ->exists();

            if ($overlaps && $this->boolean('is_active', true)) {
                $validator->errors()->add('min_score', 'This score range overlaps an existing active grade band.');
            }
        });
    }
}

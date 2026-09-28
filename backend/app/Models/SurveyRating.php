<?php

namespace App\Models;

use Database\Factories\SurveyRatingFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['survey_response_id', 'question_key', 'score'])]
class SurveyRating extends Model
{
    /** @use HasFactory<SurveyRatingFactory> */
    use HasFactory;

    public function surveyResponse(): BelongsTo
    {
        return $this->belongsTo(SurveyResponse::class);
    }

    protected function casts(): array
    {
        return ['score' => 'integer'];
    }
}

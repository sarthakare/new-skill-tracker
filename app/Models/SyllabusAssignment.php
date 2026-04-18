<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SyllabusAssignment extends Model
{
    protected $fillable = [
        'syllabus_subtopic_id',
        'title',
        'description',
        'difficulty',
        'starter_code',
        'test_cases',
        'expected_output',
        'time_limit',
        'languages_supported',
    ];

    protected $casts = [
        'languages_supported' => 'array',
    ];

    public function syllabusSubtopic(): BelongsTo
    {
        return $this->belongsTo(SyllabusSubtopic::class);
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(SyllabusAssignmentSubmission::class);
    }

    /**
     * Semester/program this assignment belongs to (via syllabus topic).
     */
    public function programId(): ?int
    {
        $this->loadMissing('syllabusSubtopic.syllabusTopic');

        return $this->syllabusSubtopic?->syllabusTopic?->program_id;
    }

    /**
     * Judge0 language rows for the student code runner: restricted list when the manager
     * chose languages; otherwise the full catalog from config.
     *
     * @return list<array{id: int, name: string}>
     */
    public function allowedJudge0Languages(): array
    {
        $all = config('judge0.languages', []);
        $ids = array_values(array_filter(
            $this->languages_supported ?? [],
            static fn ($id) => $id !== null && $id !== ''
        ));
        if ($ids === []) {
            return $all;
        }

        $idSet = array_flip(array_map(static fn ($id) => (int) $id, $ids));
        $filtered = array_values(array_filter($all, static fn (array $lang) => isset($idSet[$lang['id']])));

        return $filtered !== [] ? $filtered : $all;
    }

    public function allowsJudge0LanguageId(int $languageId): bool
    {
        $ids = array_values(array_filter(
            $this->languages_supported ?? [],
            static fn ($id) => $id !== null && $id !== ''
        ));
        if ($ids === []) {
            return true;
        }

        return in_array($languageId, array_map(static fn ($id) => (int) $id, $ids), true);
    }
}

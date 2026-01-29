<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuperadminSecurityQuestion extends Model
{
    protected $fillable = [
        'question',
        'answer',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get all active security questions.
     */
    public static function getActiveQuestions()
    {
        return self::where('is_active', true)->get();
    }

    /**
     * Get random questions for verification.
     *
     * @param int $count Number of questions to return
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getRandomQuestions($count = 2)
    {
        return self::where('is_active', true)
            ->inRandomOrder()
            ->take($count)
            ->get();
    }

    /**
     * Verify an answer for a specific question.
     *
     * @param int $questionId
     * @param string $answer
     * @return bool
     */
    public static function verifyAnswer($questionId, $answer)
    {
        $question = self::find($questionId);
        
        if (!$question) {
            return false;
        }

        // Case-insensitive comparison, trim whitespace
        return strtolower(trim($question->answer)) === strtolower(trim($answer));
    }
}

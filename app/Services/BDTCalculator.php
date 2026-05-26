<?php

namespace App\Services;

class BDTCalculator
{
    /**
     * Calculate the minimum number of BDT notes required for a given amount.
     *
     * @param float|int $amount The amount to break down.
     * @return array Associative array of [note_denomination => count]
     */
    public static function getNoteBreakdown($amount)
    {
        // Round to nearest integer (BDT doesn't usually use coins in everyday dining splits)
        $amount = (int) round($amount);
        
        $notes = [1000, 500, 200, 100, 50, 20, 10, 5, 2, 1];
        $breakdown = [];

        foreach ($notes as $note) {
            if ($amount >= $note) {
                $count = intdiv($amount, $note);
                $breakdown[$note] = $count;
                $amount %= $note;
            }
        }

        return $breakdown;
    }

    /**
     * Calculate strict individual note requirements and sum them up globally.
     * This ensures no awkward note sharing when handing back change.
     *
     * @param array|\Illuminate\Support\Collection $amounts
     * @return array
     */
    public static function getStrictGlobalBreakdown($amounts)
    {
        $globalBreakdown = [];
        
        foreach ($amounts as $amount) {
            $individualBreakdown = self::getNoteBreakdown($amount);
            foreach ($individualBreakdown as $note => $count) {
                if (!isset($globalBreakdown[$note])) {
                    $globalBreakdown[$note] = 0;
                }
                $globalBreakdown[$note] += $count;
            }
        }
        
        // Sort keys descending so highest notes are first
        krsort($globalBreakdown);
        
        return $globalBreakdown;
    }
}

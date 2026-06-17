<?php

if (!function_exists('weighted_average')) {
    // $notes: array of ['note' => float, 'coeff' => int, 'subject' => string, 'optional' => bool]
    function weighted_average(array $notes): float
    {
        // handle optionals: keep only highest note per optional subject group (subject name)
        $filtered = [];
        foreach ($notes as $n) {
            $subject = $n['subject'] ?? null;
            $optional = !empty($n['optional']);
            if ($optional) {
                if (!isset($filtered[$subject]) || $n['note'] > $filtered[$subject]['note']) {
                    $filtered[$subject] = $n;
                }
            } else {
                // use a unique key for non-optionals
                $filtered[uniqid('s_', true)] = $n;
            }
        }

        $sum = 0.0;
        $coeffSum = 0.0;
        foreach ($filtered as $n) {
            $coeff = max(0, floatval($n['coeff'] ?? 1));
            $sum += floatval($n['note']) * $coeff;
            $coeffSum += $coeff;
        }

        if ($coeffSum <= 0) return 0.0;
        return round($sum / $coeffSum, 2);
    }
}

if (!function_exists('calculate_credits')) {
    // returns total credits obtained using each subject credit when available
    function calculate_credits(array $notes, float $passing_avg = 10.0, int $credit_per_subject = 3): int
    {
        // after filtering optionals (best optional per subject)
        $filtered = [];
        foreach ($notes as $n) {
            $subject = $n['subject'] ?? null;
            $optional = !empty($n['optional']);
            if ($optional) {
                if (!isset($filtered[$subject]) || $n['note'] > $filtered[$subject]['note']) {
                    $filtered[$subject] = $n;
                }
            } else {
                $filtered[uniqid('s_', true)] = $n;
            }
        }

        $credits = 0;
        foreach ($filtered as $n) {
            if (floatval($n['note']) >= $passing_avg) {
                $credits += intval($n['credit'] ?? $credit_per_subject);
            }
        }
        return $credits;
    }
}

if (!function_exists('passes_semester')) {
    // Returns bool if student passes semester based on required credits
    function passes_semester(array $notes, int $required_credits = 30, float $passing_avg = 10.0, int $credit_per_subject = 3): bool
    {
        $obtained = calculate_credits($notes, $passing_avg, $credit_per_subject);
        return $obtained >= $required_credits;
    }
}

if (!function_exists('mention_from_avg')) {
    function mention_from_avg(float $avg): string
    {
        if ($avg < 10.0) return 'Ajourné';
        if ($avg >= 10.0 && $avg < 12.0) return 'Passable';
        if ($avg >= 12.0 && $avg < 14.0) return 'Assez Bien';
        if ($avg >= 14.0 && $avg < 16.0) return 'Bien';
        return 'Très Bien';
    }
}

if (!function_exists('best_optional')) {
    // returns best optional note among provided optional subjects
    function best_optional(array $notes): ?array
    {
        $best = null;
        foreach ($notes as $n) {
            if (!empty($n['optional'])) {
                if ($best === null || floatval($n['note']) > floatval($best['note'])) {
                    $best = $n;
                }
            }
        }
        return $best;
    }
}

if (!function_exists('note_with_coeff')) {
    function note_with_coeff(float $note, float $coeff): float
    {
        return round($note * $coeff, 2);
    }
}

?>
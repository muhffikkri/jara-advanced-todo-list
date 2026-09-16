<?php

namespace App\Support;

/**
 * Kalkulasi progres penyelesaian tugas dalam sebuah daftar.
 */
final class ListProgress
{
    /**
     * Persentase tugas selesai dari total tugas.
     * Nilai diklampirkan ke rentang 0–100.
     */
    public static function percentage(int $total, int $completed): int
    {
        if ($total <= 0) {
            return 0;
        }

        $completed = max(0, min($completed, $total));

        return (int) round(($completed / $total) * 100);
    }
}

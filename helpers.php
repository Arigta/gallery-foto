<?php
function formatTimeAgo($timestamp)
{
    // Set timezone default
    date_default_timezone_set('Asia/Jakarta');

    // Validasi format timestamp
    if (!$timestamp || $timestamp == '0000-00-00 00:00:00') {
        return "Invalid date";
    }

    // Convert timestamp to Unix timestamp
    $time_ago = strtotime($timestamp);
    $current_time = time();

    // Jika timestamp invalid atau di masa depan
    if ($time_ago === false || $time_ago > $current_time) {
        return "Invalid date";
    }

    $time_difference = $current_time - $time_ago;

    // Detik dalam berbagai unit waktu
    $seconds = $time_difference;
    $minutes = floor($seconds / 60);
    $hours = floor($seconds / 3600);
    $days = floor($seconds / 86400);
    $weeks = floor($seconds / 604800);
    $months = floor($seconds / 2592000);
    $years = floor($seconds / 31536000);

    // Return waktu dalam format yang sesuai
    if ($seconds <= 60) {
        return "Baru saja";
    } elseif ($minutes <= 60) {
        return $minutes . " menit lalu";
    } elseif ($hours <= 24) {
        return $hours . " jam lalu";
    } elseif ($days <= 7) {
        return $days . " hari lalu";
    } elseif ($weeks <= 4) {
        return $weeks . " minggu lalu";
    } elseif ($months <= 12) {
        return $months . " bulan lalu";
    } else {
        return date('j F Y', $time_ago);
    }
}

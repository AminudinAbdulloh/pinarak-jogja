<?php

/**
 * Format tanggal ke format Indonesia
 */
function formatDateIndonesia($date) {
    $months = [
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];
    
    $timestamp = strtotime($date);
    $day = date('d', $timestamp);
    $month = $months[(int)date('m', $timestamp)];
    $year = date('Y', $timestamp);
    
    return "$day $month $year";
}

/**
 * Potong teks dengan panjang tertentu
 */
function truncateText($text, $length = 100) {
    if (strlen($text) <= $length) {
        return $text;
    }
    
    return substr($text, 0, $length) . '...';
}

/**
 * Sanitize input untuk mencegah XSS
 */
function clean($data) {
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

/**
 * Format rupiah
 */
function formatRupiah($angka) {
    return 'Rp ' . number_format($angka, 0, ',', '.');
}

function formatDate($date, $format = 'd M Y H:i') {
    // Daftar bulan dalam Bahasa Indonesia
    $bulanIndo = [
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember'
    ];

    $timestamp = strtotime($date);
    if (!$timestamp) return '-'; // jika tanggal tidak valid

    // Jika user minta format "11 Oktober 2025"
    if ($format === 'indo') {
        $hari = date('d', $timestamp);
        $bulan = $bulanIndo[(int)date('m', $timestamp)];
        $tahun = date('Y', $timestamp);
        return "$hari $bulan $tahun";
    }

    // Format default atau kustom sesuai parameter kedua
    return date($format, $timestamp);
}

<?php

// Include autoloader
require_once 'vendor/autoload.php';

// Test class untuk DocumentNumberExtractor tanpa dependency Laravel
class TestDocumentNumberExtractor
{
    /**
     * Find document number pattern in content
     */
    public static function findDocumentNumber($content)
    {
        if (empty($content)) {
            return null;
        }

        echo "Analyzing content for document number, length: " . strlen($content) . "\n";

        // Pola-pola untuk mencari nomor dokumen (AR, KP, RT, KU)
        // Urutan sangat penting - pola yang lebih spesifik harus di atas
        $patterns = [
            // PRIORITAS TERTINGGI: Format dengan Nomor: untuk AR dan KU 4-level
            '/Nomor\s*:\s*((AR)\.(\d{2})\.(\d{2})\.(\d{2})\/(\d+)\-([A-Za-z\.]+)\/([A-Z]+)\/(\d{4}))/i',
            '/Nomor\s*:\s*((KU)\.(\d{2})\.(\d{2})\.(\d{2})\/(\d+)\-([A-Za-z\.]+)\/([A-Z]+)\/(\d{4}))/i',

            // PRIORITAS TINGGI: Format lengkap untuk AR dan KU 4-level
            '/\b(AR)\.(\d{2})\.(\d{2})\.(\d{2})\/(\d+)\-([A-Za-z\.]+)\/([A-Z]+)\/(\d{4})\b/i',
            '/\b(KU)\.(\d{2})\.(\d{2})\.(\d{2})\/(\d+)\-([A-Za-z\.]+)\/([A-Z]+)\/(\d{4})\b/i',

            // Format dengan Nomor: - prioritas untuk KU, KP, RT, AR 3-level
            '/Nomor\s*:\s*((KU)\.(\d{2})\.(\d{2})\/(\d+)\-([A-Za-z\.]+)\/([A-Z]+)\/(\d{4}))/i',
            '/Nomor\s*:\s*((KP)\.(\d{2})\.(\d{2})\/(\d+)\-([A-Za-z\.]+)\/([A-Z]+)\/(\d{4}))/i',
            '/Nomor\s*:\s*((RT)\.(\d{2})\.(\d{2})\/(\d+)\-([A-Za-z\.]+)\/([A-Z]+)\/(\d{4}))/i',
            '/Nomor\s*:\s*((AR)\.(\d{2})\.(\d{2})\/(\d+)\-([A-Za-z\.]+)\/([A-Z]+)\/(\d{4}))/i',

            // Format lengkap - prioritas untuk KU, KP dan RT 3-level
            '/\b(KU)\.(\d{2})\.(\d{2})\/(\d+)\-([A-Za-z\.]+)\/([A-Z]+)\/(\d{4})\b/i',
            '/\b(KP)\.(\d{2})\.(\d{2})\/(\d+)\-([A-Za-z\.]+)\/([A-Z]+)\/(\d{4})\b/i',
            '/\b(RT)\.(\d{2})\.(\d{2})\/(\d+)\-([A-Za-z\.]+)\/([A-Z]+)\/(\d{4})\b/i',
            '/\b(AR)\.(\d{2})\.(\d{2})\/(\d+)\-([A-Za-z\.]+)\/([A-Z]+)\/(\d{4})\b/i',

            // Format lebih umum untuk AR dan KU 4-level
            '/\b(AR)\.(\d{2})\.(\d{2})\.(\d{2})(?:\/(\d+))?(?:\-([A-Za-z\.]+))?(?:\/([A-Z]+))?(?:\/(\d{4}))?\b/i',
            '/\b(KU)\.(\d{2})\.(\d{2})\.(\d{2})(?:\/(\d+))?(?:\-([A-Za-z\.]+))?(?:\/([A-Z]+))?(?:\/(\d{4}))?\b/i',
        ];

        foreach ($patterns as $index => $pattern) {
            if (preg_match($pattern, $content, $matches)) {
                echo "Found match with pattern #" . ($index + 1) . ": " . $matches[0] . "\n";
                echo "Full matches array: " . json_encode($matches) . "\n";

                // Jika match dari pattern Nomor: yang menggunakan capture group 1
                if (strpos($pattern, 'Nomor') !== false && isset($matches[1])) {
                    $result = trim($matches[1]);
                    echo "Extracted from Nomor pattern: " . $result . "\n";
                    return $result;
                }

                // Untuk pattern lainnya, gunakan match lengkap
                $result = trim($matches[0]);
                echo "Extracted full match: " . $result . "\n";
                return $result;
            }
        }

        echo "No document number patterns matched\n";
        return null;
    }

    /**
     * Format document number to standard format
     */
    public static function formatDocumentNumber($extractedNumber)
    {
        $cleaned = trim($extractedNumber);

        // Hapus prefix "Nomor:" atau "No:" jika ada
        $cleaned = preg_replace('/(?:Nomor|No)\.?\s*:\s*/i', '', $cleaned);

        echo "Formatting document number: {$extractedNumber} -> {$cleaned}\n";

        // Jika sudah dalam format yang benar untuk AR, KP, RT, atau KU (3 atau 4 level), return apa adanya
        if (preg_match('/^(AR|KP|RT|KU)\.(\d{2})\.(\d{2})(?:\.(\d{2}))?\/\d+\-[A-Za-z\.]+\/[A-Z]+\/\d{4}$/', $cleaned)) {
            return $cleaned;
        }

        // Coba ekstrak komponen-komponen untuk format AR 4-level
        if (preg_match('/(AR)\.?(\d{2})\.?(\d{2})\.?(\d{2})(?:\/(\d+))?(?:\-([A-Za-z\.]+))?(?:\/([A-Z]+))?(?:\/(\d{4}))?/i', $cleaned, $parts)) {
            $prefix = strtoupper($parts[1]); // AR
            $formatted = $prefix . '.' . str_pad($parts[2], 2, '0', STR_PAD_LEFT) . '.' . str_pad($parts[3], 2, '0', STR_PAD_LEFT) . '.' . str_pad($parts[4], 2, '0', STR_PAD_LEFT);

            // Tambahkan nomor referensi (default 1)
            $refNum = !empty($parts[5]) ? $parts[5] : '1';
            $formatted .= '/' . $refNum;

            // Tambahkan kode lokasi (default Kec.Cddp)
            $location = !empty($parts[6]) ? $parts[6] : 'Kec.Cddp';
            $formatted .= '-' . $location;

            // Tambahkan bulan dalam angka Romawi (default bulan sekarang)
            $month = !empty($parts[7]) ? $parts[7] : self::getCurrentMonthRoman();
            $formatted .= '/' . strtoupper($month);

            // Tambahkan tahun (default tahun sekarang)
            $year = !empty($parts[8]) ? $parts[8] : date('Y');
            $formatted .= '/' . $year;

            echo "Formatted AR 4-level to: {$formatted}\n";
            return $formatted;
        }

        // Jika tidak bisa diparse, return yang sudah dibersihkan
        return $cleaned;
    }

    /**
     * Get current month in Roman numerals
     */
    private static function getCurrentMonthRoman()
    {
        $romanMonths = [
            1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV',
            5 => 'V', 6 => 'VI', 7 => 'VII', 8 => 'VIII',
            9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII'
        ];

        return $romanMonths[(int)date('n')];
    }
}

echo "=== Testing AR 4-Level Code Extraction Priority ===\n\n";

// Test 1: Teks yang berisi AR 4-level dan AR 3-level
$testContent1 = "
NOTA DINAS
Nomor: AR.01.01.02/1-Kec.Cddp/XII/2025
Tanggal: 15 Desember 2025

Kepada: Bupati Cidrap
Dari: Camat Cidrap

Perihal: Laporan Kegiatan MONEV SRIKANDI

Dalam rangka pelaksanaan monitoring dan evaluasi program SRIKANDI...

Footer dokumen: AR.03.09/2082-Disarpus/XI/2024
";

echo "Test 1: Konten dengan AR 4-level dan AR 3-level\n";
echo "Content preview: " . substr(str_replace("\n", " ", $testContent1), 0, 100) . "...\n";

$extracted1 = TestDocumentNumberExtractor::findDocumentNumber($testContent1);
echo "Extracted: " . ($extracted1 ?: 'NULL') . "\n";

if ($extracted1) {
    $formatted1 = TestDocumentNumberExtractor::formatDocumentNumber($extracted1);
    echo "Formatted: " . $formatted1 . "\n";

    // Verifikasi apakah ini AR 4-level
    if (preg_match('/^AR\.\d{2}\.\d{2}\.\d{2}/', $formatted1)) {
        echo "✅ PASS: AR 4-level correctly prioritized\n";
    } else {
        echo "❌ FAIL: AR 4-level NOT prioritized\n";
    }
} else {
    echo "❌ FAIL: No document number extracted\n";
}

echo "\n" . str_repeat("-", 50) . "\n\n";

// Test 2: Multiple AR codes dalam konten
$testContent2 = "
Referensi dokumen sebelumnya: AR.03.09/2082-Disarpus/XI/2024

NOTA DINAS BARU
Nomor: AR.01.01.02/1-Kec.Cddp/XII/2025
Tanggal: 15 Desember 2025

Terkait juga dengan AR.02.01/500-Sekda/X/2024
";

echo "Test 2: Multiple AR codes - should prioritize AR 4-level\n";
echo "Content preview: " . substr(str_replace("\n", " ", $testContent2), 0, 100) . "...\n";

$extracted2 = TestDocumentNumberExtractor::findDocumentNumber($testContent2);
echo "Extracted: " . ($extracted2 ?: 'NULL') . "\n";

if ($extracted2) {
    $formatted2 = TestDocumentNumberExtractor::formatDocumentNumber($extracted2);
    echo "Formatted: " . $formatted2 . "\n";

    // Verifikasi apakah ini AR 4-level yang benar
    if ($formatted2 === 'AR.01.01.02/1-Kec.Cddp/XII/2025') {
        echo "✅ PASS: Correct AR 4-level prioritized\n";
    } else {
        echo "❌ FAIL: Wrong AR code prioritized\n";
    }
} else {
    echo "❌ FAIL: No document number extracted\n";
}

echo "\n=== Test Complete ===\n";

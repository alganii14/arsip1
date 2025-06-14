<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;
use Smalot\PdfParser\Parser;

class DocumentNumberExtractor
{    /**
     * Extract document number from file content
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @return string|null
     */
    public static function extractFromFile($file)
    {
        $result = self::extractDocumentData($file);
        return $result['documentNumber'] ?? null;
    }

    /**
     * Extract document data (number and date) from file content
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @return array
     */
    public static function extractDocumentData($file)    {
        $extension = strtolower($file->getClientOriginalExtension());
        $fileName = $file->getClientOriginalName();

        Log::info("DocumentNumberExtractor: Starting extraction from file: {$fileName} with extension: {$extension}");

        $result = [
            'documentNumber' => null,
            'documentDate' => null,
            'documentName' => null
        ];

        // Analisis nama file untuk pola khusus
        $docNumberFromFilename = self::extractFromFilename($fileName);
        if ($docNumberFromFilename) {
            Log::info("DocumentNumberExtractor: Found document number in filename: {$docNumberFromFilename}");
            $result['documentNumber'] = $docNumberFromFilename;
        }

        $content = null;

        // Extract content berdasarkan tipe file
        switch ($extension) {
            case 'pdf':
                Log::info("DocumentNumberExtractor: Extracting from PDF file");
                $content = self::extractFromPdf($file);
                break;

            case 'doc':
            case 'docx':
                Log::info("DocumentNumberExtractor: Extracting from Word file");
                $content = self::extractFromWord($file);
                break;

            case 'jpg':
            case 'jpeg':
            case 'png':
                Log::info("DocumentNumberExtractor: Processing image file (filename only)");
                // Untuk gambar, gunakan nama file saja karena butuh OCR untuk baca isi
                $content = $fileName;
                break;

            default:
                Log::info("DocumentNumberExtractor: Using filename for unsupported file type");
                $content = $fileName;
                break;
        }

        // Cari nomor dokumen dan tanggal dalam konten
        if (!empty($content)) {
            // Extract document number if not found in filename
            if (!$result['documentNumber']) {
                $docNumber = self::findDocumentNumber($content);
                if ($docNumber) {
                    Log::info("DocumentNumberExtractor: Found document number in content: {$docNumber}");
                    $result['documentNumber'] = self::formatDocumentNumber($docNumber);
                }
            }

            // Extract document date
            $docDate = self::findDocumentDate($content);
            if ($docDate) {
                Log::info("DocumentNumberExtractor: Found document date in content: {$docDate}");
                $result['documentDate'] = $docDate;
            }

            // Extract document name/subject
            $docName = self::findDocumentName($content);
            if ($docName) {
                Log::info("DocumentNumberExtractor: Found document name in content: {$docName}");
                $result['documentName'] = $docName;
            }
        }

        if (!$result['documentNumber'] && !$result['documentDate'] && !$result['documentName']) {
            Log::warning("DocumentNumberExtractor: No document data found in file: {$fileName}");
        }

        return $result;
    }

    /**
     * Extract document number from filename
     */
    private static function extractFromFilename($fileName)
    {
        // Cari pola nomor dokumen langsung di nama file terlebih dahulu
        $docNumber = self::findDocumentNumber($fileName);
        if ($docNumber) {
            Log::info("DocumentNumberExtractor: Found document number in filename: {$docNumber}");
            return $docNumber;
        }

        // Jika tidak ada pola nomor yang ditemukan, tidak ada fallback khusus
        Log::info("DocumentNumberExtractor: No document number pattern found in filename");
        return null;
    }

    /**
     * Extract text from PDF file
     */
    protected static function extractFromPdf($file)
    {
        try {
            if (!class_exists('\\Smalot\\PdfParser\\Parser')) {
                Log::warning("DocumentNumberExtractor: PDF Parser not available");
                return null;
            }

            $parser = new Parser();
            $pdf = $parser->parseFile($file->getPathname());

            $text = '';
            $pages = $pdf->getPages();

            Log::info("DocumentNumberExtractor: PDF has " . count($pages) . " pages");

            // Ambil metadata PDF
            $details = $pdf->getDetails();
            if (!empty($details)) {
                foreach ($details as $key => $value) {
                    if (is_string($value)) {
                        $text .= $key . ': ' . $value . "\n";
                    }
                }
            }

            // Proses halaman-halaman
            foreach ($pages as $i => $page) {
                $pageText = $page->getText();
                $text .= $pageText . "\n";

                Log::info("DocumentNumberExtractor: Processed page " . ($i+1) . ", text length: " . strlen($pageText));

                // Jika sudah menemukan indikator nomor dokumen, bisa berhenti
                if (preg_match('/Nomor\s*:/i', $pageText) && preg_match('/AR[\.\s\/]?\d{2}[\.\s\/]?\d{2}/i', $pageText)) {
                    Log::info("DocumentNumberExtractor: Found document number indicators on page " . ($i+1));
                    break;
                }
            }

            Log::info("DocumentNumberExtractor: Total extracted text length: " . strlen($text));

            return $text;

        } catch (\Exception $e) {
            Log::error("DocumentNumberExtractor: PDF extraction error: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Extract text from Word document
     */
    protected static function extractFromWord($file)
    {
        try {
            if (!class_exists('\\PhpOffice\\PhpWord\\IOFactory')) {
                Log::warning("DocumentNumberExtractor: PhpWord not available");
                return null;
            }

            $phpWord = \PhpOffice\PhpWord\IOFactory::load($file->getPathname());
            $text = '';

            foreach ($phpWord->getSections() as $section) {
                $text .= self::extractTextFromSection($section);
            }

            Log::info("DocumentNumberExtractor: Extracted text from Word document, length: " . strlen($text));
            return $text;

        } catch (\Exception $e) {
            Log::error("DocumentNumberExtractor: Word extraction error: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Extract text from a Word document section
     */
    private static function extractTextFromSection($section)
    {
        $text = '';

        foreach ($section->getElements() as $element) {
            $elementClass = get_class($element);

            if ($element instanceof \PhpOffice\PhpWord\Element\TextRun) {
                foreach ($element->getElements() as $childElement) {
                    if ($childElement instanceof \PhpOffice\PhpWord\Element\Text) {
                        $text .= $childElement->getText() . ' ';
                    }
                }
            } elseif ($element instanceof \PhpOffice\PhpWord\Element\Text) {
                $text .= $element->getText() . ' ';
            } elseif (method_exists($element, 'getText')) {
                $text .= $element->getText() . ' ';
            }
        }

        return $text;
    }

    /**
     * Find document number pattern in content
     */
    public static function findDocumentNumber($content)
    {
        if (empty($content)) {
            return null;
        }

        Log::info("DocumentNumberExtractor: Analyzing content for document number, length: " . strlen($content));

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

            // Format lebih umum untuk 3-level
            '/\b(KU)\.(\d{2})\.(\d{2})(?:\/(\d+))?(?:\-([A-Za-z\.]+))?(?:\/([A-Z]+))?(?:\/(\d{4}))?\b/i',
            '/\b(KP)\.(\d{2})\.(\d{2})(?:\/(\d+))?(?:\-([A-Za-z\.]+))?(?:\/([A-Z]+))?(?:\/(\d{4}))?\b/i',
            '/\b(RT)\.(\d{2})\.(\d{2})(?:\/(\d+))?(?:\-([A-Za-z\.]+))?(?:\/([A-Z]+))?(?:\/(\d{4}))?\b/i',
            '/\b(AR)\.(\d{2})\.(\d{2})(?:\/(\d+))?(?:\-([A-Za-z\.]+))?(?:\/([A-Z]+))?(?:\/(\d{4}))?\b/i',

            // Format dengan garis miring
            '/(KU)\/(\d{2})\/(\d{2})(?:\/(\d+))?(?:\-([A-Za-z\.]+))?(?:\/([A-Z]+))?(?:\/(\d{4}))?/i',
            '/(KP)\/(\d{2})\/(\d{2})(?:\/(\d+))?(?:\-([A-Za-z\.]+))?(?:\/([A-Z]+))?(?:\/(\d{4}))?/i',
            '/(RT)\/(\d{2})\/(\d{2})(?:\/(\d+))?(?:\-([A-Za-z\.]+))?(?:\/([A-Z]+))?(?:\/(\d{4}))?/i',
            '/(AR)\/(\d{2})\/(\d{2})(?:\/(\d+))?(?:\-([A-Za-z\.]+))?(?:\/([A-Z]+))?(?:\/(\d{4}))?/i',

            // Format di baris Nomor (lebih umum)
            '/Nomor\s*:\s*((KU)[^\n\r]*)/i',
            '/Nomor\s*:\s*((KP)[^\n\r]*)/i',
            '/Nomor\s*:\s*((RT)[^\n\r]*)/i',
            '/Nomor\s*:\s*((AR)[^\n\r]*)/i',

            // Format No. atau No:
            '/No\.?\s*:\s*((KU)[^\n\r]*)/i',
            '/No\.?\s*:\s*((KP)[^\n\r]*)/i',
            '/No\.?\s*:\s*((RT)[^\n\r]*)/i',
            '/No\.?\s*:\s*((AR)[^\n\r]*)/i',
        ];

        foreach ($patterns as $index => $pattern) {
            if (preg_match($pattern, $content, $matches)) {
                Log::info("DocumentNumberExtractor: Found match with pattern #" . ($index + 1) . ": " . $matches[0]);
                Log::info("DocumentNumberExtractor: Full matches array: " . json_encode($matches));

                // Jika match dari pattern Nomor: yang menggunakan capture group 1
                if (strpos($pattern, 'Nomor') !== false && isset($matches[1])) {
                    $result = trim($matches[1]);
                    Log::info("DocumentNumberExtractor: Extracted from Nomor pattern: " . $result);
                    return $result;
                }

                // Untuk pattern lainnya, gunakan match lengkap
                $result = trim($matches[0]);
                Log::info("DocumentNumberExtractor: Extracted full match: " . $result);
                return $result;
            }
        }

        Log::info("DocumentNumberExtractor: No document number patterns matched");
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

        Log::info("DocumentNumberExtractor: Formatting document number: {$extractedNumber} -> {$cleaned}");

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

            Log::info("DocumentNumberExtractor: Formatted AR 4-level to: {$formatted}");
            return $formatted;
        }

        // Coba ekstrak komponen-komponen untuk format KU 4-level
        if (preg_match('/(KU)\.?(\d{2})\.?(\d{2})\.?(\d{2})(?:\/(\d+))?(?:\-([A-Za-z\.]+))?(?:\/([A-Z]+))?(?:\/(\d{4}))?/i', $cleaned, $parts)) {
            $prefix = strtoupper($parts[1]); // KU
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

            Log::info("DocumentNumberExtractor: Formatted KU 4-level to: {$formatted}");
            return $formatted;
        }

        // Coba ekstrak komponen-komponen untuk format 3-level (AR, KP, RT, KU)
        if (preg_match('/(AR|KP|RT|KU)\.?(\d{2})\.?(\d{2})(?:\/(\d+))?(?:\-([A-Za-z\.]+))?(?:\/([A-Z]+))?(?:\/(\d{4}))?/i', $cleaned, $parts)) {
            $prefix = strtoupper($parts[1]); // AR, KP, RT, atau KU
            $formatted = $prefix . '.' . str_pad($parts[2], 2, '0', STR_PAD_LEFT) . '.' . str_pad($parts[3], 2, '0', STR_PAD_LEFT);

            // Tambahkan nomor referensi (default 1)
            $refNum = !empty($parts[4]) ? $parts[4] : '1';
            $formatted .= '/' . $refNum;

            // Tambahkan kode lokasi (default Kec.Cddp)
            $location = !empty($parts[5]) ? $parts[5] : 'Kec.Cddp';
            $formatted .= '-' . $location;

            // Tambahkan bulan dalam angka Romawi (default bulan sekarang)
            $month = !empty($parts[6]) ? $parts[6] : self::getCurrentMonthRoman();
            $formatted .= '/' . strtoupper($month);

            // Tambahkan tahun (default tahun sekarang)
            $year = !empty($parts[7]) ? $parts[7] : date('Y');
            $formatted .= '/' . $year;

            Log::info("DocumentNumberExtractor: Formatted to: {$formatted}");
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

    /**
     * Debug method to examine file content
     */
    public static function debugFileContent($filePath)
    {
        $result = [
            'success' => false,
            'text' => '',
            'patterns_found' => [],
            'filename_analysis' => []
        ];

        try {
            $fileName = basename($filePath);

            // Analisis nama file
            $result['filename_analysis']['original'] = $fileName;
            $result['filename_analysis']['patterns'] = [];

            $filenamePatterns = [
                '/NOTA\s*DINAS/i' => 'Contains NOTA DINAS',
                '/MONEV.*SRIKANDI/i' => 'Contains MONEV SRIKANDI',
                '/AR\.?\d{2}\.?\d{2}/i' => 'Contains AR classification',
                '/Kec\.?\w+/i' => 'Contains Kecamatan',
            ];

            foreach ($filenamePatterns as $pattern => $description) {
                if (preg_match($pattern, $fileName)) {
                    $result['filename_analysis']['patterns'][] = $description;
                }
            }

            // Ekstrak konten file jika PDF
            if (strtolower(pathinfo($filePath, PATHINFO_EXTENSION)) === 'pdf') {
                if (class_exists('\\Smalot\\PdfParser\\Parser')) {
                    $parser = new Parser();
                    $pdf = $parser->parseFile($filePath);
                    $result['text'] = $pdf->getText();
                    $result['success'] = true;

                    // Test patterns
                    $testPatterns = [
                        '/Nomor\s*:\s*(AR[^\n\r]*)/i' => 'Nomor line with AR',
                        '/AR\.(\d{2})\.(\d{2})/i' => 'AR classification pattern',
                        '/Kec\.?\w+/i' => 'Kecamatan pattern',
                        '/NOTA\s*DINAS/i' => 'Nota Dinas header',
                    ];

                    foreach ($testPatterns as $pattern => $description) {
                        if (preg_match($pattern, $result['text'], $matches)) {
                            $result['patterns_found'][] = [
                                'description' => $description,
                                'pattern' => $pattern,
                                'match' => $matches[0]
                            ];
                        }
                    }
                }
            }

        } catch (\Exception $e) {
            $result['error'] = $e->getMessage();
        }

        return $result;
    }

    /**
     * Find document date pattern in content
     */
    public static function findDocumentDate($content)
    {
        if (empty($content)) {
            return null;
        }

        Log::info("DocumentNumberExtractor: Analyzing content for document date, length: " . strlen($content));
        Log::info("DocumentNumberExtractor: Content preview: " . substr($content, 0, 500));

        // Pola-pola untuk mencari tanggal dokumen
        $patterns = [
            // Format: Tanggal : 5 Desember 2024
            '/Tanggal\s*:\s*(\d{1,2})\s+([A-Za-z]+)\s+(\d{4})/i',

            // Format: Tgl : 5 Desember 2024
            '/Tgl\.?\s*:\s*(\d{1,2})\s+([A-Za-z]+)\s+(\d{4})/i',

            // Format: 5 Desember 2024 (tanpa label)
            '/(\d{1,2})\s+([A-Za-z]+)\s+(\d{4})(?!\d)/i',

            // Format ISO: 2024-12-05
            '/(\d{4})-(\d{1,2})-(\d{1,2})/i',

            // Format Indonesia: 05/12/2024 atau 5/12/2024
            '/(\d{1,2})\/(\d{1,2})\/(\d{4})/i',

            // Format dengan kata Pada: Pada tanggal 5 Desember 2024
            '/Pada\s+tanggal\s+(\d{1,2})\s+([A-Za-z]+)\s+(\d{4})/i',
        ];

        foreach ($patterns as $index => $pattern) {
            Log::info("DocumentNumberExtractor: Testing pattern #" . ($index + 1) . ": " . $pattern);
            if (preg_match($pattern, $content, $matches)) {
                Log::info("DocumentNumberExtractor: Found date match with pattern #" . ($index + 1) . ": " . $matches[0]);
                Log::info("DocumentNumberExtractor: Full matches: " . json_encode($matches));

                $date = null;

                if ($index == 0 || $index == 1 || $index == 2 || $index == 5) {
                    // Format: hari bulan tahun (Indonesia)
                    $day = str_pad($matches[1], 2, '0', STR_PAD_LEFT);
                    $monthName = strtolower($matches[2]);
                    $year = $matches[3];

                    Log::info("DocumentNumberExtractor: Parsing date - Day: {$day}, Month: {$monthName}, Year: {$year}");

                    $monthNum = self::getMonthNumber($monthName);
                    Log::info("DocumentNumberExtractor: Month number for '{$monthName}': " . ($monthNum ?? 'null'));

                    if ($monthNum) {
                        $month = str_pad($monthNum, 2, '0', STR_PAD_LEFT);
                        $date = "{$year}-{$month}-{$day}";
                        Log::info("DocumentNumberExtractor: Constructed date: {$date}");
                    }
                } elseif ($index == 3) {
                    // Format ISO: YYYY-MM-DD
                    $date = $matches[0];
                } elseif ($index == 4) {
                    // Format: DD/MM/YYYY
                    $day = str_pad($matches[1], 2, '0', STR_PAD_LEFT);
                    $month = str_pad($matches[2], 2, '0', STR_PAD_LEFT);
                    $year = $matches[3];
                    $date = "{$year}-{$month}-{$day}";
                }

                if ($date && self::isValidDate($date)) {
                    Log::info("DocumentNumberExtractor: Extracted valid date: {$date}");
                    return $date;
                } else {
                    Log::warning("DocumentNumberExtractor: Invalid date format: " . ($date ?? 'null'));
                }
            }
        }

        Log::info("DocumentNumberExtractor: No valid date patterns matched");
        return null;
    }

    /**
     * Convert Indonesian month name to number
     */
    private static function getMonthNumber($monthName)
    {
        $months = [
            'januari' => 1, 'february' => 2, 'maret' => 3, 'april' => 4,
            'mei' => 5, 'juni' => 6, 'juli' => 7, 'agustus' => 8,
            'september' => 9, 'oktober' => 10, 'november' => 11, 'desember' => 12,
            'jan' => 1, 'feb' => 2, 'mar' => 3, 'apr' => 4,
            'may' => 5, 'jun' => 6, 'jul' => 7, 'aug' => 8,
            'sep' => 9, 'oct' => 10, 'nov' => 11, 'dec' => 12,
            // Add English variants (avoid duplicates)
            'january' => 1, 'march' => 3, 'june' => 6, 'july' => 7,
            'august' => 8, 'october' => 10, 'december' => 12
        ];

        return $months[strtolower($monthName)] ?? null;
    }

    /**
     * Validate if date string is valid
     */
    private static function isValidDate($date)
    {
        $d = \DateTime::createFromFormat('Y-m-d', $date);
        return $d && $d->format('Y-m-d') === $date;
    }

    /**
     * Find document name/subject pattern in content
     */
    public static function findDocumentName($content)
    {
        if (empty($content)) {
            return null;
        }

        Log::info("DocumentNumberExtractor: Analyzing content for document name, length: " . strlen($content));

        // Pola-pola untuk mencari nama/subjek dokumen
        $patterns = [
            // Format: Hal : Laporan Monev SRIKANDI
            '/Hal\s*:\s*([^\n\r]+)/i',

            // Format: Subject : ...
            '/Subject\s*:\s*([^\n\r]+)/i',

            // Format: Perihal : ...
            '/Perihal\s*:\s*([^\n\r]+)/i',

            // Format: Tentang : ...
            '/Tentang\s*:\s*([^\n\r]+)/i',

            // Format: Re : ...
            '/Re\s*:\s*([^\n\r]+)/i',

            // Look for document titles after headers like "NOTA DINAS"
            '/NOTA\s+DINAS[^\n\r]*\n[^\n\r]*\n[^\n\r]*\n[^\n\r]*\n[^\n\r]*\n[^\n\r]*\nHal\s*:\s*([^\n\r]+)/i',

            // Look for specific patterns in SRIKANDI documents
            '/Laporan\s+Monev\s+SRIKANDI/i',
            '/MONEV\s+SRIKANDI/i',
        ];

        foreach ($patterns as $index => $pattern) {
            if (preg_match($pattern, $content, $matches)) {
                Log::info("DocumentNumberExtractor: Found document name with pattern #" . ($index + 1) . ": " . $matches[0]);

                $documentName = null;

                if ($index < 5) {
                    // Patterns with capture groups (Hal:, Subject:, etc.)
                    $documentName = trim($matches[1]);
                } else {
                    // Direct patterns or complex patterns
                    $documentName = trim($matches[0]);
                }

                // Clean up the document name
                $documentName = self::cleanDocumentName($documentName);

                if (!empty($documentName)) {
                    Log::info("DocumentNumberExtractor: Extracted document name: {$documentName}");
                    return $documentName;
                }
            }
        }

        // Fallback: try to extract from filename if it contains meaningful words
        $fileName = basename($content);
        $cleanFileName = self::extractNameFromFilename($fileName);
        if ($cleanFileName) {
            Log::info("DocumentNumberExtractor: Extracted document name from filename: {$cleanFileName}");
            return $cleanFileName;
        }

        Log::info("DocumentNumberExtractor: No document name patterns matched");
        return null;
    }

    /**
     * Clean document name text
     */
    private static function cleanDocumentName($name)
    {
        // Remove common prefixes
        $name = preg_replace('/^(Hal\s*:\s*|Subject\s*:\s*|Perihal\s*:\s*|Tentang\s*:\s*|Re\s*:\s*)/i', '', $name);

        // Remove extra whitespace
        $name = preg_replace('/\s+/', ' ', $name);

        // Trim
        $name = trim($name);

        // Remove trailing punctuation
        $name = rtrim($name, '.,;:-');

        return $name;
    }

    /**
     * Extract meaningful name from filename
     */
    private static function extractNameFromFilename($fileName)
    {
        // Remove file extension
        $name = pathinfo($fileName, PATHINFO_FILENAME);

        // Look for meaningful parts
        if (preg_match('/NOTA\s*DINAS.*MONEV.*SRIKANDI/i', $name)) {
            return 'Nota Dinas Monev SRIKANDI';
        }

        if (preg_match('/MONEV.*SRIKANDI/i', $name)) {
            return 'Monev SRIKANDI';
        }

        if (preg_match('/DRAFT.*NODIN.*MONEV/i', $name)) {
            return 'Draft Nota Dinas Monev';
        }

        // Remove common prefixes like dates, numbers
        $name = preg_replace('/^\d{8,14}[-_]?/', '', $name);
        $name = preg_replace('/^DRAFT[-_\s]*/i', '', $name);

        // Replace underscores and dashes with spaces
        $name = str_replace(['_', '-'], ' ', $name);

        // Remove multiple spaces
        $name = preg_replace('/\s+/', ' ', $name);

        $name = trim($name);

        // Only return if it's meaningful (more than 3 characters and contains letters)
        if (strlen($name) > 3 && preg_match('/[a-zA-Z]/', $name)) {
            return ucwords(strtolower($name));
        }

        return null;
    }
}

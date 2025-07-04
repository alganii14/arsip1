<?php

// Comprehensive test for document name extraction and cleaning

require_once 'app/Helpers/DocumentNumberExtractor.php';

use App\Helpers\DocumentNumberExtractor;

function testExtraction($content, $description) {
    echo "=== $description ===\n";
    echo "Input content:\n$content\n";

    $result = DocumentNumberExtractor::findDocumentName($content);

    echo "Extracted name: '$result'\n";
    echo "---\n\n";

    return $result;
}

// Test cases
echo "=== Comprehensive Document Name Extraction Tests ===\n\n";

// Test 1: Normal case with "Hal:"
$content1 = "Nomor : 123/ABC/2024
Tanggal : 15 Desember 2024
Sifat : Biasa
Lampiran : -
Hal : Penyerahan SK Kenaikan Pangkat Periode Desember 2024

Yth. Bapak/Ibu Pegawai";

testExtraction($content1, "Normal case with Hal field");

// Test 2: Case with malformed prefix in content
$content2 = "Nomor : 456/DEF/2024
Tanggal : 16 Desember 2024
Sifat : Biasa
Lampiran : -
Hal : : : Biasa - Penyerahan SK Kenaikan Pangkat Periode Desember 2024

Yth. Kepala Bagian";

testExtraction($content2, "Case with malformed prefix in Hal field");

// Test 3: Multi-line Hal field
$content3 = "Nomor : 789/GHI/2024
Tanggal : 17 Desember 2024
Sifat : Biasa
Lampiran : -
Hal : Penyerahan SK Kenaikan Pangkat
      Periode Desember 2024

Yth. Direktur";

testExtraction($content3, "Multi-line Hal field");

// Test 4: Subject field instead of Hal
$content4 = "Number : 101/JKL/2024
Date : 18 December 2024
Subject : : Biasa - Annual Performance Review

Dear Sir/Madam";

testExtraction($content4, "Subject field with malformed prefix");

// Test 5: Case where Sifat field contains document info (should be ignored)
$content5 = "Nomor : 202/MNO/2024
Tanggal : 19 Desember 2024
Sifat : Important Document - Please Review
Lampiran : -
Hal : Regular Monthly Report

Yth. Manager";

testExtraction($content5, "Case with Sifat field containing document info (should ignore Sifat)");

echo "=== All tests completed ===\n";

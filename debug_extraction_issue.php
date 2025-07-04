<?php
// Debug the extraction issue where "Yth." is not being caught properly

$content = "Surat Biasa
Nomor : 005/AR.01/XII/2024
Tanggal : 5 Desember 2024

Hal : Penyerahan SK Kenaikan Pangkat
      Periode Desember 2024

Yth. Kepala Perangkat Daerah
     (Daftar Terlampir)

Di
Bandung

Disampaikan dengan hormat, sehubungan telah terselesaikannya proses kenaikan pangkat periode Desember 2024...";

echo "=== DEBUGGING EXTRACTION ISSUE ===\n";
echo "Content:\n";
echo $content . "\n\n";

// Current pattern
$pattern = '/Hal\s*:\s*(.*?)(?=\n\s*\n|\n\s*Kepada|\n\s*Yth\.|\n\s*[A-Z][a-z]*\s*:|$)/s';

echo "=== TESTING CURRENT PATTERN ===\n";
echo "Pattern: " . $pattern . "\n";

if (preg_match($pattern, $content, $matches)) {
    echo "Match found!\n";
    echo "Full match: '" . $matches[0] . "'\n";
    echo "Capture group: '" . $matches[1] . "'\n";
} else {
    echo "No match found\n";
}

echo "\n=== TESTING IMPROVED PATTERN ===\n";
// Try a more precise pattern
$improvedPattern = '/Hal\s*:\s*(.*?)(?=\n\s*Yth\.|\n\s*Kepada|\n\s*\n)/s';
echo "Pattern: " . $improvedPattern . "\n";

if (preg_match($improvedPattern, $content, $matches)) {
    echo "Match found!\n";
    echo "Full match: '" . $matches[0] . "'\n";
    echo "Capture group: '" . $matches[1] . "'\n";
} else {
    echo "No match found\n";
}

echo "\n=== TESTING MOST PRECISE PATTERN ===\n";
// Even more precise pattern
$mostPrecisePattern = '/Hal\s*:\s*(.*?)(?=\n\s*Yth\.)/s';
echo "Pattern: " . $mostPrecisePattern . "\n";

if (preg_match($mostPrecisePattern, $content, $matches)) {
    echo "Match found!\n";
    echo "Full match: '" . $matches[0] . "'\n";
    echo "Capture group: '" . $matches[1] . "'\n";

    // Clean the result
    $cleaned = trim($matches[1]);
    $cleaned = preg_replace('/\s*\n\s*/', ' ', $cleaned);
    $cleaned = preg_replace('/\s+/', ' ', $cleaned);
    $cleaned = trim($cleaned);

    echo "Cleaned result: '" . $cleaned . "'\n";
} else {
    echo "No match found\n";
}
?>

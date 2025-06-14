<?php

namespace App\Helpers;

class ClassificationFormatter
{
    /**
     * Get description for classification code
     *
     * @param string $code
     * @return string
     */
    public static function getDescription($code)
    {
        $description = '';

        if (strpos($code, 'AR.') === 0) {
            $parts = explode('.', $code);
            $mainCode = isset($parts[1]) ? $parts[1] : null;
            $subCode1 = isset($parts[2]) ? $parts[2] : null;
            $subCode2 = isset($parts[3]) ? $parts[3] : null;

            if ($mainCode == '01') {
                $description = 'Kebijakan';
                if ($subCode1 == '01') {
                    $description .= ' - Peraturan Daerah';
                    if ($subCode2 == '01') $description .= ' - Pengkajian dan Pengusulan';
                    elseif ($subCode2 == '02') $description .= ' - Penyusunan Rancangan Peraturan Daerah';
                    elseif ($subCode2 == '03') $description .= ' - Pembahasan dan Persetujuan Rancangan Peraturan Daerah';
                    elseif ($subCode2 == '04') $description .= ' - Penetapan Peraturan Daerah';
                    elseif ($subCode2 == '05') $description .= ' - Sosialisasi Peraturan Daerah';
                }
                elseif ($subCode1 == '02') {
                    $description .= ' - Peraturan Wali Kota';
                    if ($subCode2 == '01') $description .= ' - Pengkajian dan Pembahasan Rancangan';
                    elseif ($subCode2 == '02') $description .= ' - Pengusulan dan Penetapan';
                    elseif ($subCode2 == '03') $description .= ' - Sosialisasi';
                }
                elseif ($subCode1 == '03') {
                    $description .= ' - Penetapan Organisasi Kearsipan';
                    if ($subCode2 == '01') $description .= ' - Unit Pengolah';
                    elseif ($subCode2 == '02') $description .= ' - Unit Kearsipan';
                }
            }
            elseif ($mainCode == '02') {
                $description = 'Pembinaan Kearsipan';
                if ($subCode1 == '01') {
                    $description .= ' - Bina Arsiparis';
                    if ($subCode2 == '01') $description .= ' - Formasi Jabatan Arsiparis';
                    elseif ($subCode2 == '02') $description .= ' - Standar Kompetensi Arsiparis';
                    elseif ($subCode2 == '03') $description .= ' - Bimbingan Konsultasi Arsiparis';
                    elseif ($subCode2 == '04') $description .= ' - Penilaian Arsiparis';
                    elseif ($subCode2 == '05') $description .= ' - Penyelenggaraan Pemilihan Arsiparis Teladan';
                    elseif ($subCode2 == '06') $description .= ' - Berkas Penetapan Arsiparis Teladan';
                    elseif ($subCode2 == '07') $description .= ' - Data Base Arsiparis';
                }
                elseif ($subCode1 == '02') {
                    $description .= ' - Bimbingan dan Konsultasi';
                    if ($subCode2 == '01') $description .= ' - Penerapan Sistem Kearsipan';
                    elseif ($subCode2 == '02') $description .= ' - Penggunaan Sarana dan Prasarana Kearsipan';
                    elseif ($subCode2 == '03') $description .= ' - Unit Kearsipan';
                    elseif ($subCode2 == '04') $description .= ' - Sumber Daya Manusia';
                }
                elseif ($subCode1 == '03') {
                    $description .= ' - Supervisi dan Evaluasi';
                    if ($subCode2 == '01') $description .= ' - Perencanaan';
                    elseif ($subCode2 == '02') $description .= ' - Pelaksanaan';
                    elseif ($subCode2 == '03') $description .= ' - Laporan Hasil Supervisi dan Evaluasi';
                }
                elseif ($subCode1 == '04') $description .= ' - Data Base Bimbingan, Supervisi dan Evaluasi';
                elseif ($subCode1 == '05') {
                    $description .= ' - Fasilitasi Kearsipan';
                    if ($subCode2 == '01') $description .= ' - Sumber Daya Manusia Kearsipan';
                    elseif ($subCode2 == '02') $description .= ' - Prasarana dan Sarana';
                }
                elseif ($subCode1 == '06') {
                    $description .= ' - Lembaga/Unit Kearsipan Teladan';
                    if ($subCode2 == '01') $description .= ' - Penyelenggaraan';
                    elseif ($subCode2 == '02') $description .= ' - Berkas Penetapan Lembaga/Unit Kearsipan Teladan';
                }
            }
            elseif ($mainCode == '03') {
                $description = 'Pengelolaan Arsip Dinamis';
                if ($subCode1 == '01') {
                    $description .= ' - Penciptaan';
                    if ($subCode2 == '01') $description .= ' - Pencatatan';
                    elseif ($subCode2 == '02') $description .= ' - Pendistribusian';
                }
                elseif ($subCode1 == '02') {
                    $description .= ' - Penggunaan';
                    if ($subCode2 == '01') $description .= ' - Pengklasifikasian Pengamanan dan Akses';
                    elseif ($subCode2 == '02') $description .= ' - Peminjaman';
                }
                elseif ($subCode1 == '03') {
                    $description .= ' - Pemeliharaan';
                    if ($subCode2 == '01') $description .= ' - Pemberkasan';
                    elseif ($subCode2 == '02') $description .= ' - Penataan Arsip Inaktif';
                }
                elseif ($subCode1 == '04') {
                    $description .= ' - Penyimpanan';
                    if ($subCode2 == '01') $description .= ' - Skema Penyimpanan';
                    elseif ($subCode2 == '02') $description .= ' - Pengamanan';
                }
                elseif ($subCode1 == '05') {
                    $description .= ' - Alih Media';
                    if ($subCode2 == '01') $description .= ' - Kebijakan Alih Media';
                    elseif ($subCode2 == '02') $description .= ' - Autentikasi';
                    elseif ($subCode2 == '03') $description .= ' - Berita Acara';
                    elseif ($subCode2 == '04') $description .= ' - Daftar Arsip Alih Media';
                }
                elseif ($subCode1 == '06') {
                    $description .= ' - Program Arsip Vital';
                    if ($subCode2 == '01') $description .= ' - Identifikasi';
                    elseif ($subCode2 == '02') $description .= ' - Pelindungan dan Pengamanan';
                    elseif ($subCode2 == '03') $description .= ' - Penyelamatan dan Pemulihan';
                }
                elseif ($subCode1 == '07') {
                    $description .= ' - Autentikasi Arsip Dinamis';
                    if ($subCode2 == '01') $description .= ' - Pembuktian Autentisitas';
                    elseif ($subCode2 == '02') $description .= ' - Pendapat Tenaga Ahli';
                    elseif ($subCode2 == '03') $description .= ' - Pengujian';
                    elseif ($subCode2 == '04') $description .= ' - Penetapan Autentisitas';
                }                elseif ($subCode1 == '08') {
                    $description .= ' - Penyusutan';
                    if ($subCode2 == '01') $description .= ' - Pemindahan Arsip Inaktif';
                    elseif ($subCode2 == '02') {
                        $description .= ' - Pemusnahan Arsip yang Tidak Bernilai Guna';
                        if (isset($parts[4]) && $parts[4] == '01') $description .= ' - Panitia Penilai';
                        elseif (isset($parts[4]) && $parts[4] == '02') $description .= ' - Penilaian Panitia Penilai';
                        elseif (isset($parts[4]) && $parts[4] == '03') $description .= ' - Permintaan Persetujuan';
                        elseif (isset($parts[4]) && $parts[4] == '04') $description .= ' - Penetapan Arsip yang Dimusnakan';
                        elseif (isset($parts[4]) && $parts[4] == '05') $description .= ' - Berita Acara Pemusnahan Arsip';
                        elseif (isset($parts[4]) && $parts[4] == '06') $description .= ' - Daftar Arsip yang Dimusnakan';
                    }
                    elseif ($subCode2 == '03') {
                        $description .= ' - Penyerahan Arsip Statis';
                        if (isset($parts[4]) && $parts[4] == '01') $description .= ' - Pembentukan Panitia Penilai';
                        elseif (isset($parts[4]) && $parts[4] == '02') $description .= ' - Notulen Rapat';
                        elseif (isset($parts[4]) && $parts[4] == '03') $description .= ' - Surat Pertimbangan Panitia Penilai';
                        elseif (isset($parts[4]) && $parts[4] == '04') $description .= ' - Surat Persetujuan dari Kepala Lembaga Kearsipan';
                        elseif (isset($parts[4]) && $parts[4] == '05') $description .= ' - Surat Pernyataan Autentik, Terpercaya, Utuh, dan dapat Digunakan dari Pencipta Arsip';
                        elseif (isset($parts[4]) && $parts[4] == '06') $description .= ' - Keputusan Penetapan Penyerahan';
                        elseif (isset($parts[4]) && $parts[4] == '07') $description .= ' - Berita Acara Penyerahan Arsip';
                        elseif (isset($parts[4]) && $parts[4] == '08') $description .= ' - Daftar Arsip yang Diserahkan';
                    }
                }                elseif ($subCode1 == '09') {
                    $description .= ' - Data Base Pengelolaan Arsip Dinamis';
                    if ($subCode2 == '01') $description .= ' - Data Base Pengelolaan Arsip Aktif';
                    elseif ($subCode2 == '02') $description .= ' - Data Base Pengelolaan Arsip Inaktif';
                }
            }                elseif ($mainCode == '05') {
                $description = 'Jasa Kearsipan';
                if ($subCode1 == '01') $description .= ' - Konsultasi Kearsipan';
                elseif ($subCode1 == '02') $description .= ' - Manual Kearsipan';
                elseif ($subCode1 == '03') $description .= ' - Penataan Arsip';
                elseif ($subCode1 == '04') $description .= ' - Otomasi Kearsipan';
                elseif ($subCode1 == '05') $description .= ' - Penyimpanan Arsip/Dokumen';
                elseif ($subCode1 == '06') $description .= ' - Perawatan Arsip/Dokumen';
                elseif ($subCode1 == '07') $description .= ' - Data Base Jasa Kearsipan';
            }
            elseif ($mainCode == '06') {
                $description = 'Pembinaan dan Pengawasan Kearsipan';
                if ($subCode1 == '01') {
                    $description .= ' - Pembinaan Internal';
                    if ($subCode2 == '01') {
                        $description .= ' - Kegiatan Pembinaan terhadap Perangkat Daerah';
                    } elseif ($subCode2 == '02') {
                        $description .= ' - Laporan Hasil Pembinaan Perangkat Daerah';
                    }
                }
                elseif ($subCode1 == '02') {
                    $description .= ' - Pembinaan Eksternal';
                    if ($subCode2 == '01') {
                        $description .= ' - Kegiatan Pembinaan terhadap BUMD, Orpol, Ormas, Swasta, dan Masyarakat';
                    } elseif ($subCode2 == '02') {
                        $description .= ' - Laporan Hasil Pembinaan Eksternal';
                    }
                }
                elseif ($subCode1 == '03') {
                    $description .= ' - Pengawasan Internal';
                    if ($subCode2 == '01') {
                        $description .= ' - Kegiatan Pengawasan terhadap Perangkat Daerah';
                    } elseif ($subCode2 == '02') {
                        $description .= ' - Laporan Hasil Audit Kearsipan Internal terhadap Perangkat Daerah';
                    }
                }
                elseif ($subCode1 == '04') {
                    $description .= ' - Pengawasan Eksternal';
                    if ($subCode2 == '01') {
                        $description .= ' - Kegiatan Pengawasan Kearsipan Eksternal terhadap BUMD, Orpol, Ormas, Swasta, dan Masyarakat';
                    } elseif ($subCode2 == '02') {
                        $description .= ' - Laporan Hasil Audit Kearsipan Eksternal';
                    }
                }
            }
            elseif ($mainCode == '04') {
                $description = 'Pengelolaan Arsip Statis';
                if ($subCode1 == '01') {
                    $description .= ' - Akuisisi';
                    if ($subCode2 == '01') $description .= ' - Monitoring Fisik dan Daftar';
                    elseif ($subCode2 == '02') $description .= ' - Verifikasi Terhadap Daftar Arsip';
                    elseif ($subCode2 == '03') $description .= ' - Menetapkan Status Arsip Statis';
                    elseif ($subCode2 == '04') $description .= ' - Persetujuan untuk Penyerahan';
                    elseif ($subCode2 == '05') $description .= ' - Penetapan Arsip yang Diserahkan';
                    elseif ($subCode2 == '06') $description .= ' - Berita Acara Penyerahan Arsip';
                    elseif ($subCode2 == '07') $description .= ' - Daftar Arsip yang Diserahkan';
                }
                elseif ($subCode1 == '02') {
                    $description .= ' - Sejarah Lisan';
                    if ($subCode2 == '01') $description .= ' - Administrasi Pelaksanaan Sejarah Lisan';
                    elseif ($subCode2 == '02') {
                        $description .= ' - Hasil Wawancara Sejarah Lisan';
                        if (isset($parts[4]) && $parts[4] == '01') $description .= ' - Berita Acara Wawancara Sejarah Lisan';
                        elseif (isset($parts[4]) && $parts[4] == '02') $description .= ' - Laporan Kegiatan';
                        elseif (isset($parts[4]) && $parts[4] == '03') $description .= ' - Hasil Wawancara (kaset atau CD) dan Transkrip';
                    }
                }
                elseif ($subCode1 == '03') {
                    $description .= ' - Daftar Pencarian Arsip Statis';
                    if ($subCode2 == '01') $description .= ' - Pengumuman';
                    elseif ($subCode2 == '02') $description .= ' - Akuisisi Daftar Pencarian Arsip Statis';
                }
                elseif ($subCode1 == '04') $description .= ' - Penghargaan dan Imbalan';
                elseif ($subCode1 == '05') {
                    $description .= ' - Pengolahan';
                    if ($subCode2 == '01') $description .= ' - Menata Informasi';
                    elseif ($subCode2 == '02') $description .= ' - Menata Fisik';
                    elseif ($subCode2 == '03') $description .= ' - Menyusun Sarana Bantu Temu Balik: Daftar Arsip Statis, Inventaris Arsip Statis dan Petunjuk';
                }
                elseif ($subCode1 == '06') {
                    $description .= ' - Preservasi Preventif';
                    if ($subCode2 == '01') $description .= ' - Penyimpanan';
                    elseif ($subCode2 == '02') $description .= ' - Pengendalian Hama Terpadu';
                    elseif ($subCode2 == '03') $description .= ' - Reproduksi (Alih Media): Berita Acara Alih Media dan Daftar Arsip Alih Media';
                    elseif ($subCode2 == '04') $description .= ' - Perencanaan dan Penanggulangan Bencana';
                }
                elseif ($subCode1 == '07') {
                    $description .= ' - Preventif Kuratif';
                    if ($subCode2 == '01') $description .= ' - Perawatan Arsip';
                    elseif ($subCode2 == '02') $description .= ' - Laporan Hasil Pengujian Preservasi';
                }
                elseif ($subCode1 == '08') {
                    $description .= ' - Autentikasi Arsip Statis';
                    if ($subCode2 == '01') $description .= ' - Pembuktian Autentisitas';
                    elseif ($subCode2 == '02') $description .= ' - Pendapat Tenaga Ahli';
                    elseif ($subCode2 == '03') $description .= ' - Pengujian';
                    elseif ($subCode2 == '04') $description .= ' - Penetapan Autentisitas Arsip Statis/Surat Pernyataan';
                }
                elseif ($subCode1 == '09') {
                    $description .= ' - Akses Arsip Statis';
                    if ($subCode2 == '01') $description .= ' - Layanan Arsip Statis';
                    elseif ($subCode2 == '02') $description .= ' - Administrasi dan Proses Penyusunan Penerbitan Naskah Sumber';
                    elseif ($subCode2 == '03') $description .= ' - Hasil Naskah Sumber Arsip';
                    elseif ($subCode2 == '04') $description .= ' - Pameran Arsip';
                }
            }
        }
        // Handle KP (Kepegawaian) classification
        elseif (strpos($code, 'KP.') === 0) {
            $parts = explode('.', $code);
            $mainCode = isset($parts[1]) ? $parts[1] : null;
            $subCode1 = isset($parts[2]) ? $parts[2] : null;
            $subCode2 = isset($parts[3]) ? $parts[3] : null;

            if ($mainCode == '01') {
                $description = 'Persediaan Pegawai';
            }
            elseif ($mainCode == '02') {
                $description = 'Formasi Pegawai';
                if ($subCode1 == '01') $description .= ' - Usulan Unit Kerja';
                elseif ($subCode1 == '02') $description .= ' - Usulan Formasi';
                elseif ($subCode1 == '03') $description .= ' - Persetujuan/Penetapan Formasi';
                elseif ($subCode1 == '04') $description .= ' - Penetapan Formasi Khusus';
            }
            elseif ($mainCode == '03') {
                $description = 'Pengadaan Formasi';
                if ($subCode1 == '01') $description .= ' - Penerimaan';
                elseif ($subCode1 == '02') $description .= ' - Pengangkatan CPNS dan PNS';
                elseif ($subCode1 == '03') $description .= ' - Prajabatan';
            }
            elseif ($mainCode == '04') {
                $description = 'Ujian Kenaikan Pangkat/Jabatan';
                if ($subCode1 == '01') $description .= ' - Ujian Penyesuaian Ijazah';
                elseif ($subCode1 == '02') $description .= ' - Ujian Dinas';

            }
            elseif ($mainCode == '05') {
                $description = 'Ujian Kompetensi';
                if ($subCode1 == '01') $description .= ' - Assessment Test Pegawai';
                elseif ($subCode1 == '02') $description .= ' - Talent Mapping/Pemetaan Pegawai';
            }
            elseif ($mainCode == '06') {
                $description = 'Mutasi';
                if ($subCode1 == '01') $description .= ' - Kenaikan Pangkat/Golongan';
                elseif ($subCode1 == '02') $description .= ' - Kenaikan Gaji Berkala';
                elseif ($subCode1 == '03') $description .= ' - Penyesuaian Masa Kerja';
                elseif ($subCode1 == '04') $description .= ' - Penyesuaian Tunjangan Keluarga';
                elseif ($subCode1 == '05') $description .= ' - Penyesuaian Kelas Jabatan';
                elseif ($subCode1 == '06') $description .= ' - Alih Tugas';
            }
            elseif ($mainCode == '07') {
                $description = 'Pengangkatan dan Pemberhentian Jabatan';
                if ($subCode1 == '01') $description .= ' - Pengangkatan Jabatan';
                elseif ($subCode1 == '02') $description .= ' - Pemberhentian Jabatan Struktural';
            }
            elseif ($mainCode == '08') {
                $description = 'Pendelegasian Wewenang';
                if ($subCode1 == '01') $description .= ' - Penjabat (Pj)';
                elseif ($subCode1 == '02') $description .= ' - Pelaksana Tugas (Plt)';
                elseif ($subCode1 == '03') $description .= ' - Pelaksana Harian (Plh)';
            }
            elseif ($mainCode == '09') {
                $description = 'Pendidikan dan Pelatihan Pegawai';
                if ($subCode1 == '01') $description .= ' - Program Diploma';
                elseif ($subCode1 == '02') $description .= ' - Program Sarjana';
                elseif ($subCode1 == '03') $description .= ' - Program Pasca Sarjana';
                elseif ($subCode1 == '04') $description .= ' - Pendidikan dan Pelatihan Penjenjangan';
                elseif ($subCode1 == '05') $description .= ' - Kursus/Diklat Fungsional';
                elseif ($subCode1 == '06') $description .= ' - Kurus/Diklat Teknis';
                elseif ($subCode1 == '07') $description .= ' - Orientasi CPNS';
            }
            elseif ($mainCode == '10') {
                $description = 'Administrasi Pegawai';
                if ($subCode1 == '01') $description .= ' - Data/Keterangan Pegawai';
                elseif ($subCode1 == '02') $description .= ' - Kartu Pegawai';
                elseif ($subCode1 == '03') $description .= ' - Karis/Karsu';
                elseif ($subCode1 == '04') $description .= ' - Kartu Taspen';
                elseif ($subCode1 == '05') $description .= ' - Kartu Jaminan Kesehatan';
                elseif ($subCode1 == '06') $description .= ' - Tanda Jasa';
                elseif ($subCode1 == '07') $description .= ' - Keterangan Penerimaan Pembiayaan Penghasilan Pegawai (KP4)';
                elseif ($subCode1 == '08') $description .= ' - Laporan Harta Kekayaan Penyelenggara Negara (LHKPN)';
                elseif ($subCode1 == '09') $description .= ' - Tunjangan Kinerja dan Uang Makan';
            }
            elseif ($mainCode == '11') {
                $description = 'Cuti Pegawai';
                if ($subCode1 == '01') $description .= ' - Cuti Tahunan';
                elseif ($subCode1 == '02') $description .= ' - Cuti Besar';
                elseif ($subCode1 == '03') $description .= ' - Cuti Sakit';
                elseif ($subCode1 == '04') $description .= ' - Cuti Bersalin';
                elseif ($subCode1 == '05') $description .= ' - Cuti Karena Alasan Penting';
                elseif ($subCode1 == '06') $description .= ' - Cuti di Luar Tanggungan Negara';
            }
            elseif ($mainCode == '12') {
                $description = 'Pembinaan Pegawai';
                if ($subCode1 == '01') $description .= ' - Penilaian Prestasi Kerja';
                elseif ($subCode1 == '02') $description .= ' - Sasaran Kerja Pegawai';
                elseif ($subCode1 == '03') $description .= ' - Pembinaan Mental';
                elseif ($subCode1 == '04') $description .= ' - Hukuman Disiplin';
            }
            elseif ($mainCode == '13') {
                $description = 'Pembinaan Jabatan Fungsional';
                if ($subCode1 == '01') $description .= ' - Pengangkatan Jabatan Fungsional Tertentu';
                elseif ($subCode1 == '02') $description .= ' - Kenaikan Jenjang Jabatan';
                elseif ($subCode1 == '03') $description .= ' - Pemindahan Jabatan Fungsional Tertentu';
                elseif ($subCode1 == '04') $description .= ' - Pengangkatan Jabatan Fungsional Umum';
                elseif ($subCode1 == '05') $description .= ' - Pemindahan Jabatan Fungsional Umum';
                elseif ($subCode1 == '06') $description .= ' - Pemberhentian';
            }
            elseif ($mainCode == '14') {
                $description = 'Kesejahteraan';
                if ($subCode1 == '01') $description .= ' - Kesehatan';
                elseif ($subCode1 == '02') $description .= ' - Rekreasi/Kesenian/Olahraga';
                elseif ($subCode1 == '03') $description .= ' - Bantuan Sosial';
                elseif ($subCode1 == '04') $description .= ' - Perumahan';
            }
            elseif ($mainCode == '15') {
                $description = 'Pemberhentian Pegawai';
                if ($subCode1 == '01') $description .= ' - Dengan Hormat';
                elseif ($subCode1 == '02') $description .= ' - Tidak dengan Hormat';
            }
            elseif ($mainCode == '16') {
                $description = 'Pemberhentian dan Penetapan Pensiun Pegawai/Janda/Duda/PNS yang Tewas';
            }
            elseif ($mainCode == '17') {
                $description = 'Perselisihan/Sengketa Pegawai';
            }
            elseif ($mainCode == '18') {
                $description = 'Organisasi Non Kedinasan';
                if ($subCode1 == '01') $description .= ' - KORPRI';
                elseif ($subCode1 == '02') $description .= ' - Dharma Wanita';
                elseif ($subCode1 == '03') $description .= ' - Koperasi';
                elseif ($subCode1 == '04') $description .= ' - Lain-lain';
            }
        }
        // Handle RT (Kerumahtanggaan) classification
        elseif (strpos($code, 'RT.') === 0) {
            $parts = explode('.', $code);
            $mainCode = isset($parts[1]) ? $parts[1] : null;
            $subCode1 = isset($parts[2]) ? $parts[2] : null;
            $subCode2 = isset($parts[3]) ? $parts[3] : null;

            if ($mainCode == '01') {
                $description = 'Perjalanan Dinas Pimpinan';
                if ($subCode1 == '01') $description .= ' - Dalam Negeri';
                elseif ($subCode1 == '02') $description .= ' - Luar Negeri';
            }
            elseif ($mainCode == '02') {
                $description = 'Rapat Pimpinan';
                if ($subCode1 == '01') $description .= ' - Sarana dan Prasarana';
                elseif ($subCode1 == '02') $description .= ' - Jamuan Rapat';
            }
            elseif ($mainCode == '03') {
                $description = 'Kantor';
                if ($subCode1 == '01') $description .= ' - Pemeliharaan Gedung';
                elseif ($subCode1 == '02') $description .= ' - Perlengkapan Kantor';
                elseif ($subCode1 == '03') $description .= ' - Air, Listrik dan Telekomunikasi';
                elseif ($subCode1 == '04') $description .= ' - Keamanan Kantor';
                elseif ($subCode1 == '05') $description .= ' - Kebersihan Kantor';
                elseif ($subCode1 == '06') $description .= ' - Jamuan Tamu';
                elseif ($subCode1 == '07') $description .= ' - Halaman dan Taman';
            }
            elseif ($mainCode == '05') {
                $description = 'Fasilitas Pimpinan';
                if ($subCode1 == '01') $description .= ' - Kendaraan Dinas';
                elseif ($subCode1 == '02') $description .= ' - Pengawalan dan Pengamanan';
                elseif ($subCode1 == '03') $description .= ' - Telekomunikasi';
            }
        }

        return $description;
    }

    /**
     * Get badge class based on classification code
     *
     * @param string $code
     * @return string
     */
    public static function getBadgeClass($code)
    {
        if (strpos($code, 'AR.') === 0) {
            return 'border-primary text-primary bg-primary';
        } elseif (strpos($code, 'KP.') === 0) {
            return 'border-success text-success bg-success';
        } elseif (strpos($code, 'RT.') === 0) {
            return 'border-warning text-warning bg-warning';
        }

        return 'border-secondary text-secondary bg-secondary';
    }
}

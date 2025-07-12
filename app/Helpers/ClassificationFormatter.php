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
        // Handle KU (Keuangan) classification
        elseif (strpos($code, 'KU.') === 0) {
            $parts = explode('.', $code);
            $mainCode = isset($parts[1]) ? $parts[1] : null;
            $subCode1 = isset($parts[2]) ? $parts[2] : null;
            $subCode2 = isset($parts[3]) ? $parts[3] : null;
            $subCode3 = isset($parts[4]) ? $parts[4] : null;

            if ($mainCode == '01') {
                $description = 'Rencana Anggaran Pendapatan dan Belanja Daerah, dan Anggaran Pendapatan dan Belanja Daerah Perubahan';
                if ($subCode1 == '01') {
                    $description .= ' - Penyusunan Prioritas Plafon Anggaran';
                    if ($subCode2 == '01') $description .= ' - Kebijakan Umum, Strategi, Prioritas dan Renstra';
                    elseif ($subCode2 == '02') $description .= ' - Dokumen Rancangan kebijakan Umum Anggaran (KUA) yang telah dibahas bersama antara DPRD dan Pemerintah Daerah';
                    elseif ($subCode2 == '03') $description .= ' - KUA beserta Nota Kesepakatannya';
                    elseif ($subCode2 == '04') $description .= ' - Dokumen Rancangan Prioritas Plafon Anggaran Sementara (PPAS)';
                    elseif ($subCode2 == '05') $description .= ' - Nota Kesepakatan PPA';
                    elseif ($subCode2 == '06') $description .= ' - Prioritas Plafon Anggaran';
                }
                elseif ($subCode1 == '02') {
                    $description .= ' - Penyusunan Rencana Kerja Anggaran Satuan Kerja Perangkat Daerah (RKA-SKPD)';
                    if ($subCode2 == '01') $description .= ' - Dokumen Pedoman Penyusunan RKA-SKPD yang telah disetujui Sekretaris Daerah';
                    elseif ($subCode2 == '02') $description .= ' - Dokumen RKA-SKPD';
                }
                elseif ($subCode1 == '03') {
                    $description .= ' - Penyampaian Rancangan Anggaran Pendapatan dan Belanja Daerah kepada Dewan Perwakilan Rakyat Daerah';
                    if ($subCode2 == '01') $description .= ' - Pengantar Nota Keuangan Pemerintah dan Rancangan Peraturan Daerah APBD';
                    elseif ($subCode2 == '02') $description .= ' - Hasil Pembahasan Rancangan Anggaran Pendapatan dan Belanja Daerah (RAPBD) oleh Dewan Perwakilan Rakyat Daerah (DPRD) dan Pemerintah Daerah';
                    elseif ($subCode2 == '03') $description .= ' - Dokumen Persetujuan Evaluasi kepada Gubernur tentang Rancangan Peraturan Daerah APBD Perubahan';
                    elseif ($subCode2 == '04') $description .= ' - Dokumen Rancangan Penjabaran APBD beserta Lampirannya';
                    elseif ($subCode2 == '05') $description .= ' - Penyampaian Permohonan Evaluasi kepada Gubernur tentang RAPBD beserta penjabarannya';
                    elseif ($subCode2 == '06') $description .= ' - Hasil Evaluasi Gubernur tentang RAPBD';
                    elseif ($subCode2 == '07') $description .= ' - Penetapan Peraturan Daerah APBD oleh Wali Kota';
                    elseif ($subCode2 == '08') $description .= ' - Peraturan Daerah tentang APBD';
                }
                elseif ($subCode1 == '04') {
                    $description .= ' - Anggaran Pendapatan dan Belanja Daerah Perubahan (RAPBD-P)';
                    if ($subCode2 == '01') $description .= ' - Kebijakan Umum, Strategi, Prioritas, dan Renstra';
                    elseif ($subCode2 == '02') $description .= ' - Dokumen Rancangan Kebijakan Umum Anggaran (KUA) yang telah dibahas bersama antara DPRD dan Pemerintah Daerah';
                    elseif ($subCode2 == '03') $description .= ' - KUA Perubahan beserta Nota Kesepakatannya';
                    elseif ($subCode2 == '04') $description .= ' - Dokumen Rancangan Prioritas Plafon Anggaran Sementara (PPAS) Perubahan';
                    elseif ($subCode2 == '05') $description .= ' - Nota Kesepakatan Prioritas Plafon Anggaran';
                    elseif ($subCode2 == '06') $description .= ' - Prioritas Plafon Anggaran Perubahan';
                }
                elseif ($subCode1 == '05') {
                    $description .= ' - Penyusunan Rencana Kerja Anggaran Satuan Kerja Perangkat Daerah (RKA-SKPD) Perubahan';
                    if ($subCode2 == '01') $description .= ' - Dokumen Pedoman Penyusunan RKA-SKPD Perubahan yang telah disetujui Sekretaris Daerah';
                    elseif ($subCode2 == '02') $description .= ' - Dokumen RKA-SKPD Perubahan';
                }
                elseif ($subCode1 == '06') {
                    $description .= ' - Penyampaian Rancangan Anggaran Pendapatan dan Belanja Daerah Perubahan kepada Dewan Perwakilan Rakyat Daerah (DPRD)';
                    if ($subCode2 == '01') $description .= ' - Pengantar Nota Keuangan Pemerintah dan Rancangan Peraturan Daerah RAPBD Perubahan, Nota Keuangan Pemerintah dan Materi RAPBD Perubahan';
                    elseif ($subCode2 == '02') $description .= ' - Hasil Pembahasan Rencana Anggaran Pendapatan dan Belanja Daerah (RAPBD) Perubahan oleh Dewan Perwakilan Rakyat Daerah (DPRD) dan Pemerintah Daerah';
                    elseif ($subCode2 == '03') $description .= ' - Dokumen Persetujuan Bersama antara DPRD dan Kepala Daerah tentang Rancangan Peraturan Daerah APBD Perubahan';
                    elseif ($subCode2 == '04') $description .= ' - Dokumen Rancangan Penjabaran APBD beserta Lampirannya';
                    elseif ($subCode2 == '05') $description .= ' - Penyampaian Permohonan Evaluasi kepada Gubernur tentang RAPBD Perubahan beserta Penjabarannya';
                    elseif ($subCode2 == '06') $description .= ' - Hasil Evaluasi Gubernur tentang RAPBD Perubahan';
                    elseif ($subCode2 == '07') $description .= ' - Penetapan Peraturan Daerah APBD Perubahan oleh Wali Kota beserta Penjabarannya';
                    elseif ($subCode2 == '08') $description .= ' - Peraturan Daerah tentang APBD Perubahan';
                }
            }
            elseif ($mainCode == '02') {
                $description = 'Penyusunan Anggaran';
                if ($subCode1 == '01') $description .= ' - Hasil Musyawarah Rencana Pembangunan (Musrenbang) Kelurahan';
                elseif ($subCode1 == '02') $description .= ' - Hasil Musyawarah Rencana Pembangunan (Musrenbang) Kecamatan';
                elseif ($subCode1 == '03') $description .= ' - Hasil Musyawarah Rencana Pembangunan (Musrenbang) Kota';
                elseif ($subCode1 == '04') $description .= ' - Rancangan Dokumen Pelaksanaan Anggaran (RDPA) SKPD yang telah disetujui Sekretaris Daerah';
                elseif ($subCode1 == '05') $description .= ' - Dokumen Pelaksanaan Anggaran (DPA) SKPD yang telah disahkan oleh Pejabat Pengelola Keuangan Daerah';
            }
            elseif ($mainCode == '03') {
                $description = 'Pelaksanaan Anggaran';
                if ($subCode1 == '01') {
                    $description .= ' - Surat Penyedia Dana (SPP, SPM, dan SP2D): UP, GU, TU, LS';
                }
                elseif ($subCode1 == '02') {
                    $description .= ' - Pendapatan Asli Daerah';
                    if ($subCode2 == '01') $description .= ' - Surat Setoran Pajak (SSP) Daerah Pajak Kendaraan Bermotor';
                    elseif ($subCode2 == '02') $description .= ' - Surat Setoran Pajak (SSP) Daerah Pajak Bea Balik Nama Kendaraan Bermotor (BBNKB)';
                    elseif ($subCode2 == '03') $description .= ' - Surat Setoran Pajak (SSP) Daerah Pajak Bahan Bakar Kendaraan Bermotor (PBBKB)';
                    elseif ($subCode2 == '04') $description .= ' - Surat Setoran Pajak (SSP) Daerah Pajak Air Permukaan';
                    elseif ($subCode2 == '05') $description .= ' - Surat Setoran Pajak (SSP) Daerah Pajak Rokok';
                    elseif ($subCode2 == '06') $description .= ' - Surat Ketetapan Retribusi Daerah';
                    elseif ($subCode2 == '07') $description .= ' - Bukti Pembayaran Retribusi Jasa Umum';
                    elseif ($subCode2 == '08') $description .= ' - Bukti Pembayaran Retribusi Jasa Usaha';
                    elseif ($subCode2 == '09') $description .= ' - Bukti Pembayaran Retribusi Perizinan Tertentu';
                    elseif ($subCode2 == '10') $description .= ' - Bukti Pembayaran Retribusi Pengendalian Lalu Lintas';
                    elseif ($subCode2 == '11') $description .= ' - Bukti Pembayaran Retribusi Perpanjangan Izin Mempekerjakan Tenaga Kerja Asing (IMTA)';
                    elseif ($subCode2 == '12') $description .= ' - Bukti Penerimaan Jasa Layanan Kesehatan Masyarakat';
                    elseif ($subCode2 == '13') $description .= ' - Dokumen Rasionalitas Hasil Pengelolaan Kekayaan Daerah';
                    elseif ($subCode2 == '14') $description .= ' - Bukti Penerimaan SKPD dari Badan Pengelolaan Pendapatan Daerah';
                    elseif ($subCode2 == '15') $description .= ' - Bukti Penerimaan dari Pengelolaan Dana Bergulir';
                    elseif ($subCode2 == '16') $description .= ' - Bukti Penerimaan Bunga dan atau Jasa Giro pada Bank';
                }
                elseif ($subCode1 == '03') {
                    $description .= ' - Dokumen Penerimaan Dana Perimbangan';
                    if ($subCode2 == '01') $description .= ' - Dana Bagi Hasil yang Bersumber dari Pajak dan Bukan Pajak';
                    elseif ($subCode2 == '02') $description .= ' - Dana Bagi Hasil untuk Kota';
                    elseif ($subCode2 == '03') $description .= ' - Dana Alokasi Umum (Dana Alokasi Umum)';
                    elseif ($subCode2 == '04') $description .= ' - Daerah yang Menerima DAU';
                    elseif ($subCode2 == '05') $description .= ' - Dana Alokasi Khusus (DAK)';
                }
                elseif ($subCode1 == '04') {
                    $description .= ' - Dokumen Penerimaan Lain-lain Pendapatan yang Sah';
                    if ($subCode2 == '01') $description .= ' - Alokasi Dana Penyesuaian';
                    elseif ($subCode2 == '02') $description .= ' - Dana Otonomi Khusus dan Bantuan Operasional Sekolah';
                    elseif ($subCode2 == '03') $description .= ' - Bagi Hasil Pajak dari Pemerintah Pusat';
                    elseif ($subCode2 == '04') $description .= ' - Bantuan Keuangan Pemerintah Pusat';
                    elseif ($subCode2 == '05') $description .= ' - Penerimaan Hibat yang Bersumber dari APBN, Pemerintah Daerah Lainnya atau Sumbangan dari Pihak Ketiga';
                }
                elseif ($subCode1 == '05') {
                    $description .= ' - Surat Setoran Bukan Pajak (SSBP)';
                }
                elseif ($subCode1 == '06') {
                    $description .= ' - Penerimaan Sisa Lebih Perhitungan Anggaran (SiLPA)';
                }
                elseif ($subCode1 == '07') {
                    $description .= ' - Dokumen Pengelolaan Barang Milik Negara/Daerah';
                }
                elseif ($subCode1 == '08') {
                    $description .= ' - Dokumen Piutang Daerah';
                }
                elseif ($subCode1 == '09') {
                    $description .= ' - Dokumen Pengelolaan Investasi';
                }
                elseif ($subCode1 == '10') {
                    $description .= ' - Dokumen Belanja Langsung';
                    if ($subCode2 == '01') $description .= ' - Belanja Pegawai';
                    elseif ($subCode2 == '02') $description .= ' - Belanja Barang Jasa';
                    elseif ($subCode2 == '03') $description .= ' - Belanja Modal';
                }
                elseif ($subCode1 == '11') {
                    $description .= ' - Dokumen Belanja Tidak Langsung';
                    if ($subCode2 == '01') $description .= ' - Pegawai';
                    elseif ($subCode2 == '02') $description .= ' - Hibah';
                    elseif ($subCode2 == '03') $description .= ' - Belanja Bagi Hasil';
                    elseif ($subCode2 == '04') $description .= ' - Subsidi';
                    elseif ($subCode2 == '05') $description .= ' - Bunga';
                    elseif ($subCode2 == '06') $description .= ' - Bantuan Sosial';
                    elseif ($subCode2 == '07') $description .= ' - Bantuan Keuangan Pemerintah Pusat';
                    elseif ($subCode2 == '08') $description .= ' - Belanja Tidak Terduga';
                }
            }
            elseif ($mainCode == '04') {
                $description = 'Pembiayaan Daerah';
                if ($subCode1 == '01') {
                    $description .= ' - Bukti Penerimaan Pembiayaan';
                    if ($subCode2 == '01') $description .= ' - SiLPA';
                    elseif ($subCode2 == '02') $description .= ' - Dana Cadangan';
                    elseif ($subCode2 == '03') $description .= ' - Dana Berguir';
                    elseif ($subCode2 == '04') $description .= ' - Pinjaman Daerah';
                    elseif ($subCode2 == '05') $description .= ' - Pengalihan Piutang PBB P2 menjadi PAD';
                }
                elseif ($subCode1 == '02') {
                    $description .= ' - Bukti Pengeluaran Pembiayaan';
                    if ($subCode2 == '01') $description .= ' - Investasi Jangka Panjang dalam Bentuk Dana Berguir';
                    elseif ($subCode2 == '02') $description .= ' - Penyertaan Modal pada BUMD';
                    elseif ($subCode2 == '03') $description .= ' - Penambahan Penyertaan Modal pada BUMD';
                    elseif ($subCode2 == '04') $description .= ' - Pengeluaran dari Dana Cadangan';
                    elseif ($subCode2 == '05') $description .= ' - Pembiayaan bagi Usaha Masyarakat Kecil dan Menengah (UMKM)';
                    elseif ($subCode2 == '06') $description .= ' - Penyertaan Modal pada Bank Perkreditan Rakyat (BPR) milik Pemerintah Daerah';
                }
            }
            elseif ($mainCode == '05') {
                $description = 'Dokumen Penatausahaan Keuangan';
                if ($subCode1 == '01') $description .= ' - Surat Penyediaan Dana (SPD)';
                elseif ($subCode1 == '02') $description .= ' - Surat Permohonan Pembayaran (SPP)';
                elseif ($subCode1 == '03') $description .= ' - Surat Perintah Membayar (SPM)';
                elseif ($subCode1 == '04') $description .= ' - Surat Perintah Pencairan Dana (SP2D)';
            }
            elseif ($mainCode == '06') {
                $description = 'Pertanggungjawaban Penggunaan Dana';
                if ($subCode1 == '01') $description .= ' - Buku Kas Umum (BKU)';
                elseif ($subCode1 == '02') $description .= ' - Buku Kas Pembantu (BKP)';
                elseif ($subCode1 == '03') $description .= ' - Ringkasan Perincian Pengeluaran Objek';
                elseif ($subCode1 == '04') $description .= ' - Rekening Koran Bank';
                elseif ($subCode1 == '05') $description .= ' - Pertanggungjawaban Fungsionalitas dan Administrasi';
                elseif ($subCode1 == '06') $description .= ' - Bukti Penyetoran Pajak';
                elseif ($subCode1 == '07') $description .= ' - Register Penutupan Kas';
                elseif ($subCode1 == '08') $description .= ' - Berita Acara Pemeriksaan';
                elseif ($subCode1 == '09') $description .= ' - Laporan Realisasi Anggaran (LRA), Neraca, Laporan Operasional (LO), Laporan Perubahan Ekuitas (LPE), Catatan Atas Laporan Keuangan (CaLK), Arsip Data Komputer (ADK)';
                elseif ($subCode1 == '10') $description .= ' - Laporan Pendapatan Daerah';
                elseif ($subCode1 == '11') $description .= ' - Laporan Keadaan Kredit Anggaran';
                elseif ($subCode1 == '12') $description .= ' - Laporan Realisasi Anggaran, Laporan Operasional, Neraca Bulanan/Triwulan/Semesteran';
                elseif ($subCode1 == '13') $description .= ' - Berita Acara Rekonsiliasi Data Realisasi Pendapatan Daerah';
                elseif ($subCode1 == '14') $description .= ' - Berita Acara Rekonsiliasi Data Realisasi Belanja Daerah dan Pembiayaan Daerah';
            }
            elseif ($mainCode == '07') {
                $description = 'Daftar Gaji';
            }
            elseif ($mainCode == '08') {
                $description = 'Kartu Gaji';
            }
            elseif ($mainCode == '09') {
                $description = 'Data Rekening Bendahara Umum Daerah (BUD)';
            }
            elseif ($mainCode == '10') {
                $description = 'Laporan Keuangan Tahunan';
                if ($subCode1 == '01') $description .= ' - Laporan Realisasi Anggaran (LRA)';
                elseif ($subCode1 == '02') $description .= ' - Laporan Perubahan Saldo Anggaran Lebih (LP-SAL)';
                elseif ($subCode1 == '03') $description .= ' - Neraca';
                elseif ($subCode1 == '04') $description .= ' - Laporan Operasional (LO)';
                elseif ($subCode1 == '05') $description .= ' - Laporan Arus Kas (LAK)';
                elseif ($subCode1 == '06') $description .= ' - Laporan Perubahan Ekuitas (LPE)';
                elseif ($subCode1 == '07') $description .= ' - Catatan Atas Laporan Keuangan (CaLK)';
            }
            elseif ($mainCode == '11') {
                $description = 'Bantuan/Pinjaman Luar Negeri';
                if ($subCode1 == '01') $description .= ' - Permohonan Pinjaman Luar Negeri (Blue Book)';
                elseif ($subCode1 == '02') $description .= ' - Dokumen Kesanggupan Negara Donor untuk Membiayai (Green Book)';
                elseif ($subCode1 == '03') $description .= ' - Dokumen Memorandum of Understanding (MoU), dan Dokumen Sejenisnya';
                elseif ($subCode1 == '04') $description .= ' - Dokumen Loan Agreement (PLHN) seperti Draft Agreement, Legal Opinion, Surat Menyurat dengan Lender';
                elseif ($subCode1 == '05') $description .= ' - Alokasi dan Relokasi Penggunaan Dana Luar Negeri, antara lain Usulan Luncuran Dana';
                elseif ($subCode1 == '06') {
                    $description .= ' - Aplikasi Penarikan Dana Bantuan Luar Negeri berikut Lampirannya';
                    if ($subCode2 == '01') $description .= ' - Reimbursement';
                    elseif ($subCode2 == '02') $description .= ' - Direct Payment/Transfer Procedure';
                    elseif ($subCode2 == '03') $description .= ' - Special Commitment/L/C Opening';
                    elseif ($subCode2 == '04') $description .= ' - Special Account/Impress Fund';
                }
                elseif ($subCode1 == '07') $description .= ' - Dokumen Otorisasi Penarikan Dana (Payment Advice)';
                elseif ($subCode1 == '08') $description .= ' - Dokumen Realisasi Pencairan Dana Bantuan Luar Negeri, yaitu: Surat Perintah Pencairan Dana, SPM beserta lampirannya, antara lain SPP, Kontrak, BA, dan Data Pendukung Lainnya';
                elseif ($subCode1 == '09') $description .= ' - Replenishment (Permintaan Penarikan Dana dari Negara Donor) meliputi antara lain No Object Letter (NOL), Project Implementation, Notification of Contract, Withdrawal Authorization (WA), Statement of Expenditure (SE)';
                elseif ($subCode1 == '10') $description .= ' - Staff Appraisal Report';
                elseif ($subCode1 == '11') {
                    $description .= ' - Report/Laporan yang terdiri dari';
                    if ($subCode2 == '01') $description .= ' - Progress Report';
                    elseif ($subCode2 == '02') $description .= ' - Monthly Report';
                    elseif ($subCode2 == '03') $description .= ' - Quarterly Report';
                }
                elseif ($subCode1 == '12') {
                    $description .= ' - Laporan Hutang Daerah';
                    if ($subCode2 == '01') $description .= ' - Laporan Pembayaran Hutang Daerah';
                    elseif ($subCode2 == '02') $description .= ' - Laporan Posisi Hutang Daerah';
                }
                elseif ($subCode1 == '13') $description .= ' - Completion Report/Annual Report';
                elseif ($subCode1 == '14') $description .= ' - Ketentuan/Peraturan yang Menyangkut Bantuan/Pinjaman Luar Negeri';
            }
            elseif ($mainCode == '12') {
                $description = 'Pengelolaan APBD/Dana Pinjaman/Hibah Luar Negeri (PHLN)';
                if ($subCode1 == '01') {
                    $description .= ' - Keputusan Kepala Daerah tentang Penetapan';
                    if ($subCode2 == '01') $description .= ' - Kuasa Penggunaan Anggaran';
                    elseif ($subCode2 == '02') $description .= ' - Kuasa Pengguna Barang/Jasa';
                    elseif ($subCode2 == '03') $description .= ' - Pejabat Pembuat Komitmen';
                    elseif ($subCode2 == '04') $description .= ' - Pejabat Pembuat Daftar Gaji';
                    elseif ($subCode2 == '05') $description .= ' - Pejabat Penandatanganan SPM';
                    elseif ($subCode2 == '06') $description .= ' - Bendahara Penerimaan/Pengeluaran';
                    elseif ($subCode2 == '07') $description .= ' - Pengelola Barang';
                    elseif ($subCode2 == '08') $description .= ' - Berita Acara Serah Terima Jabatan';
                }
            }
            elseif ($mainCode == '13') {
                $description = 'Akuntansi Pemerintah Daerah';
                if ($subCode1 == '01') $description .= ' - Kebijakan Akuntansi Pemerintah Daerah';
                elseif ($subCode1 == '02') $description .= ' - Sistem Akuntansi Pemerintah Daerah';
                elseif ($subCode1 == '03') $description .= ' - Bagan Akun Standar';
                elseif ($subCode1 == '04') $description .= ' - Arsip Data Komputer';
            }
            elseif ($mainCode == '14') {
                $description = 'Penyaluran Anggaran Tugas Pembantuan';
                if ($subCode1 == '01') $description .= ' - Penetapan Pemimpin Proyek/Bagian Proyek, Bendahara, atas Penggunaan Anggaran Kegiatan Pembantuan, termasuk Specimen Tanda Tangan';
                elseif ($subCode1 == '02') {
                    $description .= ' - Berkas Permintaan Pembayaran (SPP) dan lampirannya:';
                    if ($subCode2 == '01') $description .= ' - SPP-SPP-Daftar Perincian Penggunaan SPPR-SPDR-L, SPM-LS, SPM-DUA, Bilyet giro, SPM Nihil';
                    elseif ($subCode2 == '02') $description .= ' - Penagihan/Invoice, Faktur Pajak, Bukti Penerimaan Kas/Bank beserta Bukti Pendukungnya antara lain Copy Faktur Pajak dan Nota Kredit Bank';
                    elseif ($subCode2 == '03') $description .= ' - Permintaan Pelayanan Jasa/Service Report dan Berita Acara Penyelesaian Pekerjaan';
                }
                elseif ($subCode1 == '03') $description .= ' - Buku Rekening Bank';
                elseif ($subCode1 == '04') $description .= ' - Keputusan Pembukuan Rekening';
                elseif ($subCode1 == '05') {
                    $description .= ' - Pembukuan Anggaran terdiri dari:';
                    if ($subCode2 == '01') $description .= ' - Buku Kas Umum (BKU)';
                    elseif ($subCode2 == '02') $description .= ' - Buku Kas Pembantu';
                    elseif ($subCode2 == '03') $description .= ' - Register dan Buku Tambahan';
                    elseif ($subCode2 == '04') $description .= ' - Daftar Pembukuan Selama Rekening masih aktif';
                    elseif ($subCode2 == '05') $description .= ' - Pencairan/Pengeluaran (DPP)';
                    elseif ($subCode2 == '06') $description .= ' - Daftar Pembukuan Pencairan/Pengeluaran (DPP)';
                    elseif ($subCode2 == '07') $description .= ' - Daftar Himpunan Pencairan (DHIP)';
                    elseif ($subCode2 == '08') $description .= ' - Rekening Koran';
                }
            }
            elseif ($mainCode == '15') {
                $description = 'Penerimaan Anggaran Tugas Pembantuan';
                if ($subCode1 == '01') $description .= ' - Berkas Penerimaan Keuangan Pelaksanaan dan Tugas Pembantuan Termasuk Dana Sisa atau Pengeluaran Lainnya';
                elseif ($subCode1 == '02') $description .= ' - Berkas Penerimaan Pajak termasuk PPh 21, PPh 22, PPh 23, dan PPn, dan Denda Keterlambatan Menyelesaikan Pekerjaan';
            }
            elseif ($mainCode == '16') {
                $description = 'Pengelolaan Anggaran Pemilu';
                if ($subCode1 == '01') {
                    $description .= ' - Penyusunan Anggaran Pilkada dan Biaya Bantuan Pemilu dari APBD';
                    if ($subCode2 == '01') $description .= ' - Kebijakan Keuangan Pilkada dan Penyusunan Anggaran Bantuan Pemilu';
                    elseif ($subCode2 == '02') $description .= ' - Peraturan/Pedoman/Standar Belanja Pegawai, Barang dan Jasa, Operasional dan Kontingensi untuk Biaya Pilkada dan Bantuan Pemilu';
                    elseif ($subCode2 == '03') $description .= ' - Bahan Usulan Rencana Kegiatan dan Anggaran (RKA) Pilkada KPUD dan Panitia Pengawas Daerah Provinsi, PPK, PPS, KPPS dan Permohonan Pengajuan RKA KPUD dan Panitia Pengawas';
                    elseif ($subCode2 == '04') $description .= ' - Berkas Pembahasan RKA Pilkada dan Bantuan Pemilu';
                    elseif ($subCode2 == '05') $description .= ' - Rencana Anggaran Satuan Kerja (RASK) Pilkada dan Bantuan Pemilu Provinsi';
                    elseif ($subCode2 == '06') $description .= ' - Dokumen Rancangan Anggaran Satuan Kerja (DRASK) Pilkada KPUD dan Panitia Pengawas Provinsi dan Bantuan Biaya Pemilu dari APBD';
                    elseif ($subCode2 == '07') $description .= ' - Berkas Pembentukan Dana Cadangan Pilkada';
                    elseif ($subCode2 == '08') $description .= ' - Bahan Rapat Rancangan Peraturan Daerah tentang Pilkada, dan Bantuan Biaya Pemilu dari APBD';
                    elseif ($subCode2 == '09') $description .= ' - Nota Persetujuan DPRD tentang Peraturan Daerah Pilkada dan Bantuan Biaya Pemilu dari APBD';
                    elseif ($subCode2 == '10') {
                        $description .= ' - Pelaksanaan Anggaran Pilkada dan Anggaran Biaya Bantuan Pemilu';
                        if ($subCode3 == '01') $description .= ' - Berkas Penetapan Bendahara dan Atasan Langsung Bendahara KPUD, Bendahara Panitia Pengawas Daerah dan Bendahara pada Panitia Pilkada dan Pemilu';
                        elseif ($subCode3 == '02') $description .= ' - Berkas Penerimaan Komisi, Rabat Pembayaran Pengadaan Jasa, Bunga, Pelaksanaan Pilkada/Pemilu';
                        elseif ($subCode3 == '03') $description .= ' - Berkas Setor Sisa Dana Pilkada/Pemilu';
                        elseif ($subCode3 == '04') $description .= ' - Berkas Penyaluran Biaya Pemilu Termasuk Di antaranya Bukti Transfer Bank';
                        elseif ($subCode3 == '05') $description .= ' - Pedoman Dokumen Penyediaan Pembiayaan Kegiatan Operasional (PPKO) Pemilu Termasuk Perubahan/Pergeseran/Revisinya';
                    }
                    elseif ($subCode2 == '11') {
                        $description .= ' - Pelaksanaan Anggaran Operasional Pemilu';
                        if ($subCode3 == '01') $description .= ' - Dokumen Penyediaan Pembiayaan Kegiatan Operasional (PPKO) Pemilu termasuk Perubahan/Pergeseran/Revisinya';
                        elseif ($subCode3 == '02') $description .= ' - Berkas Penetapan Bendahara dan Atasan Langsung Bendahara KPUD Provinsi, Panitia Pengawas Daerah dan Pemegang Uang Muka Cabang (PUMC) PPK dan Panitia Pengawas';
                        elseif ($subCode3 == '03') $description .= ' - Berkas Penyaluran Biaya Pemilu ke PPK, PPS, dan KPPS Termasuk di antaranya Bukti Transfer Bank';
                    }
                    elseif ($subCode2 == '12') {
                        $description .= ' - Pemeriksaan/ Pengawasan Keuangan Daerah';
                        if ($subCode3 == '01') $description .= ' - Laporan Hasil Pemeriksaan Badan Pemeriksa Keuangan';
                        elseif ($subCode3 == '02') $description .= ' - Hasil Pengawasan dan Pemeriksaan Internal';
                        elseif ($subCode3 == '03') {
                            $description .= ' - Laporan Aparat Pemeriksaan Fungsional';
                            if (isset($parts[4])) {
                                $subCode4 = $parts[4];
                                if ($subCode4 == '01') $description .= ' - LHP (Laporan Hasil Pemeriksaan)';
                                elseif ($subCode4 == '02') $description .= ' - MHP (Memorandum Hasil Pemeriksaan)';
                                elseif ($subCode4 == '03') $description .= ' - Tindak Lanjut/Tanggapan LHP';
                            }
                        }
                        elseif ($subCode3 == '04') {
                            $description .= ' - Dokumen Penyelesaian Kerugian Daerah';
                            if (isset($parts[4])) {
                                $subCode4 = $parts[4];
                                if ($subCode4 == '01') $description .= ' - Tuntutan Perbendaharaan';
                                elseif ($subCode4 == '02') $description .= ' - Tuntutan Ganti Rugi';
                            }
                        }
                    }
                }
            }
            elseif ($mainCode == '17') {
                $description = 'Pengadaan Barang/Jasa';
                if ($subCode1 == '01') $description .= ' - Rencana Umum Pengadaan (RUP)';
                elseif ($subCode1 == '02') {
                    $description .= ' - Pelaksanaan Pengadaan';
                    if ($subCode2 == '01') $description .= ' - Swakelola';
                    elseif ($subCode2 == '02') $description .= ' - Pengadaan Langsung';
                    elseif ($subCode2 == '03') $description .= ' - Penunjukan Langsung';
                    elseif ($subCode2 == '04') $description .= ' - Tender';
                    elseif ($subCode2 == '05') $description .= ' - E-Purchasing';
                }
                elseif ($subCode1 == '03') $description .= ' - Laporan Pengadaan Barang/Jasa';
            }
        } elseif (strpos($code, 'KU.') === 0) {
            $parts = explode('.', $code);
            $mainCode = isset($parts[1]) ? $parts[1] : null;
            $subCode1 = isset($parts[2]) ? $parts[2] : null;
            $subCode2 = isset($parts[3]) ? $parts[3] : null;

            if ($mainCode == '01') {
                $description = 'Anggaran';
                if ($subCode1 == '01') $description .= ' - Rencana Kerja dan Anggaran (RKA)';
                elseif ($subCode1 == '02') $description .= ' - Dokumen Pelaksanaan Anggaran (DPA)';
                elseif ($subCode1 == '03') $description .= ' - Anggaran Pendapatan dan Belanja Daerah (APBD)';
                elseif ($subCode1 == '04') $description .= ' - Perubahan APBD';
                elseif ($subCode1 == '05') $description .= ' - Perhitungan APBD';
                elseif ($subCode1 == '06') $description .= ' - Rencana Strategis (Renstra)';
                elseif ($subCode1 == '07') $description .= ' - Rencana Kerja (Renja)';
            }
            elseif ($mainCode == '02') {
                $description = 'Akuntansi';
                if ($subCode1 == '01') $description .= ' - Sistem Akuntansi Pemerintah Daerah';
                elseif ($subCode1 == '02') $description .= ' - Jurnal';
                elseif ($subCode1 == '03') $description .= ' - Buku Besar';
                elseif ($subCode1 == '04') $description .= ' - Neraca';
                elseif ($subCode1 == '05') $description .= ' - Laporan Realisasi Anggaran';
                elseif ($subCode1 == '06') $description .= ' - Laporan Arus Kas';
                elseif ($subCode1 == '07') $description .= ' - Catatan atas Laporan Keuangan';
            }
            elseif ($mainCode == '03') {
                $description = 'Pelaksanaan Anggaran';
                if ($subCode1 == '01') $description .= ' - Surat Penyedia Dana (SPP, SPM, dan SP2D): UP, GU, TU, LS';
                elseif ($subCode1 == '02') $description .= ' - Pendapatan Asli Daerah';
                elseif ($subCode1 == '03') $description .= ' - Dana Perimbangan';
                elseif ($subCode1 == '04') $description .= ' - Lain-lain Pendapatan Daerah yang Sah';
                elseif ($subCode1 == '05') $description .= ' - Belanja Tidak Langsung';
                elseif ($subCode1 == '06') $description .= ' - Belanja Langsung';
                elseif ($subCode1 == '07') $description .= ' - Dokumen Pengelolaan Barang Milik Negara/Daerah';
                elseif ($subCode1 == '08') $description .= ' - Dokumen Investasi';
                elseif ($subCode1 == '09') $description .= ' - Penerimaan Pembiayaan';
                elseif ($subCode1 == '10') $description .= ' - Pengeluaran Pembiayaan';
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
        } elseif (strpos($code, 'KU.') === 0) {
            return 'border-info text-info bg-info';
        }

        return 'border-secondary text-secondary bg-secondary';
    }

    /**
     * Get classification hierarchy information
     *
     * @param string $code
     * @return array
     */
    public static function getClassificationInfo($code)
    {
        $info = [];

        if (strpos($code, 'AR.') === 0) {
            $parts = explode('.', $code);
            $info['AR'] = 'Arsip';

            if (isset($parts[1])) {
                $mainCode = $parts[1];
                $info['AR.' . $mainCode] = self::getMainCodeDescription($mainCode);

                if (isset($parts[2])) {
                    $subCode1 = $parts[2];
                    $info['AR.' . $mainCode . '.' . $subCode1] = self::getSubCode1Description($mainCode, $subCode1);

                    if (isset($parts[3])) {
                        $subCode2 = $parts[3];
                        $info['AR.' . $mainCode . '.' . $subCode1 . '.' . $subCode2] = self::getSubCode2Description($mainCode, $subCode1, $subCode2);
                    }
                }
            }
        } elseif (strpos($code, 'KU.') === 0) {
            $parts = explode('.', $code);
            $info['KU'] = 'Keuangan';

            if (isset($parts[1])) {
                $mainCode = $parts[1];
                $info['KU.' . $mainCode] = self::getKuMainCodeDescription($mainCode);

                if (isset($parts[2])) {
                    $subCode1 = $parts[2];
                    $info['KU.' . $mainCode . '.' . $subCode1] = self::getKuSubCode1Description($mainCode, $subCode1);
                }
            }
        }

        return $info;
    }

    /**
     * Get main code description for AR codes
     */
    private static function getMainCodeDescription($mainCode)
    {
        $descriptions = [
            '01' => 'Kebijakan',
            '02' => 'Pembinaan Kearsipan',
            '03' => 'Penyelenggaraan Kearsipan',
            // Add more as needed
        ];

        return $descriptions[$mainCode] ?? 'Tidak diketahui';
    }

    /**
     * Get sub code 1 description for AR codes
     */
    private static function getSubCode1Description($mainCode, $subCode1)
    {
        if ($mainCode == '01') {
            $descriptions = [
                '01' => 'Peraturan Daerah',
                '02' => 'Peraturan Wali Kota',
                '03' => 'Penetapan Organisasi Kearsipan',
            ];
        } elseif ($mainCode == '02') {
            $descriptions = [
                '01' => 'Bina Arsiparis',
                '02' => 'Bina Kearsipan',
                '03' => 'Akreditasi Lembaga Kearsipan',
            ];
        } elseif ($mainCode == '03') {
            $descriptions = [
                '01' => 'Penciptaan Arsip',
                '02' => 'Pengelolaan Arsip',
                '03' => 'Penggunaan Arsip',
            ];
        } else {
            $descriptions = [];
        }

        return $descriptions[$subCode1] ?? 'Tidak diketahui';
    }

    /**
     * Get sub code 2 description for AR codes
     */
    private static function getSubCode2Description($mainCode, $subCode1, $subCode2)
    {
        // This would be a more complex mapping based on the full classification
        // For now, return a generic description
        return 'Sub kategori ' . $subCode2;
    }

    /**
     * Get main code description for KU codes
     */
    private static function getKuMainCodeDescription($mainCode)
    {
        $descriptions = [
            '01' => 'Anggaran',
            '02' => 'Akuntansi',
            '03' => 'Perbendaharaan',
            '04' => 'Pendapatan',
            '05' => 'Belanja',
            '06' => 'Pembiayaan',
            '07' => 'Pelaksanaan Anggaran',
            // Add more as needed
        ];

        return $descriptions[$mainCode] ?? 'Tidak diketahui';
    }

    /**
     * Get sub code 1 description for KU codes
     */
    private static function getKuSubCode1Description($mainCode, $subCode1)
    {
        // This would be a more complex mapping
        // For now, return a generic description
        return 'Sub kategori ' . $subCode1;
    }
}

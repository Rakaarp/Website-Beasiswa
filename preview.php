<?php

include "config.php";
require('fpdf/fpdf.php');

// ambil data dari input
$tahun=$_POST['tahun'];

class PDF extends FPDF
{
	//Page footer
	function Footer()
	{
		//atur posisi 1.5 cm dari bawah
		$this->SetY(-1);
		//Arial italic 9
		$this->SetFont('Arial','I',9);
		//nomor halaman
		$this->Cell(0,0.8,'Halaman '.$this->PageNo().' dari {nb}',0,0,'C');
	}
}

$pdf = new PDF('P','cm','A4');
$pdf->SetMargins(1,1,1);
$pdf->AliasNbPages();
$pdf->AddPage();

    // Judul REPORT HEADER
    $pdf->SetFont('Arial','B',11);
    $pdf->Cell(19,0.5,'LAPORAN DATA PERANGKINGAN',0,1,'C');
    $pdf->Cell(19,0.5,'UNIVERSITAS GUNADARMA',0,1,'C');
    $pdf->Cell(19,0.5,'TAHUN' . $tahun,0,1,'C');
    $pdf->Cell(19,0.5,'',0,1,'C');


    // REPORT DETAIL JUDUL TABEL
    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(1,0.8,'No',1,0,'L');
    $pdf->Cell(2,0.8,'NIM',1,0,'L');
    $pdf->Cell(6,0.8,'Nama Mahasiswa',1,0,'L');
    $pdf->Cell(2.5,0.8,'n_Pendapatan',1,0,'L');
    $pdf->Cell(2.5,0.8,'n_IPK',1,0,'L');
    $pdf->Cell(2.5,0.8,'N_Saudara',1,0,'L');
    $pdf->Cell(2.5,0.8,'Preferensi',1,1,'L');


    // REPORT DETAIL ISI TABEL
    $pdf->SetFont('Arial','',9);

    $no=1;
    $sql = "SELECT perangkingan.idperangkingan,pendaftaran.iddaftar,pendaftaran.tgldaftar,pendaftaran.nim,mahasiswa.nama_mahasiswa,perangkingan.n_pendapatan,perangkingan.n_ipk,perangkingan.n_saudara,perangkingan.preferensi 
    FROM perangkingan INNER JOIN pendaftaran ON perangkingan.iddaftar=pendaftaran.iddaftar
    INNER JOIN mahasiswa ON pendaftaran.nim = mahasiswa.nim WHERE tahun='$tahun' ORDER BY preferensi DESC";
    $result = $conn->query($sql);
    while($row = $result->fetch_assoc()) {

        $pdf->Cell(1,0.8,$no++,1,0,'L');
        $pdf->Cell(2,0.8,$row['nim'],1,0,'L');
        $pdf->Cell(6,0.8,$row['nama_mahasiswa'],1,0,'L');
        $pdf->Cell(2.5,0.8,$row['n_pendapatan'],1,0,'L');
        $pdf->Cell(2.5,0.8,$row['n_ipk'],1,0,'L');
        $pdf->Cell(2.5,0.8,$row['n_saudara'],1,0,'L');
        $pdf->Cell(2.5,0.8,$row['preferensi'],1,1,'L');
    }

    $pdf->Output("laporan_perangkingan.pdf","I");
    exit;
?>
<?php
function tgl_indo($tanggal){
    $bulan = array (
        1 =>   'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember'
    );
    $pecahkan = explode('-', $tanggal);
    
    // variabel pecahkan 0 = tanggal
    // variabel pecahkan 1 = bulan
    // variabel pecahkan 2 = tahun
 
    return $pecahkan[2] . ' ' . $bulan[ (int)$pecahkan[1] ] . ' ' . $pecahkan[0];
}
$base_url = base_url();
$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$range = basename($actual_link);
$array = explode (":", $range);  
$tglawal = $array[0]; 
$tglakhir = $array[1]; 
$tglawal_ = date("Y-m-d", strtotime($tglawal));
$tglakhir_ = date("Y-m-d", strtotime($tglakhir));
$tgl_awal = tgl_indo(date("Y-m-d", strtotime($tglawal)));
$tgl_akhir = tgl_indo(date("Y-m-d", strtotime($tglakhir)));
$today = tgl_indo(date("Y-m-d"));

if($range == 'cetak_laporan') $rentang = '';
if($range != 'cetak_laporan') $rentang = $tgl_awal.' - '.$tgl_akhir;
// $kodepesanan = substr($b->kodepesanan,4,7);
// $time = tgl_indo(date("Y-m-d", strtotime($b->waktu)));
// $idpesanan = $b->idpesanan;
// $rowpesanan = $this->db->query("SELECT pesanan.biaya as totalpesanan FROM pesanan where pesanan.idpesanan = $idpesanan")->result();
// $biaya = number_format($rowpesanan[0]->totalpesanan,0,",",".");
$strhtml = '<!doctype html>
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    
    <title>Tandem - Laporan</title>
    
    <style type="text/css">
  .clearfix:after {
  content: "";
  display: table;
  clear: both;
  }

  a {
    color: #5D6975;
    text-decoration: underline;
  }

  body {
    position: relative;
    width: 21cm;  
    height: 29.7cm; 
    margin: 0 auto; 
    color: #001028;
    background: #FFFFFF; 
    font-family: Arial, sans-serif; 
    font-size: 12px; 
    font-family: Arial;
  }

  header {
    padding: 10px 0;
    margin-bottom: 30px;
  }

  #logo {
    text-align: center;
    margin-bottom: 10px;
  }

  #logo img {
    width: 90px;
  }

  h1 {
    border-top: 1px solid  #5D6975;
    border-bottom: 1px solid  #5D6975;
    color: #5D6975;
    font-size: 1.4em;
    line-height: 1.4em;
    font-weight: normal;
    text-align: center;
    margin: 0 0 20px 0;
    background: url("dimension.png");
  }

  #project {
    float: left;
  }

  #project span {
    color: #5D6975;
    text-align: right;
    width: 52px;
    margin-right: 10px;
    display: inline-block;
    font-size: 0.8em;
  }

  #company {
    float: right;
    text-align: right;
  }

  #project div,
  #company div {
    white-space: nowrap;        
  }

  table {
    width: 100%;
    border-collapse: collapse;
    border-spacing: 0;
    margin-bottom: 20px;
  }

  table tr:nth-child(2n-1) td {
    background: #F5F5F5;
  }

  table th,
  table td {
    text-align: center;
  }

  table th {
    padding: 5px 20px;
    color: #5D6975;
    border-bottom: 1px solid #C1CED9;
    white-space: nowrap;        
    font-weight: normal;
  }

  table .service,
  table .desc {
    text-align: left;
  }

  table td {
    padding: 20px;
    text-align: right;
  }

  table td.service,
  table td.desc {
    vertical-align: top;
    font-size: 1em;
  }

  table td.unit,
  table td.qty,
  table td.total {
    font-size: 1em;
  }

  table td.grand {
    border-top: 1px solid #5D6975;;
  }

  #notices .notice {
    color: #5D6975;
    font-size: 1.2em;
  }

  footer {
    color: #5D6975;
    width: 100%;
    height: 30px;
    position: absolute;
    bottom: 0;
    border-top: 1px solid #C1CED9;
    padding: 8px 0;
    text-align: center;
  }
  </style>
</head>

<body>
    <header class="clearfix">
      <div id="logo">
        <img src="'.$base_url.'assets/img/logo.png">
      </div>
      <h1>Laporan Transaksi '.$rentang; 
      $strhtml .='</h1>
      
    </header>
    <main>
      <table>
        <thead>
          <tr>
            <th class="service">No.</th>
            <th class="desc">Kode Room</th>
            <th class="desc">Nama Pemesan</th>
            <th class="desc">Waktu Transaksi</th>
            <th>Nilai Transaksi</th>
          </tr>
        </thead>
        <tbody>';
          $no = 0;
          foreach ($laporan as $b) {
          $kode_room = $b->kode_room;
          $waktu = tgl_indo(date("Y-m-d", strtotime($b->waktu)));
          $nilai_transaksi = number_format($b->nilai_transaksi,0,",",".");
          $totalpendapatan = number_format($b->totalpendapatan,0,",",".");
          // if($range != 'cetak_laporan') $grand = $this->db->query("SELECT SUM(konfirmasi_bayar.nilai_transaksi) as totalpendapatan FROM konfirmasi_bayar where konfirmasi_bayar.status = 'T' and DATE(konfirmasi_bayar.waktu) >= '$tglawal_' and DATE(konfirmasi_bayar.waktu) <= '$tglakhir_'")->result();
          // if($range == 'cetak_laporan') $grand = $this->db->query("SELECT SUM(konfirmasi_bayar.nilai_transaksi) as totalpendapatan FROM konfirmasi_bayar where konfirmasi_bayar.status = 'T'")->result();
          $no++;
          $strhtml .='<tr>
            <td class="service">'.$no.'</td>
            <td class="desc">'.$b->kode_room.'</td>
            <td class="desc">'.$b->nama_user.'</td>
            <td class="desc">'.$waktu.'</td>
            <td class="total">Rp. '.$nilai_transaksi.'</td>
          </tr>';
          }
        
          $strhtml .='
          <tr>
            <td colspan="4" class="grand total">Total Penghasilan</td>
            <td class="grand total">Rp. '.$totalpendapatan.'</td>
          </tr>
        </tbody>
      </table>
    </main>
    <footer>
      Laporan ini dicetak pada '.$today.'
    </footer>
  </body>
</html>
';



$mpdf = new \Mpdf\Mpdf();
$fileName = 'Invoice';
$stylesheet = file_get_contents('assets/css/invoice.css'); // external css
$mpdf->showWatermarkImage = true;
$mpdf->AddPage('P','','','','',10, 10, 20, 20, 30, 10);
$mpdf->SetTitle('Invoice');
$mpdf->WriteHTML($stylesheet,1);
$mpdf->WriteHTML($strhtml);
$mpdf->Output($fileName. '.pdf','I');
<?php
require_once 'vendor/autoload.php';
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: Glory
 * Date: 06/12/2018
 * Time: 10:58
 */
class transaksi extends CI_Controller
{
    var $API ="";
    var $userSession;
    var $navbarTitle;
    var $gallerypath;


    function __construct()
    {
        parent::__construct();
        $this->API="http://localhost/tandem_local/api/tandem/web/";
        $this->load->library('curl');
        $this->load->helper(array('url'));
        $this->load->library(array('form_validation','pagination','session'));
        $this->userSession = $this->session->userdata('admin');

        $idadmin = $this->session->userdata('idadmin');

        if ($idadmin != "") {

            $this->navbarTitle = "Transaksi";

        } else {
            redirect('auth');
        }
    }

    function room(){
        
        $idadmin = $this->session->userdata('idadmin');
        $level_admin = $this->session->userdata('level');
        if ($idadmin != "" && $level_admin == 'admin') {
            
        $this->navbarTitle = "Room";

        $room = json_decode($this->curl->simple_get($this->API.'room?action=selectRoom'));
        if (isset($room[0]->status) && $room[0]->status == 'Null') {
            $data['room'] = 'No data';
        }
        else {
            $data['room'] = json_decode($this->curl->simple_get($this->API.'room?action=selectRoom'));
        }
        $data['jenis'] = json_decode($this->curl->simple_get($this->API.'select?action=jenis'));
        $data['lapangan'] = json_decode($this->curl->simple_get($this->API.'select?action=lapangan'));
        $data['dashboard'] = json_decode($this->curl->simple_get($this->API.'admin?action=dashboard'));

        $this->load->view('parts/header');
        $this->load->view('parts/sidebar',$data);
        $this->load->view('room',$data);
        $this->load->view('parts/footer');
      } else {
          redirect('auth');
      }
    }

    function konfirmasi_bayar(){
        
        $idadmin = $this->session->userdata('idadmin');
        $level_admin = $this->session->userdata('level');
        if ($idadmin != "" && $level_admin == 'admin') {
        $this->navbarTitle = "Konfirmasi Bayar";

        $data['konfirmasi_bayar'] = json_decode($this->curl->simple_get($this->API.'room?action=konfirmasiBayar'));
        $data['jenis'] = json_decode($this->curl->simple_get($this->API.'select?action=jenis'));
        $data['lapangan'] = json_decode($this->curl->simple_get($this->API.'select?action=lapangan'));
        $data['dashboard'] = json_decode($this->curl->simple_get($this->API.'admin?action=dashboard'));

        $this->load->view('parts/header');
        $this->load->view('parts/sidebar',$data);
        $this->load->view('konfirmasi_bayar',$data);
        $this->load->view('parts/footer');
        } else {
          redirect('auth');
      }
    }

    function registrasi_member(){
        
        $idadmin = $this->session->userdata('idadmin');
        $level_admin = $this->session->userdata('level');
        if ($idadmin != "" && $level_admin == 'admin') {
        $this->navbarTitle = "Registrasi Member";

        $data['registrasi_member'] = json_decode($this->curl->simple_get($this->API.'user/registrasiMember'));
        $data['jenis'] = json_decode($this->curl->simple_get($this->API.'select/jenis'));
        $data['lapangan'] = json_decode($this->curl->simple_get($this->API.'select/lapangan'));
        $data['dashboard'] = json_decode($this->curl->simple_get($this->API.'admin/dashboard'));

        $this->load->view('parts/header');
        $this->load->view('parts/sidebar',$data);
        $this->load->view('registrasi_member',$data);
        $this->load->view('parts/footer');
        } else {
          redirect('auth');
      }
    }

    function laporan(){
        $this->navbarTitle = "Laporan";

        $data['laporan'] = json_decode($this->curl->simple_get($this->API.'laporan?action=selectLaporan'));
        $data['jenis'] = json_decode($this->curl->simple_get($this->API.'select?action=jenis'));
        $data['lapangan'] = json_decode($this->curl->simple_get($this->API.'select?action=lapangan'));
        $data['dashboard'] = json_decode($this->curl->simple_get($this->API.'admin?action=dashboard'));

        $this->load->view('parts/header');
        $this->load->view('parts/sidebar',$data);
        $this->load->view('laporan',$data);
        $this->load->view('parts/footer');
    }

    function filterLaporan($range){
        $this->navbarTitle = "Laporan";

        // $data['laporan'] = $this->transaksi_model->getDataLaporanFilter($range)->result();
        // $data['laporan'] = json_decode($this->curl->simple_get($this->API.'laporan/selectLaporan'));

        $range_opt = array(
            'range'=>$range
        );

        $filter =   $this->curl->simple_post($this->API.'laporan?action=filterLaporan', $range_opt, array(CURLOPT_BUFFERSIZE => 10));
        $data['laporan'] = json_decode($filter);

        $data['jenis'] = json_decode($this->curl->simple_get($this->API.'select?action=jenis'));
        $data['lapangan'] = json_decode($this->curl->simple_get($this->API.'select?action=lapangan'));
        $data['dashboard'] = json_decode($this->curl->simple_get($this->API.'admin?action=dashboard'));
        $data['navbarTitle'] = $this->navbarTitle;
        $this->load->view('parts/header');
        $this->load->view('parts/sidebar',$data);
        $this->load->view('laporan',$data);
        $this->load->view('parts/footer');
    }

    function cetaklaporan($range){ 
        
    $range_opt = array(
            'range'=>$range
    );

    $filter =   $this->curl->simple_post($this->API.'laporan?action=filterLaporan', $range_opt, array(CURLOPT_BUFFERSIZE => 10));
    $data['laporan'] = json_decode($filter);

    if ($data['laporan'] == null) {
        # code...
        redirect('notFound');
    }

    else {
        $this->load->view('cetaklaporan',$data);
     }
    }
    
    function cetak_laporan(){ 
    $data['laporan'] = json_decode($this->curl->simple_get($this->API.'laporan?action=selectLaporan'));

    if ($data['laporan'] == null) {
        # code...
        redirect('notFound');
    }

    else {
        $this->load->view('cetaklaporan',$data);
     }
    }

    function tambahRoom(){
        $this->navbarTitle = "Room";

        $nama_room = $this->input->post("nama_room");
        $waktu = $this->input->post("waktu");
        $id_lapangan = $this->input->post("id_lapangan");

        $roomData = array(
            'nama_room'=>$nama_room,
            'waktu'=>$waktu,
            'id_lapangan'=>$id_lapangan,
            'id_user'=>'1'
        );

        $addRoom =   $this->curl->simple_post($this->API.'room?action=tambahRoom', $roomData, array(CURLOPT_BUFFERSIZE => 10));
        $result = json_decode($addRoom);
        if (isset($result[0]->status) && $result[0]->status == 'OK') {

            $this->session->set_flashdata("inserted","inserted");
            redirect('transaksi/room');

        } else {
            $this->session->set_flashdata("failed","failed");
        }

        
    }

    function event(){
        $idadmin = $this->session->userdata('idadmin');
        $level_admin = $this->session->userdata('level');
        if ($idadmin != "" && $level_admin == 'admin') {
        $this->navbarTitle = "Event";

        $event = json_decode($this->curl->simple_get($this->API.'event?action=selectEvent'));
        if (isset($event[0]->status) && $event[0]->status == 'Null') {
            $data['event'] = 'No data';
        }
        else {
            $data['event'] = json_decode($this->curl->simple_get($this->API.'event?action=selectEvent'));
        }
        $data['jenis'] = json_decode($this->curl->simple_get($this->API.'select?action=jenis'));
        $data['lapangan'] = json_decode($this->curl->simple_get($this->API.'select?action=lapangan'));
        $data['dashboard'] = json_decode($this->curl->simple_get($this->API.'admin?action=dashboard'));

        $this->load->view('parts/header');
        $this->load->view('parts/sidebar',$data);
        $this->load->view('event',$data);
        $this->load->view('parts/footer');
        } else {
          redirect('auth');
      }
    }

    function tambahEvent(){
        $this->navbarTitle = "Event";

        function generateRandomString($length = 10) {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }
            return $randomString;
        }

        $nama_event = $this->input->post("nama_event");
        $waktu = $this->input->post("waktu");
        $id_lapangan = $this->input->post("id_lapangan");
        $deskripsi = $this->input->post("deskripsi");
        $image = base64_decode($this->input->post("imageUrl"));
        $image_name = generateRandomString();
        $filename = $image_name . '.' . 'png';
        //rename file name with random number
        $path = "http://tapisdev.tech/api/tandem/web/uploads/".$filename;
        //image uploading folder path
        file_put_contents($path . $filename, $image);
        // image is bind and uploa

        $eventData = array(
            'nama_event'=>$nama_event,
            'waktu'=>$waktu,
            'deskripsi'=>$deskripsi,
            'id_lapangan'=>$id_lapangan,
            'imageUrl'=>$filename
        );

        $addEvent =   $this->curl->simple_post($this->API.'event?action=tambahEvent', $eventData, array(CURLOPT_BUFFERSIZE => 10));
        $result = json_decode($addEvent);
        if (isset($result[0]->status) && $result[0]->status == 'OK') {

            $this->session->set_flashdata("inserted","inserted");
            redirect('transaksi/event');

        } else {
            $this->session->set_flashdata("failed","failed");
        }

        
    }
}
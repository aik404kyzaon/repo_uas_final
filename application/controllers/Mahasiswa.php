<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Mahasiswa extends CI_Controller
{
    // fungsi selalu dijalankan.
    public function __construct()
    {
        parent::__construct();
        // load models.
        $this->load->model('Mahasiswa_model');
    }

    // fungsi pertama dijalankan.
    public function index()
    {
        // $array data mulai.
        $data = [
            // judul tab.
            "judul" => "Data Mahasiswa",
            // load fungsi dari model.
            "mahasiswa" => $this->Mahasiswa_model->getAllMahasiswa()
        ];
        // array data selesai.
        // load view mulai
        // mengandung array $data
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar');
        // mengandung array $data
        $this->load->view('mahasiswa/v_mahasiswa', $data);
        $this->load->view('template/footer');
        // load view selesai.
    }

    // fungsi tambah
    public function add()
    {
        // data array mulai.
        $data = [
            // judul tab.
            "judul" => "Tambah Data Mahasiswa"
        ];
        // data array selesai.
        // mengeatur validasi dari inputan "name" mulai.
        $this->form_validation->set_rules('nim', 'NIM', 'required|numeric|max_length[12]');
        $this->form_validation->set_rules('nama', 'Nama', 'required|max_length[32]');
        $this->form_validation->set_rules('alamat', 'Alamat', 'required|max_length[32]');
        $this->form_validation->set_rules('telp', 'Telepon', 'required|numeric|max_length[13]');
        // mengatur validasi dari inputan "name" selesai.
        // pengkondisian jika validasi berjalan mulai.
        // jika validasi salah maka.
        if ($this->form_validation->run() == FALSE) {
            // load view mulai.
            // mengandung array $data
            $this->load->view('template/header', $data);
            $this->load->view('template/sidebar');
            // mengandung array $data
            $this->load->view('mahasiswa/v_tambah', $data);
            $this->load->view('template/footer');
            // load view selesai.
        }
        // jika benar maka.
        else {
            // load fungsi dari model.
            $this->Mahasiswa_model->tambahDataMahasiswa();
            // membuat session flash.
            $this->session->set_flashdata('flash', 'Data berhasil ditambahkan!');
            // kembali ke controller mahasiswa.
            redirect('mahasiswa'); // kembali ke controller mahasiswa.
        }
        // pengkodisian jika validasi berjalan selesai.
    }

    // fungsi ubah berdasarkan paramater $id.
    public function update($id)
    {
        // data array mulai.
        $data = [
            // judul tab.
            'judul' => 'Ubah Data Mahasiswa',
            // load fungsi model dengan mengirimkan $id.
            'mahasiswa' => $this->Mahasiswa_model->getMahasiswaById($id)
        ];
        // data array selesai.
        // mengatur validasi dari inputan "name" mulai.
        $this->form_validation->set_rules('nim', 'NIM', 'required|numeric|max_length[12]');
        $this->form_validation->set_rules('nama', 'Nama', 'required|max_length[32]');
        $this->form_validation->set_rules('alamat', 'Alamat', 'required|max_length[32]');
        $this->form_validation->set_rules('telp', 'Telepon', 'required|numeric|max_length[13]');
        // mengatur validasi dari inputan "name" selesai.
        // pengkodisian jika validasi berjalan mulai.
        // jika validasi salah maka.
        if ($this->form_validation->run() == FALSE) {
            // jika validasi salah maka.
            // load view.
            // mengandung array $data.
            $this->load->view('template/header', $data);
            $this->load->view('template/sidebar');
            // mengandung array $data
            $this->load->view('mahasiswa/v_ubah', $data);
            $this->load->view('template/footer');
        }
        // jika benar maka.
        else {
            // load fungsi model.
            $this->Mahasiswa_model->ubahDataMahasiswa($id);
            // membuat session flash.
            $this->session->set_flashdata('flash', 'Data berhasil diubah!');
            // kembali ke controller mahasiswa.
            redirect('mahasiswa');
        }
        // pengkodisian jika validasi berjalan selesai.
    }

    // fungsi hapus berdasarkan paramater $id.
    public function delete($id)
    {
        // load fungsi model dengan mengirimkan $id.
        $this->Mahasiswa_model->hapusDataMahasiswa($id);
        // membuat session flash.
        $this->session->set_flashdata('flash', 'Data berhasil dihapus!');
        // kembali ke controller mahasiswa.
        redirect('mahasiswa');
    }

    // fungsi riwayat.
    public function log()
    {
        // data array mulai.
        $data = [
            // judul tab.
            'judul' => "Riwayat No. HP Mahasiswa",
            // load fungsi model.
            'mahasiswa' => $this->Mahasiswa_model->getLog()
        ];
        // data array selesai.
        // load view mulai.
        // mengandung data array.
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar');
        // mengandung data array.
        $this->load->view('mahasiswa/v_log', $data);
        $this->load->view('template/footer');
        // load view selesai.
    }

    // fungsi export excel
    public function export_tampil_mhs()
    {
        //load fungsi model.
        $data['mahasiswa'] = $this->Mahasiswa_model->getAllMahasiswa();
        //load plugin PHPExcel.
        require(APPPATH . 'PHPExcel-1.8/Classes/PHPExcel.php');
        require(APPPATH . 'PHPExcel-1.8/Classes/PHPExcel/Writer/Excel2007.php');
        //load plugin selesai.

        //panggil class PHPExcel.
        $objPHPExcel = new PHPExcel();

        // membuat properties file.
        $objPHPExcel->getProperties()->setCreator("Arky");
        $objPHPExcel->getProperties()->setLastModifiedBy("Arky");
        $objPHPExcel->getProperties()->setTitle("Data Mahasiswa");
        $objPHPExcel->getProperties()->setSubject("");
        $objPHPExcel->getProperties()->setDescription("");
        // membuat file properties selesai.

        // variabel untuk menampung style dari header tabel (judul).
        $style_col = array(
            //set font bold
            'font' => array('bold' => true),
            'alignment' => array(
                //text center
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                //text middle
                'vertikal' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            ),
            //set border atas, bawah, kanan, dan kiri dengan garis tipis.
            'borders' => array(
                'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
                'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
                'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
                'left' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
            )
            //set border selesai.
        );
        $style_row = array(
            'alignment' => array(
                //text center
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                //text middle
                'vertikal' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            ),
            //set border atas, bawah, kanan, dan kiri dengan garis tipis.
            'borders' => array(
                'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
                'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
                'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
                'left' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
            )
            //set border selesai.
        );

        //mengatur sheet aktif.
        $objPHPExcel->setActiveSheetIndex(0);
        // merge cell a1 sampai f1
        $objPHPExcel->getActiveSheet()->mergeCells("A1:F1");
        // font bold
        $objPHPExcel->getActiveSheet()->getStyle("A1")->getFont()->setBold(TRUE);
        // font size
        $objPHPExcel->getActiveSheet()->getStyle("A1")->getFont()->setSize(14);
        // font center
        $objPHPExcel->getActiveSheet()->getStyle("A1")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        // mengatur nama field.
        $objPHPExcel->getActiveSheet()->setCellValue("A1", "Data Mahasiswa");
        $objPHPExcel->getActiveSheet()->setCellValue("A3", "No");
        $objPHPExcel->getActiveSheet()->setCellValue("B3", "NIM");
        $objPHPExcel->getActiveSheet()->setCellValue("C3", "Nama");
        $objPHPExcel->getActiveSheet()->setCellValue("D3", "Alamat");
        $objPHPExcel->getActiveSheet()->setCellValue("E3", "Jenis Kelamin");
        $objPHPExcel->getActiveSheet()->setCellValue("F3", "Nomer Telepon");

        // style kolom.
        $objPHPExcel->getActiveSheet()->getStyle("A3")->applyFromArray($style_col);
        $objPHPExcel->getActiveSheet()->getStyle("B3")->applyFromArray($style_col);
        $objPHPExcel->getActiveSheet()->getStyle("C3")->applyFromArray($style_col);
        $objPHPExcel->getActiveSheet()->getStyle("D3")->applyFromArray($style_col);
        $objPHPExcel->getActiveSheet()->getStyle("E3")->applyFromArray($style_col);
        $objPHPExcel->getActiveSheet()->getStyle("F3")->applyFromArray($style_col);

        // memasukkan data
        $baris = 4;
        $x = 1;
        //looping
        foreach ($data['mahasiswa'] as $data) {
            //mengambil data tabel.
            $objPHPExcel->getActiveSheet()->setCellValue("A" . $baris, $x);
            $objPHPExcel->getActiveSheet()->setCellValue("B" . $baris, $data['nim']);
            $objPHPExcel->getActiveSheet()->setCellValue("C" . $baris, $data['nama']);
            $objPHPExcel->getActiveSheet()->setCellValue("D" . $baris, $data['alamat']);
            $objPHPExcel->getActiveSheet()->setCellValue("E" . $baris, $data['jk']);
            $objPHPExcel->getActiveSheet()->setCellValue("F" . $baris, $data['telp']);
            //styel row
            $objPHPExcel->getActiveSheet()->getStyle("A" . $baris)->applyFromArray($style_row);
            $objPHPExcel->getActiveSheet()->getStyle("B" . $baris)->applyFromArray($style_row);
            $objPHPExcel->getActiveSheet()->getStyle("C" . $baris)->applyFromArray($style_row);
            $objPHPExcel->getActiveSheet()->getStyle("D" . $baris)->applyFromArray($style_row);
            $objPHPExcel->getActiveSheet()->getStyle("E" . $baris)->applyFromArray($style_row);
            $objPHPExcel->getActiveSheet()->getStyle("F" . $baris)->applyFromArray($style_row);
            $x++;
            $baris++;
        }
        //looping selesai.
        //lebar kolom otomatis.
        $objPHPExcel->getActiveSheet()->getColumnDimension("A")->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension("B")->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension("C")->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension("D")->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension("E")->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension("F")->setAutoSize(true);
        //set tinggi kolom otomatis
        $objPHPExcel->getActiveSheet()->getDefaultRowDimension()->getRowHeight(-1);
        // membuat file excel
        $filename = "Data Mahasiswa " . date("Y-m-d-H-i-s") . ".xlsx";
        $objPHPExcel->getActiveSheet()->setTitle("Data Mahasiswa");

        //proses file excel.
        header("Content-Type: apllication/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $writer->save('php://output');
        exit;
    }

    // fungsi export excel
    public function export_tampil_semua()
    {
        //load fungsi model.
        $data['mahasiswa'] = $this->Mahasiswa_model->getLog();
        //load plugin PHPExcel.
        require(APPPATH . 'PHPExcel-1.8/Classes/PHPExcel.php');
        require(APPPATH . 'PHPExcel-1.8/Classes/PHPExcel/Writer/Excel2007.php');
        //load plugin selesai.

        //panggil class PHPExcel.
        $objPHPExcel = new PHPExcel();

        // membuat properties file.
        $objPHPExcel->getProperties()->setCreator("Arky");
        $objPHPExcel->getProperties()->setLastModifiedBy("Arky");
        $objPHPExcel->getProperties()->setTitle("Data Mahasiswa");
        $objPHPExcel->getProperties()->setSubject("");
        $objPHPExcel->getProperties()->setDescription("");
        // membuat file properties selesai.

        // variabel untuk menampung style dari header tabel (judul).
        $style_col = array(
            //set font bold
            'font' => array('bold' => true),
            'alignment' => array(
                //text center
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                //text middle
                'vertikal' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            ),
            //set border atas, bawah, kanan, dan kiri dengan garis tipis.
            'borders' => array(
                'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
                'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
                'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
                'left' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
            )
            //set border selesai.
        );
        $style_row = array(
            'alignment' => array(
                //text center
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                //text middle
                'vertikal' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            ),
            //set border atas, bawah, kanan, dan kiri dengan garis tipis.
            'borders' => array(
                'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
                'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
                'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
                'left' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
            )
            //set border selesai.
        );

        //mengatur sheet aktif.
        $objPHPExcel->setActiveSheetIndex(0);
        // merge cell a1 sampai f1
        $objPHPExcel->getActiveSheet()->mergeCells("A1:H1");
        // font bold
        $objPHPExcel->getActiveSheet()->getStyle("A1")->getFont()->setBold(TRUE);
        // font size
        $objPHPExcel->getActiveSheet()->getStyle("A1")->getFont()->setSize(14);
        // font center
        $objPHPExcel->getActiveSheet()->getStyle("A1")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        // mengatur nama field.
        $objPHPExcel->getActiveSheet()->setCellValue("A1", "Riwayat Ganti Telepon Mahasiswa");
        $objPHPExcel->getActiveSheet()->setCellValue("A3", "No");
        $objPHPExcel->getActiveSheet()->setCellValue("B3", "NIM");
        $objPHPExcel->getActiveSheet()->setCellValue("C3", "Nama");
        $objPHPExcel->getActiveSheet()->setCellValue("D3", "Alamat");
        $objPHPExcel->getActiveSheet()->setCellValue("E3", "Jenis Kelamin");
        $objPHPExcel->getActiveSheet()->setCellValue("F3", "Nomer Baru");
        $objPHPExcel->getActiveSheet()->setCellValue("G3", "Nomer Lama");
        $objPHPExcel->getActiveSheet()->setCellValue("H3", "Tanggal Diubah");

        // style kolom.
        $objPHPExcel->getActiveSheet()->getStyle("A3")->applyFromArray($style_col);
        $objPHPExcel->getActiveSheet()->getStyle("B3")->applyFromArray($style_col);
        $objPHPExcel->getActiveSheet()->getStyle("C3")->applyFromArray($style_col);
        $objPHPExcel->getActiveSheet()->getStyle("D3")->applyFromArray($style_col);
        $objPHPExcel->getActiveSheet()->getStyle("E3")->applyFromArray($style_col);
        $objPHPExcel->getActiveSheet()->getStyle("F3")->applyFromArray($style_col);
        $objPHPExcel->getActiveSheet()->getStyle("G3")->applyFromArray($style_col);
        $objPHPExcel->getActiveSheet()->getStyle("H3")->applyFromArray($style_col);

        // memasukkan data
        $baris = 4;
        $x = 1;
        //looping
        foreach ($data['mahasiswa'] as $data) {
            //mengambil data tabel.
            $objPHPExcel->getActiveSheet()->setCellValue("A" . $baris, $x);
            $objPHPExcel->getActiveSheet()->setCellValue("B" . $baris, $data['nim']);
            $objPHPExcel->getActiveSheet()->setCellValue("C" . $baris, $data['nama']);
            $objPHPExcel->getActiveSheet()->setCellValue("D" . $baris, $data['alamat']);
            $objPHPExcel->getActiveSheet()->setCellValue("E" . $baris, $data['jk']);
            $objPHPExcel->getActiveSheet()->setCellValue("F" . $baris, $data['telp_lama']);
            $objPHPExcel->getActiveSheet()->setCellValue("G" . $baris, $data['telp_baru']);
            $objPHPExcel->getActiveSheet()->setCellValue("H" . $baris, $data['tgl_diubah']);
            //styel row
            $objPHPExcel->getActiveSheet()->getStyle("A" . $baris)->applyFromArray($style_row);
            $objPHPExcel->getActiveSheet()->getStyle("B" . $baris)->applyFromArray($style_row);
            $objPHPExcel->getActiveSheet()->getStyle("C" . $baris)->applyFromArray($style_row);
            $objPHPExcel->getActiveSheet()->getStyle("D" . $baris)->applyFromArray($style_row);
            $objPHPExcel->getActiveSheet()->getStyle("E" . $baris)->applyFromArray($style_row);
            $objPHPExcel->getActiveSheet()->getStyle("F" . $baris)->applyFromArray($style_row);
            $objPHPExcel->getActiveSheet()->getStyle("G" . $baris)->applyFromArray($style_row);
            $objPHPExcel->getActiveSheet()->getStyle("H" . $baris)->applyFromArray($style_row);
            $x++;
            $baris++;
        }
        //looping selesai.
        //lebar kolom otomatis.
        $objPHPExcel->getActiveSheet()->getColumnDimension("A")->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension("B")->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension("C")->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension("D")->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension("E")->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension("F")->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension("G")->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension("H")->setAutoSize(true);
        //set tinggi kolom otomatis
        $objPHPExcel->getActiveSheet()->getDefaultRowDimension()->getRowHeight(-1);
        // membuat file excel
        $filename = "Riwayat Telepon " . date("Y-m-d-H-i-s") . ".xlsx";
        $objPHPExcel->getActiveSheet()->setTitle("Riwayat Telepon");

        //proses file excel.
        header("Content-Type: apllication/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $writer->save('php://output');
        exit;
    }
}

/* End of file Mahasiswa.php */

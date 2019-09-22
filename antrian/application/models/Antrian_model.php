<?php 
defined('BASEPATH') OR exit('No direct access script allowed');

class Antrian_model extends CI_Model {

	public function __construct() {
		parent::__construct();
	}

	/**
	* antriBaru
	*
	* fungsi saat pelanggan mulai antri
	*
	* @return array
	*/
	public function antriBaru() {

		$this->db->trans_start();

		$this->db->query("INSERT INTO `na_antrian` (`tanggal`, `nomor_antrian`) SELECT tgl,nmr FROM (SELECT DATE(NOW()) AS tgl, IF(MAX(`nomor_antrian`) IS NULL, 1, MAX(`nomor_antrian`)+1) AS nmr FROM `na_antrian` WHERE `tanggal`=DATE(NOW())) a");
	
		$antrian = $this->db->query("SELECT `tanggal`,MAX(`nomor_antrian`) AS nomor FROM `na_antrian` WHERE `tanggal` = DATE(NOW()) GROUP BY `tanggal`")->row_array();

		$this->db->trans_complete();

		return $antrian;
	}

	/**
	* terimaAntrian
	*
	* fungsi saat loket menerima pelanggan dengan nomer antrian terakhir
	*
	* @param $namaLoket 	nama loket yang menerima pelanggan
	* @return void
	*/
	public function terimaAntrian($namaLoket) {

		$this->db->query("INSERT INTO `na_pelayanan` (tanggal, nomor_antrian, id_loket) SELECT tgl,nmr,lkt FROM (SELECT DATE(NOW()) AS tgl, (SELECT IF(MAX(`nomor_antrian`) IS NULL, 1, MAX(`nomor_antrian`)+1) AS nomor FROM `na_pelayanan` WHERE `tanggal` = DATE(NOW())) AS nmr, (SELECT id FROM `na_loket` WHERE nama = '{$namaLoket}') AS lkt) a");
	}

	/**
	* daftarAntrian
	*
	* fungsi untuk melihat daftar antrian
	* melihat semua antrian pada hari itu jika tidak mengisi parameter $namaLoket
	* melihat semua antrian untuk loket tertentu pada hari itu jika mengisi parameter $namaLoket
	*
	* @param $namaLoket 	nama loket yang menerima antrian
	* @return array
	*/
	public function daftarAntrian($namaLoket=NULL) {

		$this->db->select('na_pelayanan.id, na_pelayanan.tanggal, na_pelayanan.nomor_antrian, na_loket.nama AS nama_loket, na_pelayanan.id_loket');

		$this->db->join('na_loket', 'na_loket.id = na_pelayanan.id_loket');

		if(!is_null($namaLoket)) $this->db->where('id_loket', "(SELECT id FROM na_loket WHERE nama = '{$namaLoket}')", FALSE);
		$this->db->where('tanggal', 'DATE(NOW())', FALSE);
		$this->db->order_by('na_pelayanan.nomor_antrian', 'DESC');

		return $this->db->get('na_pelayanan')->result_array();
	}
}
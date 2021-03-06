<?php
	if (!defined('BASEPATH')) exit('No direct script access allowed');

	class Komisi extends MX_Controller{
		function __construct(){
			parent::__construct();
			$this->load->model('komisi_model', 'komisi_model', TRUE);
			$this->load->model('template/template_model', 'template', TRUE);
		}

		function index(){
		$datalogin=$this->session->all_userdata();
		if($this->session->userdata('login')===TRUE && $this->session->userdata('rolename')==='member' && $this->session->userdata('userid')!=null)
		{
			$session=$this->session->all_userdata();
			$page['title']='Komisi';
			$page['menus']=$this->template->menu('member');
			$this->load->view('template/header_member', $page);

			$data=array(
					'transfer_sukses'=>$this->lihat_sukses($datalogin['userid']),
					'transfer_pending'=>$this->lihat_pending($datalogin['userid']),
					'transfer_dihapus'=>$this->lihat_dihapus($datalogin['userid']),
					'total_komisi'=>$this->show_totalkomisi($this->session->userdata('userid')),
					'income_komisi'=>$this->statistik_income($this->session->userdata('userid'), 2),
					'pending_komisi'=>$this->statistik_income($this->session->userdata('userid'), 1),
					'aktif_premium'=>$this->komisi_premuim(1),
					'pending_premium'=>$this->komisi_premuim(0)
					);
			
		
			$this->load->view('komisi', $data);
			

		} else {redirect('login');}

		}

		function statistik_income($userid, $isactive)
		{
			$result=null;
			if ($userid==null || ($isactive==null&&$isactive!=0)) {
				redirect('error');
			}

			$total=$this->komisi_model->view_income_komisi($userid, $isactive);

			if ($total!=null) {
				foreach ($total as $sukses) {
					$result=$sukses->total;
				}
			}

			return $result;
		}


		function show_totalkomisi($userid)
		{
			if ($userid==null) {
				redirect('home');
			}

			$result=0;

			$total_komisi=$this->komisi_model->show_all_komisi($userid);

			if ($total_komisi!=0 || $total_komisi!=null) {
				$result=$total_komisi;
			}
			

			return $result;

		}

		function lihat_sukses($userid)
		{

			if ($userid==null) {
				redirect('home');
			}

			$result="<table class='table table-striped table-hover'>
						<thead>
						<tr style='font-weight:900'>
							<td>No.</td><td>User Referall</td><td>Alamat</td><td>Total Komisi (Rp.)</td><td>Tanggal Transaksi</td><td>Detail</td>
						</tr>
						</thead>
						<tbody>";
			$data_komisi=$this->komisi_model->show_komisi($userid, 2);
			if ($data_komisi!=null) {
				$i=1;
				foreach ($data_komisi as $komisi) {
					$result.="<tr>
								<td>".$i."</td><td>".$komisi->Nama_Pembeli."</td>
								<td>".$komisi->Alamat." ".$komisi->Kota."</td><td>".$this->komisi_model->show_total_komisi($this->session->userdata('userid'), $komisi->id)."</td>
								<td>".$komisi->tgl_pembelian."</td>
								<td><a href='".base_url()."komisi/detail/".$komisi->id."' data-target='#myModal' role='button' class='btn btn-info' data-toggle='modal'>View</a></td>
							</tr>";
						$i++;
						}
			}
			$result.="</tbody></table>";

			return $result;
		}

		function lihat_pending($userid)
		{

			if ($userid==null) {
				redirect('home');
			}

			$result="<table class='table table-striped table-hover'>
						<thead>
						<tr style='font-weight:900'>
							<td>No.</td><td>User Referall</td><td>Alamat</td><td>Total Komisi (Rp.)</td><td>Tanggal Transaksi</td><td>Detail</td>
						</tr>
						</thead>
						<tbody>";
			$data_komisi=$this->komisi_model->show_komisi($userid, 1);
			if ($data_komisi!=null) {
				$i=1;
				foreach ($data_komisi as $komisi) {
					$result.="<tr>
								<td>".$i."</td><td>".$komisi->Nama_Pembeli."</td>
								<td>".$komisi->Alamat." ".$komisi->Kota."</td><td>".$this->komisi_model->show_total_komisi($this->session->userdata('userid'), $komisi->id)."</td>
								<td>".$komisi->tgl_pembelian."</td>
								<td><a href='".base_url()."komisi/detail/".$komisi->id."' data-target='#myModal' role='button' class='btn btn-info' data-toggle='modal'>View</a></td>
							</tr>";
						$i++;
						}
			}
			$result.="</tbody></table>";

			return $result;
		}

		function lihat_dihapus($userid)
		{

			if ($userid==null) {
				redirect('home');
			}

			$result="<table class='table table-striped table-hover'>
						<thead>
						<tr style='font-weight:900'>
							<td>No.</td><td>User Referall</td><td>Alamat</td><td>Total Komisi (Rp.)</td><td>Tanggal Transaksi</td><td>Detail</td>
						</tr>
						</thead>
						<tbody>";
			$data_komisi=$this->komisi_model->show_komisi($userid, 3);
			if ($data_komisi!=null) {
				$i=1;
				foreach ($data_komisi as $komisi) {
					$result.="<tr>
								<td>".$i."</td><td>".$komisi->Nama_Pembeli."</td>
								<td>".$komisi->Alamat." ".$komisi->Kota."</td><td>".$this->komisi_model->show_total_komisi($this->session->userdata('userid'), $komisi->id)."</td>
								<td>".$komisi->tgl_pembelian."</td>
								<td><a href='".base_url()."komisi/detail/".$komisi->id."' data-target='#myModal' role='button' class='btn btn-info' data-toggle='modal'>View</a></td>
							</tr>";
						$i++;
						}
			}
			$result.="</tbody></table>";

			return $result;
		}

		function detail($id=null)
		{
			$result="<table  class='table table-striped'><thead><tr><td>No.</td><td>Nama Barang</td><td>Harga</td><td>qty</td><td>Total</td><td>Komisi</td></tr></thead>";
			if ($id!=null) {
			 $detail_pembeli=$this->komisi_model->show_detail($this->session->userdata('userid'), $id);
			 	if ($detail_pembeli!=null) {
			 		$i=1;
			 		foreach ($detail_pembeli as $pembeli) {
         			 $result.="<tr><td>".$i."</td><td><strong>".$pembeli->nama_produk."</strong></td><td>".$pembeli->harga."</td><td>".$pembeli->Jumlah."</td><td>".$pembeli->harga*$pembeli->Jumlah."</td><td>".$pembeli->harga*0.2*$pembeli->Jumlah."</td></tr>";  
         			 $i++;
         			}
			 	}
			}
			$result.="</table>";

			$data=array(
					'result'=>$result
				);

			$this->load->view("komisi_detail", $data);

			

		}

		function komisi_premuim($isactive)
		{
			// if ($isactive==null) {
			// 	redirect('home');
			// }

			$result="<table class='table table-striped table-hover'>
						<thead>
						<tr style='font-weight:900'>
							<td>No.</td><td>User Name</td><td>Transfer</td><td>Total Komisi (Rp.)</td><td>Tanggal Transaksi</td>
						</tr>
						</thead>
						<tbody>";
			$data_komisi=$this->komisi_model->show_komisi_premium($this->session->userdata('userid'), $isactive);
			if ($data_komisi!=null) {
				$i=1;
				foreach ($data_komisi as $komisi) {
					$result.="<tr>
								<td>".$i."</td><td>".$komisi->nama_user."</td>
								<td>".$komisi->total_komisi/0.2."</td><td>".$komisi->total_komisi."</td>
								<td>".$komisi->timestamp."</td>
							</tr>";
						$i++;
						}
			}
			$result.="<tr><td><strong>Total Komisi : </strong></td><td><strong>".$this->komisi_model->total_komisi_premium($this->session->userdata('userid'), $isactive)."</strong></td></tr></tbody></table>";

			return $result;
		}


	}
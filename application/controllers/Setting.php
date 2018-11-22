<?php 

/**
* 
*/
class Setting extends CI_Controller
{
	public $uid;
	function __construct()
	{
		# code...
		parent::__construct();
		$this->init();

	}
	public function init()
	{

		if (!$this->aauth->is_loggedin()){
        	redirect();
        }
        $this->uid = $this->session->userdata('id');
        $this->load->model('setting_m');
        $this->load->model('post_m');
        $this->load->model('group_m');

	}
	public function index($value='')
	{

		$data['welcome'] = $this->setting_m->get_all_setting(1);
		$data['header'] = $this->setting_m->get_all_setting(2);
		$data['footer'] = $this->setting_m->get_all_setting(3);
		$data['logo'] = $this->setting_m->get_all_setting(4);
		$data['guide'] = $this->setting_m->get_all_setting(5);



		$data['title']= "user access";
		$this->load->view('admin/default/header',$data);
		$this->load->view('admin/default/menu',$data);
		$this->load->view('admin/setting/site',$data);
		$this->load->view('admin/default/footer',$data);
	}
	public function update_welcome($value='')
	{
		# code...
		if ($this->input->post()) {
			# code...
			$input = (object)$this->input->post();
			$data = array('setting_value' => $input->desc);
			$update = $this->setting_m->save($input->s_id,$data);
			redirect('setting?stats='.$update);
			exit();
		}
	}
	public function update_header($value='')
	{
		# code...
		if ($this->input->post()) {
			# code...
			$input = (object)$this->input->post();
			$data = array('setting_value' => $input->desc);
			$update = $this->setting_m->save($input->s_id,$data);
			redirect('setting?stats='.$update);
			exit();
		}
	}
	public function update_footer($value='')
	{
		# code...
		if ($this->input->post()) {
			# code...
			$input = (object)$this->input->post();
			$data = array('setting_value' => $input->desc);
			$update = $this->setting_m->save($input->s_id,$data);
			redirect('setting?stats='.$update);
			exit();
		}
	}
	public function update_guide($value='')
	{
		# code...
		if ($this->input->post()) {
			# code...
			$input = (object)$this->input->post();
			$data = array('setting_value' => $input->desc);
			$update = $this->setting_m->save($input->s_id,$data);
			redirect('setting?stats='.$update);
			exit();
		}
	}
	public function user_access($value='')
	{
		# code...
		$data['title']= "user access";
		$this->load->view('admin/default/header',$data);
		$this->load->view('admin/default/menu',$data);
		$this->load->view('admin/setting/user_access',$data);
		$this->load->view('admin/default/footer',$data);
	}
	public function security($value='')
	{
		# code...
		if ($this->input->post()) {
			# code...
			$input =(object)$this->input->post();
			if($update = $this->aauth->update_user($this->uid,false,$input->password)){
				redirect('setting/security?stats=account-updated-successfully');
			}else{
				$error =  $this->aauth->print_error();
				redirect('setting/security?stats='.$error);
			}
			exit();
		}
		$data['subtitle'] = '';

		$this->template->load(false,'user/myaccount',$data);
	}
	public function group($value='')
	{
		# code...

		$data['groups'] = $this->group_m->group_type(3);
		$data['title']= "Courses";
		$this->load->view('admin/default/header',$data);
		$this->load->view('admin/default/menu',$data);
		$this->load->view('admin/setting/courses',$data);
		$this->load->view('admin/default/footer',$data);
	}
	public function roles($value='')
	{
		# code...

		$data['groups'] = $this->group_m->expert_role_type();
		$data['title']= "Courses";
		$this->load->view('admin/default/header',$data);
		$this->load->view('admin/default/menu',$data);
		$this->load->view('admin/setting/role',$data);
		$this->load->view('admin/default/footer',$data);
	}
	public function save_role(){
		if($this->input->post()){

			$role =$this->input->post('roles') ;
			$def = $this->input->post('definition') ;
			$action = false;




			if(!empty(trim($role))){

				if($msgs = $this->post_m->get_role($role)){
					$action = false;
				}else{

					$action = $this->group_m->add_expert_role_type(array('role_name'=>$role,'definition'=>$def));

				}

			

			}
			redirect('setting/roles?action='.$action);
		}
	}
	public function delete_role($value='')
	{
		# code...
		if($this->input->post()){
					$id = $this->input->post('role_id');
					//echo json_encode(array('stats'=>false,'msg'=>$id));exit();
				if(	$deleted = $this->setting_m->delete_role($id)){
					echo json_encode(array('stats'=>true));
					exit();
				}

					echo json_encode(array('stats'=>false));
					exit();
		}

					echo json_encode(array('stats'=>false));
	}
	public function delete_course($value='')
	{
		# code...
		if($this->input->post()){
					$id = $this->input->post('role_id');
					//echo json_encode(array('stats'=>false,'msg'=>$id));exit();
				if(	$deleted = $this->setting_m->remove_course($id)){
					echo json_encode(array('stats'=>true));
					exit();
				}

					echo json_encode(array('stats'=>false));
					exit();
		}

					echo json_encode(array('stats'=>false));
	}

	public function course()
	{
		if ($this->input->post()) {

			$input = (object)$this->input->post();

			if($iscourse = $this->setting_m->insert_course($input->courses,$input->definition)){
				redirect('setting/group?stats='.$iscourse);
			}else{

				redirect('setting/group?stats='.$iscourse);
			}
			
		}else{
			echo json_encode(array('stats'=>false,'msg'=>'No input recieved.'));
		}
	}


	public function search_role()
	{
		# code...
		if($this->input->post()){

		$string = $this->input->post('roles');
		$msgs = $this->post_m->get_role($string);
		echo json_encode(array('stats'=>true,'msg'=>$msgs));
		}

		//$msgs = $this->post_m->get_role($string);


		
	}


}
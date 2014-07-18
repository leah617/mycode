<?php
class Page extends Controller {
	function __construct() {
		parent::Controller();
		$this->load->model('Page_model');
		$this->load->model('Menu_model');
	}
	
	function index() {		
		$data['title'] = "Home";
		# Menu
		$data['about'] = $this->Menu_model->get_links(1);
		$data['services'] = $this->Menu_model->get_links(2);
		$data['eventsgallery'] = $this->Menu_model->get_links(4);
		$data['casestudies'] = $this->Menu_model->get_links(5);
		$data['testimonials'] = $this->Menu_model->get_links(6);
		$data['mediafocus'] = $this->Menu_model->get_links(7);
		$this->load->view('pages/header',$data);
		$this->load->view('pages/home');
		$this->load->view('pages/footer');

	}
	
	
	function content($category_name="",$page_name="") {
		# First determine the category
		$category_name = str_replace("_","-",$category_name);
		$category = $this->Page_model->get_category_by_name($category_name);
		$category_id = $category['id'];
		
		# Get the page
		if ($page_name == "") {
			$page_name = $category['default_page'];
		}
		$page = $this->Page_model->get_page_by_name($category_id,$page_name);
		
		# If not found, display not found page
		if (!$page) {
			show_404('page');
		}
		
		# Otherwise, get the neccessary information
		# Title
		$title = ucwords($category['title']." &raquo; ".str_replace(":","",$page['title']));
		
		# Photos of gallery if exists
		$photos = false;
		if ($page['extra_type'] == "gallery") {
			$this->load->model('Gallery_model');
			if ($page['extra_value'] > 0) {
				$photos = $this->Gallery_model->get_photos($page['extra_value']);
			}
		}
		
		# Menu
		$data['about'] = $this->Menu_model->get_links(1);
		$data['services'] = $this->Menu_model->get_links(2);
		$data['eventsgallery'] = $this->Menu_model->get_links(4);
		$data['casestudies'] = $this->Menu_model->get_links(5);
		$data['testimonials'] = $this->Menu_model->get_links(6);
		$data['mediafocus'] = $this->Menu_model->get_links(7);
				
		$data['title'] = $title;
		$data['page'] = $page;
		$data['photos'] = $photos;
		$this->load->view('pages/header',$data);
		if ($category_name == "events-gallery" && $this->uri->segment(2) != "") {
			$this->load->view('pages/gallery',$data);
		} else {
			$this->load->view('pages/content',$data);
		}
		$this->load->view('pages/footer');
	}
	
	function elle_models($sex="") 
	{		
		# Title
		$title = "Elle Models";
		$gender = 'any';
		if ($sex == "guys") {
			$gender = 'm';
			$title .= " &raquo; Guys";
		} else if ($sex == "girls") {
			$gender = 'f';
			$title .= " &raquo; Girls";
		}
		$this->load->model('Talent_model');
		#$talents = $this->Talent_model->search_talents('',$gender,'any','any','live');
		$talents = $this->Talent_model->search_talents('',$gender,'any','live','any','any','any','any','');
		$data['talents'] = $talents;
		
		# Menu
		$data['about'] = $this->Menu_model->get_links(1);
		$data['services'] = $this->Menu_model->get_links(2);
		$data['eventsgallery'] = $this->Menu_model->get_links(4);
		$data['casestudies'] = $this->Menu_model->get_links(5);
		$data['testimonials'] = $this->Menu_model->get_links(6);
		$data['mediafocus'] = $this->Menu_model->get_links(7);
				
		$data['title'] = $title;
		$this->load->view('pages/header',$data);
		if ($sex == "") {
			$this->load->view('pages/elle_models');
		} else {
			$this->load->view('pages/elle_list',$data);
		}
		$this->load->view('pages/footer');
	}
	
	function profile($id="") 
	{		
		# Title
		$title = "Elle Model Profile";		
		$this->load->model('Talent_model');
		$talent = $this->Talent_model->get_talent($id);
		# If not found, display not found page
		if (!$talent) {
			show_404('page');
		}
		$data['talent'] = $talent;
		
		# Menu
		$data['about'] = $this->Menu_model->get_links(1);
		$data['services'] = $this->Menu_model->get_links(2);
		$data['eventsgallery'] = $this->Menu_model->get_links(4);
		$data['casestudies'] = $this->Menu_model->get_links(5);
		$data['testimonials'] = $this->Menu_model->get_links(6);
		$data['mediafocus'] = $this->Menu_model->get_links(7);
				
		$data['title'] = $title;
		$this->load->view('pages/header',$data);
		$this->load->view('pages/elle_profile',$data);
		$this->load->view('pages/footer');
	}
	
	function contact() 
	{
		if (isset($_POST['name'])) {			
			$message = sprintf("
%s
			",$_POST['message']);
		
			$this->load->library('email');		
			$this->email->from($_POST['email'],$_POST['name'].' ('.$_POST['company'].')');
			$this->email->to('info@ellepromotions.com.au','michelle@ellepromotions.com.au','sbotha@ellepromotions.com.au');			
			$this->email->subject('Message from Contact form @ Elle Promotions');
			$this->email->message($message);
			$this->email->send();
			redirect('contact-us/thank-you');
		}
		# Title
		$title = "Contact Elle";
		# Menu
		$data['about'] = $this->Menu_model->get_links(1);
		$data['services'] = $this->Menu_model->get_links(2);
		$data['eventsgallery'] = $this->Menu_model->get_links(4);
		$data['casestudies'] = $this->Menu_model->get_links(5);
		$data['testimonials'] = $this->Menu_model->get_links(6);
		$data['mediafocus'] = $this->Menu_model->get_links(7);
				
		$data['title'] = $title;
		$this->load->view('pages/header',$data);
		$this->load->view('pages/contact');
		$this->load->view('pages/footer');
	}
	function thankyou_contact() 
	{		
		# Menu
		$data['about'] = $this->Menu_model->get_links(1);
		$data['services'] = $this->Menu_model->get_links(2);
		$data['eventsgallery'] = $this->Menu_model->get_links(4);
		$data['casestudies'] = $this->Menu_model->get_links(5);
		$data['testimonials'] = $this->Menu_model->get_links(6);
		$data['mediafocus'] = $this->Menu_model->get_links(7);
		# Title
		$data['title'] = "Thank you";
		$this->load->view('pages/header',$data);
		$this->load->view('pages/contact_thankyou');
		$this->load->view('pages/footer');
	}
	function signup() 
	{
		if (isset($_POST['name'])) {
			$validate = true;
			if (trim($_POST['name']) == "") {
				$this->session->set_flashdata('su_name',true);
				$validate = false;
			}
			if (trim($_POST['address']) == "") {
				$this->session->set_flashdata('su_address',true);
				$validate = false;
			}
			if (trim($_POST['city']) == "") {
				$this->session->set_flashdata('su_city',true);
				$validate = false;
			}
			if (trim($_POST['postcode']) == "") {
				$this->session->set_flashdata('su_postcode',true);
				$validate = false;
			}
			if (trim($_POST['phone']) == "") {
				$this->session->set_flashdata('su_phone',true);
				$validate = false;
			}
			if (trim($_POST['mobile']) == "") {
				$this->session->set_flashdata('su_mobile',true);
				$validate = false;
			}
			if (trim($_POST['email']) == "") {
				$this->session->set_flashdata('su_email',true);
				$validate = false;
			} else {
				$this->load->helper('email');
				if (!valid_email($_POST['email'])) {
					$this->session->set_flashdata('su_email',true);
					$validate = false;
				}
			}
						
			$config['upload_path'] = "./tmp";
			$config['allowed_types'] = 'gif|jpg|png';
			$config['max_size']	= '4096'; // 4 MB
			$config['max_width']  = '4000';
			$config['max_height']  = '4000';
			$config['overwrite'] = FALSE;
			$config['remove_space'] = TRUE;
			
			$this->load->library('upload', $config);
		
			if ( ! $this->upload->do_upload()) {
				$this->session->set_flashdata('su_userfile',true);
				$validate = false;
			} else {
				$data = array('upload_data' => $this->upload->data());				
			}
			
			$this->session->set_userdata('signup_talent',$_POST);
			if (!$validate) {
				if (isset($data)) { unlink($data['upload_data']['full_path']); }
				redirect('sign-up');
			}
			
			// Insert data
			$name = explode(" ",$_POST['name']);
			$first_name = $name[0];
			$last_name = '';
			if (count($name) > 1) {
				$last_name = $name[1];
			}
			$talent = array(
				'first_name' => $first_name,
				'last_name' => $last_name,
				'address' => $_POST['address'],
				'city' => $_POST['city'],
				'state' => $_POST['state'],
				'postcode' => $_POST['postcode'],
				'phone' => $_POST['phone'],
				'email' => $_POST['email'],
				'dob' => $_POST['dob'],
				'gender' => $_POST['gender'],
				'status' => 'pending'
			);
			$this->load->model('Talent_model');
			$id = $this->Talent_model->add_talent($talent);
			$this->send_pending($id);
			$this->send_notification($id);
			
			$mobile = str_replace(" ","",$_POST['mobile']);
			$mobile = trim($mobile);
			$newdata = array(
				'mobile' => $mobile,
				'created' => date('Y-m-d H:i:s'),
				'modified' => date('Y-m-d H:i:s')
				);
			$this->Talent_model->update_talent($id,$newdata);
			
			$path = "./uploads/talents";
			$newfolder = md5('elletalent'.$id);
			$dir = $path."/".$newfolder;
			
			mkdir($dir,0777);
			chmod($dir,0777);
			$thumbnails = $dir."/thumbnails";
			mkdir($thumbnails,0777);
			chmod($thumbnails,0777);
			$resizes = $dir."/resizes";
			mkdir($resizes,0777);
			chmod($resizes,0777);
			
			$field = "photo_1";
			$dir_name = md5('elletalent'.$id);			
			
			$file_name = $data['upload_data']['file_name'];
			rename($data['upload_data']['full_path'],"./uploads/talents/".$dir_name."/".$file_name);
			
			$width = $data['upload_data']['image_width'];
			$height = $data['upload_data']['image_height'];
			$photo = array(
				$field => '0~'.$file_name,
				'modified' => date('Y-m-d H:i:s')
			);
			$pid = $this->Talent_model->update_talent($id,$photo);
			if ($width > 304 || $height > 412) {
				$config = array();
				// Resize image
				$config['source_image'] = "./uploads/talents/".$dir_name."/".$file_name;
				$config['create_thumb'] = TRUE;
				$config['maintain_ratio'] = TRUE;
				$config['quality'] = 100;
				$config['width'] = 304;
				$config['height'] = 412;
				$config['master_dim'] = 'auto';
				$this->load->library('image_lib');
				$this->image_lib->clear();
				$this->image_lib->initialize($config);
				$this->image_lib->resize();
				unlink("./uploads/talents/".$dir_name."/".$file_name);
				rename("./uploads/talents/".$dir_name."/".$data['upload_data']['raw_name']."_thumb".$data['upload_data']['file_ext'],"./uploads/talents/".$dir_name."/".$file_name);
				$this->image_lib->clear();
			}
			
			// Resize creation
			$config = array();
			$config['source_image'] = "./uploads/talents/".$dir_name."/".$file_name;
			$config['create_thumb'] = TRUE;
			$config['new_image'] = "./uploads/talents/".$dir_name."/resizes/".$file_name;
			$config['maintain_ratio'] = TRUE;
			$config['quality'] = 100;
			$config['width'] = 141;
			$config['height'] = 191;
			$this->load->library('image_lib');
			$this->image_lib->clear();
			$this->image_lib->initialize($config);
			$this->image_lib->resize();
			
			rename("./uploads/talents/".$dir_name."/resizes/".$data['upload_data']['raw_name']."_thumb".$data['upload_data']['file_ext'],"./uploads/talents/".$dir_name."/resizes/".$file_name);
			$this->image_lib->clear();
			
			
			// Thumbnail creation
			$config = array();
			$config['source_image'] = "./uploads/talents/".$dir_name."/".$file_name;
			$config['create_thumb'] = TRUE;
			$config['new_image'] = "./uploads/talents/".$dir_name."/thumbnails/".$file_name;
			$config['maintain_ratio'] = TRUE;
			$config['quality'] = 100;
			$config['width'] = 53;
			$config['height'] = 72;
			$this->load->library('image_lib');
			$this->image_lib->clear();
			$this->image_lib->initialize($config);
			$this->image_lib->resize();
			
			rename("./uploads/talents/".$dir_name."/thumbnails/".$data['upload_data']['raw_name']."_thumb".$data['upload_data']['file_ext'],"./uploads/talents/".$dir_name."/thumbnails/".$file_name);
			$this->image_lib->clear();
			
			$this->session->unset_userdata('signup_talent');
			redirect('sign-up/thank-you');
		} else {		
			$title = "Become an Elle promoter";	
		}
		
		$data['states'] = $this->Util_model->get_states();
		# Menu
		$data['about'] = $this->Menu_model->get_links(1);
		$data['services'] = $this->Menu_model->get_links(2);
		$data['eventsgallery'] = $this->Menu_model->get_links(4);
		$data['casestudies'] = $this->Menu_model->get_links(5);
		$data['testimonials'] = $this->Menu_model->get_links(6);
		$data['mediafocus'] = $this->Menu_model->get_links(7);
		# Title
		$data['title'] = $title;
		$this->load->view('pages/header',$data);
		$this->load->view('pages/signup');
		$this->load->view('pages/footer');
	}
	function thankyou_signup() 
	{		
		# Menu
		$data['about'] = $this->Menu_model->get_links(1);
		$data['services'] = $this->Menu_model->get_links(2);
		$data['eventsgallery'] = $this->Menu_model->get_links(4);
		$data['casestudies'] = $this->Menu_model->get_links(5);
		$data['testimonials'] = $this->Menu_model->get_links(6);
		$data['mediafocus'] = $this->Menu_model->get_links(7);
		# Title
		$data['title'] = "Thank you";
		$this->load->view('pages/header',$data);
		$this->load->view('pages/signup_thankyou');
		$this->load->view('pages/footer');
	}
	function send_notification($id)
	{
		$url = base_url().'admin/talent/view/'.$id;
		$message = sprintf("

A new application has been placed through the Elle Promotions website.

Please login to the system to view staff profile

Url: %s

		",$url);
	
		$this->load->library('email');		
		$this->email->from('noreply@ellepromotions.com.au','Elle Promotions');
		$this->email->to('staff@ellepromotions.com.au');
		$this->email->cc('michelle@ellepromotions.com.au');
		#$this->email->bcc('nam@propagate.com.au');		
		$this->email->subject('New Application @ Elle Promotions');
		$this->email->message($message);
		$this->email->send();
	}
	
	function send_pending($id)
	{
		$talent = $this->Talent_model->get_talent($id);
		$message = sprintf("
Hi %s,

Thank you for signing up to become an Elle Promoter.

Your application is currently in our system and pending approval.

You should receive an email from us shortly.

Kind Regards
The Elle Team
		",$talent['first_name']);
	
		$this->load->library('email');		
		$this->email->from('noreply@ellepromotions.com.au','Elle Promotions');
		$this->email->to($talent['email']);
		
		$this->email->subject('Pending approval at Elle Promotions');
		$this->email->message($message);
		$this->email->send();
	}
	
	function forgotreset() 
	{
		if (isset($_POST['username'])) {
			if ($_POST['type'] == "staff") {
				$this->load->model('Talent_model');
				$talent = $this->Talent_model->lookup($_POST['username']);
				if ($talent) {
					$this->load->helper('string');
					$new_password = random_string('alnum', 8);
					if ($this->send_new_password($talent['email'],$new_password)) {
						$this->Talent_model->update_talent($talent['id'],array('password' => md5($new_password)));
						$this->session->set_flashdata('ok_rs',true);
					} else {
						$this->session->set_flashdata('error_rs','Couldnot send the email. Please try later.');
					}
				} else {
					$this->session->set_flashdata('error_rs','No email address found in the system.');
				}
			} else if ($_POST['type'] == "client") {
				$this->load->model('Client_model');
				$client = $this->Client_model->lookup($_POST['username']);
				if ($client) {
					$this->load->helper('string');
					$new_password = random_string('alnum', 8);
					if ($this->send_new_password($client['email'],$new_password)) {
						$this->Client_model->update_client($client['id'],array('password' => md5($new_password)));
						$this->session->set_flashdata('ok_rs',true);
					} else {
						$this->session->set_flashdata('error_rs','Couldnot send the email. Please try later.');
					}
				} else {
					$this->session->set_flashdata('error_rs','No username found in the system.');
				}
			}
		}
		redirect('forgot-password');
	}
	function forgot() 
	{
		$this->load->view('pages/forgot');
	}	
	function send_new_password($email,$password) 
	{
		$message = sprintf("
A new password has been generated for you 

Username		%s
Password		%s

Please login in by visiting the link below to access your account

%s

The Elle Team",$email,$password,base_url());
	
		$this->load->library('email');		
		$this->email->from('noreply@ellepromotions.com.au','Elle Promotions');
		$this->email->to($email);
		
		$this->email->subject('New password at Elle Promotions');
		$this->email->message($message);
		return $this->email->send();		
	}
}
?>
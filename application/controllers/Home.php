<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(COREPATH."controllers/Admin_controller.php");

class Home extends Admin_Controller {

	public function index()
	{
		//echo base_path();
		$this->layout->view('frontend/home/index');
	}
	public function login()
	{
		$this->layout->view('frontend/home/login');
	}
	public function register()
	{
		$this->layout->view('frontend/home/signup');
	}
	public function contact()
	{
		$this->layout->view('frontend/home/contact');
	}
	public function events()
	{
		$this->layout->view('frontend/home/events');
	}
	public function about()
	{
		$this->layout->view('frontend/home/about');
	}
	public function services()
	{
		$this->layout->view('frontend/home/services');
	}
	/*public function login()
	{
		$this->layout->view('frontend/home/login');
	}
	public function login()
	{
		$this->layout->view('frontend/home/login');
	}*/

}
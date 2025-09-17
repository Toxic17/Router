<?php 
	namespace App\Http\Controller;

	use Core\View;

	class Controller
	{
		protected $view;

		public function __construct()
		{
			$this->view = new View();
		}

	}
?>
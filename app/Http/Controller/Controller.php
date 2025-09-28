<?php 
	namespace App\Http\Controller;

	use Core\RequestFactory;
    use Core\View;


	class Controller
	{
		protected $view;
        protected $request;

        protected $get_params;

        protected $post_params;

		public function __construct()
		{
			$this->view = new View();

            $this->request = RequestFactory::createFromGlobals();
            $this->get_params = RequestFactory::getQueryParams();
            $this->post_params = RequestFactory::getPostParams();

		}

	}
?>
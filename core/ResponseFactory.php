<?php 
	namespace Core;

	class ResponseFactory
	{
		public function json(array $data,$status = 200) : string 
		{
			$stream = new Stream('php://memory', 'w+b');
        	$stream->write(json_encode($data));
        	$stream->rewind();
        	$response = new Response($stream,['Content-Type'=>['application/json'],'Access-Control-Allow-Origin'=>['*']]);
        	$response->withStatus($status);
        	$response->send();
		    return $response;
        }

		public function view($templateName, $data = [], $status = 200, $headers = [])
		{
			$stream = new Stream('php://memory', 'w+b');
        	$view = new View();
        	$body = $view->view('main.index',$data);
        	
        	$stream->write($body);
        	$stream->rewind();

        	$response = new Response($stream,$headers,'1.1',200,'test');
        	$response->send();
            return $response;
		}
		



	}
?>
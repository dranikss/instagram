<?php
/*
* Instagram API мини обертка
* @author Drankov Sergey [dranikss]
*
*/
 class Instagram {

 	private $client_id;
 	private $access_token;
 	private $endpoint = 'https://api.instagram.com/v1/';

 	public function __construct($cfg){
 		if( array_key_exists('client_id', $cfg) ){
 			$this->client_id = $cfg['client_id'];
 		}

 		if( array_key_exists('access_token', $cfg) ){
 			$this->access_token = $cfg['access_token'];
 		}
 	}

 	// Отправляем запрос
 	private function request($endpoint, $params = array()){
 		$request = $this->build_request($endpoint, $params);
 		return $this->send_request($request);
 	}

 	// Формируем строку запроса
 	public function build_request($endpoint = '', $params = ''){
 		$endpoint = $this->endpoint . $endpoint . '?';

 		if($this->client_id) {
 			$endpoint = $endpoint . 'client_id=' . $this->client_id . '&';
 		}

 		if($this->access_token) {
 			$endpoint = $endpoint . 'access_token=' . $this->access_token . '&';
 		}

 		if($params) {
 			$endpoint = $endpoint . http_build_query($params);
 		}

 		return $endpoint;
 	}

 	// CURL запрос
 	private function send_request($uri){
 		$curl = curl_init($uri);
 		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
 		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
 		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
 		$response = curl_exec($curl);
 		curl_close($curl);
 		return $response;
 	}

 	// Json декодирование
 	public function decode( $response ){
 		return json_decode( $response );
 	}

 	// Получаем мини инфо пользователя, включая user_id
 	public function get_users_search($q, $count = null){
 		return $this->request('users/search', array('q'=>$q, 'count'=>$count));
 	}

 	// Получаем изображения пользователя со всей инфой
 	public function get_users_media_recent($user_id, $params = array()){
 		return $this->request('users/' . $user_id . '/media/recent', $params);
 	}

 	// Получаем подробную инфу о пользователе
 	public function get_users($user_id){
 		return $this->request('users/' . $user_id);
 	}

 	// Получаем первые 20 фоток пользователя
 	public function get_user_20_photo($user_nickname, $type = "url"){
		$array    = array();
		$response = $this->get_users_search($user_nickname);
		$response = $this->decode($response);
		$user_id  = $response->data[0]->id;
		$response = $this->get_users_media_recent($user_id);
		$response = $this->decode($response);

		if(is_array($response->data)){
	 		foreach ($response->data as $value) {
				switch ( $type ) {
					case 'url':
						$array[] = $value->images->standard_resolution->url;
						break;
					case 'img':
						$array[] = '<img src="'.$value->images->standard_resolution->url.'" />';
					default:
						break;
				}
			}
		}

 		return $array;
 	}

 }
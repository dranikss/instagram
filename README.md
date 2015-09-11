# Мини обертка для instagram API

PHP обертка запросов instagram API https://instagram.com/developer/endpoints/

      require_once('lib_instagram.php');

      $instagram = new Instagram(
        array(
          'client_id' => 'CLIENT_ID',
        )
      );

      // Получить первые 20 фотографий пользователя USERNAME
      //$response = $instagram->get_user_20_photo('USERNAME', 'url');
      $response = $instagram->get_user_20_photo('USERNAME', 'img');

      if($response) {
        foreach ($response as $value) {
		      echo $value;
	      }
      }

<?php

$data = json_decode(file_get_contents('data.json'),1);

function getLastProd() {
	global $data;
	while(true) {
		$magicArray = json_decode(file_get_contents('http://api.pouet.net/v1/prod/?id='.$data['idEnd']),1);

		if($magicArray['success']==1) {
			$data['idEnd']=$data['idEnd']+1;
		} else {
			$data['idEnd']=$data['idEnd']-1;
			file_put_contents('data.json', json_encode($data));
			break;
		}
	}
	return $data['idEnd'];
}

function getProd($id) {
	$magicArray = json_decode(file_get_contents('http://api.pouet.net/v1/prod/?id='.$id),1);
	return $magicArray;
}

function randomId() {
	global $data;
	$random = mt_rand($data['idStart'],$data['idEnd']);
	return $random;
}

function postImg($imgdata) {
	global $data;
	$api_img = curl_init();
	curl_setopt($api_img, CURLOPT_URL, 'https://'.$data['instance'].'/api/v1/media');
	curl_setopt($api_img, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($api_img, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$data['token']));
	curl_setopt($api_img, CURLOPT_POST, 1);
	$args['file'] = new CurlFile('/tmp/tmppouet.png', 'multipart/form-data');
	curl_setopt($api_img, CURLOPT_POSTFIELDS, $args);
	$api_img_data = curl_exec($api_img);
	curl_close($api_img);
	return json_decode($api_img_data,1);
}

function post($magicArray) {
	global $data;
	$by = '';
	foreach($magicArray['prod']['groups'] as $group) {
		$by = $by.$group['name'].', ';
	}
	if($by != '') {
		$by = 'by '.$by;
	}
	$url = str_replace('\ ', '', $magicArray['prod']['screenshot']);
	$imgdata = file_get_contents($url);
	file_put_contents('/tmp/tmppouet.png', $imgdata);
	$img = postImg('/tmp/tmppouet.png');
	$api = curl_init();
	curl_setopt($api, CURLOPT_URL, 'https://'.$data['instance'].'/api/v1/statuses');
	curl_setopt($api, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($api, CURLOPT_POST, 1);
	curl_setopt($api, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$data['token']));
	curl_setopt($api, CURLOPT_POSTFIELDS,
			http_build_query(['status' => '"'.$magicArray['prod']['name'].'" '.$by.' http://www.pouet.net/prod.php?which='.$magicArray['prod']['id'],'media_ids[]' => $img['id']]));
	$api_data = curl_exec($api);
	curl_close($api);

}


getLastProd();

$random = randomId();
$prodId = getProd($random);

post($prodId);
<?php declare(strict_types=1);
/**
 * Created by: Niki Ewald Zakariassen
 * Date: 14-04-2020 - 13:01
 */

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://regex-and-pe-to-dfa.com/Darjeeling/");
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(['field' => $_REQUEST["automata"]]));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$server_output = curl_exec($ch);

curl_close ($ch);
//$image = imagecreatefromstring($server_output);

header('Content-type: image/png');
echo base64_decode($server_output);
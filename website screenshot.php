<?php

// Get the URL from the "url" parameter
$url = isset($_GET['url']) ? $_GET['url'] : '';

// Check if URL is provided
if(empty($url)) {
    die("URL parameter is missing!");
}

// Function to make cURL request
function makeRequest($url, $device) {
    // Set the URL and other parameters for the cURL request
    $data = array(
        'url' => $url,
        'device' => $device,
        'flag' => 'main'
    );
    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://www.page2images.com/api/call',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => http_build_query($data),
      CURLOPT_HTTPHEADER => array(
        'Accept: */*',
        'Accept-Language: en-US,en;q=0.9',
        'Connection: keep-alive',
        'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
        'Origin: https://www.page2images.com',
        'Referer: https://www.page2images.com/',
        'Sec-Fetch-Dest: empty',
        'Sec-Fetch-Mode: cors',
        'Sec-Fetch-Site: same-origin',
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/123.0.0.0 Safari/537.36',
        'X-Requested-With: XMLHttpRequest',
        'sec-ch-ua: "Google Chrome";v="123", "Not:A-Brand";v="8", "Chromium";v="123"',
        'sec-ch-ua-mobile: ?0',
        'sec-ch-ua-platform: "Windows"',
      ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);

    return json_decode($response, true);
}

// Make requests for both mobile and desktop versions
$mobile_response = makeRequest($url, '2');
$desktop_response = makeRequest($url, '6');

if ($mobile_response['status'] === 'processing'){

  die(json_encode($mobile_response));
}
// Combine responses into a single JSON object
$output = array(
    'status' => $mobile_response['status'], // assuming both responses have same status
    'mobile_image_url' => $mobile_response['image_url'],
    'desktop_image_url' => $desktop_response['image_url'],
    'ori_url' => $mobile_response['ori_url'] // assuming ori_url is the same in both responses
);

// Output the combined JSON response
echo json_encode($output);
?>

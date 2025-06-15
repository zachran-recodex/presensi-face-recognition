# GET Get Counters
```
https://fr.neoapi.id/risetai/face-api/client/get-counters
```

This API fetches client's API Counters Remaining Quota (API Hits, Num Faces Enrolled, & Num FaceGallery Owned).

## Example Request
```php
<?php
$client = new Client();
$headers = [
  'Accesstoken' => 'TOKEN_ID'
];
$body = '{
    "trx_id" : "alphanumericalstring1234"
}';
$request = new Request('GET', 'https://fr.neoapi.id/risetai/face-api/client/get-counters', $headers, $body);
$res = $client->sendAsync($request)->wait();
echo $res->getBody();
```

---

# GET My Facegalleries
```
https://fr.neoapi.id/risetai/face-api/facegallery/my-facegalleries
```

This API gives the list of facegallery.

## Example Request
```php
<?php
$client = new Client();
$headers = [
  'Accesstoken' => 'TOKEN_ID'
];
$request = new Request('GET', 'https://fr.neoapi.id/risetai/face-api/facegallery/my-facegalleries', $headers);
$res = $client->sendAsync($request)->wait();
echo $res->getBody();
```

---

# POST Create Facegallery
```
https://fr.neoapi.id/risetai/face-api/facegallery/create-facegallery
```

This API creates the new face gallery.

## Example Request
```php
<?php
$client = new Client();
$headers = [
  'Accesstoken' => 'TOKEN_ID'
];
$body = '{
  "facegallery_id":   "riset.ai@production",
  "trx_id":           "alphanumericalstring1234"
}';
$request = new Request('POST', 'https://fr.neoapi.id/risetai/face-api/facegallery/create-facegallery', $headers, $body);
$res = $client->sendAsync($request)->wait();
echo $res->getBody();
```

---

# DELETE Delete Facegalleries
```
https://fr.neoapi.id/risetai/face-api/facegallery/delete-facegallery
```

This API deletes a facegallery.

## Example Request
```php
<?php
$client = new Client();
$headers = [
  'Accesstoken' => 'TOKEN_ID'
];
$body = '{
  "facegallery_id":   "riset.ai@production",
  "trx_id":           "alphanumericalstring1234"
}';
$request = new Request('DELETE', 'https://fr.neoapi.id/risetai/face-api/facegallery/delete-facegallery', $headers, $body);
$res = $client->sendAsync($request)->wait();
echo $res->getBody();
```

---

# POST Enroll Face
```
https://fr.neoapi.id/risetai/face-api/facegallery/enroll-face
```

This API registers a user to the database.

## Example Request
```php
<?php
$client = new Client();
$headers = [
  'Accesstoken' => 'TOKEN_ID'
];
$body = '{
  "user_id":          "risetai1234",
  "user_name":        "RisetAi Username1",
  "facegallery_id":   "riset.ai@production",
  "image":            "/9j/4AAQSkZJRogZ2QtankcgSlBFRyB2NjIpLCBxdWFsaXR5gMTAwCv/bAEMAAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAf/a3dl06dLJK9rPTy1t10VKVaUvfioxvpZWdvd9Lq3ptbVM0vCd9qMnjKeG6t/sdpc6TZajNf+bMNX1W7juJpgNQ2ts0RYlvoEzYuhlSNI5cqqge9eG/CV3ret2Gqazr0EVvDLGbGys5ru1trYREEGe+R3+3zXAAM8EgMEbswCBcV5VrkGutHZ6X4dbStPtYYH0/Wr+4tZ4tZl+0Olxbx6dBHJb2qfa3V4d27ToIY7dRbre2ljcpcfWPwx8LjwhoWnjTw00++78uEF3uF3L5hSYzKrNMZ3kgmZlVnMZVlGNozetSL72/8AbSqk402qunKradNLJ6PTbV6a3Z6BNbw+VL54a4kEUnkrbOGUQ/2Q==",
  "trx_id":           "alphanumericalstring1234"
}';
$request = new Request('POST', 'https://fr.neoapi.id/risetai/face-api/facegallery/enroll-face', $headers, $body);
$res = $client->sendAsync($request)->wait();
echo $res->getBody();
```

---

# GET List Faces
```
https://fr.neoapi.id/risetai/face-api/facegallery/list-faces
```

This API gives a list of the registered user.

## Example Request
```php
<?php
$client = new Client();
$headers = [
  'Accesstoken' => 'TOKEN_ID'
];
$body = '{
  "facegallery_id": "riset.ai@production",
  "trx_id":         "alphanumericalstring1234"
}';
$request = new Request('GET', 'https://fr.neoapi.id/risetai/face-api/facegallery/list-faces', $headers, $body);
$res = $client->sendAsync($request)->wait();
echo $res->getBody();
```

---

# POST Verify Face
```
https://fr.neoapi.id/risetai/face-api/facegallery/verify-face
```

This API verifies an user_id and an image with a registered user or it does 1:1 authentication.

## Example Request
```php
<?php
$client = new Client();
$headers = [
  'Accesstoken' => 'TOKEN_ID'
];
$body = '{
  "user_id":          "risetai1234",
  "facegallery_id":   "riset.ai@production",
  "image":            "/9j/4AAQSkZJRogZ2QtankcgSlBFRyB2NjIpLCBxdWFsaXR5gMTAwCv/bAEMAAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAf/a3dl06dLJK9rPTy1t10VKVaUvfioxvpZWdvd9Lq3ptbVM0vCd9qMnjKeG6t/sdpc6TZajNf+bMNX1W7juJpgNQ2ts0RYlvoEzYuhlSNI5cqqge9eG/CV3ret2Gqazr0EVvDLGbGys5ru1trYREEGe+R3+3zXAAM8EgMEbswCBcV5VrkGutHZ6X4dbStPtYYH0/Wr+4tZ4tZl+0Olxbx6dBHJb2qfa3V4d27ToIY7dRbre2ljcpcfWPwx8LjwhoWnjTw00++78uEF3uF3L5hSYzKrNMZ3kgmZlVnMZVlGNozetSL72/8AbSqk402qunKradNLJ6PTbV6a3Z6BNbw+VL54a4kEUnkrbOGUQ/2Q==",
  "trx_id":           "alphanumericalstring1234"
}';
$request = new Request('POST', 'https://fr.neoapi.id/risetai/face-api/facegallery/verify-face', $headers, $body);
$res = $client->sendAsync($request)->wait();
echo $res->getBody();
```

---

# POST Identify Face
```
https://fr.neoapi.id/risetai/face-api/facegallery/identify-face
```

This API identify an image with a registered user or it do 1:N authentication.

## Example Request
```php
<?php
$client = new Client();
$headers = [
  'Accesstoken' => 'TOKEN_ID'
];
$body = '{
  "facegallery_id":   "riset.ai@production",
  "image":            "/9j/4AAQSkZJRogZ2QtankcgSlBFRyB2NjIpLCBxdWFsaXR5gMTAwCv/bAEMAAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAf/a3dl06dLJK9rPTy1t10VKVaUvfioxvpZWdvd9Lq3ptbVM0vCd9qMnjKeG6t/sdpc6TZajNf+bMNX1W7juJpgNQ2ts0RYlvoEzYuhlSNI5cqqge9eG/CV3ret2Gqazr0EVvDLGbGys5ru1trYREEGe+R3+3zXAAM8EgMEbswCBcV5VrkGutHZ6X4dbStPtYYH0/Wr+4tZ4tZl+0Olxbx6dBHJb2qfa3V4d27ToIY7dRbre2ljcpcfWPwx8LjwhoWnjTw00++78uEF3uF3L5hSYzKrNMZ3kgmZlVnMZVlGNozetSL72/8AbSqk402qunKradNLJ6PTbV6a3Z6BNbw+VL54a4kEUnkrbOGUQ/2Q==",
  "trx_id":           "alphanumericalstring1234"
}';
$request = new Request('POST', 'https://fr.neoapi.id/risetai/face-api/facegallery/identify-face', $headers, $body);
$res = $client->sendAsync($request)->wait();
echo $res->getBody();
```

___

# DELETE Delete Face
```
https://fr.neoapi.id/risetai/face-api/facegallery/delete-face
```

This API deletes a user.

## Example Request
```php
<?php
$client = new Client();
$headers = [
  'Accesstoken' => 'TOKEN_ID'
];
$body = '{
  "user_id":        "risetai1234",
  "facegallery_id": "riset.ai@production",
  "trx_id":         "alphanumericalstring1234"
}';
$request = new Request('DELETE', 'https://fr.neoapi.id/risetai/face-api/facegallery/delete-face', $headers, $body);
$res = $client->sendAsync($request)->wait();
echo $res->getBody();
```

---

# POST Compare Images
```
https://fr.neoapi.id/risetai/face-api/compare-images
```

This API compares the two images to determine if they are verified or not. This API does not use the information in the database.

## Example Request
```php
<?php
$client = new Client();
$headers = [
  'Accesstoken' => 'TOKEN_ID'
];
$body = '{
  "source_image":     "/9ZJRogZ2QtankcgSlBFRyB2NjIpLCBxdWFsaXR5gMTAwCv/bAEMAAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAf/a3dl06dLJK9rPTy1t10VKVaUvfioxvpZWdvd9Lq3ptbVM0vCd9qMnjKeG6t/sdpc6TZajNf+bMNX1W7juJpgNQ2ts0RYlvoEzYuhlSNI5cqqge9eG/CV3ret2Gqazr0EVvDLGbGys5ru1trYREEGe+R3+3zXAAM8EgMEbswCBcV5VrkGutHZ6X4dbStPtYYH0/Wr+4tZ4tZl+0Olxbx6dBHJb2qfa3V4d27ToIY7dRbre2ljcpcfWPwx8LjwhoWnjTw00++78uEF3uF3L5hSYzKrNMZ3kgmZlVnMZVlGNozetSL72/8AbSqk402qunKradNLJ6PTbV6a3Z6BNbw+VL54a4kEUnkrbOGUQ/2Q==",
  "target_image":     "/9ZJRogZ2QtankcgSlBFRyB2NjIpLCBxdWFsaXR5gMTAwCv/+3zXAAM8EgMEbswCBcV5VrkGutHZ6X4dbStPtYYH0/Wr+4tZ4tZl+0Olxbx6dBHJb2qfa3V4d27ToIY7dRbre2ljcpcfWPwx8LjwhoWnjTw00++78uEF3uF3L5hSYzKrNMZ3kgmZlVnMZVlGNozetSL72/8AbSqk402qunKradNLJ6PTbV6a3Z6BNbw+VL54a4kEUnkrbOGUQ/2Q==bAEMAAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAf/a3dl06dLJK9rPTy1t10VKVaUvfioxvpZWdvd9Lq3ptbVM0vCd9qMnjKeG6t/sdpc6TZajNf+bMNX1W7juJpgNQ2ts0RYlvoEzYuhlSNI5cqqge9eG/CV3ret2Gqazr0EVvDLGbGys5ru1trYREEGe+R3",
  "trx_id":           "alphanumericalstring1234"
}';
$request = new Request('POST', 'https://fr.neoapi.id/risetai/face-api/compare-images', $headers, $body);
$res = $client->sendAsync($request)->wait();
echo $res->getBody();
```

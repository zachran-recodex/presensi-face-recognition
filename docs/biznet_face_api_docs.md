# Biznet Face API Documentation

## Overview

**Face AI at Riset.ai** - Creating artificial intelligence algorithms for seamless non-contact authentication using locally developed datasets. Perfect for multimodal verification techniques.

## Getting Started

### 1. Obtain Token ID
1. Login to [Biznet Portal](https://portal.biznetgio.com/)
2. Navigate to **AI and ML** → **Face Recognition**
3. Click **Create New Face Recognition Service**
4. Fill in Service Name and Package selection
5. Choose payment method and complete order
6. Access Token ID from your created service page

### 2. API Architecture

**System Components:**
- **Client** - Your application accessing the API (can manage multiple FaceGalleries)
- **FaceGallery** - Collection of users from a specific location/area (acts as database)
- **User** - Individual person enrolled for face recognition

### 3. Quick Start
1. [Create FaceGallery](https://documenter.getpostman.com/view/16178629/UVsEVpHD#d937e1b1-2ac3-4d7a-955f-2946cc98e6e0)
2. [Enroll User](https://documenter.getpostman.com/view/16178629/UVsEVpHD#4dff20c5-74ab-44f8-ac34-fb977c643d1d)
3. Test [Identification](https://documenter.getpostman.com/view/16178629/UVsEVpHD#7e0c2077-938c-4b1d-84a7-9de63370dc3d) and [Verification](https://documenter.getpostman.com/view/16178629/UVsEVpHD#8225df79-e2bd-43ef-8330-d233beffe151)

**Additional Help:** [Tutorial Guide](https://kb.biznetgio.com/id_ID/NEO-Face-Recognition/cara-menggunakan-layanan-aiml-face-recognition?from_search=91613883)

## Status Codes

| Code | Type | Description |
|------|------|-------------|
| 200 | Success | Success messages |
| 400 | General Error | Request malformed |
| 401 | General Error | Access token not authorized |
| 403 | General Error | Requested resource denied |
| 411 | Business Process Warning | Face not verified or unregistered |
| 412 | Business Process Warning | Face not detected |
| 413 | Business Process Warning | Face too small |
| 415 | Resource Not Found | user_id not found |
| 416 | Resource Not Found | facegallery_id not found |
| 451 | Resource Not Found | Image is null |
| 452 | Data Format Error | user_id is null |
| 453 | Data Format Error | user_name is null |
| 454 | Data Format Error | facegallery_id is null |
| 455 | Data Format Error | target_image is null |
| 456 | Data Format Error | source_image is null |
| 490 | Image Error | Cannot decode image base64 |
| 491 | Image Error | Image type not recognized |
| 492 | Image Error | Cannot decode target_image base64 |
| 493 | Image Error | Image error |
| 494 | Image Error | Cannot decode source_image base64 |
| 495 | Image Error | source_image type not recognized |
| 500 | Procedural Error | Server error |

## API Endpoints

**Base URL:** `https://fr.neoapi.id/risetai/face-api`

**Authentication:** Include `Accesstoken: TOKEN_ID` in headers for all requests.

### Client Management

#### Get API Counters
```
GET /client/get-counters
```

**Request Body:**
```json
{
  "trx_id": "alphanumericalstring1234"
}
```

**Response:**
```json
{
  "status": "string",
  "status_message": "string",
  "remaining_limit": {
    "n_api_hits": "int",
    "n_face": "int", 
    "n_facegallery": "int"
  }
}
```

### FaceGallery Management

#### List My FaceGalleries
```
GET /facegallery/my-facegalleries
```

**Response:**
```json
{
  "status": "string",
  "status_message": "string",
  "facegallery_id": ["list"]
}
```

#### Create FaceGallery
```
POST /facegallery/create-facegallery
```

**Request Body:**
```json
{
  "facegallery_id": "riset.ai@production",
  "trx_id": "alphanumericalstring1234"
}
```

#### Delete FaceGallery
```
DELETE /facegallery/delete-facegallery
```

**Request Body:**
```json
{
  "facegallery_id": "riset.ai@production",
  "trx_id": "alphanumericalstring1234"
}
```

### User Management

#### Enroll Face
```
POST /facegallery/enroll-face
```

**Request Body:**
```json
{
  "user_id": "risetai1234",
  "user_name": "RisetAi Username1",
  "facegallery_id": "riset.ai@production",
  "image": "base64encodedimage",
  "trx_id": "alphanumericalstring1234"
}
```

#### List Enrolled Faces
```
GET /facegallery/list-faces
```

**Request Body:**
```json
{
  "facegallery_id": "riset.ai@production",
  "trx_id": "alphanumericalstring1234"
}
```

**Response:**
```json
{
  "status": "string",
  "status_message": "string",
  "faces": ["list"]
}
```

#### Delete Face
```
DELETE /facegallery/delete-face
```

**Request Body:**
```json
{
  "user_id": "risetai1234",
  "facegallery_id": "riset.ai@production",
  "trx_id": "alphanumericalstring1234"
}
```

### Face Recognition

#### Verify Face (1:1 Authentication)
```
POST /facegallery/verify-face
```

**Request Body:**
```json
{
  "user_id": "risetai1234",
  "facegallery_id": "riset.ai@production",
  "image": "base64encodedimage",
  "trx_id": "alphanumericalstring1234"
}
```

**Response:**
```json
{
  "status": "string",
  "status_message": "string",
  "user_name": "string",
  "similarity": "float (0.0-1.0)",
  "masker": "boolean",
  "verified": "boolean"
}
```

#### Identify Face (1:N Authentication)
```
POST /facegallery/identify-face
```

**Request Body:**
```json
{
  "facegallery_id": "riset.ai@production",
  "image": "base64encodedimage",
  "trx_id": "alphanumericalstring1234"
}
```

**Response:**
```json
{
  "status": "string",
  "status_message": "string",
  "confidence_level": "float (0.0-1.0)",
  "mask": "boolean",
  "user_id": "string",
  "user_name": "string"
}
```

#### Compare Images
```
POST /compare-images
```

**Request Body:**
```json
{
  "source_image": "base64encodedimage",
  "target_image": "base64encodedimage", 
  "trx_id": "alphanumericalstring1234"
}
```

**Response:**
```json
{
  "status": "string",
  "status_message": "string",
  "similarity": "float (0.0-1.0)",
  "verified": "boolean",
  "masker": "boolean"
}
```

## Notes

- All images must be base64 encoded JPG or PNG
- Similarity scores range from 0.0 to 1.0 (0% to 100%)
- Default verification threshold is typically 0.75
- Mask detection available for all face recognition operations
- Transaction ID (`trx_id`) required for logging and debugging
# API Specification

## Sistem Informasi Asrama Haji Kelas I Banjarmasin

---

## Daftar Isi

1. [API Overview](#api-overview)
2. [Authentication](#authentication)
3. [Response Format](#response-format)
4. [Endpoint Specifications](#endpoint-specifications)
5. [Error Handling](#error-handling)
6. [Rate Limiting](#rate-limiting)

---

## API Overview

### Base URL

```
Development: http://localhost:3000/api
Production: https://asrama-haji.banjarmasin.go.id/api
```

### API Version

```
Current: v1
```

### Authentication

- **Type**: JWT (JSON Web Token)
- **Header**: `Authorization: Bearer <token>`
- **Token Expiry**: 7 days (access token), 30 days (refresh token)

---

## Authentication

### 1. Login

**Endpoint**: `POST /api/auth/login`

**Request**:

```json
{
  "email": "admin@asrama.local",
  "password": "securePassword123"
}
```

**Response (200 OK)**:

```json
{
  "success": true,
  "data": {
    "user": {
      "id": "uuid-xxx",
      "name": "Admin User",
      "email": "admin@asrama.local",
      "role": {
        "id": "uuid-role",
        "name": "Admin",
        "description": "Full system access"
      }
    },
    "tokens": {
      "accessToken": "eyJhbGciOiJIUzI1NiIs...",
      "refreshToken": "eyJhbGciOiJIUzI1NiIs...",
      "expiresIn": 604800
    }
  },
  "message": "Login successful"
}
```

**Error Response (401 Unauthorized)**:

```json
{
  "success": false,
  "data": null,
  "message": "Invalid email or password",
  "errors": {
    "email": ["Email not found or password incorrect"]
  }
}
```

---

### 2. Logout

**Endpoint**: `POST /api/auth/logout`

**Headers**:

```
Authorization: Bearer <accessToken>
```

**Response (200 OK)**:

```json
{
  "success": true,
  "data": null,
  "message": "Logout successful"
}
```

---

### 3. Refresh Token

**Endpoint**: `POST /api/auth/refresh`

**Request**:

```json
{
  "refreshToken": "eyJhbGciOiJIUzI1NiIs..."
}
```

**Response (200 OK)**:

```json
{
  "success": true,
  "data": {
    "accessToken": "eyJhbGciOiJIUzI1NiIs...",
    "expiresIn": 604800
  },
  "message": "Token refreshed"
}
```

---

### 4. Get Current User

**Endpoint**: `GET /api/auth/me`

**Headers**:

```
Authorization: Bearer <accessToken>
```

**Response (200 OK)**:

```json
{
  "success": true,
  "data": {
    "id": "uuid-xxx",
    "name": "Admin User",
    "email": "admin@asrama.local",
    "status": "active",
    "role": {
      "id": "uuid-role",
      "name": "Admin"
    },
    "createdAt": "2026-01-01T10:00:00Z",
    "updatedAt": "2026-03-30T14:30:00Z"
  },
  "message": "User retrieved"
}
```

---

## Response Format

### Success Response (2xx)

```json
{
  "success": true,
  "data": {
    /* any data */
  },
  "message": "Operation successful",
  "pagination": {
    "page": 1,
    "pageSize": 20,
    "total": 150,
    "totalPages": 8
  }
}
```

### Error Response (4xx, 5xx)

```json
{
  "success": false,
  "data": null,
  "message": "Descriptive error message",
  "errors": {
    "fieldName": ["Error detail 1", "Error detail 2"]
  }
}
```

### Pagination

- **Default Page Size**: 20
- **Max Page Size**: 100
- **Query Params**: `?page=1&pageSize=20`

---

## Endpoint Specifications

### System Settings Management

#### 1. Get All Settings

**Endpoint**: `GET /api/system-settings`

**Query Params**:

```
?group=general    // Filter by group: general, landing, api
```

**Response**:

```json
{
  "success": true,
  "data": [
    {
      "id": "uuid-1",
      "key": "active_mode",
      "value": "PUBLIC_SERVICE",
      "group": "general",
      "updatedAt": "2026-03-30T10:00:00Z"
    },
    {
      "id": "uuid-2",
      "key": "site_name",
      "value": "Asrama Haji Kelas I Banjarmasin",
      "group": "general",
      "updatedAt": "2026-03-30T10:00:00Z"
    }
  ],
  "message": "Settings retrieved"
}
```

#### 2. Update Setting

**Endpoint**: `PUT /api/system-settings/[id]`

**Auth**: Required (Role: Admin)

**Request**:

```json
{
  "value": "HAJJ_SEASON"
}
```

**Response**:

```json
{
  "success": true,
  "data": {
    "id": "uuid-1",
    "key": "active_mode",
    "value": "HAJJ_SEASON",
    "group": "general",
    "updatedAt": "2026-03-30T14:30:00Z"
  },
  "message": "Setting updated"
}
```

---

### Location Management

#### 1. Create Location

**Endpoint**: `POST /api/locations`

**Auth**: Required (Role: Admin)

**Request**:

```json
{
  "name": "Gedung A",
  "type": "GEDUNG",
  "parentId": null
}
```

**Response (201 Created)**:

```json
{
  "success": true,
  "data": {
    "id": "uuid-loc-1",
    "name": "Gedung A",
    "type": "GEDUNG",
    "parentId": null,
    "path": "Gedung A",
    "children": [],
    "createdAt": "2026-03-30T10:00:00Z"
  },
  "message": "Location created"
}
```

#### 2. List Locations (Hierarchical)

**Endpoint**: `GET /api/locations`

**Query Params**:

```
?parentId=uuid-loc-1   // Get children of specific location
```

**Response**:

```json
{
  "success": true,
  "data": [
    {
      "id": "uuid-loc-1",
      "name": "Gedung A",
      "type": "GEDUNG",
      "path": "Gedung A",
      "children": [
        {
          "id": "uuid-loc-2",
          "name": "Lantai 1",
          "type": "LANTAI",
          "path": "Gedung A > Lantai 1",
          "children": [
            {
              "id": "uuid-loc-3",
              "name": "Ruangan 101",
              "type": "RUANGAN",
              "path": "Gedung A > Lantai 1 > Ruangan 101",
              "children": []
            }
          ]
        }
      ]
    }
  ],
  "message": "Locations retrieved"
}
```

#### 3. Update Location

**Endpoint**: `PUT /api/locations/[id]`

**Auth**: Required (Role: Admin)

**Request**:

```json
{
  "name": "Gedung A (Baru)",
  "type": "GEDUNG"
}
```

#### 4. Delete Location

**Endpoint**: `DELETE /api/locations/[id]`

**Auth**: Required (Role: Admin)

**Validation**: Cannot delete location with child locations or facilities

---

### Facility Management

#### 1. Create Facility

**Endpoint**: `POST /api/facilities`

**Auth**: Required (Role: Admin, Manager)

**Request**:

```json
{
  "name": "Kamar 101",
  "slug": "kamar-101",
  "locationId": "uuid-loc-3",
  "capacity": 4,
  "genderPolicy": "FEMALE_ONLY",
  "status": "AVAILABLE"
}
```

**Response (201 Created)**:

```json
{
  "success": true,
  "data": {
    "id": "uuid-fac-1",
    "name": "Kamar 101",
    "slug": "kamar-101",
    "locationId": "uuid-loc-3",
    "location": {
      "id": "uuid-loc-3",
      "name": "Ruangan 101",
      "path": "Gedung A > Lantai 1 > Ruangan 101"
    },
    "capacity": 4,
    "genderPolicy": "FEMALE_ONLY",
    "status": "AVAILABLE",
    "createdAt": "2026-03-30T10:00:00Z"
  },
  "message": "Facility created"
}
```

#### 2. List Facilities

**Endpoint**: `GET /api/facilities`

**Query Params**:

```
?page=1&pageSize=20
?status=AVAILABLE
?locationId=uuid-loc-3
?search=Kamar    // Search by name
```

**Response**:

```json
{
  "success": true,
  "data": [
    {
      "id": "uuid-fac-1",
      "name": "Kamar 101",
      "slug": "kamar-101",
      "capacity": 4,
      "genderPolicy": "FEMALE_ONLY",
      "status": "AVAILABLE",
      "currentOccupancy": 0,
      "occupancyRate": 0
    }
  ],
  "pagination": {
    "page": 1,
    "pageSize": 20,
    "total": 45,
    "totalPages": 3
  },
  "message": "Facilities retrieved"
}
```

#### 3. Get Facility Details (dengan gallery)

**Endpoint**: `GET /api/facilities/[id]`

**Response**:

```json
{
  "success": true,
  "data": {
    "id": "uuid-fac-1",
    "name": "Kamar 101",
    "locationId": "uuid-loc-3",
    "location": {
      "id": "uuid-loc-3",
      "path": "Gedung A > Lantai 1 > Ruangan 101"
    },
    "capacity": 4,
    "genderPolicy": "FEMALE_ONLY",
    "status": "AVAILABLE",
    "services": [
      {
        "id": "uuid-svc-1",
        "name": "Sewa Harian",
        "price": 250000,
        "isExclusive": false
      }
    ],
    "mediaLinks": [
      {
        "id": "uuid-media-1",
        "mediaFile": {
          "id": "uuid-mf-1",
          "fileName": "kamar-101.jpg",
          "filePath": "/images/facility/kamar-101.jpg"
        }
      }
    ]
  }
}
```

#### 4. Update Facility

**Endpoint**: `PUT /api/facilities/[id]`

**Auth**: Required (Role: Admin, Manager)

#### 5. Delete Facility

**Endpoint**: `DELETE /api/facilities/[id]`

**Auth**: Required (Role: Admin)

**Validation**: Cannot delete facility dengan active occupancy

---

### Facility Service Management

#### 1. Create Service

**Endpoint**: `POST /api/facility-services`

**Auth**: Required (Role: Admin, Manager)

**Request**:

```json
{
  "facilityId": "uuid-fac-1",
  "name": "Sewa Harian",
  "duration": 24,
  "price": 250000,
  "isExclusive": false
}
```

**Response (201 Created)**:

```json
{
  "success": true,
  "data": {
    "id": "uuid-svc-1",
    "facilityId": "uuid-fac-1",
    "name": "Sewa Harian",
    "duration": 24,
    "price": "250000.00",
    "isExclusive": false,
    "createdAt": "2026-03-30T10:00:00Z"
  },
  "message": "Service created"
}
```

#### 2. List Services by Facility

**Endpoint**: `GET /api/facility-services?facilityId=[id]`

#### 3. Update Service

**Endpoint**: `PUT /api/facility-services/[id]`

**Auth**: Required (Role: Admin, Manager)

#### 4. Delete Service

**Endpoint**: `DELETE /api/facility-services/[id]`

**Auth**: Required (Role: Admin)

---

### Reservation Management

#### 1. Create Reservation (Booking)

**Endpoint**: `POST /api/reservations`

**Auth**: Optional (untuk guest publik) / Required (untuk admin)

**Request**:

```json
{
  "guestId": "uuid-guest-1",
  "items": [
    {
      "facilityId": "uuid-fac-1",
      "serviceId": "uuid-svc-1",
      "quantity": 1,
      "checkInDate": "2026-04-15",
      "checkOutDate": "2026-04-17"
    }
  ]
}
```

**Response (201 Created)**:

```json
{
  "success": true,
  "data": {
    "id": "uuid-res-1",
    "bookingCode": "BK-20260330-00001",
    "guestId": "uuid-guest-1",
    "guest": {
      "id": "uuid-guest-1",
      "name": "John Doe",
      "email": "john@example.com"
    },
    "status": "PENDING",
    "totalPrice": "500000.00",
    "items": [
      {
        "id": "uuid-resitem-1",
        "facilityId": "uuid-fac-1",
        "facility": { "name": "Kamar 101" },
        "serviceId": "uuid-svc-1",
        "service": { "name": "Sewa Harian", "price": "250000.00" },
        "quantity": 1,
        "subtotal": "500000.00"
      }
    ],
    "createdAt": "2026-03-30T10:00:00Z"
  },
  "message": "Reservation created"
}
```

#### 2. Get Reservation Details

**Endpoint**: `GET /api/reservations/[id]`

**Response**: Include guest info, items, invoice status

#### 3. List Reservations

**Endpoint**: `GET /api/reservations`

**Query Params**:

```
?page=1&pageSize=20
?status=CONFIRMED,PENDING
?guestId=uuid-guest-1
?dateRange=2026-04-01:2026-04-30
?search=BK-20260330
```

#### 4. Update Reservation Status

**Endpoint**: `PUT /api/reservations/[id]`

**Auth**: Required (Role: Admin, Manager, Resepsionis)

**Request**:

```json
{
  "status": "CONFIRMED"
}
```

**Allowed transitions**:

- DRAFT → PENDING
- PENDING → CONFIRMED
- PENDING → CANCELLED
- CONFIRMED → CANCELLED (dengan catatan)

#### 5. Check Availability

**Endpoint**: `GET /api/reservations/availability`

**Query Params**:

```
?facilityId=uuid-fac-1
?checkInDate=2026-04-15
?checkOutDate=2026-04-17
```

**Response**:

```json
{
  "success": true,
  "data": {
    "facilityId": "uuid-fac-1",
    "name": "Kamar 101",
    "capacity": 4,
    "checkInDate": "2026-04-15",
    "checkOutDate": "2026-04-17",
    "isAvailable": true,
    "occupiedDates": ["2026-04-16"],
    "availableSlots": 3
  }
}
```

---

### Occupancy Management (Check-in / Check-out)

#### 1. Check-In

**Endpoint**: `POST /api/occupancy/check-in`

**Auth**: Required (Role: Resepsionis, Admin)

**Request**:

```json
{
  "reservationId": "uuid-res-1",
  "facilityId": "uuid-fac-1",
  "guestId": "uuid-guest-1",
  "checkInTime": "2026-04-15T14:00:00Z",
  "notes": "Tamu sudah menerima kunci kamar"
}
```

**Response (201 Created)**:

```json
{
  "success": true,
  "data": {
    "id": "uuid-occ-1",
    "facilityId": "uuid-fac-1",
    "facility": { "name": "Kamar 101" },
    "guestId": "uuid-guest-1",
    "guest": { "name": "John Doe" },
    "reservationId": "uuid-res-1",
    "checkInTime": "2026-04-15T14:00:00Z",
    "checkOutTime": null,
    "status": "CHECKED_IN",
    "capacityUsed": 1,
    "isExclusive": false
  },
  "message": "Guest checked in successfully"
}
```

#### 2. Check-Out

**Endpoint**: `POST /api/occupancy/check-out`

**Auth**: Required (Role: Resepsionis, Admin)

**Request**:

```json
{
  "occupancyId": "uuid-occ-1",
  "checkOutTime": "2026-04-17T11:00:00Z",
  "damageAssessment": {
    "hasDamage": false,
    "description": null,
    "estimatedCost": 0
  },
  "notes": "Kamar dalam kondisi baik"
}
```

**Response**:

```json
{
  "success": true,
  "data": {
    "id": "uuid-occ-1",
    "facilityId": "uuid-fac-1",
    "guestId": "uuid-guest-1",
    "checkInTime": "2026-04-15T14:00:00Z",
    "checkOutTime": "2026-04-17T11:00:00Z",
    "status": "CHECKED_OUT",
    "stayDuration": {
      "days": 2,
      "hours": 21
    }
  },
  "message": "Guest checked out successfully"
}
```

#### 3. Get Current Occupancy Status

**Endpoint**: `GET /api/occupancy`

**Query Params**:

```
?facilityId=uuid-fac-1
?status=CHECKED_IN
?kloterId=uuid-kloter-1
```

**Response**: List of all checked-in guests

---

### Kloter Management (Hajj Season)

#### 1. Create Kloter

**Endpoint**: `POST /api/kloter`

**Auth**: Required (Role: Admin, HajjManager)

**Request**:

```json
{
  "code": "JKT-01",
  "province": "Jakarta",
  "totalPilgrims": 450
}
```

#### 2. List Kloter

**Endpoint**: `GET /api/kloter`

**Query Params**:

```
?page=1&pageSize=20
?province=Jakarta
?search=JKT
```

#### 3. Import Guests to Kloter

**Endpoint**: `POST /api/kloter/[id]/import-guests`

**Auth**: Required (Role: Admin, HajjManager)

**Request**:

```json
{
  "guests": [
    {
      "name": "Muhammad Ali",
      "gender": "M",
      "identity": "3500123456789012",
      "phone": "081234567890"
    },
    {
      "name": "Aisyah Nur",
      "gender": "F",
      "identity": "3500123456789013",
      "phone": "081234567891"
    }
  ]
}
```

#### 4. Auto-Plot Guests to Rooms

**Endpoint**: `POST /api/kloter/[id]/plot`

**Auth**: Required (Role: Admin, HajjManager)

**Request**:

```json
{
  "algorithm": "gender-based-fifo",
  "includeExclusiveFacilities": false,
  "notes": "Plotting otomatis berdasarkan gender dan ketersediaan"
}
```

**Response**: Creation of multiple Occupancy records

**Algorithm Options**:

- `gender-based-fifo`: Assign berdasarkan gender, first-in-first-out
- `optimize-capacity`: Minimisir jumlah ruangan yang dipakai
- `gender-block`: Group same-gender guests per facility

#### 5. Manual Plotting (Drag & Drop)

**Endpoint**: `PUT /api/kloter/[id]/plot-guest`

**Auth**: Required (Role: Admin, HajjManager)

**Request**:

```json
{
  "guestId": "uuid-guest-1",
  "facilityId": "uuid-fac-1"
}
```

**Validation**: Gender policy must match

#### 6. Bulk Check-in for Kloter

**Endpoint**: `POST /api/kloter/[id]/bulk-check-in`

**Auth**: Required (Role: Admin, Resepsionis)

**Request**:

```json
{
  "checkInTime": "2026-04-15T06:00:00Z",
  "notes": "Rombongan haji JKT-01 check-in pagi"
}
```

**Response**: Occupancy status untuk semua guest in kloter updated to CHECKED_IN

#### 7. Bulk Check-out for Kloter

**Endpoint**: `POST /api/kloter/[id]/bulk-check-out`

**Auth**: Required (Role: Admin, Resepsionis)

---

### Media Management

#### 1. Upload Media

**Endpoint**: `POST /api/media/upload`

**Auth**: Required

**Form Data**:

```
file: <binary>
description: "Optional description"
```

**Response (201 Created)**:

```json
{
  "success": true,
  "data": {
    "id": "uuid-mf-1",
    "fileName": "kamar-101.jpg",
    "filePath": "/images/facility/kamar-101.jpg",
    "fileSize": 1024000,
    "mimeType": "image/jpeg",
    "uploadedAt": "2026-03-30T10:00:00Z"
  },
  "message": "File uploaded successfully"
}
```

#### 2. Link Media to Entity

**Endpoint**: `POST /api/media/link`

**Auth**: Required

**Request**:

```json
{
  "mediaId": "uuid-mf-1",
  "facilityId": "uuid-fac-1"
}
```
*(Also supports `postId`, `assetId`, `archiveId`, `itemId`)*

#### 3. Delete Media

**Endpoint**: `DELETE /api/media/[id]`

**Auth**: Required (Role: Admin)

**Soft-delete**: File tidak dihapus, hanya di-flag sebagai deleted

---

### Posts (CMS)

#### 1. Create Post

**Endpoint**: `POST /api/posts`

**Auth**: Required (Role: Admin, Humas)

**Request**:

```json
{
  "title": "Selamat Datang di Asrama Haji Kelas I",
  "slug": "selamat-datang-asrama-haji",
  "content": "Ipsum dolor sit amet...",
  "status": "DRAFT",
  "publishedAt": null
}
```

#### 2. List Posts

**Endpoint**: `GET /api/posts`

**Query Params**:

```
?status=PUBLISHED
?authorId=uuid-user-1
?dateRange=2026-01-01:2026-12-31
```

#### 3. Get Post Details

**Endpoint**: `GET /api/posts/[slug]` atau `GET /api/posts/[id]`

#### 4. Update Post

**Endpoint**: `PUT /api/posts/[id]`

**Auth**: Required (Role: Author, Admin)

#### 5. Delete Post

**Endpoint**: `DELETE /api/posts/[id]`

**Auth**: Required (Role: Admin)

---

### Invoice & Payment

#### 1. Get Invoice

**Endpoint**: `GET /api/invoices/[reservationId]`

**Response**:

```json
{
  "success": true,
  "data": {
    "id": "uuid-inv-1",
    "reservationId": "uuid-res-1",
    "reservation": { "bookingCode": "BK-20260330-00001" },
    "grandTotal": "500000.00",
    "status": "UNPAID",
    "payments": [],
    "createdAt": "2026-03-30T10:00:00Z"
  }
}
```

#### 2. Record Payment

**Endpoint**: `POST /api/invoices/[id]/payment`

**Auth**: Required (Role: Admin, Finance)

**Request**:

```json
{
  "amount": "250000.00",
  "method": "TRANSFER",
  "reference": "BCA Transfer - A/C 1234567890",
  "paidAt": "2026-03-31T10:00:00Z"
}
```

**Response (201 Created)**:

```json
{
  "success": true,
  "data": {
    "id": "uuid-pay-1",
    "invoiceId": "uuid-inv-1",
    "amount": "250000.00",
    "method": "TRANSFER",
    "paidAt": "2026-03-31T10:00:00Z"
  },
  "message": "Payment recorded"
}
```

---

## Error Handling

### Error Codes & Messages

| Code             | Status | Message                              | Action                            |
| ---------------- | ------ | ------------------------------------ | --------------------------------- |
| INVALID_REQUEST  | 400    | Request format tidak valid           | Periksa request body, headers     |
| UNAUTHORIZED     | 401    | Token tidak valid atau expired       | Refresh token atau login ulang    |
| FORBIDDEN        | 403    | Tidak memiliki akses ke resource ini | Check user role/permissions       |
| NOT_FOUND        | 404    | Resource tidak ditemukan             | Verifikasi ID yang dikirim        |
| CONFLICT         | 409    | Data sudah exists (duplikasi)        | Check unique constraints          |
| VALIDATION_ERROR | 400    | Validation failed                    | Lihat `errors` field untuk detail |
| INTERNAL_ERROR   | 500    | Server error                         | Check server logs                 |

### Example Error Response

```json
{
  "success": false,
  "data": null,
  "message": "Validation failed",
  "errors": {
    "email": ["Email already registered"],
    "password": ["Password must be at least 8 characters"],
    "phone": ["Invalid phone format"]
  }
}
```

---

## Rate Limiting

### Limits

- **Default**: 100 requests per 15 minutes
- **Auth endpoints**: 5 requests per minute
- **File upload**: 10 requests per hour

### Headers

```
X-RateLimit-Limit: 100
X-RateLimit-Remaining: 95
X-RateLimit-Reset: 1617235123
```

### Exceeded Response (429)

```json
{
  "success": false,
  "data": null,
  "message": "Too many requests. Try again later.",
  "retryAfter": 45
}
```

---

## Changelog

| Version | Date        | Changes          |
| ------- | ----------- | ---------------- |
| 1.0     | 30 Mar 2026 | Initial API spec |

---

**Status**: Draft - Ready for Development  
**Maintainer**: Backend Team

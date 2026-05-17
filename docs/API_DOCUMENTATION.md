# API Documentation

This documentation outlines the available and unavailable API endpoints in the Next.js application based on the Prisma schema and the current Next.js `app/api` folder structure.

## Available APIs

The following APIs are implemented in the `app/api` directory. They generally follow standard RESTful conventions with `GET` and `POST` methods available at the collection level (e.g., `/api/users`), and `GET`, `PUT`, and `DELETE` methods available at the item level (e.g., `/api/users/[id]`).

### 1. Activity Logs
- **Endpoint:** `/api/activity-logs`
- **Methods:** `GET`, `POST`
- **Item Endpoint:** `/api/activity-logs/[id]`
- **Item Methods:** `GET`, `PUT`, `DELETE`
- **Model:** `ActivityLog`

### 2. Archive Items
- **Endpoint:** `/api/archive-items`
- **Methods:** `GET`, `POST`
- **Item Endpoint:** `/api/archive-items/[id]`
- **Item Methods:** `GET`, `PUT`, `DELETE`
- **Model:** `ArchiveItem`

### 3. Asset Items
- **Endpoint:** `/api/asset-items`
- **Methods:** `GET`, `POST`
- **Item Endpoint:** `/api/asset-items/[id]`
- **Item Methods:** `GET`, `PUT`, `DELETE`
- **Model:** `AssetItem`

### 4. Asset Maintenances
- **Endpoint:** `/api/asset-maintenances`
- **Methods:** `GET`, `POST`
- **Item Endpoint:** `/api/asset-maintenances/[id]`
- **Item Methods:** `GET`, `PUT`, `DELETE`
- **Model:** `AssetMaintenance`

### 5. Facilities
- **Endpoint:** `/api/facilities`
- **Methods:** `GET`, `POST`
- **Item Endpoint:** `/api/facilities/[id]`
- **Item Methods:** `GET`, `PUT`, `DELETE`
- **Model:** `Facility`

### 6. Facility Services
- **Endpoint:** `/api/facility-services`
- **Methods:** `GET`, `POST`
- **Item Endpoint:** `/api/facility-services/[id]`
- **Item Methods:** `GET`, `PUT`, `DELETE`
- **Model:** `FacilityService`

### 7. Guests
- **Endpoint:** `/api/guests`
- **Methods:** `GET`, `POST`
- **Item Endpoint:** `/api/guests/[id]`
- **Item Methods:** `GET`, `PUT`, `DELETE`
- **Model:** `Guest`

### 8. Invoices
- **Endpoint:** `/api/invoices`
- **Methods:** `GET`, `POST`
- **Item Endpoint:** `/api/invoices/[id]`
- **Item Methods:** `GET`, `PUT`, `DELETE`
- **Model:** `Invoice`

### 9. Items
- **Endpoint:** `/api/items`
- **Methods:** `GET`, `POST`
- **Item Endpoint:** `/api/items/[id]`
- **Item Methods:** `GET`, `PUT`, `DELETE`
- **Model:** `Item`

### 10. Kloters
- **Endpoint:** `/api/kloters`
- **Methods:** `GET`, `POST`
- **Item Endpoint:** `/api/kloters/[id]`
- **Item Methods:** `GET`, `PUT`, `DELETE`
- **Model:** `Kloter`

### 11. Locations
- **Endpoint:** `/api/locations`
- **Methods:** `GET`, `POST`
- **Item Endpoint:** `/api/locations/[id]`
- **Item Methods:** `GET`, `PUT`, `DELETE`
- **Model:** `Location`

### 12. Media Files
- **Endpoint:** `/api/media-files`
- **Methods:** `GET`, `POST`
- **Item Endpoint:** `/api/media-files/[id]`
- **Item Methods:** `GET`, `PUT`, `DELETE`
- **Model:** `MediaFile`

### 13. Media Links
- **Endpoint:** `/api/media-links`
- **Methods:** `GET`, `POST`
- **Item Endpoint:** `/api/media-links/[id]`
- **Item Methods:** `GET`, `PUT`, `DELETE`
- **Model:** `MediaLink`

### 14. Occupancies
- **Endpoint:** `/api/occupancies`
- **Methods:** `GET`, `POST`
- **Item Endpoint:** `/api/occupancies/[id]`
- **Item Methods:** `GET`, `PUT`, `DELETE`
- **Model:** `Occupancy`

### 15. Payments
- **Endpoint:** `/api/payments`
- **Methods:** `GET`, `POST`
- **Item Endpoint:** `/api/payments/[id]`
- **Item Methods:** `GET`, `PUT`, `DELETE`
- **Model:** `Payment`

### 16. Posts
- **Endpoint:** `/api/posts`
- **Methods:** `GET`, `POST`
- **Item Endpoint:** `/api/posts/[id]`
- **Item Methods:** `GET`, `PUT`, `DELETE`
- **Model:** `Post`

### 17. Reservation Items
- **Endpoint:** `/api/reservation-items`
- **Methods:** `GET`, `POST`
- **Item Endpoint:** `/api/reservation-items/[id]`
- **Item Methods:** `GET`, `PUT`, `DELETE`
- **Model:** `ReservationItem`

### 18. Reservations
- **Endpoint:** `/api/reservations`
- **Methods:** `GET`, `POST`
- **Item Endpoint:** `/api/reservations/[id]`
- **Item Methods:** `GET`, `PUT`, `DELETE`
- **Model:** `Reservation`

### 19. Roles
- **Endpoint:** `/api/roles`
- **Methods:** `GET`, `POST`
- **Item Endpoint:** `/api/roles/[id]`
- **Item Methods:** `GET`, `PUT`, `DELETE`
- **Model:** `Role`

### 20. Stock Transactions
- **Endpoint:** `/api/stock-transactions`
- **Methods:** `GET`, `POST`
- **Item Endpoint:** `/api/stock-transactions/[id]`
- **Item Methods:** `GET`, `PUT`, `DELETE`
- **Model:** `StockTransaction`

### 21. System Settings
- **Endpoint:** `/api/system-settings`
- **Methods:** `GET`, `POST`
- **Item Endpoint:** `/api/system-settings/[id]`
- **Item Methods:** `GET`, `PUT`, `DELETE`
- **Model:** `SystemSetting`

### 22. Users
- **Endpoint:** `/api/users`
- **Methods:** `GET`, `POST`
- **Item Endpoint:** `/api/users/[id]`
- **Item Methods:** `GET`, `PUT`, `DELETE`
- **Model:** `User`

## Unavailable APIs

Based on the Prisma schema, all `model` declarations have a corresponding API endpoint implementation in the `app/api` directory.
There are no models missing API implementations.

Summary of Prisma models and their implementations:
- `Role` -> `/api/roles`
- `User` -> `/api/users`
- `ActivityLog` -> `/api/activity-logs`
- `SystemSetting` -> `/api/system-settings`
- `Post` -> `/api/posts`
- `Location` -> `/api/locations`
- `Kloter` -> `/api/kloters`
- `Guest` -> `/api/guests`
- `Facility` -> `/api/facilities`
- `FacilityService` -> `/api/facility-services`
- `Occupancy` -> `/api/occupancies`
- `Reservation` -> `/api/reservations`
- `ReservationItem` -> `/api/reservation-items`
- `Invoice` -> `/api/invoices`
- `Payment` -> `/api/payments`
- `AssetItem` -> `/api/asset-items`
- `AssetMaintenance` -> `/api/asset-maintenances`
- `ArchiveItem` -> `/api/archive-items`
- `Item` -> `/api/items`
- `StockTransaction` -> `/api/stock-transactions`
- `MediaFile` -> `/api/media-files`
- `MediaLink` -> `/api/media-links`

Every model mapped from the database is exposed through a REST API endpoint.

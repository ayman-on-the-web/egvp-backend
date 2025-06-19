# Project Entities and Relationships

## Admin
- **Table**: `users`
- **Attributes**: Inherits all attributes from the `User` model.
- **Relationships**: None specific to `Admin`.

## Application
- **Table**: `applications`
- **Attributes**:
  - `volunteer_id`: Foreign key to `Volunteer`
  - `event_id`: Foreign key to `Event`
  - `is_approved`: Boolean indicating approval status
  - `status`: Application status (`Pending`, `Rejected`, `Approved`)
  - `decision_at`: Timestamp of the decision
- **Relationships**:
  - `belongsTo(Volunteer::class)`: Belongs to a `Volunteer`
  - `belongsTo(Event::class)`: Belongs to an `Event`

## Attendance
- **Table**: `attendances`
- **Attributes**:
  - `event_id`: Foreign key to `Event`
  - `volunteer_id`: Foreign key to `Volunteer`
  - `hours`: Number of hours attended
- **Relationships**:
  - `belongsTo(Event::class)`: Belongs to an `Event`
  - `belongsTo(Volunteer::class)`: Belongs to a `Volunteer`

## Event
- **Table**: `events`
- **Attributes**:
  - `start_date`: Start date of the event
  - `end_date`: End date of the event
  - `target_hours`: Target hours for the event
  - `points`: Points associated with the event
  - `address`: Event address
  - `city`: Event city
  - `country`: Event country
  - `is_approved`: Boolean indicating approval status
  - `status`: Event status (`Pending`, `Rejected`, `Approved`)
  - `event_category_id`: Foreign key to `EventCategory`
  - `organization_id`: Foreign key to `Organization`
- **Relationships**:
  - `belongsTo(EventCategory::class)`: Belongs to an `EventCategory`
  - `belongsTo(Organization::class)`: Belongs to an `Organization`

## EventCategory
- **Table**: `event_categories`
- **Attributes**:
  - `name`: Name of the event category
- **Relationships**:
  - `hasMany(Event::class)`: Has many `Event`

## Organization
- **Table**: `users`
- **Attributes**: Inherits all attributes from the `User` model.
- **Relationships**:
  - `hasMany(Event::class)`: Has many `Event`

## Rating
- **Table**: `ratings`
- **Attributes**:
  - `volunteer_id`: Foreign key to `Volunteer`
  - `event_id`: Foreign key to `Event`
  - `rate`: Rating value
- **Relationships**:
  - `belongsTo(Volunteer::class)`: Belongs to a `Volunteer`
  - `belongsTo(Event::class)`: Belongs to an `Event`

## User
- **Table**: `users`
- **Attributes**:
  - `name`: User name
  - `email`: User email
  - `password`: User password
  - `phone`: User phone number
  - `address`: User address
  - `profile_photo_base64`: Base64 encoded profile photo
  - `identification_type`: Type of identification
  - `identification_number`: Identification number
  - `user_type`: User type (`Admin`, `Organization`, `Volunteer`)
  - `is_active`: Boolean indicating active status
  - `active_until`: Date until active
  - `is_approved`: Boolean indicating approval status
  - `approved_at`: Timestamp of approval
  - `points`: User points
  - `skills`: User skills
  - `details`: Additional details
- **Relationships**: None specific to `User`.

## Volunteer
- **Table**: `users`
- **Attributes**: Inherits all attributes from the `User` model.
- **Relationships**: None specific to `Volunteer`.
# Entities and Relationships

## Entities

1. **Admin**
   - First Name
   - Last Name

2. **User**
   - Id
   - email
   - username
   - password
   - License_active_until
   - is_active

3. **Organization**
   - Org. Name
   - Org. Address
   - Org. Phone
   - Commercial reg. No

4. **Application**
   - id
   - user_id
   - event_id
   - status
   - decision_at
   - created_at

5. **Event**
   - id
   - start_date
   - end_date
   - target_hours
   - points
   - address
   - city
   - country

6. **Event Category**
   - Id
   - Name

7. **Attendance**
   - id
   - hours

8. **Rating**
   - id
   - rate
   - comment
   - created_at

9. **Volunteer**
   - Identification Number
   - FName
   - LName
   - phone number
   - profile photo
   - skills
   - points
   - description

## Relationships

1. **Admin is User**: One-to-One
2. **User is Volunteer**: One-to-One
3. **Organization owns Event**: One-to-Many
4. **Volunteer has Application**: One-to-Many
5. **Application to Event**: Many-to-One
6. **Event has Event Category**: Many-to-One
7. **Volunteer made Attendance**: One-to-Many
8. **Attendance to Event**: Many-to-One
9. **Volunteer owns Rating**: One-to-Many
10. **Rating to Event**: Many-to-One
# Event Documentation

## EventController

The `EventController` class is responsible for handling CRUD operations for events in the Laravel project. It extends the `Controller` class and includes the following methods:

### index
- **Description**: Retrieves a paginated collection of events.
- **Return Type**: `\Illuminate\Http\Resources\Json\AnonymousResourceCollection`

### store
- **Description**: Creates a new event based on the validated request data.
- **Parameters**: `EventRequest $request`
- **Return Type**: `EventResource|\Illuminate\Http\JsonResponse`
- **Authorization**: Only users with the `TYPE_ADMIN` or `TYPE_ORGANIZATION` user type are authorized to create events.

### show
- **Description**: Retrieves a specific event by its ID.
- **Parameters**: `Event $event`
- **Return Type**: `EventResource`

### update
- **Description**: Updates an existing event based on the validated request data.
- **Parameters**: `EventRequest $request, Event $event`
- **Return Type**: `EventResource|\Illuminate\Http\JsonResponse`
- **Authorization**: Only users with the `TYPE_ADMIN` user type are authorized to update events.

### destroy
- **Description**: Deletes an existing event.
- **Parameters**: `Event $event`
- **Return Type**: `\Illuminate\Http\JsonResponse`
- **Authorization**: Only users with the `TYPE_ADMIN` user type are authorized to delete events.

## EventRequest

The `EventRequest` class is a form request class that defines the validation rules for event-related requests. It extends the `FormRequest` class and includes the following methods:

### authorize
- **Description**: Determines if the user is authorized to make the request.
- **Return Type**: `bool`

### rules
- **Description**: Defines the validation rules for the request.
- **Return Type**: `array`
- **Rules**:
  - `start_date`: Must be a valid date.
  - `end_date`: Must be a valid date.
  - `target_hours`: Must be an integer.
  - `points`: Must be an integer.
  - `address`: Must be a string.
  - `city`: Must be a string.
  - `country`: Must be a string.
  - `is_approved`: Must be an integer.
  - `event_category_id`: Must be an integer.
  - `organization_id`: Must be an integer.

### failedValidation
- **Description**: Handles a failed validation attempt by throwing an `HttpResponseException` with a JSON response containing the validation errors.
- **Parameters**: `Validator $validator`

## Event

The `Event` model represents an event in the Laravel project. It extends the `Model` class and includes the following attributes, relationships, and methods:

### Attributes
- `start_date`: The start date of the event.
- `end_date`: The end date of the event.
- `target_hours`: The target hours for the event.
- `points`: The points associated with the event.
- `address`: The address of the event.
- `city`: The city of the event.
- `country`: The country of the event.
- `is_approved`: A boolean indicating if the event is approved.
- `status`: The status of the event (Pending, Rejected, Approved).
- `event_category_id`: The ID of the associated event category.
- `organization_id`: The ID of the associated organization.

### Relationships
- `event_category()`: Belongs to an `EventCategory` model.
- `organization()`: Belongs to an `Organization` model.
- `volunteers()`: Has many `Volunteer` models.

### Methods
- `make_decision($decision)`: Updates the event's status and approval based on the given decision.
- `approve()`: Approves the event by calling `make_decision` with the `STATUS_APPROVED` constant.
- `reject()`: Rejects the event by calling `make_decision` with the `STATUS_REJECTED` constant.

## EventResource

The `EventResource` class is a JSON resource class that defines the resource representation for events. It extends the `JsonResource` class and includes the following method:

### toArray
- **Description**: Converts the event model into an array representation.
- **Parameters**: `Request $request`
- **Return Type**: `array`
- **Array Structure**:
  - `id`: The ID of the event.
  - `start_date`: The start date of the event.
  - `end_date`: The end date of the event.
  - `target_hours`: The target hours for the event.
  - `points`: The points associated with the event.
  - `address`: The address of the event.
  - `city`: The city of the event.
  - `country`: The country of the event.
  - `status`: The status of the event.
  - `is_approved`: A boolean indicating if the event is approved.
  - `event_category_id`: The ID of the associated event category.
  - `organization_id`: The ID of the associated organization.
  - `event_category`: The associated event category.
  - `organization`: The associated organization.
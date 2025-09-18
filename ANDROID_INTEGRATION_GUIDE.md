# Android App Integration Guide

## Backend API Endpoint
**POST** `/api/auth/register`

## Field Mapping from Android Layout to Backend

### ✅ Current Android Fields → Backend Fields

| Android Field ID | Backend Field | Required | Notes |
|------------------|---------------|----------|-------|
| `@+id/fn` | `firstName` | ✅ Yes | First Name |
| `@+id/ln` | `lastName` | ✅ Yes | Last Name |
| `@+id/EmailAddress` | `email` | ✅ Yes | Email Address |
| `@+id/password` | `password` | ✅ Yes | Password |
| `@+id/spinner_user_type` | `role` | ✅ Yes | "Resident" or "Responder" |
| `@+id/bday` | `birthday` | ❌ Optional | Birthday (Date format) |

### ❌ Missing Android Fields (Need to Add)

| Field | Backend Field | Required | Notes |
|-------|---------------|----------|-------|
| Contact Number | `contact` | ❌ Optional | Phone number |
| Username | `username` | ❌ Auto-generated | Will be created from firstName + lastName + timestamp |
| Responder Type | `responderType` | ✅ If role="Responder" | Spinner: "police", "fire", "hospital", "barangay" |

## Required Android Layout Changes

### 1. Add Contact Field
```xml
<EditText
    android:id="@+id/contact"
    android:layout_width="match_parent"
    android:layout_height="wrap_content"
    android:hint="Contact Number (Optional)"
    android:inputType="phone" />
```

### 2. Add Responder Type Spinner (Conditional)
```xml
<Spinner
    android:id="@+id/spinner_responder_type"
    android:layout_width="match_parent"
    android:layout_height="wrap_content"
    android:visibility="gone"
    android:prompt="@string/responder_type_prompt" />
```

### 3. Update strings.xml
```xml
<string-array name="user_types_array">
    <item>Resident</item>
    <item>Responder</item>
</string-array>

<string-array name="responder_types_array">
    <item>police</item>
    <item>fire</item>
    <item>hospital</item>
    <item>barangay</item>
</string-array>

<string name="responder_type_prompt">Select Responder Type</string>
```

## Android Java/Kotlin Implementation

### Registration Request Body
```java
// Example JSON payload to send to backend
{
    "firstName": "John",
    "lastName": "Doe", 
    "email": "john.doe@email.com",
    "password": "securePassword123",
    "role": "Resident", // or "Responder"
    "birthday": "1990-01-15", // Optional, ISO date format
    "contact": "+1234567890", // Optional
    "responderType": "police" // Required only if role="Responder"
}
```

### Field Validation Logic
```java
// Show/hide responder type spinner based on role selection
spinner_user_type.setOnItemSelectedListener(new AdapterView.OnItemSelectedListener() {
    @Override
    public void onItemSelected(AdapterView<?> parent, View view, int position, long id) {
        String selectedRole = (String) parent.getItemAtPosition(position);
        if ("Responder".equals(selectedRole)) {
            spinner_responder_type.setVisibility(View.VISIBLE);
        } else {
            spinner_responder_type.setVisibility(View.GONE);
        }
    }
    
    @Override
    public void onNothingSelected(AdapterView<?> parent) {}
});
```

### API Request Implementation
```java
private void registerUser() {
    // Get values from form fields
    String firstName = fn.getText().toString().trim();
    String lastName = ln.getText().toString().trim();
    String email = EmailAddress.getText().toString().trim();
    String password = password.getText().toString();
    String role = spinner_user_type.getSelectedItem().toString();
    String birthday = bday.getText().toString().trim(); // Optional
    String contact = contact.getText().toString().trim(); // Optional
    
    // Validate required fields
    if (firstName.isEmpty() || lastName.isEmpty() || email.isEmpty() || password.isEmpty()) {
        showError("Please fill in all required fields");
        return;
    }
    
    // Validate email format
    if (!isValidEmail(email)) {
        showError("Please enter a valid email address");
        return;
    }
    
    // If Responder, validate responderType
    String responderType = null;
    if ("Responder".equals(role)) {
        if (spinner_responder_type.getVisibility() == View.VISIBLE) {
            responderType = spinner_responder_type.getSelectedItem().toString();
        } else {
            showError("Please select a responder type");
            return;
        }
    }
    
    // Create request body
    JSONObject requestBody = new JSONObject();
    try {
        requestBody.put("firstName", firstName);
        requestBody.put("lastName", lastName);
        requestBody.put("email", email);
        requestBody.put("password", password);
        requestBody.put("role", role);
        
        if (!birthday.isEmpty()) {
            requestBody.put("birthday", birthday);
        }
        if (!contact.isEmpty()) {
            requestBody.put("contact", contact);
        }
        if (responderType != null) {
            requestBody.put("responderType", responderType);
        }
    } catch (JSONException e) {
        e.printStackTrace();
    }
    
    // Make API call
    makeRegistrationRequest(requestBody);
}

private boolean isValidEmail(String email) {
    return android.util.Patterns.EMAIL_ADDRESS.matcher(email).matches();
}
```

## Backend Response Format

### Success Response (201)
```json
{
    "message": "User registered successfully",
    "user": {
        "id": "64f1a2b3c4d5e6f7g8h9i0j1",
        "firstName": "John",
        "lastName": "Doe",
        "username": "john_doe_1699123456789",
        "email": "john.doe@email.com",
        "role": "Resident",
        "responderType": null,
        "birthday": "1990-01-15T00:00:00.000Z"
    }
}
```

### Error Responses (400)
```json
// Missing required fields
{
    "message": "Missing required fields: firstName, lastName, email, password, role are required"
}

// Invalid email
{
    "message": "Invalid email format"
}

// Invalid role
{
    "message": "Invalid role. Must be 'Resident' or 'Responder'"
}

// Missing responderType for Responder role
{
    "message": "responderType is required when role is 'Responder'"
}

// Duplicate email
{
    "message": "Email already registered"
}

// Duplicate username
{
    "message": "Username or email already exists"
}
```

## Notes for Android Developer

1. **Username Auto-generation**: The backend will automatically generate a unique username if not provided, using the format: `firstName_lastName_timestamp`

2. **Birthday Format**: Send birthday as ISO date string (YYYY-MM-DD) or null if not provided

3. **Contact Field**: Make contact field optional in your layout

4. **Conditional UI**: Show responder type spinner only when "Responder" is selected

5. **Validation**: Implement client-side validation for email format and required fields

6. **Error Handling**: Handle all possible error responses from the backend

7. **Loading States**: Show loading spinner during API calls

8. **Success Flow**: On successful registration, you can either:
   - Redirect to login screen
   - Auto-login the user and redirect to dashboard
   - Show success message and stay on registration screen

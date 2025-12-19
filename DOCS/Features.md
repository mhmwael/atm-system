# System Features Documentation

## 1. Authentication & Authorization
- **Implementation**: Custom `AuthController` handling Login and Registration.
- **Security**: 
    - Passwords hashed using Bcrypt (Laravel default).
    - CSRF protection enabled on all forms.
    - Session-based authentication.
- **Authorization**:
    - Middleware approach used.
    - Role-based logic (`isAdmin`) available in User model.

## 2. Singleton Logging
- **Service**: `App\Services\AppLogger`
- **Pattern**: Singleton.
- **Usage**: Used in Controllers to log critical actions (Login, Create, Update, Delete).

## 3. CRUD Operations
- **Controller**: `AccountController`
- **Actions**:
    - **C**reate: Create new accounts.
    - **R**ead: List and View accounts.
    - **U**pdate: Edit account details.
    - **D**elete: Remove accounts.

## 4. File Upload
- **Feature**: Profile Photo Upload.
- **Implementation**: 
    - User uploads image via Dashboard.
    - Image stored in `storage/app/public/profile-photos`.
    - Path saved in DB.
    - Displayed using `Storage::url()`.

## 5. Ajax Requests
- **Feature**: Balance Refresh.
- **Implementation**: 
    - `fetch()` API call allows updating the balance on the dashboard without reloading the page.
    - Endpoint: `/api/balance`.
    - Format: JSON.

## 6. User Interface
- **Style**: Custom "Glassmorphism" Design (Vanilla CSS).
- **Files**: `public/css/app.css` and `resources/views/layouts/app.blade.php`.

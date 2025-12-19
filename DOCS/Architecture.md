# System Architecture

## MVC Design Pattern
The application follows the **Model-View-Controller (MVC)** architectural pattern, which is inherent to the Laravel framework.

### Model (M)
- **Role**: Represents the data and business logic.
- **Location**: `app/Models/`
- **Examples**: `User`, `Account`, `Transaction`.
- **docs**: See `DOCS/Models/` for detailed breakdown of each model.

### View (V)
- **Role**: Handles the presentation layer and user interface.
- **Location**: `resources/views/`
- **Implementation**: Blade templates utilizing Vanilla CSS (`public/css/app.css`) for a premium, custom look.

### Controller (C)
- **Role**: Handles user requests, interacts with Models, and returns Views.
- **Location**: `app/Http/Controllers/`

## Singleton Design Pattern
To satisfy the requirement for a **Singleton Design Pattern**, we implemented a dedicated Logging Service.

### Implementation: `App\Services\AppLogger`
- **Purpose**: Provides a centralized, single instance for logging system events.
- **Pattern**: The class has a private constructor and a static `getInstance()` method, ensuring only one instance exists throughout the request lifecycle.
- **Location**: `app/Services/AppLogger.php`

## Authentication & Authorization
- **Authentication**: Stateful session-based auth (Laravel default).
- **Authorization**: Role-based access control (RBAC) using the `role` column on the `users` table.
- **Middleware**: Custom middleware or simple checks `if (auth()->user()->role === 'admin')` protec route access.

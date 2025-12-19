# User Model Documentation

## Overview
The `User` model represents a registered entity in the ATM system.

## Database Table: `users`

| Column | Type | Description |
|--------|------|-------------|
| `id` | BigInt (PK) | Unique identifier |
| `name` | String | Full name |
| `email` | String | Unique email |
| `phone_number` | String | User's phone number |
| `is_verified` | Boolean | Verification status |
| `password` | String | Hashed password |
| `role` | String | 'admin' or 'user' |
| `profile_photo_path` | String | Path to profile photo |

## Relationships
- **HasMany Accounts**: A user can have multiple accounts.
- **HasMany Fingerprints**: Biometric templates associated with user.
- **HasMany Transactions**: History of user activities.

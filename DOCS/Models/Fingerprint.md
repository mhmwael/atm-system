# Fingerprint Model Documentation

## Overview
Stores biometric data for user authentication.

## Database Table: `fingerprints`

| Column | Type | Description |
|--------|------|-------------|
| `id` | BigInt (PK) | Unique identifier |
| `user_id` | BigInt (FK) | Reference to `users` |
| `template` | Binary (BLOB) | Biometric template data |
| `image_path` | String | Path to scanned image |
| `is_active` | Boolean | Biometric status |
| `last_used` | Timestamp | Last authentication time |

## Relationships
- **BelongsTo User**: Associated user.

# Account Model Documentation

## Overview
Represents a bank account.

## Database Table: `accounts`

| Column | Type | Description |
|--------|------|-------------|
| `id` | BigInt (PK) | Unique identifier |
| `user_id` | BigInt (FK) | Reference to `users` |
| `account_number` | String | Unique account number |
| `account_type` | Enum | 'checking', 'savings' |
| `balance` | Decimal | Current balance |
| `is_active` | Boolean | Account status |

## Relationships
- **BelongsTo User**: Owner of the account.
- **HasMany Cards**: Physical cards linked to this account.
- **HasMany Transactions**: Outgoing and incoming transfers.

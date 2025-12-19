# Card Model Documentation

## Overview
Represents physical credit/debit cards.

## Database Table: `cards`

| Column | Type | Description |
|--------|------|-------------|
| `id` | BigInt (PK) | Unique identifier |
| `account_id` | BigInt (FK) | Reference to `accounts` |
| `card_number` | String(16) | Unique card digits |
| `cvv` | Integer | Security code |
| `expiry_date` | Char(5) | Format MM/YY |

## Relationships
- **BelongsTo Account**: Linked bank account.

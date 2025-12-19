# Transaction Model Documentation

## Overview
Records all financial activities.

## Database Table: `transactions`

| Column | Type | Description |
|--------|------|-------------|
| `id` | BigInt (PK) | Unique identifier |
| `user_id` | BigInt (FK) | User who initiated transaction |
| `to_account_id` | BigInt (FK) | Destination account |
| `from_account_id` | BigInt (FK) | Source account |
| `amount` | Decimal | Value of transaction |
| `type` | Enum | 'transfer', 'deposit', 'withdrawal' |
| `status` | Enum | 'pending', 'completed', 'failed' |
| `location` | String | Physical/Virtual location |
| `ip_address` | String | Origin IP |
| `device_info` | Text | Browser/Device details |

## Relationships
- **BelongsTo User**: Initiator.
- **BelongsTo ToAccount**: Target account.
- **BelongsTo FromAccount**: Origin account.
- **HasOne Fraud**: Linked fraud detection record.

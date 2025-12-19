# Fraud Model Documentation

## Overview
Records detected fraudulent activities.

## Database Table: `frauds`

| Column | Type | Description |
|--------|------|-------------|
| `id` | BigInt (PK) | Unique identifier |
| `transaction_id` | BigInt (FK) | Reference to `transactions` |
| `reason` | String(254) | Reason for fraud flag |
| `status` | Enum | 'pending', 'confirmed', 'rejected' |
| `detected_at` | Timestamp | Time of detection |

## Relationships
- **BelongsTo Transaction**: Flagged transaction.
                                                                           

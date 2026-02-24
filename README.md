# E-Commerce

Remake of [Site-ECommerce](https://github.com/EECKHOUTRemi/Site-ECommerce) with Symfony.

## Requirements

- PHP 7.3.33
- Symfony 5.4.51
- Composer 2.9.4

## Database Schema

```mermaid
erDiagram
    User ||--o{ Order : places
    User ||--o{ RacquetRating : rates
    Order ||--o{ RacquetOrdered : contains
    Order }o--|| PromoCode : uses
    Racquet ||--o{ RacquetRating : receives
    Racquet ||--o{ RacquetOrdered : "is ordered as"

    User {
        int id PK
        string username UK
        string email UK
        json roles
        string password
        string lastname
        string firstname
        string phone
    }

    Order {
        int id PK
        int user_id FK
        int promo_code_id FK
        string status
        datetime createdAt
        datetime updatedAt
        float total
    }

    Racquet {
        int id PK
        string brand
        string model
        float price
        smallint quantity
        string imgExtension
        float avgRating
    }

    RacquetOrdered {
        int id PK
        int racquet_id FK
        int order_id FK
        smallint head_size
        string string_pattern
        smallint weight
        smallint grip_size
        smallint quantity
    }

    RacquetRating {
        int id PK
        int user_id FK
        int racquet_id FK
        smallint rating
    }

    PromoCode {
        int id PK
        string name
        smallint discount
    }
```

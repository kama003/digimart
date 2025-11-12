<?php

namespace App\Enums;

enum UserRole: string
{
    case CUSTOMER = 'customer';
    case SELLER = 'seller';
    case ADMIN = 'admin';
}

<?php

namespace App\Enums;

enum AssetCondition: string
{
    case EXCELLENT = 'Excellent';
    case GOOD = 'Good';
    case FAIR = 'Fair';
    case POOR = 'Poor';
    case UNDER_REPAIR = 'Under Repair';
}

<?php

namespace App\Entity\Vehicle;

enum VehicleType: string
{
    case InternalCombustion =  'internalCombustion';
    case Hybrid = 'hybrid';
    case Electric = 'electric';
}

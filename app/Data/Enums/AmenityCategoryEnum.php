<?php
  
namespace App\Data\Enums;

enum AmenityCategoryEnum:string {

    case Confort = 'comfort';
    case Services = 'services';
    case Classification = 'classification'; // compostion
    case Options = 'options';
    case Activities = 'activities';
    case Around = 'around'; // dans les enviirons
    case Theme = 'themes';
}
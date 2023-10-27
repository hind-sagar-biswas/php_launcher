<?php

namespace Core\Router;


enum RouteSystem: string
{
    case RAW = 'raw';
    case CTR = 'controlled';
}

<?php

function gaurd_guest()
{
    if ($GLOBALS['logged_in']) return ['admin', 'User Already logged in'];
}

function gaurd_auth()
{
    if (!$GLOBALS['logged_in']) return ['login', 'Must login before accessing the page'];
}
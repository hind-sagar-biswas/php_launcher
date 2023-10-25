<?php

function gaurd_auth()
{
    if (!$GLOBALS['logged_in']) return [403, 'Must login before accessing the data'];
}
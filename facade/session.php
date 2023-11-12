<?php

use Core\Base\Session;

if (isset(REQUEST->query['set'])) d(Session::set(REQUEST->query['set'], REQUEST));
elseif (isset(REQUEST->query['get'])) d(Session::get(REQUEST->query['get']));
else d($_SESSION);
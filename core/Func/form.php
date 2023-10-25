<?php

function _csrf() {
    echo "<input type='hidden' name='_csrf_token' value='" . CSRF_TOKEN . "' />";
}
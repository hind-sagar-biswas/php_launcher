<?php

/**
 * This function loads a JavaScript file from the assets/js directory.
 *
 * @param string $fileName The name of the JavaScript file to load.
 * @param bool $location Whether to return the file path or echo the script tag.
 * @return string|null The file path or the script tag, depending on the value of `$location`.
 */
function _js(string $fileName, bool $location = false): string|null
{
    $fileName = APP_URL . 'assets/js/' . $fileName . '.js';
    if ($location) {
        return $fileName;
    }
    echo '<script defer src="' . $fileName . '"></script>';
    return null;
}

/**
 * This function loads a CSS file from the assets/css directory.
 *
 * @param string $fileName The name of the CSS file to load.
 * @param bool $location Whether to return the file path or echo the link tag.
 * @return string|null The file path or the link tag, depending on the value of `$location`.
 */
function _css(string $fileName, bool $location = false): string|null
{
    $fileName = APP_URL . 'assets/css/' . $fileName . '.css';
    if ($location) {
        return $fileName;
    }
    echo '<link rel="stylesheet" href="' . $fileName . '">';
    return null;
}

/**
 * This function loads a JavaScript file from the node_modules directory.
 *
 * @param string $fileName The name of the JavaScript file to load.
 * @param bool $location Whether to return the file path or echo the script tag.
 * @return string|null The file path or the script tag, depending on the value of `$location`.
 */
function _node_js(string $fileName, bool $location = false): string|null
{
    $fileName = APP_URL . 'node_modules/' . $fileName . '.js';
    if ($location) {
        return $fileName;
    }
    echo '<script defer src="' . $fileName . '"></script>';
    return null;
}

/**
 * This function loads a CSS file from the node_modules directory.
 *
 * @param string $fileName The name of the CSS file to load.
 * @param bool $location Whether to return the file path or echo the link tag.
 * @return string|null The file path or the link tag, depending on the value of `$location`.
 */
function _node_css(string $fileName, bool $location = false): string|null
{
    $fileName = APP_URL . 'node_modules/' . $fileName . '.css';
    if ($location) {
        return $fileName;
    }
    echo '<link rel="stylesheet" href="' . $fileName . '">';
    return null;
}

/**
 * This function loads an image file from the assets/images directory.
 *
 * @param string $fileName The name of the image file to load.
 * @param bool $location Whether to return the file path or echo the img tag.
 * @return string|null The file path or the img tag, depending on the value of `$location`.
 */
function _image(string $fileName, bool $location = true): string|null
{
    $fileName = APP_URL . 'assets/images/' . $fileName;
    if ($location) {
        return $fileName;
    }
    echo '<img src="' . $fileName . '">';
    return null;
}

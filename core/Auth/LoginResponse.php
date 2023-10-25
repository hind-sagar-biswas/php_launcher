<?php

namespace Core\Auth;

enum LoginResponse: string {
    case INVALID_METHOD = 'Login must be on POST request';
    case INVALID_IDENTIFIER = 'Provided Identifier is Invalid';
    case INVALID_PASSKEY = 'Provided Pass Key is Invalid';
    case ABSENT_IDENTIFIER = 'No Identifier provided for login';
    case ABSENT_PASSKEY = 'No Pass Key provided for login';
    case MISMATCHED_CREDENTIAL = 'Credentials does not match';
    case SUCCESS = 'Login Successful';
    case UNKNOWN = 'Something went wrong';
}
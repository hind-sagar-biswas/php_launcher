<?php

namespace Core\Auth;

enum SignupResponse: string
{
    case PREEXISTING_IDENTIFIER = 'Provided Identifier already exists';
    case INVALID_IDENTIFIER = 'Provided Identifier is Invalid';
    case INVALID_PASSKEY = 'Provided Pass Key is Invalid';
    case INVALID_METHOD = 'Login must be on POST request';
    case MISSMATCHED_PASSKEY = 'Retyped Pass Key does not match';
    case ABSENT_IDENTIFIER = 'No Identifier provided for signup';
    case ABSENT_RE_PASSKEY = 'No Re-Pass Key provided for signup';
    case ABSENT_PASSKEY = 'No Pass Key provided for signup';
    case LOGIN_FAILED = 'Signup Successful but Login Failed, Try logging in!';
    case SUCCESS = 'Signup Successful';
    case UNKNOWN = 'Something went wrong';
}

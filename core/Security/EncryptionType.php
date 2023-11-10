<?php

namespace Core\Security;


enum EncryptionType {
    case SINGLE_KEY;
    case DUAL_KEY;
}
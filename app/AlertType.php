<?php

namespace App;

enum AlertType: string
{
    case INFO = 'info';
    case ERROR = 'error';
    case WARNING = 'warning';
    case SUCCESS = 'success';
}

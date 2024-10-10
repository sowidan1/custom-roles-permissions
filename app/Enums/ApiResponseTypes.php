<?php
namespace App\Enums;

enum ApiResponseTypes: string
{
    case SUCCESS = "success";
    case FAILED = "fail";
    case ERROR = "error";
}
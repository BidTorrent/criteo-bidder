<?php
class UserResolver
{
    function getUserId($cookies)
    {
        return isset($cookies['uid']) ? $cookies['uid'] : '';
    }
}
?>
<?php
class UserResolver
{
    var $partner = '42';

    function getUserId($cookies) {

        if (isset($cookies['uid'])) {
            return $cookies['uid'];
        }

        if (!isset($cookies['Ids']))
        {
            return '';
        }

        $ids = @unserialize($cookies['Ids']);

        if ($ids == null || !is_array($ids) || !array_key_exists($this->partner, $ids))
        {
            return '';
        }

        return $ids[$this->partner];
    }
}
?>
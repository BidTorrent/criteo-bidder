<?php
class UserResolver
{
    var $partner = '42';

    function getUserId($cookies) {

        if (isset($cookie['uid'])) {
            return $cookie['uid'];
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
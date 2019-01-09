<?php
if (! function_exists('add_route')) {
    function add_route($param, $value)
    {

        $currentQueries = Request::route()->parameters();

        $newQueries = [$param => $value];

        $allQueries = array_merge($currentQueries, $newQueries);

        $result = Request::fullUrlWithQuery($allQueries);

        return $result;
    }
}

if (! function_exists('get_template_variable')) {
    function get_template_variable($key, $data, $field=null)
    {
        $field = $field ? $field : $key;
        if (isset($data[$field])) {
            if (is_array($data[$field])) {
                return json_encode(old($key, $data[$field]));
            } else {
                return old($key, $data[$field]);
            }
        } else {
            return old($key);
        }
    }
}


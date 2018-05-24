<?php

function route_class(){
    return str_replace(".","-",Route::currentRouteName());
}

/**
 * @param $value
 * @param int $length
 */
function make_excerpt($value,$length = 200){
    $excerpt = trim(preg_replace('/\r\n|\r|\n+/','',strip_tags($value)));
    return str_limit($excerpt,$length);
}

function model_plura_name($model){
    $full_class_name = get_class($model);

    $class_name = class_basename($full_class_name);

    $snake_case_name = snake_case($class_name);

    return str_plural($snake_case_name);
}

function model_link($title,$model,$prefix = ""){
    $model_name = model_plura_name($model);

    $prefix = $prefix?"/$prefix/":"/";

    $url = config("app.url").$prefix.$model_name."/".$model->id;

    return '<a href="'.$url.'" target = "_blank">'.$title.'</a>';
}

function model_admin_link($title,$model){
    return model_link($title,$model,"admin");
}

function get_db_config()
{
    if (getenv('IS_IN_HEROKU')) {
        $url = parse_url(getenv("DATABASE_URL"));

        return $db_config = [
            'connection' => 'pgsql',
            'host' => $url["host"],
            'database'  => substr($url["path"], 1),
            'username'  => $url["user"],
            'password'  => $url["pass"],
        ];
    } else {
        return $db_config = [
            'connection' => env('DB_CONNECTION', 'mysql'),
            'host' => env('DB_HOST', 'localhost'),
            'database'  => env('DB_DATABASE', 'forge'),
            'username'  => env('DB_USERNAME', 'forge'),
            'password'  => env('DB_PASSWORD', ''),
        ];
    }
}


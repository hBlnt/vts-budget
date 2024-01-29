<?php

const PARAMS = [
    "HOST" => 'localhost',
    "USER" => 'root',
    "PASS" => '',
    "DBNAME" => 'tourist',
    "CHARSET" => 'utf8mb4'
];

const SITE = 'localhost/2024/vts-budget/'; // enter your path on localhost

$dsn = "mysql:host=" . PARAMS['HOST'] . ";dbname=" . PARAMS['DBNAME'] . ";charset=" . PARAMS['CHARSET'];

$pdoOptions = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false
];

$actions = ['login', 'register', 'forget'];
$formActions = ['userDeleteTour', 'newTour', 'userDeleteFavourite', 'makeFavourite', 'editAttraction', 'deleteAttraction', 'newAttraction', 'newComment'];

$messages = [
    0 => 'No direct access!',
    1 => 'Unknown user!',
    2 => 'User with this e-mail already exists, choose another one!',//
    3 => 'Check your email to activate your account!',
    4 => 'Fill all the fields!',//
    5 => 'You are logged out!!',
    6 => 'Your account is activated, you can login now!',
    7 => 'Passwords are not equal!',//
    8 => 'Format of e-mail address is not valid!',//
    9 => 'Password can not be empty!',//
    10 => 'Password is not long enough! (min 8 characters)',//
    11 => 'Something went wrong with mail server. We will try to send email later!',
    12 => 'Your account is already activated!',
    13 => 'If you have an account on our site, email with instructions for reset password is sent to you.',
    14 => 'Something went wrong with server.',
    15 => 'Token or other data are invalid!',
    16 => 'Your new password is set and you can login.',
    18 => 'Fill out the new fields!',//
    22 => 'Logged in congrats, you are a USER',
    24 => 'Admin did not approve yet!',
    27 => 'Success',

    28 => 'Something went wrong',

    29 => 'Something went wrong. Please contact our support.',
    30 => 'Fill at least one field!',
    31 => 'Successfully updated your data!',
    32 => 'Successfully deleted data!',
    33 => 'Something went wrong with image upload!',
    34 => 'File is not JPG format!!',
    35 => 'Already exists attraction with this name',
    36 => 'You have no permission to this e-mail domain!'
];

$emailMessages = [
    'register' => [
        'subject' => 'Register on Toorizm',
        'altBody' => 'This is the body in plain text for non-HTML mail clients'
    ],
    'forget' => [
        'subject' => 'Forgotten password - create new password',
        'altBody' => 'This is the body in plain text for non-HTML mail clients'
    ]
];


$titles = [
    'index.php' => 'Index page',
    'login.php' => 'Login page',
    'organizations.php' => 'Create organizations',
    'new_city.php' => 'Create city',
    'all_organizations.php' => 'All organizations',
    'all_cities.php' => 'All cities',
    'user_status.php' => 'Change user status',
    'all_attractions.php' => 'All attractions',
];


$messagesAdmin = [
    'new_city.php' => [
        1 => ['style' => 'danger', 'text' => 'Please, insert required fields.'],
        2 => ['style' => 'danger', 'text' => 'There is already a city with this name.'],
        3 => ['style' => 'danger', 'text' => 'Error occurs during image upload. Please try again and upload only JPG images.'],
        4 => ['style' => 'danger', 'text' => 'Something went wrong during image upload. Please try again.'],
        5 => ['style' => 'success', 'text' => 'City created successfully, but some values were not inserted.'],
        6 => ['style' => 'success', 'text' => 'City created successfully.'],
        7 => ['style' => 'danger', 'text' => 'Upload a jpg format image!']
    ],
    'login.php' => [

        0 => ['style' => 'danger', 'text' => 'No direct access!'],
        1 => ['style' => 'danger', 'text' => 'Unknown user!'],
        2 => ['style' => 'danger', 'text' => 'You are logged out!'],
    ],
    'all_cities.php' => [

        1 => ['style' => 'danger', 'text' => 'Error occurs during image upload. Please try again and upload only JPG images.'],
        2 => ['style' => 'danger', 'text' => 'Something went wrong during image upload. Please try again.'],
    ],
    'organizations.php' => [
        1 => ['style' => 'danger', 'text' => 'Please, insert required fields.'],
        2 => ['style' => 'danger', 'text' => 'Organization with this name already exists.'],
        3 => ['style' => 'success', 'text' => 'New organization generated successfully.']
    ],
];
$GLOBALS['badWords'] = ['drop', 'delete', 'kill', 'destroy', 'fool'];

@include('errors.layout', [
    'title' => 'Page Not Found',
    'code' => '404',
    'heading' => 'Page Not Found',
    'message' => 'The page you\'re looking for doesn\'t exist, may have moved, or the link might be broken.',
    'showAdminLink' => true,
])

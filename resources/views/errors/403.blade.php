@include('errors.layout', [
    'title' => 'Access Denied',
    'code' => '403',
    'heading' => 'Access Denied',
    'message' => 'You don\'t have permission to view this page. If you think this is a mistake, please sign in with an account that has access.',
    'showAdminLink' => true,
])

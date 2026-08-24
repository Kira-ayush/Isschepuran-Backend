@include('errors.layout', [
    'title' => 'Session Expired',
    'code' => '419',
    'heading' => 'Your Session Expired',
    'message' => 'You were away for a little while and your session timed out. Please go back and try again.',
    'showAdminLink' => true,
])

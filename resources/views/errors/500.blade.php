@include('errors.layout', [
    'title' => 'Something Went Wrong',
    'code' => '500',
    'heading' => 'Something Went Wrong',
    'message' => 'Our team has been notified and we\'re looking into it. Please try again in a few minutes.',
    'showAdminLink' => false,
])

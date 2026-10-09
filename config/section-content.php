<?php
// Additive content defaults: saved CMS values always take precedence.
return [
    'process' => [
        'fields' => ['note' => 'Already have a product list? Share model references, quantities and any specifications with your enquiry.'],
        'items' => [
            'Tell us about your need' => ['icon'=>'chat-1-line', 'point_1'=>'Your care setting and intended application', 'point_2'=>'Required quantity and preferred timeline'],
            'Explore suitable options' => ['icon'=>'search-eye-line', 'point_1'=>'Product details, fittings and accessories', 'point_2'=>'Questions for your clinical or technical team'],
            'Decide with clarity' => ['icon'=>'file-list-3-line', 'point_1'=>'Confirm the model and written specifications', 'point_2'=>'Discuss availability and the next steps'],
        ],
    ],
    'guidance' => [
        'fields' => ['image'=>'assets/images/surgery.jpg', 'image_alt'=>'Healthcare professionals working in a clinical setting', 'image_caption'=>'The right details make a better starting point.', 'note'=>'A little preparation helps us understand your requirements more clearly.'],
        'items' => [
            'Care setting' => ['point_1'=>'Hospital, clinic or supervised home care', 'point_2'=>'Who will use and manage the equipment'],
            'Compatibility' => ['point_1'=>'Existing equipment and model references', 'point_2'=>'Connections, fittings and accessories'],
            'Product information' => ['point_1'=>'Specifications and available documentation', 'point_2'=>'Questions to confirm before choosing'],
            'Local conversation' => ['point_1'=>'Your location and preferred contact details', 'point_2'=>'Quantity, timeline and follow-up questions'],
        ],
    ],
    'partnership' => [
        'fields' => [
            'items' => [
                ['icon'=>'chat-1-line','title'=>'Start with your requirements','body'=>'Tell us what you need, even if you are still exploring the options.'],
                ['icon'=>'file-list-3-line','title'=>'Bring the details together','body'=>'Share your product list, model references or questions in one conversation.'],
                ['icon'=>'map-pin-2-line','title'=>'Connect with our Agra team','body'=>'Call, send an enquiry or find directions to our Pratappura address.'],
            ],
        ],
    ],
];

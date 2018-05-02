<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SocialLinks extends Model
{
    const socialMediaSites = [
        'fab fa-facebook' => 'Facebook',
        'fab fa-twitter' => 'Twitter',
        'fas fa-globe' => 'Website',
    ];
}

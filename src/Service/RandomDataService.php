<?php

namespace App\Service;

class RandomDataService
{

    const POST_IMAGES = ["profile_default.png"];

    public function getImage()
    {
        $key = array_rand(self::POST_IMAGES);
        return self::POST_IMAGES[$key];
        //bbbb
    }
}

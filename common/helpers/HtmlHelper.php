<?php

namespace common\helpers;

class HtmlHelper
{
    public static function iframeParamsCleaned()
    {
        return [
            'marginwidth' => '0',
            'marginheight' => '0',
            'align' => 'top',
            'scrolling' => 'No',
            'frameborder' => '0',
            'hspace' => '0',
            'vspace' => '0',
        ];
    }
}

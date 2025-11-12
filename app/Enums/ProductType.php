<?php

namespace App\Enums;

enum ProductType: string
{
    case AUDIO = 'audio';
    case VIDEO = 'video';
    case THREE_D = '3d';
    case TEMPLATE = 'template';
    case GRAPHIC = 'graphic';

    public function label(): string
    {
        return match($this) {
            self::AUDIO => 'Audio',
            self::VIDEO => 'Video',
            self::THREE_D => '3D Model',
            self::TEMPLATE => 'Template',
            self::GRAPHIC => 'Graphic',
        };
    }
}

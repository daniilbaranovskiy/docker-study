<?php

namespace App\Models;

use App\Core\Database\ActiveRecord;

/**
 * @property int $id Title of news
 * @property string $title Title of news
 * @property string $text Text of news
 * @property string $date Date of news
 */
class News extends ActiveRecord
{
    public function __construct()
    {
        $this->table = 'news';
        parent::__construct();
    }

}
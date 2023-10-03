<?php

namespace store\lib;

use a;
use store\model\persist\ProductPdoDbDao;
use store\model\persist\UserPdoDbDao;
use the;

require_once "model/persist/UserPdoDbDao.php";
require_once "model/persist/ProductPdoDbDao.php";

class DaoFactory
{

    /**
     * creates a proper DAO according to value of parameter $type
     * @param $type the type of DAO to create.
     * @return a DAO object or null if unknown type.
     */
    public static function getDao(string $type)
    {
        $result = null;
        switch ($type) {
            case "user":
                $result = new UserPdoDbDao();
                break;
            case "product":
                $result = new ProductPdoDbDao();
                break;
            default:
                break;
        }
        return $result;
    }

}

<?php

namespace store\model;

use id;
use store\lib\DaoFactory;
use the;

require_once "lib/DaoFactory.php";
require_once 'model/User.php';
require_once 'model/Product.php';

/**
 * Model for store application.
 *
 * @author ivandmn
 */
class Model
{

    public function __construct()
    {

    }

    /** methods related to user **/

    /**
     * searches all users in database.
     * @return array with all users found or null in case of error.
     */
    public function searchAllUsers(): ?array
    {
        $data = null;
        $dao = DaoFactory::getDao("user");
        $data = $dao->selectAll();
        return $data;
    }

    /**
     * searches users with given username and password
     * @param string $username the username to search
     * @param string $password the password to search
     * @return user with given username and password or null if not found
     */
    public function searchUserByUsernameAndPassword(string $username, string $password): ?User
    {
        $data = null;
        $dao = DaoFactory::getDao("user");
        $data = $dao->selectUsersbyUsernameAndPassword($username, $password);
        return $data;
    }

    /**
     * searches users with given username
     * @param string $username the username to search
     * @return user with given username or null if not found
     */
    public function searchUsersByUsername(string $username): ?User
    {
        $data = null;
        $dao = DaoFactory::getDao("user");
        $data = $dao->selectUsersbyUsername($username);
        return $data;
    }

    /**
     * adds a new user
     * @param User $user the user to add
     * @return User added or null
     */
    public function addUser(User $user): ?User
    {
        $data = null;
        $dao = DaoFactory::getDao("user");
        $data = $dao->insert($user);
        return $data;
    }

    /**
     * search a user with given id
     * @param int $id the id to search
     * @return the user searched or null if not found
     */
    public function searchUserById(int $id): ?User
    {
        $found = null;
        $dao = DaoFactory::getDao("user");
        $u = new User($id);
        $found = $dao->select($u);
        return $found;
    }

    /**
     * modify user with user given
     * @param $user user to modify
     * @return int 0 if not modified 1 if modified
     */
    public function modifyUser(User $user): int
    {
        $dao = DaoFactory::getDao("user");
        $found = $dao->update($user);
        return $found;
    }

    /**
     * delete user with id given
     * @param $id id of user to delete
     * @return int 0 if not deleted 1 if deleted
     */
    public function deleteUser(int $id): int
    {
        $dao = DaoFactory::getDao("user");
        $u = new User($id);
        $usersDeleted = $dao->delete($u);
        return $usersDeleted;
    }

    /** methods related to product **/

    /**
     * searches all products in database.
     * @return array with all products found or null in case of error.
     */
    public function searchAllProducts(): ?array
    {
        $data = null;
        $dao = DaoFactory::getDao("product");
        $data = $dao->selectAll();
        return $data;
    }

    /**
     * search a product with given id
     * @param int $id the id to search
     * @return the product searched or null if not found
     */
    public function searchProductById(int $id): ?Product
    {
        $found = null;
        $dao = DaoFactory::getDao("product");
        $u = new Product($id);
        $found = $dao->select($u);
        return $found;
    }

    /**
     * adds a product to database.
     * @param Product $product the product to add.
     * @return Product added or null
     */
    public function addProduct(Product $product): ?Product
    {
        $result = null;
        $dao = DaoFactory::getDao("product");
        $result = $dao->insert($product);
        return $result;
    }

    /**
     * modifies a product to database.
     * @param Product $product the product to modify.
     * @return int result code for this operation.
     */
    public function modifyProduct(Product $product): int
    {
        $dao = DaoFactory::getDao("product");
        $found = $dao->update($product);
        return $found;
    }

    /**
     * removes a product to database.
     * @param $id id of product to delete
     * @return int result code for this operation.
     */
    public function removeProduct(int $id): int
    {
        $dao = DaoFactory::getDao("product");
        $p = new Product($id);
        $usersDeleted = $dao->delete($p);
        return $usersDeleted;
    }

}
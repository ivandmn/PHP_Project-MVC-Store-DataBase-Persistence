<?php

namespace store\model\persist;

use PDO;
use store\model\User;
use type;

require_once __DIR__ . '/StorePdoDb.php';
require_once  __DIR__ . '/../User.php';


/**
 * User database persistence class.
 * @author ivandmn
 */
class UserPdoDbDao
{

    private StorePdoDb $storeDb;
    private static string $TABLE_NAME = 'users';
    private array $queries;

    public function __construct()
    {
        $this->storeDb = new StorePdoDb();
        $this->queries = array();
        $this->initQueries();
    }

    /**
     * selects an object by its PK.
     * @param User $entity the object to search
     * @return User the object found or null in case of error or not found
     */
    public function select(User $entity): ?User
    {
        $data = null;
        try {
            //PDO object creation.
            $connection = $this->storeDb->getConnection();
            //query preparation.
            $stmt = $connection->prepare($this->queries['SELECT_WHERE_ID']);
            $stmt->bindValue("id", $entity->getId(), \PDO::PARAM_INT);
            //query execution.
            $success = $stmt->execute();
            //If $succes == true enter if
            if ($success) {
                //$stmt->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, 'User');
                //$result = $stmt->fetch();
                $result = $this->fetchToUser($stmt);
                //If result is a user (!= false) enter if
                if ($result !== \false) {
                    $data = $result;
                } else {
                    //If result is false $data = null;
                    $data = null;
                }
            }
        } catch (\PDOException $e) {
            //If PDO Exception $data = null;
            $data = null;
            //Examples of how to get errors. TODO: delete this and treat properly the exceptions.
            //print "Error Code <br>".$e->getCode();
            //print "Error Message <br>".$e->getMessage();
            //print "Strack Trace <br>".nl2br($e->getTraceAsString());
        }
        return $data;
    }

    /**
     * selects user from table at database with given $username.
     * @return User the object found or null in case of error or not found
     */
    public function selectUsersbyUsername(string $username): ?User
    {
        $data = null;
        try {
            //PDO object creation.
            $connection = $this->storeDb->getConnection();
            //query preparation.
            $stmt = $connection->prepare($this->queries['SELECT_WHERE_USERNAME']);
            $stmt->bindValue("username", $username, \PDO::PARAM_STR);
            //query execution.
            $success = $stmt->execute();
            //If $succes == true enter if
            if ($success) {
                //$stmt->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, 'User');
                //$result = $stmt->fetch();
                $result = $this->fetchToUser($stmt);
                //If result is a user (!= false) enter if
                if ($result !== \false) {
                    $data = $result;
                } else {
                    //If result is false $data = null;
                    $data = null;
                }
            }
        } catch (\PDOException $e) {
            //If PDO Exception $data = null;
            $data = null;
            //Examples of how to get errors. TODO: delete this and treat properly the exceptions.
            //print "Error Code <br>".$e->getCode();
            //print "Error Message <br>".$e->getMessage();
            //print "Strack Trace <br>".nl2br($e->getTraceAsString());
        }
        return $data;
    }

    /**
     * selects user from table at database with given $username and $password.
     * @return User the object found or null in case of error or not found
     */
    public function selectUsersbyUsernameAndPassword(string $username, string $password): ?User
    {
        $data = null;
        try {
            //PDO object creation.
            $connection = $this->storeDb->getConnection();
            //query preparation.
            $stmt = $connection->prepare($this->queries['SELECT_WHERE_USERNAME_AND_PASSWORD']);
            $stmt->bindValue("username", $username, \PDO::PARAM_STR);
            $stmt->bindValue("password", $password, \PDO::PARAM_STR);
            //query execution.
            $success = $stmt->execute();
            //If $succes == true enter if
            if ($success) {
                //$stmt->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, 'User');
                //$result = $stmt->fetch();
                $result = $this->fetchToUser($stmt);
                //If result is a user (!= false) enter if
                if ($result !== \false) {
                    $data = $result;
                } else {
                    //If result is false $data = null;
                    $data = null;
                }
            }
        } catch (\PDOException $e) {
            //If PDO Exception $data = null;
            $data = null;
            //Examples of how to get errors. TODO: delete this and treat properly the exceptions.
            //print "Error Code <br>".$e->getCode();
            //print "Error Message <br>".$e->getMessage();
            //print "Strack Trace <br>".nl2br($e->getTraceAsString());
        }
        return $data;
    }

    /**
     * selects all records from table at database.
     * @return array the array of objects retrieved from database.
     */
    public function selectAll(): array
    {
        $data = array();
        try {
            //PDO object creation.
            $connection = $this->storeDb->getConnection();
            //query preparation.
            $stmt = $connection->prepare($this->queries['SELECT_ALL']);
            //query execution.
            $success = $stmt->execute();
            //If $succes == true enter if
            if ($success) {
                if ($stmt->rowCount() > 0) {
                    //retrieve data with helper method $this->fetchToUser()
                    $stmt->setFetchMode(PDO::FETCH_ASSOC);
                    //get one row at the time
                    while ($u = $this->fetchToUser($stmt)) {
                        array_push($data, $u);
                    }
                    //retrieve data as object of given class
                    //$stmt->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, "User");
                    //$data = $stmt->fetchAll();

                    //or in one single sentence:
                    //$data = $stmt->fetchAll(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, 'User');
                } else {
                    $data = array();
                }
            } else {
                $data = array();
            }
        } catch (\PDOException $e) {
            //If PDO Exception $data = null;
            $data = array();
            //Examples of how to get errors. TODO: delete this and treat properly the exceptions.
            //print "Error Code <br>".$e->getCode();
            //print "Error Message <br>".$e->getMessage();
            //print "Strack Trace <br>".nl2br($e->getTraceAsString());
        }
        return $data;
    }

    /**
     * inserts an object into database
     * @param User $entity the object to insert
     * @return User the object inserted or null in case of error
     */
    public function insert(User $entity): ?User
    {
        $data = null;
        try {
            //PDO object creation.
            $connection = $this->storeDb->getConnection();
            //query preparation.
            $stmt = $connection->prepare($this->queries['INSERT']);
            $stmt->bindValue("username", $entity->getUsername(), \PDO::PARAM_STR);
            $stmt->bindValue("password", $entity->getPassword(), \PDO::PARAM_STR);
            $stmt->bindValue("role", $entity->getRole(), \PDO::PARAM_STR);
            $stmt->bindValue("name", $entity->getName(), \PDO::PARAM_STR);
            $stmt->bindValue("surname", $entity->getSurname(), \PDO::PARAM_STR);
            //query execution.
            $success = $stmt->execute();
            //If $succes == true enter if
            if ($success) {
                $user = $this->selectUsersbyUsername($entity->getUsername());
                if ($user != null) {
                    $data = $user;
                }
            }
        } catch (\PDOException $ex) {
            //If PDO Exception  $data = null;;
            $data = null;
            //Examples of how to get errors. TODO: delete this and treat properly the exceptions.
            //print "Error Code <br>".$ex->getCode();
            //print "Error Message <br>".$ex->getMessage();
            //print "Strack Trace <br>".nl2br($ex->getTraceAsString());
        }
        return $data;
    }

    /**
     * updates an object in database
     * @param User $entity the object to update
     * @return int number of objects updated
     */
    public function update(User $entity): int
    {
        $numAffected = 0;
        try {
            //PDO object creation.
            $connection = $this->storeDb->getConnection();
            //query preparation.
            $stmt = $connection->prepare($this->queries['UPDATE']);
            $stmt->bindValue("id", $entity->getId(), \PDO::PARAM_INT);
            $stmt->bindValue("username", $entity->getUsername(), \PDO::PARAM_STR);
            $stmt->bindValue("password", $entity->getPassword(), \PDO::PARAM_STR);
            $stmt->bindValue("role", $entity->getRole(), \PDO::PARAM_STR);
            $stmt->bindValue("name", $entity->getName(), \PDO::PARAM_STR);
            $stmt->bindValue("surname", $entity->getSurname(), \PDO::PARAM_STR);
            //query execution.
            $success = $stmt->execute();
            //If $succes == true enter if
            if ($success) {
                $numAffected = $stmt->rowCount();
            }
        } catch (\PDOException $ex) {
            //If PDO Exception  $data = null;
            $numAffected = 0;
            //Examples of how to get errors. TODO: delete this and treat properly the exceptions.
            //print "Error Code <br>".$ex->getCode();
            //print "Error Message <br>".$ex->getMessage();
            //print "Strack Trace <br>".nl2br($ex->getTraceAsString());
        }
        return $numAffected;
    }

    /**
     * deletes an object from database
     * @param User $entity the object to delete
     * @return int number of objects deleted
     */
    public function delete(User $entity): int
    {
        $numAffected = 0;
        try {
            //PDO object creation.
            $connection = $this->storeDb->getConnection();
            //query preparation.
            $stmt = $connection->prepare($this->queries['DELETE']);
            //bind parameter value.
            $stmt->bindValue(":id", $entity->getId(), \PDO::PARAM_INT);
            //query execution.
            $success = $stmt->execute(); //bool
            //Statement data recovery.
            if ($success) {
                $numAffected = $stmt->rowCount();
            } else {
                $numAffected = 0;
            }
        } catch (\PDOException $e) {
            //If PDO Exception  $numAffected = 0;
            $numAffected = 0;
            //Examples of how to get errors. TODO: delete this and treat properly the exceptions.
            // print "Error Code <br>".$e->getCode();
            // print "Error Message <br>".$e->getMessage();
            // print "Strack Trace <br>".nl2br($e->getTraceAsString());
        }
        return $numAffected;
    }

    /**
     * defines queries to database
     */
    private function initQueries()
    {
        //query definition.
        $this->queries['SELECT_ALL'] = \sprintf(
            "select * from %s",
            self::$TABLE_NAME
        );
        $this->queries['SELECT_WHERE_ID'] = \sprintf(
            "select * from %s where id = :id",
            self::$TABLE_NAME
        );
        $this->queries['SELECT_WHERE_USERNAME'] = \sprintf(
            "select * from %s where username = :username",
            self::$TABLE_NAME
        );
        $this->queries['SELECT_WHERE_USERNAME_AND_PASSWORD'] = \sprintf(
            "select * from %s where username = :username and password = :password",
            self::$TABLE_NAME
        );
        $this->queries['INSERT'] = \sprintf(
            "insert into %s values (0, :username, :password, :role, :name, :surname)",
            self::$TABLE_NAME
        );
        $this->queries['UPDATE'] = \sprintf(
            "update %s set username = :username, password = :password, role= :role, name= :name, surname= :surname where id = :id",
            self::$TABLE_NAME
        );
        $this->queries['DELETE'] = \sprintf(
            "delete from %s where id = :id",
            self::$TABLE_NAME
        );
    }

    /**
     * gets data from resultset and builds an object with retrieved data
     * @param type $statement the resultset to get data from
     * @return mixed the object with read data or false in case of error
     */
    private function fetchToUser($statement): mixed
    {
        $row = $statement->fetch();
        if ($row) {
            $id = $row['id'];
            $username = $row['username'];
            $password = $row['password'];
            $role = $row['role'];
            $name = $row['name'];
            $surname = $row['surname'];
            return new User($id, $username, $password, $role, $name, $surname);
        } else {
            return false;
        }

    }
}

?>
<?php

namespace store\controllers;

use store\lib\ViewLoader;
use store\model\Model;
use store\model\Product;
use store\model\User;

require_once 'lib/ViewLoader.php';
require_once 'model/Model.php';
require_once "model/User.php";
require_once "model/Product.php";

/**
 * Main controller for store application.
 *
 * @author ivandmn
 */
class MainController
{
    /**
     * @var Model $model . The model to provide data services.
     */
    private Model $model;
    /**
     * @var ViewLoader $view . The loader to forward views.
     */
    private ViewLoader $view;
    /**
     * @var string $action . The action requested by client.
     */
    private string $action;

    public function __construct()
    {
        //instantiate the view loader.
        $this->view = new ViewLoader();
        //instantiate the model.
        $this->model = new Model();
    }

    /**
     * processes requests made by client.
     */
    public function processRequest()
    {
        $requestMethod = filter_input(INPUT_SERVER, 'REQUEST_METHOD');
        switch ($requestMethod) {
            case 'GET':
            case 'get':
                $this->processGet();
                break;
            case 'POST':
            case 'post':
                $this->processPost();
                break;
            default:
                $this->processError();
                break;
        }
    }

    /**
     * processes get request made by client.
     */
    private function processGet()
    {
        $this->action = "";
        if (filter_has_var(INPUT_GET, 'action')) {
            $this->action = filter_input(INPUT_GET, 'action');
        }
        switch ($this->action) {
            case 'home':  //home page.
                $this->doHomePage();
                break;
            case 'product/listAll': //list all products.
                $this->doListAllProducts();
                break;
            case 'user/listAll':
                $this->doListAllUsers();   //list all users.
                break;
            case 'product/form':
                $this->doProductForm();   //show product form.
                break;
            case 'user/form':
                $this->doUserForm();   //show user form.
                break;
            case 'login/form':
                $this->doLoginForm();   //show user form.
                break;
            case 'logout':
                $this->doLogoutPage();   //show user form.
                break;
            default:  //processing default action.
                $this->doHomePage();
                break;
        }
    }

    /**
     * processes post request made by client.
     */
    private function processPost()
    {
        $this->action = "";
        if (filter_has_var(INPUT_POST, 'action')) {
            $this->action = filter_input(INPUT_POST, 'action');
        }
        switch ($this->action) {
            case 'home':  //home page.
                $this->doHomePage();
                break;
            case 'product/search': //search product.
                $this->doSearchProductbyId();
                break;
            case 'product/add': //add product.
                $this->doAddProduct();
                break;
            case 'product/modify': //modify product.
                $this->doModifyProduct();
                break;
            case 'product/remove': //remove product.
                $this->doRemoveProduct();
                break;
            case 'product/form':
                $this->doProductForm(); //show product form.
                break;
            case 'user/search': //search user
                $this->doSearchUserbyId();
                break;
            case 'user/add': //add user.
                $this->doAddUser();
                break;
            case 'user/modify': //modify user.
                $this->doModifyUser();
                break;
            case 'user/remove':  //remove user.
                $this->doRemoveUser();
                break;
            case 'user/login': //loginUser
                $this->doLoginUser();
                break;
            case 'user/logout':
                $this->doLogout();   //show user form.
                break;
            case 'user/form':
                $this->doUserForm(); //show user form.
                break;
            default:
                $this->doHomePage(); //processing default action.
                break;
        }
    }

    /**
     * processes error.
     */
    private function processError()
    {
        trigger_error("Bad method", E_USER_NOTICE);
    }

    /**
     * displays home page content.
     */
    private function doHomePage()
    {
        $this->view->show("home.php", []);
    }

    /**
     * displays user form
     */
    private function doUserForm()
    {
        $this->view->show("user/user-form.php", ['action' => 'user/form']);
    }

    /**
     * displays login form
     */
    private function doLoginForm()
    {
        $this->view->show("login-form.php", []);
    }

    /**
     * display logout page content
     */
    private function doLogoutPage()
    {
        $this->view->show("logout.php", []);
    }

    /**
     * performs logoin users
     */
    private function doLoginUser()
    {
        //Take username and password variables from form.
        $username = filter_input(INPUT_POST, 'username');
        $password = filter_input(INPUT_POST, 'password');
        //Validate user format input with Regex
        if (filter_var($username, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^[_A-Za-z0-9]+$/")))
            && filter_var($username, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^[_A-Za-z0-9]+$/")))) {
            //Search user by username and password calling function from model
            $user = $this->model->searchUserByUsernameAndPassword($username, $password);
            //If user found enter if
            if (!empty($user)) {
                //If no user are logged enter if
                if (!isset($_SESSION['username'])) {
                    //initialize sesion variables
                    $_SESSION['username'] = $user->getUsername();
                    $_SESSION['role'] = $user->getRole();
                    //Create cookie
                    $cookieUser = $user->getUsername() . ";" . $user->getRole();
                    setcookie("user", $cookieUser);
                    //Refresh page and send user to home
                    echo "<meta http-equiv='refresh' content='0'>";
                    $this->view->show("home.php", []);
                } else {
                    //If one user is logged already send login with error message
                    $data['message'] = "Can't login if a user is logged in";
                    $data['username'] = $username;
                    $this->view->show("login-form.php", $data);
                }
            } else {
                //If user not found send to login with error message
                $data['message'] = "Username/Password not correct";
                $data['username'] = $username;
                $this->view->show("login-form.php", $data);
            }
        } else {
            //If input password/username format not correct send to to login with message error
            $data['message'] = "Username/Password not correct";
            $data['username'] = $username;
            $this->view->show("login-form.php", $data);
        }
    }

    /**
     * performs logout users
     */
    private function doLogout()
    {
        //If user is logged enter if
        if (isset($_SESSION['username'])) {
            //Destroy session
            session_destroy();
            //Destroll cookie
            setcookie("user", "", time() - 3600);
            //Refresh page and send user to home
            echo "<meta http-equiv='refresh' content='0'>";
            $this->view->show("home.php", []);
        } else {
            //If user is not logged send logout page with error message
            $data['message'] = "Can't closed session, no user Logged";
            $this->view->show("logout.php", $data);
        }
    }

    /**
     * gets all users and displays them in a proper way.
     */
    private function doListAllUsers()
    {
        //Get all users and save in array
        $userList = $this->model->searchAllUsers();
        //If array is not empty enter if
        if (!empty($userList)) {
            //Send to user-list page with usersArray
            $data["userList"] = $userList;
            $this->view->show("user/list-users.php", $data);
        } else {
            //If array is null send to user-list page with error message
            $data["userList"] = $userList;
            $data['message'] = 'No users in database';
            $this->view->show("user/list-users.php", $data);
        }
    }

    /**
     * searches a user sent by user form
     */
    private function doSearchUserbyId()
    {
        //get param 'id'
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        if ($id !== false) {
            //search user by id calling model function
            $userFound = $this->model->searchUserById($id);
            //If user found enter if
            if (!is_null($userFound)) {
                //Send user and action as parameters and send to user-form page 
                $data['user'] = $userFound;
                $data['action'] = "user/search";
                $this->view->show("user/user-form.php", $data);
            } else {
                //if user not found send to user-form page with error message
                $data['message'] = "User Not Found";
                $this->view->show("user/user-form.php", $data);
            }
        } else {
            $data['message'] = "Type a correct ID";
            $this->view->show("user/user-form.php", $data);
        }
    }

    /**
     * adds a user sent by user form
     */
    private function doAddUser()
    {
        //If session role is admin enter if
        if ($_SESSION['role'] == 'admin') {
            //Take all form variables
            $id = filter_input(INPUT_POST, 'id');
            $username = filter_input(INPUT_POST, 'username');
            $password = filter_input(INPUT_POST, 'password');
            $role = filter_input(INPUT_POST, 'role');
            $name = filter_input(INPUT_POST, 'name');
            $surname = filter_input(INPUT_POST, 'surname');
            //If is null enter if
            if ($id == "") {
                //If username have correct format enter if
                if (filter_var($username, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^[_A-Za-z0-9]+$/")))) {
                    //If password have correct format enter if
                    if (filter_var($password, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^[_A-Za-z0-9]+$/")))) {
                        //If role equals admin/staff/user enter if
                        if ($role == "admin" || $role == "staff" || $role == "registered") {
                            //If name have correct format enter if
                            if (filter_var($name, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^[A-Za-z 0-9]+$/")))) {
                                //If surname have correct format enter if
                                if (filter_var($surname, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^[A-Za-z 0-9]+$/")))) {
                                    //Search if exist user with same username
                                    $userFoundUsername = $this->model->searchUsersByUsername($username);
                                    //If user with same username not found enter if
                                    if (is_null($userFoundUsername)) {
                                        //Create user with parameters
                                        $user = new User(0, $username, $password, $role, $name, $surname);
                                        //Add user to userTable in dataBase
                                        $infoNewUser = $this->model->addUser($user);
                                        $data['messageCorrect'] = "User added correctly";
                                        $data['action'] = "user/add";
                                        $data['user'] = $infoNewUser;
                                        $this->view->show("user/user-form.php", $data);
                                    } else {
                                        //If user with same username found send to user/user-form page with error message
                                        $data['message'] = "User with this username in database, change username";
                                        $userForm = new User(null, $username, $password, $role, $name, $surname);
                                        $data['user'] = $userForm;
                                        $this->view->show("user/user-form.php", $data);
                                    }
                                } else {
                                    //If surname format is not valid send to user-form page with parameters and error missage
                                    $data['message'] = "Surname format invalid";
                                    $userForm = new User(null, $username, $password, $role, $name, $surname);
                                    $data['user'] = $userForm;
                                    $this->view->show("user/user-form.php", $data);
                                }
                            } else {
                                //If name format is not valid send to user-form page with parameters and error missage
                                $data['message'] = "Name format invalid";
                                $userForm = new User(null, $username, $password, $role, $name, $surname);
                                $data['user'] = $userForm;
                                $this->view->show("user/user-form.php", $data);
                            }
                        } else {
                            //If role not equals with defined roles send to user-form page with parameters and error missage
                            $data['message'] = "Role only can be admin/staff/registered";
                            $userForm = new User(null, $username, $password, $role, $name, $surname);
                            $data['user'] = $userForm;
                            $this->view->show("user/user-form.php", $data);
                        }
                    } else {
                        //If password format is not valid send to user-form page with parameters and error missage
                        $data['message'] = "Password format invalid";
                        $userForm = new User(null, $username, $password, $role, $name, $surname);
                        $data['user'] = $userForm;
                        $this->view->show("user/user-form.php", $data);
                    }
                } else {
                    //If name format is not valid send to user-form page with parameters and error missage
                    $data['message'] = "Username format invalid";
                    $userForm = new User(null, $username, $password, $role, $name, $surname);
                    $data['user'] = $userForm;
                    $this->view->show("user/user-form.php", $data);
                }
            } else {
                //If id is not null send to user-form page with parameters and error missage
                $data['message'] = "ID must be empty for create user";
                $userForm = new User(null, $username, $password, $role, $name, $surname);
                $data['user'] = $userForm;
                $this->view->show("user/user-form.php", $data);
            }
        } else {
            //If Permission denied send to user-form page with missage error
            $data['message'] = "Permission denied";
            $this->view->show("user/user-form.php", $data);
        }
    }

    /**
     * modifies a user sent by user form
     */
    private function doModifyUser()
    {
        //If session role is admin enter if
        if ($_SESSION['role'] == 'admin') {
            //Take all form variables
            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
            $username = filter_input(INPUT_POST, 'username');
            $password = filter_input(INPUT_POST, 'password');
            $role = filter_input(INPUT_POST, 'role');
            $name = filter_input(INPUT_POST, 'name');
            $surname = filter_input(INPUT_POST, 'surname');
            //If id is not null and pass filter validate int enter if
            if ($id != false && $id >= 0) {
                //If username have correct format enter if
                if (filter_var($username, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^[_A-Za-z0-9]+$/")))) {
                    //If password have correct format enter if
                    if (filter_var($password, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^[_A-Za-z0-9]+$/")))) {
                        //If role equals admin/staff/user enter if
                        if ($role == "admin" || $role == "staff" || $role == "registered") {
                            //If name have correct format enter if
                            if (filter_var($name, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^[A-Za-z 0-9]+$/")))) {
                                //If surname have correct format enter if
                                if (filter_var($surname, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^[A-Za-z 0-9]+$/")))) {
                                    //Search if user with same id exist
                                    $userID = $this->model->searchUserById($id);
                                    //If exist in database enter if
                                    if (!is_null($userID)) {
                                        if ($userID->getUsername() != $username) {
                                            //Search if user with same username exist
                                            $userFoundusername = $this->model->searchUsersByUsername($username);
                                            //If no user found enter if
                                            if (is_null($userFoundusername)) {
                                                //Modify user and send message to user-form page
                                                $userModified = new User($id, $username, $password, $role, $name, $surname);
                                                $numberModified = $this->model->modifyUser($userModified);
                                                $data['messageCorrect'] = "User Modified correctly";
                                                $data['user'] = $userModified;
                                                $data['action'] = "user/modify";
                                                $this->view->show("user/user-form.php", $data);
                                            } else {
                                                //If user with this username is in database
                                                $data['message'] = "This username is already taken, change it please";
                                                $userForm = new User($id, $username, $password, $role, $name, $surname);
                                                $data['user'] = $userForm;
                                                $this->view->show("user/user-form.php", $data);
                                            }
                                        } else {
                                            //Modify user and send message to user-form page
                                            $userModified = new User($id, $username, $password, $role, $name, $surname);
                                            $numberModified = $this->model->modifyUser($userModified);
                                            $data['messageCorrect'] = "User Modified correctly";
                                            $data['user'] = $userModified;
                                            $data['action'] = "user/modify";
                                            $this->view->show("user/user-form.php", $data);
                                        }
                                    } else {
                                        //If user with this id is not in data base
                                        $data['message'] = "No user found with this ID for modify";
                                        $userForm = new User($id, $username, $password, $role, $name, $surname);
                                        $data['user'] = $userForm;
                                        $this->view->show("user/user-form.php", $data);
                                    }
                                } else {
                                    //If surname format is not valid send to user-form page with parameters and error missage
                                    $data['message'] = "Surname format invalid";
                                    $userForm = new User($id, $username, $password, $role, $name, $surname);
                                    $data['user'] = $userForm;
                                    $this->view->show("user/user-form.php", $data);
                                }
                            } else {
                                //If name format is not valid send to user-form page with parameters and error missage
                                $data['message'] = "Name format invalid";
                                $userForm = new User($id, $username, $password, $role, $name, $surname);
                                $data['user'] = $userForm;
                                $this->view->show("user/user-form.php", $data);
                            }
                        } else {
                            //If role not equals with defined roles send to user-form page with parameters and error missage
                            $data['message'] = "Role only can be admin/staff/registered";
                            $userForm = new User($id, $username, $password, $role, $name, $surname);
                            $data['user'] = $userForm;
                            $this->view->show("user/user-form.php", $data);
                        }
                    } else {
                        //If password format is not valid send to user-form page with parameters and error missage
                        $data['message'] = "Password format invalid";
                        $userForm = new User($id, $username, $password, $role, $name, $surname);
                        $data['user'] = $userForm;
                        $this->view->show("user/user-form.php", $data);
                    }
                } else {
                    //If name format is not valid send to user-form page with parameters and error missage
                    $data['message'] = "Username format invalid";
                    $userForm = new User($id, $username, $password, $role, $name, $surname);
                    $data['user'] = $userForm;
                    $this->view->show("user/user-form.php", $data);
                }
            } else {
                //If id is null or not pass filter send to user-form page with parameters and error missage
                $data['message'] = "ID must be a positive number";
                $userForm = new User($id, $username, $password, $role, $name, $surname);
                $data['user'] = $userForm;
                $this->view->show("user/user-form.php", $data);
            }
        } else {
            //If Permission denied send to user-form page with missage error
            $data['message'] = "Permission denied";
            $this->view->show("user/user-form.php", $data);
        }
    }

    /**
     * removes a user sent by user form
     */
    private function doRemoveUser()
    {
        //If session role is admin enter if
        if ($_SESSION['role'] == 'admin') {
            //get param 'id'
            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
            //If id is not null and pass filter validate int enter if
            if ($id !== false) {
                //Search user with same id
                $userFound = $this->model->searchUserById($id);
                //If user with same id exist enter if
                if (!is_null($userFound)) {
                    //Delete user
                    $usersDeleted = $this->model->deleteUser($id);
                    $data['action'] = 'user/remove';
                    $data['messageCorrect'] = "User with id " . $id . " removed corretly";
                    $this->view->show("user/user-form.php", $data);
                } else {
                    //If not send message error
                    $data['message'] = "User not found to remove it";
                    $this->view->show("user/user-form.php", $data);
                }
            } else {
                //If not send message error
                $data['message'] = "Id must be a number";
                $this->view->show("user/user-form.php", $data);
            }
        } else {
            //If Permission denied send to user-form page with missage error
            $data['message'] = "Permission denied";
            $this->view->show("user/user-form.php", $data);
        }
    }

    /**
     * displays product form
     */
    private function doProductForm()
    {
        $this->view->show("product/product-form.php", ['action' => 'product/form']);
    }

    /**
     * displays list of all products
     */
    private function doListAllProducts()
    {
        //Get all products and save in array
        $productList = $this->model->searchAllProducts();
        //If array is not empty enter if
        if (!empty($productList)) {
            //Send to product-list page with productArray
            $data["productList"] = $productList;
            $this->view->show("product/list-products.php", $data);
        } else {
            //If array is null send to user-list page with error message
            $data["productList"] = $productList;
            $data['message'] = 'No products in database';
            $this->view->show("product/list-products.php", $data);
        }
    }

    /**
     * searches a product sent by product form
     */
    public function doSearchProductbyId()
    {
        //get param 'id'
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        if ($id !== false) {
            //search product by id calling model function
            $productFound = $this->model->searchProductById($id);
            //If product found enter if
            if (!is_null($productFound)) {
                //Send product and action as parameters and send to product-form page 
                $data['product'] = $productFound;
                $data['action'] = "product/search";
                $this->view->show("product/product-form.php", $data);
            } else {
                //If product not found send to product-form page with error message
                $data['message'] = "Product Not Found";
                $this->view->show("product/product-form.php", $data);
            }
        } else {
            $data['message'] = "Type a correct ID";
            $this->view->show("product/product-form.php", $data);
        }
    }

    /**
     * adds product sent by product form
     */
    private function doAddProduct()
    {
        //If session role is admin/staff enter if
        if ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'staff') {
            //Take all form variables
            $id = filter_input(INPUT_POST, 'id');
            $description = filter_input(INPUT_POST, 'description');
            $price = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_FLOAT);
            $stock = filter_input(INPUT_POST, 'stock', FILTER_VALIDATE_INT);
            //If id is null enter if
            if ($id == "") {
                //If description is not null enter if
                if ($description != "") {
                    //If price is not null and pass filter validate float enter if
                    if ($price != false && $price > 0) {
                        //If stock is not null and pass filter validate int enter if
                        if ($stock != false && $stock > 0) {
                            //Create product with parameters
                            $product = new Product(0, $description, $price, $stock);
                            //Add product to product Array
                            $ProductAdded = $this->model->addProduct($product);
                            //Send to product-form page with info message
                            $data['messageCorrect'] = "Product added correctly";
                            $data['action'] = "product/add";
                            $data['product'] = $ProductAdded;
                            $this->view->show("product/product-form.php", $data);
                        } else {
                            //If stock is null or not pass filter send to product-form page with parameters and error missage
                            $data['message'] = "Stock must be a postivie number";
                            $ProductForm = new Product(null, $description, $price, 1);
                            $data['product'] = $ProductForm;
                            $this->view->show("product/product-form.php", $data);
                        }
                    } else {
                        //If price is null or not pass filter send to product-form page with parameters and error missage
                        $data['message'] = "Price must be a postivie number";
                        $ProductForm = new Product(null, $description, 1, $stock);
                        $data['product'] = $ProductForm;
                        $this->view->show("product/product-form.php", $data);
                    }
                } else {
                    //If description is null send to product-form page with parameters and error missage
                    $data['message'] = "Type a description";
                    $ProductForm = new Product(null, $description, $price, $stock);
                    $data['product'] = $ProductForm;
                    $this->view->show("product/product-form.php", $data);
                }
            } else {
                //If id is not null send to product-form page with parameters and error missage
                $data['message'] = "ID must be empty for create product";
                $ProductForm = new Product(null, $description, $price, $stock);
                $data['product'] = $ProductForm;
                $this->view->show("product/product-form.php", $data);
            }
        } else {
            //If Permission denied send to product-form page with missage error
            $data['message'] = "Permission denied";
            $this->view->show("product/product-form.php", $data);
        }
    }

    /**
     * modifies product sent by product form
     */
    private function doModifyProduct()
    {
        //If session role is admin/staff enter if
        if ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'staff') {
            //Take all form variables
            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
            $description = filter_input(INPUT_POST, 'description');
            $price = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_FLOAT);
            $stock = filter_input(INPUT_POST, 'stock', FILTER_VALIDATE_INT);
            //If id is not null and pass filter validate int enter if
            if ($id != false && $id >= 0) {
                //If price is not null and pass filter validate float enter if
                if ($price != false && $price > 0) {
                    //If stock is not null and pass filter validate int enter if
                    if ($stock != false && $stock > 0) {
                        //Search if exist product with same id
                        $ProductFoundId = $this->model->searchProductById($id);
                        //If exist enter if
                        if (!is_null($ProductFoundId)) {
                            //Create product with parameters
                            $product = new Product($id, $description, $price, $stock);
                            //Modify product
                            $this->model->modifyProduct($product);
                            //Send to product-form page with info message
                            $data['messageCorrect'] = "Product modified correctly";
                            $data['action'] = "product/modify";
                            $ProductForm = new Product($id, $description, $price, $stock);
                            $data['product'] = $ProductForm;
                            $this->view->show("product/product-form.php", $data);
                        } else {
                            //If product not found with same id send to product-form page with error message
                            $data['message'] = "No product found with this ID for modify";
                            $ProductForm = new Product($id, $description, $price, $stock);
                            $data['product'] = $ProductForm;
                            $this->view->show("product/product-form.php", $data);
                        }
                    } else {
                        //If stock is null or not pass filter send to product-form page with parameters and error missage
                        $data['message'] = "Stock must be a postivie number";
                        $ProductForm = new Product($id, $description, $price, 1);
                        $data['product'] = $ProductForm;
                        $this->view->show("product/product-form.php", $data);
                    }
                } else {
                    //If price is null or not pass filter send to product-form page with parameters and error missage
                    $data['message'] = "Price must be a postivie number";
                    $ProductForm = new Product($id, $description, 1, $stock);
                    $data['product'] = $ProductForm;
                    $this->view->show("product/product-form.php", $data);
                }
            } else {
                //If id is null or not pass filter send to product-form page with parameters and error missage
                $data['message'] = "ID must be a positive number";
                $ProductForm = new Product($id, $description, $price, $stock);
                $data['product'] = $ProductForm;
                $this->view->show("product/product-form.php", $data);
            }
        } else {
            //If Permission denied send to product-form page with missage error
            $data['message'] = "Permission denied";
            $this->view->show("product/product-form.php", $data);
        }
    }

    /**
     * removes product sent by product form
     */
    private function doRemoveProduct()
    {
        //If session role is admin/staff enter if
        if ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'staff') {
            //get param 'id'
            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
            //If id is not null and pass filter validate int enter if
            if ($id !== false) {
                //Search product with same id
                $productFound = $this->model->searchProductById($id);
                //If product with same id exist enter if
                if (!is_null($productFound)) {
                    //Delete product
                    $this->model->removeProduct($id);
                    $data['action'] = 'product/remove';
                    $data['messageCorrect'] = "Product with id " . $id . " removed corretly";
                    $this->view->show("product/product-form.php", $data);
                } else {
                    //If not send message error
                    $data['message'] = "Product not found to remove it";
                    $this->view->show("product/product-form.php", $data);
                }
            } else {
                //If not send message error
                $data['message'] = "Id must be a number";
                $this->view->show("product/product-form.php", $data);
            }
        } else {
            //If Permission denied send to product-form page with missage error
            $data['message'] = "Permission denied";
            $this->view->show("product/product-form.php", $data);
        }
    }
}
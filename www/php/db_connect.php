<?php

    /*
    *
    *
    */

    class DBConn {
        //Database variables
        const db_name = "proglang_db";
        const db_hostname = "localhost";
        const db_username = "proglang_user";
        const db_password = "This is a very long password, that cannot be unhashed easily.";

        public $conn = NULL;
        public $conn_err = NULL;

        public function __construct() {
            //error_reporting(E_ALL ^ E_WARNING);
            $conn = new mysqli(self::db_hostname, self::db_username, self::db_password, self::db_name);
            $this->conn = $conn;
            $conn_err = $conn->connect_error;
            $this->conn_err = $conn_err;
            //error_reporting(E_ALL);
        }

        public function __destruct() {
            $this->conn->close();
        }

        // Gets whether or not an error has occurred
        public function conn_err() {
            return $this->conn_err;
        }

        // Verifies a user's credentials and returns a boolean
        public function verify_user($username, $password) {
            if (!$this->conn_err) {
                $stmt = $this->conn->prepare('SELECT * FROM user WHERE username = ?');
                $stmt->bind_param('s', $username);
                $stmt->execute();
                $result = $stmt->get_result();
                $stmt->close();

                if ($result != NULL) {
                    $result = $result->fetch_assoc();
                    $passHash = $result['passwordHash'];

                    return password_verify($password, $passHash);
                    unset($password, $passHash);

                } else {
                    return false;
                }
            }
        }

        // Gets an array of all keys in any table
        public function get_where($tblname, $colname, $valtype, $value) {
            if (!$this->conn_err) {
                $stmt = $this->conn->prepare('SELECT * FROM ' . $tblname . ' WHERE ' . $colname . ' = ?');
                $stmt->bind_param($valtype, $value);
                $stmt->execute();
                $result = $stmt->get_result();
                $stmt->close();

                if ($result != NULL) {
                    $result = $result->fetch_assoc();

                    // To keep passwords secure
                    if (array_key_exists('passwordHash', $result)) {
                        unset($result['passwordHash']);
                    }

                    return $result;

                } else {
                    return NULL;
                }
            }
        }

        // Gets a single data cell
        public function get_cell($query, $cellname, $valtype, $value) {
            if (!$this->conn_err) {
                $stmt = $this->conn->prepare($query . ' = ?');
                $stmt->bind_param($valtype, $value);
                $stmt->execute();
                $result = $stmt->get_result();
                $stmt->close();

                if ($result != NULL) {
                    $result = $result->fetch_assoc();

                    if (strcmp($cellname, 'passwordHash') != 0) {
                        return $result[$cellname];
                    }

                } else {
                    return NULL;
                }
            }
        }

        // Gets full result set without only selecting the top row
        public function get_full($query, $valtype, $value) {
            if (!$this->conn_err) {
                $stmt = $this->conn->prepare($query . ' = ?');
                $stmt->bind_param($valtype, $value);
                $stmt->execute();
                $result = $stmt->get_result();
                $stmt->close();

                if ($result != NULL) {

                    return $result;

                } else {
                    return NULL;
                }
            }
        }

        // Gets an array of table contents using a specific SQL-Query
        public function get_query($query, $valtype, $value) {
            return $this->get_full($query, $valtype, $value)->fetch_assoc();
        }
    }

    // Define Value Type Enumerator
    class ValType {
        const INTEGER = 'i';
        const INT = 'i';
        const DOUBLE = 'd';
        const STRING = 's';
        const BLOB = 'b';
    }

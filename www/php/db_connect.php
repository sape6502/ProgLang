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
        public function get_table_where($tblname, $colname, $value) {
            if (!$this->conn_err) {
                $stmt = $this->conn->prepare('SELECT * FROM ' . $tblname . ' WHERE ' . $colname . ' = ?');
                $stmt->bind_param('s', $value);
                $stmt->execute();
                $result = $stmt->get_result();
                $stmt->close();

                if ($result != NULL) {
                    $result = $result->fetch_assoc();

                    // To keep passwords secure
                    if (strcmp($tblname, 'user')) {
                        unset($result['passwordHash']);
                    }

                    return $result;

                } else {
                    return NULL;
                }
            }
        }
    }

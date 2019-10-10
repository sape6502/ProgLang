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
        public function get_cell($query, $valtype, $value) {
            if (!$this->conn_err) {
                $stmt = $this->conn->prepare($query);
                $stmt->bind_param($valtype, $value);
                $stmt->execute();
                $result = $stmt->get_result();
                $stmt->close();

                if ($result != NULL) {
                    $result = $result->fetch_assoc();
                    $cellname = array_keys($result)[0];

                    if (strcmp($cellname, 'passwordHash') != 0) {
                        return $result[$cellname];
                    }

                } else {
                    return NULL;
                }
            }
        }

        // Gets full result set without only selecting the top row
        public function get_full() {
            $argc = func_num_args();

            if ($argc < 3) {
                return NULL;
            } else if (($argc - 1) % 2 != 0) {
                return NULL;
            } else {

                $query = func_get_arg(0);

                // Generate valtype string
                $data[0] = '';
                for ($i=1; $i<$argc; $i+=2) {
                    $data[0] = $data[0] . func_get_arg($i);
                }

                // Add data to enter
                for ($i=2; $i<$argc; $i+=2) {
                    array_push($data, func_get_arg($i));
                }

                // Connect to database and insert data
                if (!$this->conn_err) {

                    // Insert new row
                    $stmt = $this->conn->prepare($query);
                    call_user_func_array(array($stmt, 'bind_param'), $this->SqlArrayReferenceValues($data));
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $stmt->close();

                    return $result;
                }

            }
        }

        // Gets an array of table contents using a specific SQL-Query
        public function get_query() {
            return call_user_func_array(array($this, 'get_full'), func_get_args())->fetch_assoc();
        }

        // Updates a cell to a language
        public function update_cell($tblname, $newcol, $newvaltype, $newval, $colname, $valtype, $value) {
            if (!$this->conn_err) {
                $stmt = $this->conn->prepare('UPDATE ' . $tblname . ' SET ' . $newcol . ' = ? WHERE ' . $colname . ' = ?');
                $stmt->bind_param($newvaltype . $valtype, $newval, $value);
                $stmt->execute();
                $stmt->close();
            }
        }

        // Gets the references of arrays
        private function SqlArrayReferenceValues($arr){
            	if (strnatcmp(phpversion(),'5.3') >= 0) {
                    $refs = array();
                    foreach($arr as $key => $value)
                    $refs[$key] = &$arr[$key];
                    return $refs;
                }
            return $arr;
        }

        // Drop a row from a table
        public function delete_row($tbl, $col, $valtype, $val) {
            if (!$this->conn_err) {
                $stmt = $this->conn->prepare('DELETE FROM ' . $tbl . ' WHERE ' . $col . ' = ?');
                $stmt->bind_param($valtype, $val);
                $stmt->execute();
                $stmt->close();
            }
        }

        // Insert new row into a table
        public function insert_row() {
            $argc = func_num_args();

            if ($argc < 4) {
                return false;
            } else if (($argc - 1) % 3 != 0) {
                return false;
            } else {

                $table = func_get_arg(0);
                $sqlstring = 'INSERT INTO ' . $table . ' (';

                // Add column names to statement
                for ($i=1; $i<$argc; $i+=3) {
                    if ($i < $argc - 3) {
                        $sqlstring = $sqlstring . func_get_arg($i) . ', ';
                    } else {
                        $sqlstring = $sqlstring . func_get_arg($i) . ') VALUES (';
                    }
                }

                // Make valtype string
                $data[0] = '';
                for ($i=2; $i<$argc; $i+=3) {
                    $data[0] = $data[0] . func_get_arg($i);
                }

                // Add data to enter
                for ($i=3; $i<$argc; $i+=3) {
                    array_push($data, func_get_arg($i));
                    if ($i < $argc - 3) {
                        $sqlstring = $sqlstring . '?, ';
                    } else {
                        $sqlstring = $sqlstring . '?);';
                    }
                }

                // Connect to database and insert data
                if (!$this->conn_err) {

                    // Insert new row
                    $stmt = $this->conn->prepare($sqlstring);
                    call_user_func_array(array($stmt, 'bind_param'), $this->SqlArrayReferenceValues($data));
                    $stmt->execute();
                    $stmt->close();

                    return true;
                }

            }
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

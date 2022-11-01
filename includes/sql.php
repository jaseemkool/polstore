<?php
require_once('includes/load.php');

/*--------------------------------------------------------------*/
/* Function for find all database table rows by table name
/*--------------------------------------------------------------*/
function find_all($table)
{
  global $db;
  if (tableExists($table)) {
    return find_by_sql("SELECT * FROM " . $db->escape($table));
  }
}
/*--------------------------------------------------------------*/
/* Function for Perform queries
/*--------------------------------------------------------------*/
function find_by_sql($sql)
{
  global $db;
  $result = $db->query($sql);
  $result_set = $db->while_loop($result);
  return $result_set;
}
/*--------------------------------------------------------------*/
/*  Function for Find data from table by id
/*--------------------------------------------------------------*/
function find_by_id($table, $id)
{
  global $db;
  $id = (int)$id;
  if (tableExists($table)) {
    $sql = $db->query("SELECT * FROM {$db->escape($table)} WHERE id='{$db->escape($id)}' LIMIT 1");
    if ($result = $db->fetch_assoc($sql))
      return $result;
    else
      return null;
  }
}
/*--------------------------------------------------------------*/
/* Function for Delete data from table by id
/*--------------------------------------------------------------*/
function delete_by_id($table, $id)
{
  global $db;
  if (tableExists($table)) {
    $sql = "DELETE FROM " . $db->escape($table);
    $sql .= " WHERE id=" . $db->escape($id);
    $sql .= " LIMIT 1";
    $db->query($sql);
    return ($db->affected_rows() === 1) ? true : false;
  }
}
/*--------------------------------------------------------------*/
/* Function for Count id  By table name
/*--------------------------------------------------------------*/

function count_by_id($table)
{
  global $db;
  if (tableExists($table)) {
    $sql    = "SELECT COUNT(id) AS total FROM " . $db->escape($table);
    $result = $db->query($sql);
    return ($db->fetch_assoc($result));
  }
}
/*--------------------------------------------------------------*/
/* Determine if database table exists
/*--------------------------------------------------------------*/
function tableExists($table)
{
  global $db;
  $table_exit = $db->query('SHOW TABLES FROM ' . DB_NAME . ' LIKE "' . $db->escape($table) . '"');
  if ($table_exit) {
    if ($db->num_rows($table_exit) > 0)
      return true;
    else
      return false;
  }
}
/*--------------------------------------------------------------*/
/* Login with the data provided in $_POST,
 /* coming from the login form.
/*--------------------------------------------------------------*/
function authenticate($username = '', $password = '')
{
  global $db;
  $username = $db->escape($username);
  $password = $db->escape($password);
  $sql  = sprintf("SELECT id,username,password,user_level FROM users WHERE username ='%s' LIMIT 1", $username);
  $result = $db->query($sql);
  if ($db->num_rows($result)) {
    $user = $db->fetch_assoc($result);
    $password_request = sha1($password);
    if ($password_request === $user['password']) {
      return $user['id'];
    }
  }
  return false;
}
/*--------------------------------------------------------------*/
/* Login with the data provided in $_POST,
  /* coming from the login_v2.php form.
  /* If you used this method then remove authenticate function.
 /*--------------------------------------------------------------*/
function authenticate_v2($username = '', $password = '')
{
  global $db;
  $username = $db->escape($username);
  $password = $db->escape($password);
  $sql  = sprintf("SELECT id,username,password,user_level FROM users WHERE username ='%s' LIMIT 1", $username);
  $result = $db->query($sql);
  if ($db->num_rows($result)) {
    $user = $db->fetch_assoc($result);
    $password_request = sha1($password);
    if ($password_request === $user['password']) {
      return $user;
    }
  }
  return false;
}


/*--------------------------------------------------------------*/
/* Find current log in user by session id
  /*--------------------------------------------------------------*/
function current_user()
{
  static $current_user;
  global $db;
  if (!$current_user) {
    if (isset($_SESSION['user_id'])) :
      $user_id = intval($_SESSION['user_id']);
      $current_user = find_by_id('users', $user_id);
    endif;
  }
  return $current_user;
}
/*--------------------------------------------------------------*/
/* Find all user by
  /* Joining users table and user gropus table
  /*--------------------------------------------------------------*/
function find_all_user()
{
  global $db;
  $results = array();
  $sql = "SELECT u.id,u.name,u.username,u.user_level,u.status,u.last_login,";
  $sql .= "g.group_name ";
  $sql .= "FROM users u ";
  $sql .= "LEFT JOIN user_groups g ";
  $sql .= "ON g.group_level=u.user_level ORDER BY u.name ASC";
  $result = find_by_sql($sql);
  return $result;
}
/*--------------------------------------------------------------*/
/* Function to update the last log in of a user
  /*--------------------------------------------------------------*/

function updateLastLogIn($user_id)
{
  global $db;
  $date = make_date();
  $sql = "UPDATE users SET last_login='{$date}' WHERE id ='{$user_id}' LIMIT 1";
  $result = $db->query($sql);
  return ($result && $db->affected_rows() === 1 ? true : false);
}

/*--------------------------------------------------------------*/
/* Find all Group name
  /*--------------------------------------------------------------*/
function find_by_groupName($val)
{
  global $db;
  $sql = "SELECT group_name FROM user_groups WHERE group_name = '{$db->escape($val)}' LIMIT 1 ";
  $result = $db->query($sql);
  return ($db->num_rows($result) === 0 ? true : false);
}
/*--------------------------------------------------------------*/
/* Find group level
  /*--------------------------------------------------------------*/
function find_by_groupLevel($level)
{
  global $db;
  $sql = "SELECT group_level FROM user_groups WHERE group_level = '{$db->escape($level)}' LIMIT 1 ";
  $result = $db->query($sql);
  return ($db->num_rows($result) === 0 ? true : false);
}
/*--------------------------------------------------------------*/
/* Function for cheaking which user level has access to page
  /*--------------------------------------------------------------*/
function page_require_level($require_level)
{
  global $session;
  $current_user = current_user();
  $login_level = find_by_groupLevel($current_user['user_level']);
  if ($login_level) {
    //if user not login
    if (!$session->isUserLoggedIn(true)) :
      $session->msg('d', 'Please login...');
      redirect('index.php', false);
    //if Group status Deactive
    //elseif ($login_level['group_status'] == '0') :
    elseif ($login_level['group_status'] === '0') :
      $session->msg('d', 'This level user has been banned!');
      redirect('home.php', false);
    //cheackin log in User level and Require level is Less than or equal to
    elseif ($current_user['user_level'] <= (int)$require_level) :
      return true;
    else :
      $session->msg("d", "Sorry! you dont have permission to view the page.");
      redirect('home.php', false);
    endif;
  }
}
/*--------------------------------------------------------------*/
/* Function for Finding all product name
   /* JOIN with categorie  and media database table
   /*--------------------------------------------------------------*/
function join_product_table()
{
  global $db;
  $sql   = " SELECT p.id,p.title,p.serial_number,p.remarks,p.media_id,p.date,a.name AS availability,s.name AS status,c.name";
  $sql  .= " AS categorie, b.name AS brand, md.name AS model,m.file_name AS image, l.office AS location, dp.name AS dep_point";
  $sql  .= " FROM products p";
  $sql  .= " LEFT JOIN categories c ON c.id = p.categorie_id";
  $sql  .= " LEFT JOIN brand b ON b.id = p.brand_id";
  $sql  .= " LEFT JOIN model md ON md.id = p.model_id";
  $sql  .= " LEFT JOIN media m ON m.id = p.media_id";
  $sql  .= " LEFT JOIN availability a ON a.id = p.availability_id";
  $sql  .= " LEFT JOIN status s ON s.id = p.status_id";
  $sql  .= " LEFT JOIN location l ON l.id = p.location_id";
  $sql  .= " LEFT JOIN dep_point dp ON dp.id = p.dep_point_id";
  $sql  .= " WHERE active = TRUE";
  $sql  .= " ORDER BY p.id ASC";
  return find_by_sql($sql);
}
/*--------------------------------------------------------------*/
/* Function for Finding all product name
  /* Request coming from ajax.php for auto suggest
  /*--------------------------------------------------------------*/

function find_product_by_title($product_name)
{
  global $db;
  $p_name = remove_junk($db->escape($product_name));
  $sql   = "SELECT p.*, l.office AS office, d.name AS dep_point FROM products p";
  $sql  .= " LEFT JOIN location l ON l.id = p.location_id";
  $sql .= " LEFT JOIN dep_point d ON d.id = p.dep_point_id";
  $sql  .= " WHERE title like '%$p_name%' OR serial_number LIKE '%$p_name%'";
  //$sql  .= "  LIMIT 5";

  $result = find_by_sql($sql);
  return $result;
}

/*--------------------------------------------------------------*/
/* Function for Finding all product info by product title
  /* Request coming from ajax.php
  /*--------------------------------------------------------------*/
function find_all_product_info_by_sl($serial_number)
{
  global $db;
  $sql  = "SELECT p.*, l.office AS office, d.name AS dep_point FROM products p";
  $sql .= " LEFT JOIN location l ON l.id = p.location_id";
  $sql .= " LEFT JOIN dep_point d ON d.id = p.dep_point_id";
  $sql .= " WHERE serial_number ='{$serial_number}'";
  //$sql .=" LIMIT 1";
  return find_by_sql($sql);
}

/*--------------------------------------------------------------*/
/* Function for Display Recent product Added
  /*--------------------------------------------------------------*/
function find_recent_product_added($limit)
{
  global $db;
  $sql   = " SELECT p.id,p.title,p.sale_price,p.media_id,c.name AS categorie,";
  $sql  .= "m.file_name AS image FROM products p";
  $sql  .= " LEFT JOIN categories c ON c.id = p.categorie_id";
  $sql  .= " LEFT JOIN media m ON m.id = p.media_id";
  $sql  .= " ORDER BY p.id DESC LIMIT " . $db->escape((int)$limit);
  return find_by_sql($sql);
}
/*--------------------------------------------------------------*/
/* Function for Find Highest saleing Product
 /*--------------------------------------------------------------*/
function find_higest_saleing_product($limit)
{
  global $db;
  $sql  = "SELECT p.title, COUNT(s.product_id) AS totalSold, SUM(s.qty) AS totalQty";
  $sql .= " FROM sales s";
  $sql .= " LEFT JOIN products p ON p.id = s.product_id ";
  $sql .= " GROUP BY s.product_id";
  $sql .= " ORDER BY SUM(s.qty) DESC LIMIT " . $db->escape((int)$limit);
  return $db->query($sql);
}
/*--------------------------------------------------------------*/
/* Function for find all sales
 /*--------------------------------------------------------------*/
function find_all_transactions()
{
  global $db;
  $sql  = "SELECT t.id,p.id AS product_id,p.title,p.serial_number,l.office,dp.name AS dep_point,t.date,t.remarks";
  $sql .= " FROM transaction t";
  $sql .= " LEFT JOIN products p ON t.product_id = p.id";
  $sql .= " LEFT JOIN location l ON t.location_id = l.id";
  $sql .= " LEFT JOIN dep_point dp ON t.dep_point_id = dp.id";
  $sql .= " ORDER BY t.date DESC, t.id DESC";
  return find_by_sql($sql);
}
/*--------------------------------------------------------------*/
/* Function for Display Recent sale
 /*--------------------------------------------------------------*/
function find_recent_sale_added($limit)
{
  global $db;
  $sql  = "SELECT s.id,s.qty,s.price,s.date,p.title";
  $sql .= " FROM sales s";
  $sql .= " LEFT JOIN products p ON s.product_id = p.id";
  $sql .= " ORDER BY s.date DESC LIMIT " . $db->escape((int)$limit);
  return find_by_sql($sql);
}
/*--------------------------------------------------------------*/
/* Function for Generate sales report by two dates
/*--------------------------------------------------------------*/
function find_sale_by_dates($start_date, $end_date)
{
  global $db;
  $start_date  = date("Y-m-d", strtotime($start_date));
  $end_date    = date("Y-m-d", strtotime($end_date));
  $sql  = "SELECT s.date, p.title,p.sale_price,p.buy_price,";
  $sql .= "COUNT(s.product_id) AS total_records,";
  $sql .= "SUM(s.qty) AS total_sales,";
  $sql .= "SUM(p.sale_price * s.qty) AS total_saleing_price,";
  $sql .= "SUM(p.buy_price * s.qty) AS total_buying_price ";
  $sql .= "FROM sales s ";
  $sql .= "LEFT JOIN products p ON s.product_id = p.id";
  $sql .= " WHERE s.date BETWEEN '{$start_date}' AND '{$end_date}'";
  $sql .= " GROUP BY DATE(s.date),p.title";
  $sql .= " ORDER BY DATE(s.date) DESC";
  return $db->query($sql);
}
/*--------------------------------------------------------------*/
/* Function for Generate Daily sales report
/*--------------------------------------------------------------*/
function  dailyTransactions($year, $month, $day)
{
  global $db;
  $sql  = "SELECT t.id,p.id AS product_id,p.title,p.serial_number,l.office,dp.name AS dep_point,";
  $sql .= " DATE_FORMAT(t.date, '%Y-%m-%e') AS date,t.remarks";
  $sql .= " FROM transaction t";
  $sql .= " LEFT JOIN products p ON t.product_id = p.id";
  $sql .= " LEFT JOIN location l ON t.location_id = l.id";
  $sql .= " LEFT JOIN dep_point dp ON t.dep_point_id = dp.id";
  $sql .= " WHERE DATE_FORMAT(t.date, '%Y-%m-%d' ) = '{$year}-{$month}-{$day}'";
  $sql .= " GROUP BY DATE_FORMAT( t.date,  '%c' ),t.id";
  $sql .= " ORDER BY date_format(t.date, '%c' ) ASC";
  return find_by_sql($sql);
}
/*--------------------------------------------------------------*/
/* Function for Generate Monthly Transactions report
/*--------------------------------------------------------------*/
function  monthlyTransactions($year, $month)
{
  global $db;
  $sql  = "SELECT t.id,p.id AS product_id,p.title,p.serial_number,l.office,dp.name AS dep_point,";
  $sql .= " DATE_FORMAT(t.date, '%Y-%m-%e') AS date,t.remarks";
  $sql .= " FROM transaction t";
  $sql .= " LEFT JOIN products p ON t.product_id = p.id";
  $sql .= " LEFT JOIN location l ON t.location_id = l.id";
  $sql .= " LEFT JOIN dep_point dp ON t.dep_point_id = dp.id";
  $sql .= " WHERE DATE_FORMAT(t.date, '%Y-%m' ) = '{$year}-{$month}'";
  $sql .= " GROUP BY DATE_FORMAT( t.date,  '%c' ),t.id";
  $sql .= " ORDER BY date_format(t.date, '%c' ) ASC";

  return find_by_sql($sql);
}

/*-------------------------------------------------------------*/
/* My own functions below
/*--------------------------------------------------------------*/

/*--------------------------------------------------------------*/
/* Function for Update product location,dip pint, status after transaction added
/*--------------------------------------------------------------*/
function update_product_location($location_id, $p_id)
{
  global $db;
  $location = (int) $location_id;
  $id  = (int)$p_id;
  $sql = "UPDATE products SET location_id = '{$location}' WHERE id = '{$id}'";
  $result = $db->query($sql);
  return ($db->affected_rows() === 1 ? true : false);
}

function update_product_dip_point($dep_point_id, $p_id)
{
  global $db;
  $qty = (int) $dep_point_id;
  $id  = (int)$p_id;
  $sql = "UPDATE products SET dep_point_id = '{$qty}' WHERE id = '{$id}'";
  $result = $db->query($sql);
  return ($db->affected_rows() === 1 ? true : false);
}

function update_product_status($status_id, $p_id)
{
  global $db;
  $qty = (int) $status_id;
  $id  = (int)$p_id;
  $sql = "UPDATE products SET status_id = '{$qty}' WHERE id = '{$id}'";
  $result = $db->query($sql);
  return ($db->affected_rows() === 1 ? true : false);
}

function find_all_model()
{
  global $db;
  $sql = "SELECT m.*, b.name AS brand FROM model m LEFT JOIN brand b ON b.id = m.model_brand_id";
  return find_by_sql($sql);
}

function find_product_info_by_id($product_id)
{
  global $db;
  $p_name = remove_junk($db->escape($product_id));
  $sql   = "SELECT p.*, l.office AS office, d.name AS dep_point FROM products p";
  $sql  .= " LEFT JOIN location l ON l.id = p.location_id";
  $sql .= " LEFT JOIN dep_point d ON d.id = p.dep_point_id";
  $sql  .= " WHERE p.id = " . $p_name . " LIMIT 1";

  $result = find_by_sql($sql);
  return $result;
}

function find_product_by_all_fields($location_id, $dep_point_id, $categorie_id, $brand_id, $model_id, $availability_id, $status_id)
{

  $sql   = "SELECT p.*, l.office AS office, d.name AS dep_point, c.name AS categorie, b.name AS brand, m.name AS model, a.name AS availability, s.name AS status";
  $sql  .= " FROM products p";
  $sql  .= " LEFT JOIN location l ON l.id = p.location_id";
  $sql  .= " LEFT JOIN dep_point d ON d.id = p.dep_point_id";
  $sql  .= " LEFT JOIN categories c ON c.id = p.categorie_id";
  $sql  .= " LEFT JOIN brand b ON b.id = p.brand_id";
  $sql  .= " LEFT JOIN model m ON m.id = p.model_id";
  $sql  .= " LEFT JOIN availability a ON a.id = p.availability_id";
  $sql  .= " LEFT JOIN status s ON s.id = p.status_id";
  $sql  .= " WHERE p.location_id = " . $location_id . " AND p.dep_point_id = " . $dep_point_id;
  $sql  .= " AND p.categorie_id = " . $categorie_id . " AND p.model_id = " . $model_id . " AND p.brand_id = " . $brand_id;
  $sql  .= " AND p.availability_id = " . $availability_id . " AND p.status_id = " . $status_id;
  $sql  .= " AND p.active = TRUE";

  $result = find_by_sql($sql);
  return $result;
}

# CakePHP 3.x Remote SQL Server Driver
A SQL server driver for cakephp 3.x for remote connections. Using the default driver included in cakephp can cause huge load times due to the PDO::CURSOR_SCROLL setting.

#How to use

1. Put the /Database folder in your /src/ folder of your cakePHP installation. 
2. In your app.php, replace the driver of your sql server connection to 'driver' => 'App\Database\Driver\CustomSqlserver'
